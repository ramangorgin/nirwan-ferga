@props(['steps' => [], 'active' => 1])

<div class="rounded-2xl border border-ui-border bg-ui-surface p-3">
  <div class="grid grid-cols-{{ max(1, count($steps)) }} gap-2">
    @foreach($steps as $i => $label)
      @php $n = $i + 1; @endphp
      <div class="flex items-center justify-center gap-2 rounded-xl px-3 py-2 text-sm font-extrabold
        {{ $n === $active ? 'bg-brand-secondary/10 text-brand-secondary' : 'bg-ui-bg text-ui-muted' }}">
        <span class="h-7 w-7 rounded-full flex items-center justify-center
          {{ $n === $active ? 'bg-brand-secondary text-white' : 'bg-ui-surface border border-ui-border text-ui-muted' }}">
          {{ $n }}
        </span>
        <span>{{ $label }}</span>
      </div>
    @endforeach
  </div>
</div>