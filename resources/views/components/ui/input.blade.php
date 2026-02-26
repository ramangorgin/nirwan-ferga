@props(['label' => null, 'error' => null])

<label class="block">
  @if($label)
    <span class="block mb-2 text-sm font-semibold text-ui-text">{{ $label }}</span>
  @endif

  <input {{ $attributes->merge(['class' =>
    'w-full h-11 rounded-xl bg-ui-surface border px-3 text-ui-text placeholder:text-ui-muted focus:outline-none focus:ring-2 ' .
    ($error ? 'border-red-300 focus:ring-red-200' : 'border-ui-border focus:ring-brand-primary/30')
  ]) }}>

  @if($error)
    <div class="mt-2 text-xs text-red-600">{{ $error }}</div>
  @endif
</label>