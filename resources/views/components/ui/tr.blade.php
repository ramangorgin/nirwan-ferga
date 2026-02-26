@props(['selected' => false])

<tr {{ $attributes->merge([
  'class' =>
    'transition ' .
    ($selected ? 'bg-brand-secondary/5' : 'hover:bg-ui-bg')
]) }}>
  {{ $slot }}
</tr>