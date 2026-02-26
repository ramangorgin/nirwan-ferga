@props(['checked' => false])

<label class="inline-flex items-center gap-2 cursor-pointer">
  <input type="checkbox"
         class="h-4 w-4 rounded border-ui-border text-brand-secondary focus:ring-brand-secondary"
         @checked($checked)>
  <span class="text-sm font-semibold text-ui-text">
    {{ $slot }}
  </span>
</label>