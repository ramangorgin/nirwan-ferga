@props([
  'tone' => 'info',   // info | success | warning | danger
  'title' => null,
])

@php
$tones = [
  'info' => 'bg-brand-secondary/10 text-brand-secondary border-brand-secondary/20',
  'success' => 'bg-brand-success/10 text-brand-success border-brand-success/20',
  'warning' => 'bg-brand-primary/10 text-brand-primary border-brand-primary/20',
  'danger' => 'bg-red-50 text-red-700 border-red-200',
];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border p-4 ' . $tones[$tone]]) }}>
  @if($title)
    <div class="font-extrabold mb-1">{{ $title }}</div>
  @endif
  <div class="text-sm leading-7">
    {{ $slot }}
  </div>
</div>