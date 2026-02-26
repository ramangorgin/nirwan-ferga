@props([
  'open' => false,
  'title' => '',
])

@if($open)
  <div class="fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="absolute inset-y-0 right-0 w-full max-w-md bg-ui-surface border-l border-ui-border shadow-soft">
      <div class="p-4 border-b border-ui-border flex items-center justify-between">
        <div class="text-lg font-extrabold">{{ $title }}</div>
        <button class="h-10 w-10 rounded-xl hover:bg-ui-bg"></button>
      </div>

      <div class="p-6 overflow-y-auto max-h-[calc(100vh-160px)]">
        {{ $slot }}
      </div>

      @if(isset($footer))
        <div class="p-4 border-t border-ui-border bg-ui-bg">
          {{ $footer }}
        </div>
      @endif
    </div>
  </div>
@endif