@props([
  'variant' => 'primary', // primary | secondary | ghost
  'size' => 'md',         // sm | md | lg
])

@php
$base = "inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition select-none focus:outline-none focus:ring-2 focus:ring-brand-primary/30";

$sizes = [
  'sm' => "h-9 px-3 text-sm",
  'md' => "h-11 px-4 text-sm",
  'lg' => "h-12 px-5 text-base",
];

$variants = [
  'primary' => "bg-brand-primary text-white hover:bg-brand-primaryHover shadow-soft",
  'secondary' => "bg-brand-secondary text-white hover:bg-brand-secondaryHover shadow-soft",
  'ghost' => "bg-ui-surface border border-ui-border text-ui-text hover:bg-ui-bg",
];
@endphp

<button {{ $attributes->merge(['class' => "$base {$sizes[$size]} {$variants[$variant]}"]) }}>
  {{ $slot }}
</button>