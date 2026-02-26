@props(['checked' => false])

<input type="checkbox"
  @checked($checked)
  {{ $attributes->merge([
    'class' => 'h-4 w-4 rounded border-ui-border text-brand-secondary focus:ring-brand-secondary'
  ]) }} />