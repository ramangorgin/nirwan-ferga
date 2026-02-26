@props(['tone' => 'neutral']) {{-- neutral | info | success | warning | danger --}}

@php
$tones = [
  'neutral' => 'bg-ui-bg text-ui-text border-ui-border',
  'info' => 'bg-brand-secondary/10 text-brand-secondary border-brand-secondary/20',
  'success' => 'bg-brand-success/10 text-brand-success border-brand-success/20',
  'warning' => 'bg-brand-primary/10 text-brand-primary border-brand-primary/20',
  'danger' => 'bg-red-50 text-red-700 border-red-200',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-extrabold ' . $tones[$tone]]) }}>
  {{ $slot }}
</span>