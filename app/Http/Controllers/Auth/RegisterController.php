<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\Users\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = $this->userService->registerStudent($request->validated());

        Auth::login($user);

        // Send email verification notification
        $user->sendEmailVerificationNotification();

        return redirect()
            ->route('dashboard')
            ->with('success', 'ثبت نام با موفقیت انجام شد! لطفا ایمیل و شماره تلفن خود را برای تایید حساب کاربری بررسی کنید.');
    }
}