@props([
  'title' => 'چیزی برای نمایش وجود ندارد',
  'text' => null,
  'actionText' => null,
])

<x-ui.card class="p-8 text-center">
  <div class="mx-auto h-14 w-14 rounded-2xl bg-ui-bg border border-ui-border"></div>
  <div class="mt-4 text-lg font-extrabold">{{ $title }}</div>
  @if($text)
    <div class="mt-2 text-sm text-ui-muted leading-7">{{ $text }}</div>
  @endif

  @if($actionText)
    <div class="mt-6">
      <x-ui.button variant="secondary">{{ $actionText }}</x-ui.button>
    </div>
  @endif
</x-ui.card>