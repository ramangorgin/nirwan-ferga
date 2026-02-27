@extends('layouts.app')

@section('title', 'بازیابی رمز عبور | نیروان فێرگە')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <h1 class="text-3xl font-ku font-bold text-brand-primary mb-2">نیروان فێرگە</h1>
      <p class="text-ui-muted text-sm">لینک بازیابی رمز عبور را دریافت کنید</p>
    </div>

    <x-ui.card class="p-6 sm:p-8">
      <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <x-ui.input
          type="email"
          name="email"
          label="ایمیل"
          placeholder="example@email.com"
          value="{{ old('email') }}"
          :error="$errors->first('email')"
          required
          autofocus
        />

        <x-ui.button type="submit" class="w-full">
          <x-ui.icon name="mail" class="h-5 w-5" />
          <span>ارسال لینک بازیابی</span>
        </x-ui.button>
      </form>

      <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-ui-muted hover:text-ui-text transition">
          <x-ui.icon name="arrow-right" class="h-4 w-4" />
          <span>بازگشت به صفحه ورود</span>
        </a>
      </div>
    </x-ui.card>
  </div>
</div>
@endsection