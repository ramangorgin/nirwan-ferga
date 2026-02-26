@props(['title' => '', 'status' => 'آنلاین'])

<x-ui.card class="p-0 overflow-hidden lg:col-span-2">
  <div class="p-4 border-b border-ui-border flex items-center justify-between">
    <div class="flex items-center gap-3">
      <div class="h-10 w-10 rounded-full bg-ui-bg border border-ui-border"></div>
      <div>
        <div class="font-extrabold">{{ $title }}</div>
        <div class="text-xs text-ui-muted">{{ $status }}</div>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <button class="h-10 w-10 rounded-xl bg-ui-bg hover:bg-ui-border/40 flex items-center justify-center text-ui-text">
        <x-ui.icon name="phone" class="h-5 w-5" />
      </button>
      <button class="h-10 w-10 rounded-xl bg-ui-bg hover:bg-ui-border/40 flex items-center justify-center text-ui-text">
        <x-ui.icon name="video" class="h-5 w-5" />
      </button>
      <button class="h-10 w-10 rounded-xl bg-ui-bg hover:bg-ui-border/40 flex items-center justify-center text-ui-text">
        <x-ui.icon name="more-horizontal" class="h-5 w-5" />
      </button>
    </div>
  </div>

  <div class="p-6 bg-ui-bg min-h-[420px]">
    <div class="space-y-3">
      {{ $slot }}
    </div>
  </div>

  <div class="p-4 border-t border-ui-border bg-ui-surface">
    <div class="flex items-center gap-2">
      <button class="h-11 w-11 rounded-xl bg-ui-bg hover:bg-ui-border/40 flex items-center justify-center text-ui-text">
        <x-ui.icon name="paperclip" class="h-5 w-5" />
      </button>
      <input class="flex-1 h-11 rounded-xl bg-ui-bg border border-ui-border px-3 focus:outline-none focus:ring-2 focus:ring-brand-primary/30"
             placeholder="پیام خود را بنویسید..." />
      <button class="h-11 w-11 rounded-xl bg-brand-secondary text-white hover:bg-brand-secondaryHover flex items-center justify-center">
        <x-ui.icon name="send" class="h-5 w-5" />
      </button>
    </div>
  </div>
</x-ui.card>