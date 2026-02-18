<?php

namespace App\Services\DiscountCodes;

use App\Models\DiscountCode;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DiscountCodeService
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly SmsService $smsService
    ) {}

    /**
     * Normalize a discount code for consistent lookup/storage.
     */
    public function normalizeCode(string $code): string
    {
        return strtoupper(trim($code));
    }

    /**
     * Find a discount code by code string (normalized).
     */
    public function findByCode(string $code): ?DiscountCode
    {
        $normalized = $this->normalizeCode($code);

        return DiscountCode::query()
            ->where('code', $normalized)
            ->first();
    }

    /**
     * Validate that the discount code can be used by the given user.
     * Throws ValidationException with a user-friendly message on failure.
     */
    public function validateForUser(DiscountCode $discountCode, User $user): void
    {
        // Single source of truth: model helper
        if ($discountCode->canBeUsedBy($user)) {
            return;
        }

        // Provide specific error message based on model helpers (same order as the model logic)
        if (!$discountCode->isAvailable()) {
            if ($discountCode->isExpired()) {
                throw ValidationException::withMessages([
                    'code' => ['این کد تخفیف منقضی شده است.'],
                ]);
            }

            throw ValidationException::withMessages([
                'code' => ['این کد تخفیف غیرفعال است.'],
            ]);
        }

        if ($discountCode->isRestricted() && $discountCode->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'code' => ['این کد تخفیف برای شما قابل استفاده نیست.'],
            ]);
        }

        if (!$discountCode->hasUnlimitedUses()) {
            $remaining = $discountCode->remainingUses(); // may trigger a count query
            if ($remaining !== null && $remaining <= 0) {
                throw ValidationException::withMessages([
                    'code' => ['سقف استفاده از این کد تخفیف پر شده است.'],
                ]);
            }
        }

        throw ValidationException::withMessages([
            'code' => ['این کد تخفیف قابل استفاده نیست.'],
        ]);
    }

    /**
     * Apply a discount code to an enrollment in a transaction-safe way.
     *
     * - Locks the discount code row to prevent race conditions on max_uses.
     * - Re-validates inside the transaction.
     * - Attaches the discount to the enrollment in a flexible way:
     *   - prefers discount_code_id if the column is fillable/exists
     *   - otherwise falls back to discount_code (string) if fillable/exists
     *
     * Notifications/SMS:
     * - Sends a notification + SMS to the enrolling user about successful application.
     * - Creator/actor for notification defaults to the same user unless $actorUserId provided.
     *
     * @throws ValidationException
     */
    public function applyToEnrollment(
        string $code,
        User $user,
        Enrollment $enrollment,
        ?int $actorUserId = null
    ): DiscountCode {
        $normalized = $this->normalizeCode($code);
        $actorUserId = $actorUserId ?? $user->id;

        $discountCode = DB::transaction(function () use ($normalized, $user, $enrollment) {
            /** @var DiscountCode|null $discountCode */
            $discountCode = DiscountCode::query()
                ->where('code', $normalized)
                ->lockForUpdate()
                ->first();

            if (!$discountCode) {
                throw ValidationException::withMessages([
                    'code' => ['کد تخفیف یافت نشد.'],
                ]);
            }

            // Re-validate under lock (prevents max_uses race conditions)
            $this->validateForUser($discountCode, $user);

            // Attach discount to enrollment (flexible: id or string)
            $this->attachDiscountToEnrollment($discountCode, $enrollment);

            return $discountCode;
        });

        // Notify after transaction commit
        $this->notifyDiscountApplied($discountCode, $user, $actorUserId, $enrollment);

        return $discountCode;
    }

    /**
     * Remove/clear discount from an enrollment (optional helper).
     * Keeps it transaction-safe; does NOT send notifications by default.
     */
    public function clearFromEnrollment(Enrollment $enrollment): void
    {
        DB::transaction(function () use ($enrollment) {
            $dirty = false;

            if ($this->enrollmentSupportsField($enrollment, 'discount_code_id')) {
                if ($enrollment->discount_code_id !== null) {
                    $enrollment->discount_code_id = null;
                    $dirty = true;
                }
            }

            if ($this->enrollmentSupportsField($enrollment, 'discount_code')) {
                if (!empty($enrollment->discount_code)) {
                    $enrollment->discount_code = null;
                    $dirty = true;
                }
            }

            if ($dirty) {
                $enrollment->save();
            }
        });
    }

    /**
     * Attach discount code to enrollment using whichever schema you have.
     */
    private function attachDiscountToEnrollment(DiscountCode $discountCode, Enrollment $enrollment): void
    {
        // Prefer FK if available
        if ($this->enrollmentSupportsField($enrollment, 'discount_code_id')) {
            $enrollment->discount_code_id = $discountCode->id;
            $enrollment->save();
            return;
        }

        // Fallback to string column if that’s how your enrollments store it
        if ($this->enrollmentSupportsField($enrollment, 'discount_code')) {
            $enrollment->discount_code = $discountCode->code;
            $enrollment->save();
            return;
        }

        // If neither exists, that's a schema mismatch.
        throw new \RuntimeException(
            "Enrollment model does not support discount storage. Add `discount_code_id` (preferred) or `discount_code` column to enrollments."
        );
    }

    /**
     * Check if Enrollment supports a given attribute.
     * We avoid Schema::hasColumn to keep the service lightweight and compatible with tests.
     */
    private function enrollmentSupportsField(Enrollment $enrollment, string $field): bool
    {
        // Fillable is a strong signal; also allow if attribute exists on model already.
        if (method_exists($enrollment, 'isFillable') && $enrollment->isFillable($field)) {
            return true;
        }

        // If guarded, fillable might be empty; check attribute existence
        // (setAttribute works even if not present, but we want to avoid silent data loss)
        $attributes = $enrollment->getAttributes();
        if (array_key_exists($field, $attributes)) {
            return true;
        }

        // As a fallback, allow if there is an accessor/mutator naming
        $studly = Str::studly($field);
        if (method_exists($enrollment, "get{$studly}Attribute") || method_exists($enrollment, "set{$studly}Attribute")) {
            return true;
        }

        return false;
    }

    /**
     * Send notification + SMS to user that discount was applied.
     * Adjust the message/link formats as your frontend routes require.
     */
    private function notifyDiscountApplied(
        DiscountCode $discountCode,
        User $user,
        int $actorUserId,
        Enrollment $enrollment
    ): void {
        // Notification
        $title = 'کد تخفیف اعمال شد';
        $body = "کد تخفیف {$discountCode->code} با درصد {$discountCode->percentage}% روی ثبت‌نام شما اعمال شد.";

        // If you have a known frontend route pattern, customize it.
        // Keep it nullable if you don't want links.
        $link = null;

        $this->notificationService->notifyUser(
            recipientUserId: $user->id,
            creatorUserId: $actorUserId,
            title: $title,
            body: $body,
            link: $link
        );

        // SMS (best-effort)
        $smsMessage = "کد تخفیف {$discountCode->code} ({$discountCode->percentage}%) برای ثبت‌نام شما اعمال شد.";
        $this->smsService->sendToUserId($user->id, $smsMessage);
    }
}
