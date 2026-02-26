<div class="relative">
  <button type="button" class="h-10 w-10 rounded-xl bg-ui-bg hover:bg-ui-border/40 flex items-center justify-center text-ui-text">
    <x-ui.icon name="more-horizontal" class="h-5 w-5" />
  </button>

  @if($open)
    <div class="absolute left-0 mt-2 w-44 rounded-2xl border border-ui-border bg-ui-surface shadow-soft overflow-hidden z-10">
      {{ $slot }}
    </div>
  @endif
</div>