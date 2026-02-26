@props(['tone' => 'info']) {{-- info | success | warning | danger --}}

@php
$tones = [
  'info' => 'bg-brand-secondary/10 text-brand-secondary',
  'success' => 'bg-brand-success/10 text-brand-success',
  'warning' => 'bg-brand-primary/10 text-brand-primary',
  'danger' => 'bg-red-100 text-red-600',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ' . $tones[$tone]]) }}>
  {{ $slot }}
</span>