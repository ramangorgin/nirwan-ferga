@props([
  'tone' => 'info',
  'title' => 'پیام',
  'text' => null,
])

@php
$tones = [
  'info' => 'border-brand-secondary/20 bg-ui-surface',
  'success' => 'border-brand-success/20 bg-ui-surface',
  'warning' => 'border-brand-primary/20 bg-ui-surface',
  'danger' => 'border-red-200 bg-ui-surface',
];
@endphp

<div {{ $attributes->merge(['class' => 'w-full max-w-sm rounded-2xl border shadow-soft p-4 ' . $tones[$tone]]) }}>
  <div class="flex items-start justify-between gap-3">
    <div>
      <div class="font-extrabold">{{ $title }}</div>
      @if($text)
        <div class="mt-1 text-sm text-ui-muted leading-7">{{ $text }}</div>
      @endif
    </div>
    <button class="h-9 w-9 rounded-xl bg-ui-bg hover:bg-ui-border/40 flex items-center justify-center text-ui-text hover:text-ui-text">
      <x-ui.icon name="x" class="h-5 w-5" />
    </button>
  </div>
</div>