@props([
  'name' => '',
  'snippet' => '',
  'time' => '',
  'active' => false,
  'unread' => false,
])

<div class="flex items-center gap-3 rounded-2xl p-3 border border-ui-border cursor-pointer
  {{ $active ? 'bg-brand-secondary/10 border-brand-secondary/20' : 'bg-ui-surface hover:bg-ui-bg' }}">
  <div class="h-10 w-10 rounded-full bg-ui-bg border border-ui-border"></div>

  <div class="flex-1 min-w-0">
    <div class="flex items-center justify-between gap-2">
      <div class="font-extrabold truncate">{{ $name }}</div>
      <div class="text-xs text-ui-muted whitespace-nowrap">{{ $time }}</div>
    </div>
    <div class="text-sm text-ui-muted truncate">{{ $snippet }}</div>
  </div>

  @if($unread)
    <span class="h-2.5 w-2.5 rounded-full bg-brand-secondary"></span>
  @endif
</div>