@props(['count' => 0])

<div {{ $attributes->merge([
  'class' => 'flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-ui-border bg-ui-surface p-4 shadow-soft'
]) }}>
  <div class="text-sm font-extrabold text-ui-text">
    @faNum($count) مورد انتخاب شده
  </div>

  <div class="flex items-center gap-2">
    {{ $slot }}
  </div>
</div>