@props(['title' => 'فایل را اینجا رها کنید', 'hint' => 'PNG, JPG, PDF'])

<div class="rounded-2xl border-2 border-dashed border-ui-border bg-ui-bg p-8 text-center">
  <div class="mx-auto h-12 w-12 rounded-2xl bg-ui-surface border border-ui-border flex items-center justify-center text-ui-text">
    <x-ui.icon name="upload" class="h-6 w-6" />
  </div>
  <div class="mt-4 font-extrabold">{{ $title }}</div>
  <div class="mt-1 text-sm text-ui-muted">{{ $hint }}</div>
  <div class="mt-4">
    <x-ui.button variant="ghost">انتخاب فایل</x-ui.button>
  </div>
</div>