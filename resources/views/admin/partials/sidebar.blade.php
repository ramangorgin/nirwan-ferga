<nav class="px-3 pb-6">
  <div class="space-y-1">

    <a href="{{ route('admin.dashboard') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
              {{ request()->routeIs('admin.dashboard') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="layout-dashboard" />
      <span>داشبورد</span>
    </a>

    <a href="{{ route('courses.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('courses.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="book" />
      <span>دوره‌ها</span>
    </a>

    <a href="{{ route('admin.users.index', ['role' => 'teacher']) }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('admin.users.*') && request('role') === 'teacher' ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="user-star" />
      <span>مدرسین</span>
    </a>

    <a href="{{ route('admin.users.index', ['role' => 'student']) }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('admin.users.*') && request('role') === 'student' ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="users" />
      <span>دانش‌آموزان</span>
    </a>

    <div class="my-3 border-t border-ui-border"></div>

    <a href="{{ route('discount-codes.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('discount-codes.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="ticket" />
      <span>کدهای تخفیف</span>
    </a>

    <a href="{{ route('enrollments.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('enrollments.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="clipboard-check" />
      <span>درخواست‌های ثبت‌نام</span>
    </a>

    <a href="{{ route('admin.announcements.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
              {{ request()->routeIs('admin.announcements.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="megaphone" />
      <span>اطلاعیه‌ها</span>
    </a>

    <a href="{{ route('tickets.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('tickets.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="messages-square" />
      <span>تیکت‌ها</span>
    </a>

    <a href="{{ route('admin.posts.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
              {{ request()->routeIs('admin.posts.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="newspaper" />
      <span>پست‌ها (وبلاگ)</span>
    </a>

    <div class="my-3 border-t border-ui-border"></div>

    <a href="{{ route('profile.edit') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('profile.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="settings" />
      <span>تنظیمات</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="pt-3">
      @csrf
      <button type="submit"
              data-confirm
              data-confirm-title="خروج"
              data-confirm-text="آیا می‌خواهید از حساب کاربری خارج شوید؟"
              data-confirm-yes="بله"
              data-confirm-no="انصراف"
              class="w-full flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">
        <x-ui.icon name="log-out" />
        <span>خروج</span>
      </button>
    </form>

  </div>
</nav>