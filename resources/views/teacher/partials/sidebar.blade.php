<nav class="px-3 pb-6">
  <div class="space-y-1">

    <a href="{{ route('teacher.dashboard') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
              {{ request()->routeIs('teacher.dashboard') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="layout-dashboard" />
      <span>داشبورد</span>
    </a>

    <a href="{{ route('courses.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('courses.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="book" />
      <span>دوره‌های من</span>
    </a>

    <a href="{{ route('enrollments.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('enrollments.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="users" />
      <span>دانش‌آموزان من</span>
    </a>

    <div class="my-3 border-t border-ui-border"></div>

    <a href="{{ route('class-sessions.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('class-sessions.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="file-text" />
      <span>تکالیف</span>
    </a>

    <a href="{{ route('class-sessions.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('class-sessions.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="list-checks" />
      <span>تکالیف حل‌شده</span>
    </a>

    <a href="{{ route('admin.quizzes.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('admin.quizzes.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="clipboard-list" />
      <span>آزمونک‌ها</span>
    </a>

    <a href="{{ route('admin.quizzes.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('admin.quizzes.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="send" />
      <span>آزمونک‌های فرستاده شده</span>
    </a>

    <div class="my-3 border-t border-ui-border"></div>

    <a href="{{ route('conversations.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('conversations.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="message-square" />
      <span>گفتگوها</span>
    </a>

    <a href="{{ route('tickets.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('tickets.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="messages-square" />
      <span>تیکت‌ها</span>
    </a>

    <a href="{{ route('announcements.public.index') }}"
       class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold
          {{ request()->routeIs('announcements.public.*') ? 'bg-brand-primary/10 text-brand-primary' : 'hover:bg-ui-bg' }}">
      <x-ui.icon name="megaphone" />
      <span>اطلاعیه‌ها</span>
    </a>

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