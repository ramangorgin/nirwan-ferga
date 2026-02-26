@props(['striped' => true])

<div class="overflow-hidden rounded-2xl border border-ui-border bg-ui-surface shadow-soft">
  <div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full text-right text-sm']) }}>
      {{ $slot }}
    </table>
  </div>
</div>