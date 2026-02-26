@props(['checked' => false, 'name' => null, 'value' => null])

<label class="inline-flex items-center gap-2 cursor-pointer">
  <input type="radio"
         name="{{ $name }}"
         value="{{ $value }}"
         class="h-4 w-4 border-ui-border text-brand-secondary focus:ring-brand-secondary"
         @checked($checked)>
  <span class="text-sm font-semibold text-ui-text">
    {{ $slot }}
  </span>
</label>