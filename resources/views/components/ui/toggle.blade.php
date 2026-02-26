@props(['checked' => false])

<label class="inline-flex items-center cursor-pointer">
  <input type="checkbox" class="sr-only peer" @checked($checked)>
  <div class="w-11 h-6 bg-ui-border rounded-full peer peer-checked:bg-brand-secondary transition relative">
    <div class="absolute right-1 top-1 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-5"></div>
  </div>
</label>