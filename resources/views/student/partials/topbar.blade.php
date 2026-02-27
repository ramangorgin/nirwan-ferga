<header class="sticky top-0 z-30 bg-ui-bg/80 backdrop-blur border-b border-ui-border">
  <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-4">

    <div class="flex items-center gap-3">
      {{-- Mobile sidebar button (optional later) --}}
      <div class="lg:hidden">
        <button class="h-10 w-10 rounded-xl border border-ui-border bg-ui-surface hover:bg-ui-bg">
          <x-ui.icon name="menu-2" />
        </button>
      </div>

      <div>
        <div class="text-sm text-ui-muted">خوش آمدید</div>
        <div class="font-extrabold text-lg leading-5">داشبورد</div>
      </div>
    </div>

    <div class="flex items-center gap-2">
      {{-- Notifications (bell) --}}
      <a href="{{ route('notifications.index') }}"
         class="h-10 w-10 rounded-xl border border-ui-border bg-ui-surface hover:bg-ui-bg grid place-items-center"
         title="اعلان‌ها">
        <x-ui.icon name="bell" />
      </a>

      {{-- Avatar --}}
      <a href="{{ route('profile.edit') }}" class="flex items-center gap-2">
        <div class="h-10 w-10 rounded-full bg-ui-surface border border-ui-border overflow-hidden"></div>
      </a>
    </div>

  </div>
</header>