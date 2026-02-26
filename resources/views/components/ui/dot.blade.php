@props(['tone' => 'info']) {{-- info | success | warning | danger --}}

@php
$tones = [
  'info' => 'bg-brand-secondary',
  'success' => 'bg-brand-success',
  'warning' => 'bg-brand-primary',
  'danger' => 'bg-red-600',
];
@endphp

<span class="inline-block h-2.5 w-2.5 rounded-full {{ $tones[$tone] }}"></span>