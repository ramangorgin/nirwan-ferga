@props([
  'name',
])

@php
$path = public_path("icons/lucide/{$name}.svg");
$cls = $attributes->get('class', '');

if (!file_exists($path)) {
  $svg = null;
} else {
  $svg = file_get_contents($path);
}
@endphp

@if($svg)
  <span class="inline-flex items-center justify-center {{ $cls }}">
    {!! $svg !!}
  </span>
@else
  <span class="inline-block w-5 h-5 rounded bg-ui-border/40"></span>
@endif