<?php

namespace App\Services\Payments;

use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService
    ) {}

    /**
     * Student uploads a payment receipt for an enrollment.
     */
    public function createForEnrollment(Enrollment $enrollment, User $student, int $amount, UploadedFile $screenshot): Payment
    {
        return DB::transaction(function () use ($enrollment, $student, $amount, $screenshot) {

            // Ensure ownership
            if ((int) $enrollment->student_id !== (int) $student->id) {
                throw ValidationException::withMessages([
                    'enrollment_id' => ['You do not own this enrollment.'],
                ]);
            }

            // Store file
            // Folder: payments/{enrollment_id}/{student_id}/
            $dir = "payments/{$enrollment->id}/{$student->id}";
            $path = $screenshot->store($dir, 'public');

            // Create payment
            $payment = Payment::create([
                'enrollment_id' => $enrollment->id,
                'student_id' => $student->id,
                'amount' => $amount,
                'screenshot_path' => $path,
                'status' => 'pending',
                'admin_note' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]);

            // Optional: mark enrollment payment_status as "partial" or "unpaid"
            // Production-fast: set to "partial" if you have it, else keep existing.
            $this->syncEnrollmentPaymentStatusOnNewPayment($enrollment);

            // Notify admins (and optionally sms admins)
            $this->notifyAdminsNewPayment($payment);

            return $payment->fresh(['student', 'enrollment']);
        });
    }

    /**
     * Admin reviews payment and approves or rejects.
     */
    public function review(Payment $payment, User $admin, string $status, ?string $adminNote = null): Payment
    {
        return DB::transaction(function () use ($payment, $admin, $status, $adminNote) {

            if ($admin->role !== 'admin') {
                throw ValidationException::withMessages([
                    'review' => ['Only admins can review payments.'],
                ]);
            }

            $payment->update([
                'status' => $status,
                'admin_note' => $adminNote,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now('UTC'),
            ]);

            // Sync enrollment payment status + enrollment status optionally
            $this->syncEnrollmentPaymentStatusOnReview($payment);

            // Notify student
            $this->notifyStudentReviewed($payment);

            return $payment->fresh(['student', 'reviewer', 'enrollment']);
        });
    }

    /**
     * If you ever delete payment, delete file too.
     */
    public function delete(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            if ($payment->screenshot_path) {
                Storage::disk('public')->delete($payment->screenshot_path);
            }
            $payment->delete();
        });
    }

    protected function notifyAdminsNewPayment(Payment $payment): void
    {
        // Production-fast: notify all admins
        $admins = User::query()->where('role', 'admin')->pluck('id')->toArray();

        $link = route('admin.payments.show', $payment);

        foreach ($admins as $adminId) {
            $this->notificationService->notifyUser(
                recipientUserId: (int) $adminId,
                creatorUserId: (int) $payment->student_id,
                title: 'New payment uploaded',
                body: "Amount: {$payment->amount}",
                link: $link
            );

            // SMS for admins is usually annoying; disable by default
            // $this->smsService->sendToUserId((int) $adminId, "New payment uploaded: {$payment->amount}");
        }
    }

    protected function notifyStudentReviewed(Payment $payment): void
    {
        $statusText = $payment->status === 'approved' ? 'تایید شد' : 'رد شد';

        $link = route('payments.index'); // student page (you will add)

        $body = "پرداخت شما {$statusText}.";
        if ($payment->admin_note) {
            $body .= " توضیح: " . mb_substr($payment->admin_note, 0, 120);
        }

        $this->notificationService->notifyUser(
            recipientUserId: (int) $payment->student_id,
            creatorUserId: (int) ($payment->reviewed_by ?? $payment->student_id),
            title: 'وضعیت پرداخت شما',
            body: $body,
            link: $link
        );

        $this->smsService->sendToUserId(
            (int) $payment->student_id,
            "وضعیت پرداخت شما: {$statusText}"
        );
    }

    protected function syncEnrollmentPaymentStatusOnNewPayment(Enrollment $enrollment): void
    {
        // This depends on your Enrollment.payment_status enum.
        // Based on your earlier service: 'unpaid', 'paid', 'partial', ... (guess)
        if (property_exists($enrollment, 'payment_status')) {
            // If currently unpaid, set to partial (receipt uploaded but not approved)
            if ($enrollment->payment_status === 'unpaid') {
                $enrollment->update(['payment_status' => 'partial']);
            }
        }
    }

    protected function syncEnrollmentPaymentStatusOnReview(Payment $payment): void
    {
        $enrollment = $payment->enrollment()->first();
        if (!$enrollment) return;

        // Map payment status -> enrollment payment_status
        // Adjust these values to your enrollment enum
        if (property_exists($enrollment, 'payment_status')) {
            if ($payment->status === 'approved') {
                $enrollment->update(['payment_status' => 'paid']);

                // Optional: auto confirm enrollment when paid
                if ($enrollment->status === 'pending') {
                    $enrollment->update(['status' => 'confirmed']);
                }
            }

            if ($payment->status === 'rejected') {
                // Keep partial/unpaid depending on your preference
                // Production-fast: set unpaid
                $enrollment->update(['payment_status' => 'unpaid']);
            }
        }
    }
}