<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $login = trim($data['login']);
        $password = $data['password'];
        $remember = (bool) ($data['remember'] ?? false);

        // Determine if email or phone
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Optional: block banned users BEFORE attempting
        $user = User::query()->where($field, $login)->first();
        if ($user && in_array($user->status, ['ban', 'suspended', 'deactive'], true)) {
            throw ValidationException::withMessages([
                'login' => ['حساب شما فعال نیست.'],
            ]);
        }

        if (!Auth::attempt([$field => $login, 'password' => $password], $remember)) {
            throw ValidationException::withMessages([
                'login' => ['اطلاعات ورود نامعتبر است.'],
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}