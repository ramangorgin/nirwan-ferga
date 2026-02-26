@props(['src' => null, 'alt' => '', 'size' => 'md']) // sm|md|lg

@php
$sizes = [
  'sm' => 'h-8 w-8',
  'md' => 'h-10 w-10',
  'lg' => 'h-12 w-12',
];
@endphp

<div class="{{ $sizes[$size] }} rounded-full border border-ui-border bg-ui-bg overflow-hidden">
  @if($src)
    <img src="{{ $src }}" alt="{{ $alt }}" class="h-full w-full object-cover">
  @endif
</div>