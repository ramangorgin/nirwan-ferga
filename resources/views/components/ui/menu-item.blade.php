@props(['danger' => false])

<button type="button"
  class="w-full text-right px-4 py-3 text-sm font-semibold transition
  {{ $danger ? 'text-red-600 hover:bg-red-50' : 'text-ui-text hover:bg-ui-bg' }}">
  {{ $slot }}
</button>