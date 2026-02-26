@props(['text' => 'در حال بارگذاری...'])

<div class="flex items-center justify-center gap-3 rounded-2xl border border-ui-border bg-ui-surface p-6">
  <div class="h-8 w-8 rounded-full border-4 border-ui-border border-t-brand-secondary animate-spin"></div>
  <div class="text-sm font-extrabold text-ui-text">{{ $text }}</div>
</div>