@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-ui-bg">
  <div class="flex">

    <!-- Sidebar -->
    <aside class="w-72 bg-ui-surface border-l border-ui-border min-h-screen px-4 py-6">
      <div class="flex items-center gap-3">
        <img class="h-10 w-10 rounded-xl" src="{{ asset('images/brand/logo.png') }}" alt="logo">
        <div>
          <div class="font-ku font-bold text-ui-text">{{ env('APP_BRAND_DISPLAY', 'نیروان فێرگە') }}</div>
          <div class="text-sm text-ui-muted">پنل مدیریت</div>
        </div>
      </div>

      <nav class="mt-8 space-y-2">
        <a class="flex items-center justify-between rounded-xl px-3 py-2 bg-brand-secondary/10 text-brand-secondary" href="#">داشبورد</a>
        <a class="flex items-center justify-between rounded-xl px-3 py-2 hover:bg-ui-bg" href="#">دوره‌ها</a>
        <a class="flex items-center justify-between rounded-xl px-3 py-2 hover:bg-ui-bg" href="#">دانشجویان</a>
        <a class="flex items-center justify-between rounded-xl px-3 py-2 hover:bg-ui-bg" href="#">مدرسین</a>
        <a class="flex items-center justify-between rounded-xl px-3 py-2 hover:bg-ui-bg" href="#">پیام‌ها</a>
        <a class="flex items-center justify-between rounded-xl px-3 py-2 hover:bg-ui-bg" href="#">تنظیمات</a>
      </nav>

      <div class="mt-10 border-t border-ui-border pt-4">
        <button class="w-full rounded-xl px-3 py-2 text-red-600 hover:bg-red-50">خروج</button>
      </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8">
      <!-- Topbar -->
      <div class="flex items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-extrabold">@yield('page_title','داشبورد')</h1>
          <p class="text-ui-muted mt-1">@yield('page_subtitle','خوش آمدید')</p>
        </div>
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-full bg-ui-surface border border-ui-border"></div>
          <div class="h-10 w-10 rounded-full bg-ui-surface border border-ui-border"></div>
        </div>
      </div>

      <div class="mt-8">
        @yield('dashboard_content')
      </div>
    </main>

  </div>
</div>
@endsection