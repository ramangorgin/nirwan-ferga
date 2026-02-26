@props([
  'title' => '',
  'subtitle' => null,
])

<div class="flex flex-wrap items-start justify-between gap-4">
  <div>
    <div class="text-2xl font-extrabold">{{ $title }}</div>
    @if($subtitle)
      <div class="mt-1 text-ui-muted">{{ $subtitle }}</div>
    @endif
  </div>
  <div class="flex items-center gap-2">
    {{ $actions ?? '' }}
  </div>
</div>