<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\PhoneVerificationService;
use App\Services\Users\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhoneVerificationController extends Controller
{
    public function __construct(
        protected PhoneVerificationService $phoneVerificationService,
        protected UserService $userService
    ) {}

    public function notice(): View
    {
        return view('auth.verify-phone');
    }

    public function sendCode(Request $request): RedirectResponse
    {
        $this->phoneVerificationService->sendOtp($request->user());

        return back()->with('success', 'OTP sent.');
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required','string','max:10'],
        ]);

        $user = $request->user();

        $this->phoneVerificationService->verifyOtp($user, (string) $request->input('code'));

        // Try auto-activate if both verified
        $this->userService->updateProfile($user, [], null);

        return redirect()->route('dashboard')->with('success', 'شماره تلفن تایید شد.');
    }
}