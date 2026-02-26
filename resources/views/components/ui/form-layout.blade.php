<form {{ $attributes->merge(['class' => 'space-y-6']) }}>
  {{ $slot }}

  <div class="flex items-center justify-end gap-3 pt-4 border-t border-ui-border">
    {{ $actions ?? '' }}
  </div>
</form>