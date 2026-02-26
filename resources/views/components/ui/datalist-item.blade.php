@props(['label' => '', 'value' => ''])

<div class="flex items-center justify-between gap-4 py-2">
  <div class="text-sm font-bold text-ui-muted">{{ $label }}</div>
  <div class="text-sm font-extrabold text-ui-text">{{ $value }}</div>
</div>