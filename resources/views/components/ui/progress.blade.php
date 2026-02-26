@props(['value' => 0]) {{-- 0..100 --}}

@php
$v = max(0, min(100, (int)$value));
@endphp

<div class="w-full">
  <div class="flex items-center justify-between text-xs text-ui-muted mb-2">
    <span>پیشرفت</span>
    <span>@faNum($v)%</span>
  </div>

  <div class="h-3 rounded-full bg-ui-border/40 overflow-hidden">
    <div class="h-full bg-brand-secondary" style="width: {{ $v }}%"></div>
  </div>
</div>