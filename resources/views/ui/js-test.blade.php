@extends('layouts.app')

@section('title', 'JS Test')

@section('content')
<div class="p-6 space-y-6">

  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-extrabold">تست کتابخانه‌های جاوااسکریپت</h1>
    <div class="text-sm text-ui-muted">نێروان فێرگە</div>
  </div>

  {{-- Toastr --}}
  <div class="rounded-2xl border border-ui-border bg-ui-surface p-6">
    <div class="font-bold mb-4">Toastr</div>
    <div class="flex flex-wrap gap-3">
      <button class="h-11 px-4 rounded-xl bg-brand-secondary text-white"
        onclick="toastr.success('عملیات با موفقیت انجام شد')">
        موفق
      </button>
      <button class="h-11 px-4 rounded-xl bg-brand-primary text-white"
        onclick="toastr.info('این یک پیام اطلاعاتی است')">
        اطلاعات
      </button>
      <button class="h-11 px-4 rounded-xl bg-ui-bg border border-ui-border"
        onclick="toastr.warning('این یک هشدار است')">
        هشدار
      </button>
      <button class="h-11 px-4 rounded-xl bg-red-600 text-white"
        onclick="toastr.error('خطایی رخ داد')">
        خطا
      </button>
    </div>
  </div>

  {{-- SweetAlert2 --}}
  <div class="rounded-2xl border border-ui-border bg-ui-surface p-6">
    <div class="font-bold mb-4">SweetAlert2 (Confirm)</div>
    <div class="flex flex-wrap gap-3">
      <button class="h-11 px-4 rounded-xl bg-ui-bg border border-ui-border"
        onclick="(async()=>{ const ok = await uiConfirm({text:'آیا مطمئن هستید؟'}); if(ok) toastr.success('تأیید شد'); })()">
        تست confirm
      </button>

      <a href="{{ url('/ui') }}"
         class="h-11 px-4 rounded-xl bg-brand-secondary text-white inline-flex items-center justify-center"
         data-confirm
         data-confirm-title="خروج"
         data-confirm-text="می‌خواهید به UI Playground بروید؟"
         data-confirm-yes="برو"
         data-confirm-no="نه">
        تست data-confirm
      </a>
    </div>
  </div>

  {{-- Jalali Date Picker --}}
  <div class="rounded-2xl border border-ui-border bg-ui-surface p-6">
    <div class="font-bold mb-4">Jalali Date Picker</div>
    <label class="block">
      <span class="block mb-2 text-sm font-semibold text-ui-text">تاریخ شمسی</span>
      <input data-jdp class="w-full h-11 rounded-xl border border-ui-border bg-ui-bg px-3"
             placeholder="۱۴۰۳/۰۶/۰۴" />
    </label>
    <div class="text-sm text-ui-muted mt-2">روی ورودی کلیک کن.</div>
  </div>

  {{-- CKEditor --}}
  <div class="rounded-2xl border border-ui-border bg-ui-surface p-6">
    <div class="font-bold mb-4">CKEditor</div>
    <textarea data-ckeditor class="w-full min-h-40 rounded-xl border border-ui-border bg-ui-bg px-3 py-2">
متن تست...
    </textarea>
  </div>

  {{-- Animate.css --}}
  <div class="rounded-2xl border border-ui-border bg-ui-surface p-6">
    <div class="font-bold mb-4">Animate.css</div>
    <button class="h-11 px-4 rounded-xl bg-brand-primary text-white"
      onclick="const el=document.getElementById('animBox'); el.className='mt-4 p-4 rounded-xl bg-ui-bg border border-ui-border animate__animated animate__pulse';">
      اجرای انیمیشن
    </button>

    <div id="animBox" class="mt-4 p-4 rounded-xl bg-ui-bg border border-ui-border">
      با کلیک روی دکمه، pulse اجرا می‌شود.
    </div>
  </div>

</div>
@endsection