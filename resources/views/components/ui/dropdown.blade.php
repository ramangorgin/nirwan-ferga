@props(['label' => 'منو'])

<div class="relative inline-block">
  <button type="button" class="h-11 px-4 rounded-xl border border-ui-border bg-ui-surface hover:bg-ui-bg font-bold">
    {{ $label }}
  </button>

  {{-- UI only: show by default in playground. later we control visibility with JS --}}
  <div class="mt-2 w-56 rounded-2xl border border-ui-border bg-ui-surface shadow-soft overflow-hidden">
    {{ $slot }}
  </div>
</div>