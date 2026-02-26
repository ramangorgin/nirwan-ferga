@props(['title' => null, 'subtitle' => null])

<x-ui.card class="p-6">
  @if($title)
    <div class="flex items-start justify-between">
      <div>
        <div class="text-lg font-extrabold text-ui-text">{{ $title }}</div>
        @if($subtitle)
          <div class="mt-1 text-sm text-ui-muted">{{ $subtitle }}</div>
        @endif
      </div>
      {{ $actions ?? '' }}
    </div>
    <div class="mt-6">
      {{ $slot }}
    </div>
  @else
    {{ $slot }}
  @endif
</x-ui.card>