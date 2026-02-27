@extends('layouts.app')

@section('title', 'تماس با ما | نیروان فێرگە')

@section('content')
<div class="font-fa min-h-screen bg-ui-bg">
  <section class="bg-white border-b border-ui-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
      <a href="{{ route('home') }}" class="flex items-center gap-3">
        <img src="{{ asset('images/brand/logo.png') }}" alt="نیروان فێرگە" class="h-10 w-10 rounded-lg object-contain" />
        <span class="font-ku font-bold text-xl text-brand-primary">نیروان فێرگە</span>
      </a>
      <a href="{{ route('home') }}" class="text-sm font-semibold text-brand-secondary hover:underline">بازگشت به خانه</a>
    </div>
  </section>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="max-w-3xl mx-auto text-center mb-10 sm:mb-14">
      <h1 class="text-3xl sm:text-4xl md:text-5xl font-fa-2 font-bold text-brand-primary mb-4">تماس با ما</h1>
      <p class="text-ui-muted text-base sm:text-lg leading-8">سوال، پیشنهاد یا گزارش مشکل دارید؟ از راه‌های زیر با ما در ارتباط باشید.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
      <div class="bg-white border border-ui-border rounded-2xl p-6 sm:p-8 space-y-5">
        <h2 class="font-fa-2 font-bold text-2xl text-ui-text">اطلاعات ارتباطی</h2>

        <div class="flex items-start gap-3 text-ui-muted">
          <x-ui.icon name="mail" class="w-5 h-5 mt-1 text-brand-secondary" />
          <div>
            <p class="font-semibold text-ui-text">ایمیل</p>
            <p>info@example.com</p>
          </div>
        </div>

        <div class="flex items-start gap-3 text-ui-muted">
          <x-ui.icon name="phone" class="w-5 h-5 mt-1 text-brand-secondary" />
          <div>
            <p class="font-semibold text-ui-text">تلفن</p>
            <p>۰۲۱-۱۲۳۴۵۶۷۸</p>
          </div>
        </div>

        <div class="flex items-start gap-3 text-ui-muted">
          <x-ui.icon name="clock-3" class="w-5 h-5 mt-1 text-brand-secondary" />
          <div>
            <p class="font-semibold text-ui-text">ساعات پاسخگویی</p>
            <p>شنبه تا پنج‌شنبه، ۹:۰۰ تا ۱۸:۰۰</p>
          </div>
        </div>
      </div>

      <div class="bg-white border border-ui-border rounded-2xl p-6 sm:p-8">
        <h2 class="font-fa-2 font-bold text-2xl text-ui-text mb-6">ارسال پیام</h2>

        <form class="space-y-4" action="#" method="POST" onsubmit="event.preventDefault();">
          <div>
            <label class="block text-sm font-semibold text-ui-text mb-2">نام و نام خانوادگی</label>
            <input type="text" class="w-full h-11 rounded-xl border border-ui-border bg-ui-surface px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/30" placeholder="نام شما">
          </div>

          <div>
            <label class="block text-sm font-semibold text-ui-text mb-2">ایمیل</label>
            <input type="email" class="w-full h-11 rounded-xl border border-ui-border bg-ui-surface px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/30" placeholder="you@example.com">
          </div>

          <div>
            <label class="block text-sm font-semibold text-ui-text mb-2">موضوع</label>
            <input type="text" class="w-full h-11 rounded-xl border border-ui-border bg-ui-surface px-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/30" placeholder="موضوع پیام">
          </div>

          <div>
            <label class="block text-sm font-semibold text-ui-text mb-2">متن پیام</label>
            <textarea rows="5" class="w-full rounded-xl border border-ui-border bg-ui-surface px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/30" placeholder="پیام خود را بنویسید..."></textarea>
          </div>

          <button type="submit" class="w-full h-11 rounded-xl bg-brand-primary text-white font-semibold hover:bg-brand-primary/90 transition">ارسال پیام</button>
        </form>
      </div>
    </div>
  </main>
</div>
@endsection
