@props([
  'current' => 1,
  'total' => 10,
  'label' => 'صفحه', // متن قابل تغییر
])

<div class="flex flex-wrap items-center justify-between gap-4">
  <div class="text-sm text-ui-muted">
    {{ $label }} @faNum($current) از @faNum($total)
  </div>

  <div class="flex items-center gap-2">
    <button type="button" class="h-10 w-10 rounded-xl border border-ui-border bg-ui-surface hover:bg-ui-bg">‹</button>

    @for($i=1; $i<=$total; $i++)
      <button type="button"
        class="h-10 w-10 rounded-xl border border-ui-border
        {{ $i===$current ? 'bg-brand-secondary text-white border-brand-secondary' : 'bg-ui-surface hover:bg-ui-bg' }}">
        @faNum($i)
      </button>
    @endfor

    <button type="button" class="h-10 w-10 rounded-xl border border-ui-border bg-ui-surface hover:bg-ui-bg">›</button>
  </div>
</div>