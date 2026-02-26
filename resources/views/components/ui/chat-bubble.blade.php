@props(['me' => false, 'time' => null])

<div class="flex {{ $me ? 'justify-start' : 'justify-end' }}">
  <div class="max-w-[78%] rounded-2xl px-4 py-3 border
    {{ $me ? 'bg-brand-secondary text-white border-brand-secondary/30' : 'bg-ui-surface text-ui-text border-ui-border' }}">
    <div class="text-sm leading-7">{{ $slot }}</div>
    @if($time)
      <div class="mt-2 text-xs opacity-80">{{ $time }}</div>
    @endif
  </div>
</div>