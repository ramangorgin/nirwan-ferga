@props([
  'open' => false,
  'title' => 'تایید عملیات',
  'text' => 'آیا مطمئن هستید؟',
])

<x-ui.modal :open="$open" :title="$title">
  <div class="text-sm text-ui-muted leading-7">
    {{ $text }}
  </div>

  <x-slot:footer>
    <div class="flex items-center gap-3">
      <x-ui.button>تایید</x-ui.button>
      <x-ui.button variant="ghost">انصراف</x-ui.button>
    </div>
  </x-slot:footer>
</x-ui.modal>