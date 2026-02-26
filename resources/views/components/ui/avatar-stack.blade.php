@props(['items' => [], 'max' => 4])

<div class="flex items-center">
  @foreach(array_slice($items, 0, $max) as $i => $src)
    <div class="-ml-3 first:ml-0">
      <x-ui.avatar :src="$src" size="sm" />
    </div>
  @endforeach

  @if(count($items) > $max)
    <div class="-ml-3">
      <div class="h-8 w-8 rounded-full border border-ui-border bg-ui-surface flex items-center justify-center text-xs font-extrabold">
        +@faNum(count($items) - $max)
      </div>
    </div>
  @endif
</div>