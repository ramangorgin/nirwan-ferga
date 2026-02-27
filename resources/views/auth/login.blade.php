@extends('layouts.app')

@section('title', 'ورود به ' . env('APP_BRAND_DISPLAY', 'نیروان فێرگە'))

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="w-full max-w-md">

    {{-- Logo/Brand --}}
    <div class="text-center mb-8">
      <h1 class="text-3xl font-ku font-bold text-brand-primary mb-2">
        نیروان فێرگە
      </h1>
      <p class="text-ui-muted text-sm">
        وارد حساب کاربری خود شوید
      </p>
    </div>

    {{-- Login Card --}}
    <x-ui.card class="p-6 sm:p-8">
      <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
        @csrf

        {{-- Login Input (Email or Phone) --}}
        <x-ui.input
          type="text"
          name="login"
          label="ایمیل یا شماره موبایل"
          placeholder="example@email.com یا ۰۹۱۲۳۴۵۶۷۸۹"
          value="{{ old('login') }}"
          :error="$errors->first('login')"
          required
          autofocus
        />

        {{-- Password Input --}}
        <div>
          <div class="flex items-center justify-between mb-2">
            <span class="block text-sm font-semibold text-ui-text">رمز عبور</span>
            <a href="{{ route('password.request') }}" class="text-xs text-brand-secondary hover:underline">
              فراموشی رمز عبور؟
            </a>
          </div>
          <input
            type="password"
            name="password"
            placeholder="رمز عبور خود را وارد کنید"
            class="w-full h-11 rounded-xl bg-ui-surface border px-3 text-ui-text placeholder:text-ui-muted focus:outline-none focus:ring-2 {{ $errors->has('password') ? 'border-red-300 focus:ring-red-200' : 'border-ui-border focus:ring-brand-primary/30' }}"
            required
          >
          @error('password')
            <div class="mt-2 text-xs text-red-600">{{ $message }}</div>
          @enderror
        </div>

        {{-- Remember Me Checkbox --}}
        <div class="flex items-center">
          <label class="inline-flex items-center gap-2 cursor-pointer">
            <input
              type="checkbox"
              name="remember"
              value="1"
              class="h-4 w-4 rounded border-ui-border text-brand-secondary focus:ring-brand-secondary"
              {{ old('remember') ? 'checked' : '' }}
            >
            <span class="text-sm font-semibold text-ui-text">
              مرا به خاطر بسپار
            </span>
          </label>
        </div>

        {{-- Submit Button --}}
        <x-ui.button type="submit" class="w-full">
          <x-ui.icon name="log-in" class="h-5 w-5" />
          <span>ورود</span>
        </x-ui.button>
      </form>

      {{-- Register Link --}}
      <div class="mt-6 text-center">
        <p class="text-sm text-ui-muted">
          حساب کاربری ندارید؟
          <a href="{{ route('register') }}" class="text-brand-secondary font-semibold hover:underline">
            ثبت‌نام کنید
          </a>
        </p>
      </div>
    </x-ui.card>

    {{-- Back to Home --}}
    <div class="mt-6 text-center">
      <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-ui-muted hover:text-ui-text transition">
        <x-ui.icon name="arrow-right" class="h-4 w-4" />
        <span>بازگشت به صفحه اصلی</span>
      </a>
    </div>
  </div>
</div>
@endsection
