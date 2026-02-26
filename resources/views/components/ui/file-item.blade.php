@props(['name' => '', 'meta' => ''])

<div class="flex items-center justify-between gap-3 rounded-2xl border border-ui-border bg-ui-surface p-4">
  <div class="flex items-center gap-3 min-w-0">
    <div class="h-10 w-10 rounded-xl bg-ui-bg border border-ui-border flex items-center justify-center">📄</div>
    <div class="min-w-0">
      <div class="font-bold truncate">{{ $name }}</div>
      <div class="text-xs text-ui-muted">{{ $meta }}</div>
    </div>
  </div>

  <div class="flex items-center gap-2">
    <button class="h-10 w-10 rounded-xl bg-ui-bg hover:bg-ui-border/40">⬇️</button>
    <button class="h-10 w-10 rounded-xl bg-ui-bg hover:bg-ui-border/40 text-red-600">🗑️</button>
  </div>
</div>