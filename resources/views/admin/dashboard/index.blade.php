@extends('layouts.dashboard.admin')

@section('title', 'داشبورد مدیریت | نیروان فێرگە')

@section('dashboard_content')
<div class="space-y-6">

  <div class="flex items-start justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold">نمای کلی داشبورد</h1>
      <p class="text-ui-muted mt-1">خلاصه وضعیت سیستم و اقدامات مدیریتی</p>
    </div>

    <div class="flex gap-2">
      <a href="{{ route('courses.create') }}"
         class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-brand-primary text-white font-semibold hover:opacity-90">
        <x-ui.icon name="plus" />
        ایجاد دوره جدید
      </a>

      <a href="{{ route('admin.announcements.create') }}"
         class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-ui-surface border border-ui-border font-semibold hover:bg-ui-bg">
        <x-ui.icon name="megaphone" />
        ارسال اطلاعیه
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-ui.card class="p-5">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-ui-muted">درآمد ماهانه</div>
          <div class="text-2xl font-extrabold mt-1">@faNum(4520000) تومان</div>
          <div class="text-xs text-ui-muted mt-2">نسبت به ماه قبل</div>
        </div>
        <div class="h-10 w-10 rounded-xl bg-green-100 grid place-items-center text-green-700">
          <x-ui.icon name="banknote" />
        </div>
      </div>
    </x-ui.card>

    <x-ui.card class="p-5">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-ui-muted">دوره‌های فعال</div>
          <div class="text-2xl font-extrabold mt-1">@faNum(124)</div>
          <div class="text-xs text-ui-muted mt-2">نسبت به ماه قبل</div>
        </div>
        <div class="h-10 w-10 rounded-xl bg-brand-secondary/10 grid place-items-center text-brand-secondary">
          <x-ui.icon name="book" />
        </div>
      </div>
    </x-ui.card>

    <x-ui.card class="p-5">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-ui-muted">مجموع دانش‌آموزان</div>
          <div class="text-2xl font-extrabold mt-1">@faNum(2450)</div>
          <div class="text-xs text-ui-muted mt-2">نسبت به ماه قبل</div>
        </div>
        <div class="h-10 w-10 rounded-xl bg-brand-primary/10 grid place-items-center text-brand-primary">
          <x-ui.icon name="users" />
        </div>
      </div>
    </x-ui.card>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    <x-ui.card class="p-5">
      <div class="flex items-center justify-between">
        <div class="font-extrabold">اعلان‌های اخیر</div>
        <a href="{{ route('admin.announcements.index') }}" class="text-sm text-brand-primary font-semibold">مشاهده همه</a>
      </div>

      <div class="mt-4 space-y-3 text-sm">
        <div class="rounded-xl bg-ui-bg border border-ui-border p-3">
          <div class="font-semibold">ثبت‌نام دانش‌آموز جدید</div>
          <div class="text-xs text-ui-muted mt-1">@faNum(2) دقیقه پیش</div>
        </div>
        <div class="rounded-xl bg-ui-bg border border-ui-border p-3">
          <div class="font-semibold">پرداخت شهریه</div>
          <div class="text-xs text-ui-muted mt-1">@faNum(10) دقیقه پیش</div>
        </div>
        <div class="rounded-xl bg-ui-bg border border-ui-border p-3">
          <div class="font-semibold">ظرفیت دوره تکمیل شد</div>
          <div class="text-xs text-ui-muted mt-1">@faNum(1) ساعت پیش</div>
        </div>
      </div>
    </x-ui.card>

    <x-ui.card class="p-5 lg:col-span-2">
      <div class="flex items-center justify-between">
        <div class="font-extrabold">ثبت‌نام‌های ماهانه</div>
        <div class="text-sm text-ui-muted">آمار در @faNum(6) ماه گذشته</div>
      </div>

      <div class="mt-6 h-52 rounded-2xl bg-ui-bg border border-ui-border grid place-items-center text-ui-muted">
        {{-- Chart placeholder (later with JS/Chart library) --}}
        نمودار در فاز بعدی
      </div>
    </x-ui.card>

  </div>

  <x-ui.card class="p-5">
    <div class="flex items-center justify-between">
      <div class="font-extrabold">فعالیت‌های اخیر دوره‌ها</div>
      <a href="{{ route('courses.index') }}" class="text-sm text-brand-primary font-semibold">مشاهده همه</a>
    </div>

    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-ui-muted">
          <tr class="border-b border-ui-border">
            <th class="text-right py-2">نام دوره</th>
            <th class="text-right py-2">مدرس</th>
            <th class="text-right py-2">وضعیت</th>
            <th class="text-right py-2">آخرین بروزرسانی</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b border-ui-border">
            <td class="py-3 font-semibold">دوره نمونه @faNum(1)</td>
            <td class="py-3 text-ui-muted">استاد اکبری</td>
            <td class="py-3">
              <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">در حال برگزاری</span>
            </td>
            <td class="py-3 text-ui-muted">@faNum(2) ساعت پیش</td>
          </tr>
          <tr class="border-b border-ui-border">
            <td class="py-3 font-semibold">دوره نمونه @faNum(2)</td>
            <td class="py-3 text-ui-muted">سارا احمدی</td>
            <td class="py-3">
              <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-ui-surface border border-ui-border text-ui-muted">پیش‌نویس</span>
            </td>
            <td class="py-3 text-ui-muted">@faNum(1) روز پیش</td>
          </tr>
          <tr>
            <td class="py-3 font-semibold">دوره نمونه @faNum(3)</td>
            <td class="py-3 text-ui-muted">رضا محمدی</td>
            <td class="py-3">
              <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">غیرفعال</span>
            </td>
            <td class="py-3 text-ui-muted">@faNum(3) روز پیش</td>
          </tr>
        </tbody>
      </table>
    </div>
  </x-ui.card>

</div>
@endsection