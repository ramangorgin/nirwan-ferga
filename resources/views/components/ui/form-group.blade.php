@props([
  'label' => null,
  'hint' => null,
  'error' => null,
  'required' => false,
])

<div class="space-y-2">
  @if($label)
    <label class="block text-sm font-bold text-ui-text">
      {{ $label }}
      @if($required)
        <span class="text-red-600">*</span>
      @endif
    </label>
  @endif

  {{ $slot }}

  @if($hint)
    <div class="text-xs text-ui-muted leading-6">
      {{ $hint }}
    </div>
  @endif

  @if($error)
    <div class="text-xs text-red-600 font-semibold">
      {{ $error }}
    </div>
  @endif
</div>