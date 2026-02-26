@props(['open' => false, 'title' => ''])

@if($open)
  <div class="fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="absolute inset-0 flex items-center justify-center p-4">
      <div class="w-full max-w-xl rounded-2xl bg-ui-surface border border-ui-border shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-ui-border flex items-center justify-between">
          <div class="text-lg font-extrabold">{{ $title }}</div>
          <button class="h-10 w-10 rounded-xl hover:bg-ui-bg">✕</button>
        </div>

        <div class="p-6">
          {{ $slot }}
        </div>

        @if(isset($footer))
          <div class="px-6 py-4 border-t border-ui-border bg-ui-bg">
            {{ $footer }}
          </div>
        @endif
      </div>
    </div>
  </div>
@endif