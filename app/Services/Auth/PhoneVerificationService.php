<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class PhoneVerificationService
{
    public function __construct(
        protected SmsService $smsService
    ) {}

    /**
     * Generate and send OTP via SMS.
     */
    public function sendOtp(User $user): void
    {
        if (!$user->phone) {
            throw ValidationException::withMessages([
                'phone' => ['شماره تلفن برای تایید الزامی است.'],
            ]);
        }

        $otp = random_int(100000, 999999);

        // Store OTP in cache for 5 minutes
        Cache::put($this->cacheKey($user->id), (string) $otp, now()->addMinutes(5));

        $this->smsService->sendToUserId((int) $user->id, "کد تایید شما: {$otp}");
    }

    /**
     * Verify OTP and mark phone_verified_at.
     */
    public function verifyOtp(User $user, string $otp): void
    {
        $cached = Cache::get($this->cacheKey($user->id));

        if (!$cached || $cached !== trim($otp)) {
            throw ValidationException::withMessages([
                'code' => ['کد تایید نامعتبر یا منقضی شده است.'],
            ]);
        }

        $user->update([
            'phone_verified_at' => now('UTC'),
        ]);

        Cache::forget($this->cacheKey($user->id));
    }

    protected function cacheKey(int $userId): string
    {
        return "phone_verify_otp_user_{$userId}";
    }
}