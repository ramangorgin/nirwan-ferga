@extends('layouts.app')

@section('title', 'درباره ما | نیروان فێرگە')

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
      <h1 class="text-3xl sm:text-4xl md:text-5xl font-fa-2 font-bold text-brand-primary mb-4">درباره ما</h1>
      <p class="text-ui-muted text-base sm:text-lg leading-8">
        ما در <span class="font-ku">نیروان فێرگە</span> تلاش می‌کنیم تجربه آموزش آنلاین را ساده، قابل‌اعتماد و مؤثر کنیم.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 mb-12">
      <div class="bg-white border border-ui-border rounded-2xl p-6">
        <div class="w-12 h-12 rounded-xl bg-brand-primary/10 text-brand-primary flex items-center justify-center mb-4">
          <x-ui.icon name="target" class="w-6 h-6" />
        </div>
        <h2 class="font-fa-2 font-bold text-lg mb-2">ماموریت</h2>
        <p class="text-ui-muted text-sm leading-7">ایجاد یک فضای آموزشی روان برای مدرس و دانشجو با تمرکز بر کیفیت و دسترسی.</p>
      </div>

      <div class="bg-white border border-ui-border rounded-2xl p-6">
        <div class="w-12 h-12 rounded-xl bg-brand-secondary/10 text-brand-secondary flex items-center justify-center mb-4">
          <x-ui.icon name="eye" class="w-6 h-6" />
        </div>
        <h2 class="font-fa-2 font-bold text-lg mb-2">چشم‌انداز</h2>
        <p class="text-ui-muted text-sm leading-7">بهترین بستر آموزشی منطقه برای یادگیری مداوم، تعامل سالم و رشد مهارتی.</p>
      </div>

      <div class="bg-white border border-ui-border rounded-2xl p-6">
        <div class="w-12 h-12 rounded-xl bg-brand-primary/10 text-brand-primary flex items-center justify-center mb-4">
          <x-ui.icon name="shield-check" class="w-6 h-6" />
        </div>
        <h2 class="font-fa-2 font-bold text-lg mb-2">ارزش‌ها</h2>
        <p class="text-ui-muted text-sm leading-7">شفافیت، کیفیت، امنیت داده‌ها و احترام به زمان کاربران.</p>
      </div>
    </div>

    <div class="bg-white border border-ui-border rounded-2xl p-6 sm:p-8">
      <h3 class="font-fa-2 font-bold text-2xl text-ui-text mb-4">چرا نیروان فێرگە؟</h3>
      <ul class="space-y-3 text-ui-muted leading-8">
        <li class="flex items-start gap-3"><x-ui.icon name="check-circle-2" class="w-5 h-5 mt-1 text-green-600" /><span>رابط کاربری ساده و سریع برای موبایل و دسکتاپ</span></li>
        <li class="flex items-start gap-3"><x-ui.icon name="check-circle-2" class="w-5 h-5 mt-1 text-green-600" /><span>مدیریت کامل دوره، تکلیف، آزمون و ارتباطات آموزشی</span></li>
        <li class="flex items-start gap-3"><x-ui.icon name="check-circle-2" class="w-5 h-5 mt-1 text-green-600" /><span>پشتیبانی مداوم و بهبود مستمر بر اساس بازخورد کاربران</span></li>
      </ul>
    </div>
  </main>
</div>
@endsection
