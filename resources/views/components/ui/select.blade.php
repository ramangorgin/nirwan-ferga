@props([
  'label' => null,
  'hint' => null,
  'error' => null,
  'required' => false,

  // option-mode
  'options' => null,   // array|null
  'value' => null,
])

<x-ui.form-group :label="$label" :hint="$hint" :error="$error" :required="$required">
  <select {{ $attributes->merge([
    'class' =>
      'w-full h-11 rounded-xl bg-ui-surface border px-3 text-sm font-semibold text-ui-text focus:outline-none focus:ring-2 ' .
      ($error ? 'border-red-300 focus:ring-red-200' : 'border-ui-border focus:ring-brand-primary/30')
  ]) }}>
    @if(is_array($options))
      @foreach($options as $key => $text)
        <option value="{{ $key }}" @selected((string)$key === (string)$value)>{{ $text }}</option>
      @endforeach
    @else
      {{ $slot }}
    @endif
  </select>
</x-ui.form-group>