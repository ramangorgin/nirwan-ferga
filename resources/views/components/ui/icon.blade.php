@props([
  'name',
  'size' => 20,
])

@php
$path = public_path("icons/lucide/{$name}.svg");
$svg = file_exists($path) ? file_get_contents($path) : null;

// Inject class + size into raw SVG.
// Keep it simple; assumes lucide svg has width/height attributes.
$cls = $attributes->get('class', '');
@endphp

@if($svg)
  {!! preg_replace(
    [
      '/width="[^"]*"/',
      '/height="[^"]*"/',
      '/class="[^"]*"/',
    ],
    [
      'width="'.$size.'"',
      'height="'.$size.'"',
      'class="'.$cls.'"',
    ],
    $svg
  ) !!}
@else
  <span class="inline-block w-5 h-5 rounded bg-ui-border/40"></span>
@endif