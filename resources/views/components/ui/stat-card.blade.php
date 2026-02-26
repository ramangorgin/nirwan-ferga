@props(['title' => '', 'value' => '', 'hint' => null, 'tone' => 'info'])

@php
$tones = [
  'info' => 'bg-brand-secondary/10 text-brand-secondary',
  'success' => 'bg-brand-success/10 text-brand-success',
  'warning' => 'bg-brand-primary/10 text-brand-primary',
];
@endphp

<x-ui.card class="p-6">
  <div class="flex items-start justify-between">
    <div>
      <div class="text-sm font-bold text-ui-muted">{{ $title }}</div>
      <div class="mt-2 text-3xl font-extrabold text-ui-text">{{ $value }}</div>
    </div>
    <div class="h-10 w-10 rounded-2xl flex items-center justify-center {{ $tones[$tone] }}">
      {{ $icon ?? '•' }}
    </div>
  </div>

  @if($hint)
    <div class="mt-4 text-xs text-ui-muted">{{ $hint }}</div>
  @endif
</x-ui.card>