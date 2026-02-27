@extends('layouts.dashboard.teacher')

@section('title', 'داشبورد استاد | نیروان فێرگە')

@section('dashboard_content')
<div class="space-y-6">

  <div class="flex items-start justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold">داشبورد استاد</h1>
      <p class="text-ui-muted mt-1">نمای کلی فعالیت‌ها و کلاس‌های پیش‌رو</p>
    </div>

    <div class="flex gap-2">
      <a href="{{ route('class-sessions.index') }}"
         class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-brand-primary text-white font-semibold hover:opacity-90">
        <x-ui.icon name="video" />
        شروع جلسه زنده
      </a>

      <a href="{{ route('class-sessions.index') }}"
         class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-ui-surface border border-ui-border font-semibold hover:bg-ui-bg">
        <x-ui.icon name="upload" />
        بارگذاری منابع
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    <x-ui.card class="p-5">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-sm text-ui-muted">وضعیت کلی</div>
          <div class="font-extrabold mt-1">@faNum(98)%</div>
        </div>
        <div class="h-10 w-10 rounded-xl bg-brand-accent/10 grid place-items-center text-brand-accent">
          <x-ui.icon name="trending-up" />
        </div>
      </div>

      <div class="mt-4 space-y-3">
        <div>
          <div class="text-xs text-ui-muted mb-2">رضایت دانش‌آموزان</div>
          <div class="h-2 rounded-full bg-ui-bg border border-ui-border overflow-hidden">
            <div class="h-full w-[92%] bg-green-500"></div>
          </div>
        </div>
        <div>
          <div class="text-xs text-ui-muted mb-2">حضور در کلاس</div>
          <div class="h-2 rounded-full bg-ui-bg border border-ui-border overflow-hidden">
            <div class="h-full w-[85%] bg-brand-primary"></div>
          </div>
        </div>
      </div>
    </x-ui.card>

    <x-ui.card class="p-5">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-sm text-ui-muted">تکالیف در انتظار</div>
          <div class="text-3xl font-extrabold mt-1">@faNum(12)</div>
          <div class="text-xs text-ui-muted mt-1">@faNum(3) مورد نیاز به بررسی</div>
        </div>
        <div class="h-10 w-10 rounded-xl bg-brand-secondary/10 grid place-items-center text-brand-secondary">
          <x-ui.icon name="clipboard" />
        </div>
      </div>

      <div class="mt-4 space-y-2 text-sm">
        <div class="flex items-center justify-between">
          <span class="text-ui-muted">تمرین نوشتن (سارا)</span>
          <span class="inline-flex w-2 h-2 rounded-full bg-amber-500"></span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-ui-muted">تمرین صوتی (علی)</span>
          <span class="inline-flex w-2 h-2 rounded-full bg-blue-500"></span>
        </div>
      </div>
    </x-ui.card>

    <x-ui.card class="p-5 bg-brand-primary text-white">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-sm opacity-90">کلاس بعدی</div>
          <div class="text-2xl font-extrabold mt-2">جلسه مکالمه</div>
          <div class="text-sm opacity-90 mt-1">ساعت: @faNum('11:30') - @faNum('13:00')</div>
        </div>
        <div class="h-10 w-10 rounded-xl bg-white/15 grid place-items-center">
          <x-ui.icon name="clock" />
        </div>
      </div>

      <div class="mt-5 flex items-center justify-between">
        <a href="#" class="inline-flex items-center justify-center rounded-xl h-10 px-4 bg-white text-brand-primary font-extrabold">
          ورود به کلاس
        </a>
        <div class="text-xs opacity-90">دانش‌آموزان: @faNum(12)</div>
      </div>
    </x-ui.card>

  </div>

  <x-ui.card class="p-5">
    <div class="flex items-center justify-between">
      <div class="font-extrabold">برنامه هفتگی</div>
      <a href="{{ route('class-sessions.index') }}" class="text-sm text-brand-primary font-semibold">مشاهده کامل</a>
    </div>

    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-ui-muted">
          <tr class="border-b border-ui-border">
            <th class="text-right py-2">روز</th>
            <th class="text-right py-2">ساعت</th>
            <th class="text-right py-2">کلاس</th>
            <th class="text-right py-2">وضعیت</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b border-ui-border">
            <td class="py-3">شنبه</td>
            <td class="py-3 text-ui-muted">@faNum('11:30')-@faNum('13:00')</td>
            <td class="py-3 font-semibold">سطح @faNum(1)</td>
            <td class="py-3"><span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">برگزار شده</span></td>
          </tr>
          <tr class="border-b border-ui-border bg-ui-bg/40">
            <td class="py-3">دوشنبه</td>
            <td class="py-3 text-ui-muted">@faNum('15:30')-@faNum('14:00')</td>
            <td class="py-3 font-semibold">مکالمه پیشرفته</td>
            <td class="py-3"><span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-brand-primary/10 text-brand-primary">به زودی</span></td>
          </tr>
          <tr>
            <td class="py-3">چهارشنبه</td>
            <td class="py-3 text-ui-muted">@faNum('17:30')-@faNum('16:00')</td>
            <td class="py-3 font-semibold">دستور زبان</td>
            <td class="py-3"><span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-ui-surface border border-ui-border text-ui-muted">آینده</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </x-ui.card>

</div>
@endsection