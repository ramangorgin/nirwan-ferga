@extends('layouts.app')

@section('title', 'خانه | نێروان فێرگە')

@push('head')
  <style>
    html { scroll-behavior: smooth; }

    .reveal-on-scroll {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity .6s ease, transform .6s ease;
      will-change: opacity, transform;
    }

    .reveal-on-scroll.is-visible {
      opacity: 1;
      transform: translateY(0);
    }

    @media (prefers-reduced-motion: reduce) {
      .reveal-on-scroll {
        opacity: 1;
        transform: none;
        transition: none;
      }
    }
  </style>
@endpush

@section('content')
<div class="font-fa">
<!-- Navigation Header -->
<nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-ui-border reveal-on-scroll">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16 sm:h-20">
      <!-- Logo/Brand -->
      <div class="flex-1">
        <a href="{{ route('home') }}" class="flex items-center space-x-2 rtl:space-x-reverse">
          <img src="{{ asset('images/brand/logo.png') }}" alt="نێروان فێرگە" class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg object-contain" />
          <span class="hidden sm:inline text-xl sm:text-2xl font-ku font-bold text-brand-primary">نێروان فێرگە</span>
        </a>
      </div>

      <!-- Desktop Menu -->
      <div class="hidden md:flex items-center space-x-8 rtl:space-x-reverse">
        <a href="#features" class="text-ui-text hover:text-brand-primary transition">ویژگی‌ها</a>
        <a href="{{ route('about') }}" class="text-ui-text hover:text-brand-primary transition">درباره ما</a>
        <a href="{{ route('contact') }}" class="text-ui-text hover:text-brand-primary transition">تماس با ما</a>
      </div>

      <!-- Login Button -->
      <a href="{{ route('login') }}" class="md:ml-8 rtl:md:mr-8 px-4 sm:px-6 py-2 bg-brand-primary text-white rounded-lg font-semibold hover:bg-brand-primary/90 transition flex items-center space-x-2 rtl:space-x-reverse whitespace-nowrap text-sm sm:text-base">
        <x-ui.icon name="log-in" class="w-4 h-4 sm:w-5 sm:h-5" />
        <span>ورود</span>
      </a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="min-h-screen bg-gradient-to-br from-brand-primary/5 via-brand-secondary/5 to-transparent py-12 sm:py-20 reveal-on-scroll">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
      <!-- Left Content -->
      <div class="order-2 md:order-1">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-ku font-bold text-brand-primary mb-4 sm:mb-6 leading-tight">
          نێروان فێرگە
        </h1>
        <p class="text-lg sm:text-xl text-ui-text mb-4 sm:mb-6 leading-relaxed">
          پلتفرمی جامع آموزشی برای دانشجویان، معلمان و مربیان تحصیلی
        </p>
        <div class="space-y-4 sm:space-y-3 text-base sm:text-lg text-ui-muted mb-8 sm:mb-10">
          <p class="flex items-center space-x-3 rtl:space-x-reverse">
            <x-ui.icon name="check-circle-2" class="w-5 h-5 sm:w-6 sm:h-6 text-green-500 flex-shrink-0" />
            <span>یادگیری آسان و تعاملی</span>
          </p>
          <p class="flex items-center space-x-3 rtl:space-x-reverse">
            <x-ui.icon name="check-circle-2" class="w-5 h-5 sm:w-6 sm:h-6 text-green-500 flex-shrink-0" />
            <span>مدیریت دورۀ کلاسی کامل</span>
          </p>
          <p class="flex items-center space-x-3 rtl:space-x-reverse">
            <x-ui.icon name="check-circle-2" class="w-5 h-5 sm:w-6 sm:h-6 text-green-500 flex-shrink-0" />
            <span>بررسی‌های خودکار و فیدبک فوری</span>
          </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
          <a href="{{ route('register') }}" class="px-6 sm:px-8 py-3 sm:py-4 bg-brand-primary text-white rounded-xl font-semibold hover:bg-brand-primary/90 transition flex items-center justify-center space-x-2 rtl:space-x-reverse">
            <x-ui.icon name="user-plus" class="w-5 h-5" />
            <span>ثبت‌نام کنید</span>
          </a>
          <a href="{{ route('login') }}" class="px-6 sm:px-8 py-3 sm:py-4 border-2 border-brand-primary text-brand-primary rounded-xl font-semibold hover:bg-brand-primary/5 transition flex items-center justify-center space-x-2 rtl:space-x-reverse">
            <x-ui.icon name="log-in" class="w-5 h-5" />
            <span>وارد شوید</span>
          </a>
        </div>
      </div>

      <!-- Right Image/Illustration -->
      <div class="order-1 md:order-2 flex justify-center">
        <div class="w-full max-w-sm bg-gradient-to-br from-brand-primary/20 to-brand-secondary/20 rounded-2xl p-8 sm:p-12">
          <div class="space-y-6">
            <div class="h-20 sm:h-24 bg-brand-primary/10 rounded-xl flex items-center justify-center">
              <x-ui.icon name="book-open" class="w-12 h-12 sm:w-16 sm:h-16 text-brand-primary" />
            </div>
            <div class="h-20 sm:h-24 bg-brand-secondary/10 rounded-xl flex items-center justify-center">
              <x-ui.icon name="users" class="w-12 h-12 sm:w-16 sm:h-16 text-brand-secondary" />
            </div>
            <div class="h-20 sm:h-24 bg-brand-primary/10 rounded-xl flex items-center justify-center">
              <x-ui.icon name="zap" class="w-12 h-12 sm:w-16 sm:h-16 text-brand-primary" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="py-16 sm:py-24 bg-white reveal-on-scroll">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12 sm:mb-16">
      <h2 class="text-3xl sm:text-4xl md:text-5xl font-fa-2 font-bold text-brand-primary mb-4">ویژگی‌های اصلی</h2>
      <p class="text-lg text-ui-muted max-w-2xl mx-auto">تمام ابزارهایی که برای موفقیت در آموزش نیاز دارید</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
      <!-- Feature 1 -->
      <div class="p-6 sm:p-8 border border-ui-border rounded-2xl hover:shadow-lg transition-all hover:-translate-y-1">
        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-brand-primary/10 rounded-xl flex items-center justify-center mb-4 sm:mb-6">
          <x-ui.icon name="book-open" class="w-6 h-6 sm:w-8 sm:h-8 text-brand-primary" />
        </div>
        <h3 class="text-xl sm:text-2xl font-fa-2 font-bold text-ui-text mb-2 sm:mb-3">مطالب آموزشی</h3>
        <p class="text-ui-muted text-sm sm:text-base">مطالب آموزشی سازمان‌یافته و ساختارمند برای یادگیری مؤثر</p>
      </div>

      <!-- Feature 2 -->
      <div class="p-6 sm:p-8 border border-ui-border rounded-2xl hover:shadow-lg transition-all hover:-translate-y-1">
        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-brand-secondary/10 rounded-xl flex items-center justify-center mb-4 sm:mb-6">
          <x-ui.icon name="book-check" class="w-6 h-6 sm:w-8 sm:h-8 text-brand-secondary" />
        </div>
        <h3 class="text-xl sm:text-2xl font-fa-2 font-bold text-ui-text mb-2 sm:mb-3">تکالیف و آزمون‌ها</h3>
        <p class="text-ui-muted text-sm sm:text-base">سیستم جامع تکالیف و آزمون‌های خودکار با نتایج فوری</p>
      </div>

      <!-- Feature 3 -->
      <div class="p-6 sm:p-8 border border-ui-border rounded-2xl hover:shadow-lg transition-all hover:-translate-y-1">
        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-brand-primary/10 rounded-xl flex items-center justify-center mb-4 sm:mb-6">
          <x-ui.icon name="message-circle" class="w-6 h-6 sm:w-8 sm:h-8 text-brand-primary" />
        </div>
        <h3 class="text-xl sm:text-2xl font-fa-2 font-bold text-ui-text mb-2 sm:mb-3">تعامل و ارتباط</h3>
        <p class="text-ui-muted text-sm sm:text-base">سیستم پیام‌رسانی و گفتگو برای تعامل بین دانشجویان و معلمان</p>
      </div>

      <!-- Feature 4 -->
      <div class="p-6 sm:p-8 border border-ui-border rounded-2xl hover:shadow-lg transition-all hover:-translate-y-1">
        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-brand-secondary/10 rounded-xl flex items-center justify-center mb-4 sm:mb-6">
          <x-ui.icon name="chart-column" class="w-6 h-6 sm:w-8 sm:h-8 text-brand-secondary" />
        </div>
        <h3 class="text-xl sm:text-2xl font-fa-2 font-bold text-ui-text mb-2 sm:mb-3">گزارشات و آمار</h3>
        <p class="text-ui-muted text-sm sm:text-base">دسترسی به گزارشات دفصیل و آمار پیشرفت دانشجویان</p>
      </div>

      <!-- Feature 5 -->
      <div class="p-6 sm:p-8 border border-ui-border rounded-2xl hover:shadow-lg transition-all hover:-translate-y-1">
        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-brand-primary/10 rounded-xl flex items-center justify-center mb-4 sm:mb-6">
          <x-ui.icon name="bell" class="w-6 h-6 sm:w-8 sm:h-8 text-brand-primary" />
        </div>
        <h3 class="text-xl sm:text-2xl font-fa-2 font-bold text-ui-text mb-2 sm:mb-3">اطلاعات و یادآوری</h3>
        <p class="text-ui-muted text-sm sm:text-base">سیستم اطلاع‌رسانی برای عدم غفلت از مهم‌ترین رویدادها</p>
      </div>

      <!-- Feature 6 -->
      <div class="p-6 sm:p-8 border border-ui-border rounded-2xl hover:shadow-lg transition-all hover:-translate-y-1">
        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-brand-secondary/10 rounded-xl flex items-center justify-center mb-4 sm:mb-6">
          <x-ui.icon name="lock" class="w-6 h-6 sm:w-8 sm:h-8 text-brand-secondary" />
        </div>
        <h3 class="text-xl sm:text-2xl font-fa-2 font-bold text-ui-text mb-2 sm:mb-3">امنیت و حریم‌خصوصی</h3>
        <p class="text-ui-muted text-sm sm:text-base">حفاظت کامل اطلاعات و حریم‌خصوصی کاربران</p>
      </div>
    </div>
  </div>
</section>

<!-- About Section -->
<section id="about" class="py-16 sm:py-24 bg-ui-bg reveal-on-scroll">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
      <!-- Stats -->
      <div class="space-y-8">
        <div>
          <h2 class="text-3xl sm:text-4xl md:text-5xl font-fa-2 font-bold text-brand-primary mb-4 sm:mb-6">
            درباره <span class="font-ku">نێروان فێرگە</span>
          </h2>
          <p class="text-base sm:text-lg text-ui-muted leading-relaxed">
            <span class="font-ku">نێروان فێرگە</span> یک پلتفرم آموزشی جامع است که برای کمک به معلمان و دانشجویان در فرایند یادگیری و تدریس طراحی شده است. ما باور داریم که آموزش باید دسترس‌پذیر، مؤثر و لذت‌بخش باشد.
          </p>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:gap-6">
          <div class="p-4 sm:p-6 bg-white rounded-xl border border-ui-border">
            <div class="text-2xl sm:text-3xl font-fa-2 font-bold text-brand-primary mb-1">۵۰۰+</div>
            <p class="text-ui-muted text-sm sm:text-base">دانشجوی فعال</p>
          </div>
          <div class="p-4 sm:p-6 bg-white rounded-xl border border-ui-border">
            <div class="text-2xl sm:text-3xl font-fa-2 font-bold text-brand-primary mb-1">۱۰۰+</div>
            <p class="text-ui-muted text-sm sm:text-base">دوره آموزشی</p>
          </div>
          <div class="p-4 sm:p-6 bg-white rounded-xl border border-ui-border">
            <div class="text-2xl sm:text-3xl font-fa-2 font-bold text-brand-primary mb-1">۹۸%</div>
            <p class="text-ui-muted text-sm sm:text-base">رضایت کاربران</p>
          </div>
          <div class="p-4 sm:p-6 bg-white rounded-xl border border-ui-border">
            <div class="text-2xl sm:text-3xl font-fa-2 font-bold text-brand-primary mb-1">۲۴/۷</div>
            <p class="text-ui-muted text-sm sm:text-base">پشتیبانی</p>
          </div>
        </div>
      </div>

      <!-- Image Placeholder -->
      <div class="flex justify-center">
        <div class="w-full max-w-sm aspect-square bg-gradient-to-br from-brand-primary/20 to-brand-secondary/20 rounded-2xl flex items-center justify-center">
          <x-ui.icon name="rocket" class="w-32 h-32 sm:w-40 sm:h-40 text-brand-primary/50" />
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section id="contact" class="py-16 sm:py-24 bg-gradient-to-r from-brand-primary to-brand-secondary text-white reveal-on-scroll">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl sm:text-4xl md:text-5xl font-fa-2 font-bold mb-4 sm:mb-6">
      آماده برای شروع هستید؟
    </h2>
    <p class="text-base sm:text-lg mb-8 sm:mb-10 opacity-90">
      امروز ثبت‌نام کنید و به جامعۀ یادگیری ما بپیوندید
    </p>
    <a href="{{ route('register') }}" class="inline-flex items-center space-x-2 rtl:space-x-reverse px-8 py-4 bg-white text-brand-primary rounded-xl font-bold hover:shadow-lg transition-all hover:scale-105">
      <x-ui.icon name="user-plus" class="w-5 h-5" />
      <span>ثبت‌نام رایگان</span>
    </a>
  </div>
</section>

<!-- Footer -->
<footer class="bg-ui-text text-white py-12 sm:py-16 reveal-on-scroll">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Footer Content -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-8 sm:mb-12">
      <!-- Brand -->
      <div>
        <div class="flex items-center space-x-2 rtl:space-x-reverse mb-4">
          <img src="{{ asset('images/brand/logo.png') }}" alt="نێروان فێرگە" class="h-8 w-8 rounded-lg object-contain" />
          <span class="font-ku font-bold text-lg">نێروان فێرگە</span>
        </div>
        <p class="text-white/70 text-sm">پلتفرم جامع آموزشی برای دانشجویان و معلمان</p>
      </div>

      <!-- Quick Links -->
      <div>
        <h4 class="font-bold mb-4 text-white">پیوندهای سریع</h4>
        <ul class="space-y-2 text-white/70 text-sm">
          <li><a href="#features" class="hover:text-white transition">ویژگی‌ها</a></li>
          <li><a href="{{ route('about') }}" class="hover:text-white transition">درباره ما</a></li>
          <li><a href="{{ route('login') }}" class="hover:text-white transition">ورود</a></li>
          <li><a href="{{ route('register') }}" class="hover:text-white transition">ثبت‌نام</a></li>
        </ul>
      </div>

      <!-- Support -->
      <div>
        <h4 class="font-bold mb-4 text-white">پشتیبانی</h4>
        <ul class="space-y-2 text-white/70 text-sm">
          <li><a href="#" class="hover:text-white transition">مرکز راهنما</a></li>
          <li><a href="#" class="hover:text-white transition">FAQ</a></li>
          <li><a href="{{ route('contact') }}" class="hover:text-white transition">تماس با ما</a></li>
          <li><a href="#" class="hover:text-white transition">گزارش مشکل</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div>
        <h4 class="font-bold mb-4 text-white">ارتباط</h4>
        <ul class="space-y-3 text-white/70 text-sm">
          <li class="flex items-center space-x-2 rtl:space-x-reverse">
            <x-ui.icon name="mail" class="w-4 h-4" />
            <a href="mailto:info@example.com" class="hover:text-white transition">info@example.com</a>
          </li>
          <li class="flex items-center space-x-2 rtl:space-x-reverse">
            <x-ui.icon name="phone" class="w-4 h-4" />
            <span>۰۲۱-۱۲۳۴۵۶۷۸</span>
          </li>
        </ul>
      </div>
    </div>

    <!-- Divider -->
    <div class="border-t border-white/10 my-8"></div>

    <!-- Bottom Footer -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
      <p class="text-white/70 text-sm text-center sm:text-right">
        تمام حقوق محفوظ است © ۱۴۰۲ <span class="font-ku">نێروان فێرگە</span>
      </p>
      <div class="flex justify-center sm:justify-end gap-6">
        <a href="#" class="text-white/70 hover:text-white transition">
          <x-ui.icon name="facebook" class="w-5 h-5" />
        </a>
        <a href="#" class="text-white/70 hover:text-white transition">
          <x-ui.icon name="twitter" class="w-5 h-5" />
        </a>
        <a href="#" class="text-white/70 hover:text-white transition">
          <x-ui.icon name="instagram" class="w-5 h-5" />
        </a>
      </div>
    </div>
  </div>
</footer>
</div>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sections = document.querySelectorAll('.reveal-on-scroll');
      if (!sections.length) return;

      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            obs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

      sections.forEach((section) => observer.observe(section));
    });
  </script>
@endpush

@endsection
