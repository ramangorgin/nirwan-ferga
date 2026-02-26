@props(['items' => [], 'active' => null])

@php
$activeKey = $active ?? (count($items) ? array_key_first($items) : null);
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2 rounded-2xl bg-ui-surface border border-ui-border p-2']) }}>
  @foreach($items as $key => $label)
    <button type="button"
      class="h-10 px-4 rounded-xl text-sm font-bold transition
      {{ $key === $activeKey ? 'bg-brand-secondary text-white' : 'bg-ui-bg text-ui-text hover:bg-ui-border/40' }}">
      {{ $label }}
    </button>
  @endforeach
</div>