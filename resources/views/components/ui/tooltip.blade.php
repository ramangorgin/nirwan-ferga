@props(['text' => ''])

<span class="relative inline-flex items-center">
  {{ $slot }}

  {{-- UI only: always visible for now; later controlled with JS --}}
  <span class="absolute bottom-full right-1/2 translate-x-1/2 mb-2 whitespace-nowrap rounded-xl bg-ui-text text-white px-3 py-1 text-xs font-bold shadow-soft">
    {{ $text }}
  </span>
</span>