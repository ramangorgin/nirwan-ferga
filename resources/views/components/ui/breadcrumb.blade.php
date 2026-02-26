@props(['items' => []])

<nav class="flex flex-wrap items-center gap-2 text-sm text-ui-muted">
  @foreach($items as $i => $item)
    @if($i > 0)
      <span class="opacity-60">/</span>
    @endif

    @if(isset($item['href']))
      <a href="{{ $item['href'] }}" class="hover:text-ui-text font-semibold">{{ $item['label'] }}</a>
    @else
      <span class="text-ui-text font-extrabold">{{ $item['label'] }}</span>
    @endif
  @endforeach
</nav>