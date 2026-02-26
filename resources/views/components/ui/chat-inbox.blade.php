@props(['title' => 'صندوق پیام', 'search' => true])

<x-ui.card class="p-4">
  <div class="flex items-center justify-between">
    <div class="text-lg font-extrabold">{{ $title }}</div>
    <button class="h-10 w-10 rounded-xl bg-ui-bg hover:bg-ui-border/40">✎</button>
  </div>

  @if($search)
    <div class="mt-4">
      <x-ui.input placeholder="جستجو در گفتگوها..." />
    </div>
  @endif

  <div class="mt-4 space-y-2">
    {{ $slot }}
  </div>
</x-ui.card>