@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">

  {{-- Sidebar (RTL: right side) --}}
  <aside class="w-72 hidden lg:block border-l border-ui-border bg-ui-surface">
    <div class="p-5">
      <div class="flex items-center gap-3">
        <img src="{{ asset('logo.png') }}" class="h-10 w-10 rounded-xl object-cover" alt="Nirwan-ferga">
        <div>
          <div class="font-ku font-extrabold leading-5">نیروان فێرگە</div>
          <div class="text-xs text-ui-muted mt-1">پنل مدیریت</div>
        </div>
      </div>
    </div>

    @include('admin.partials.sidebar')
  </aside>

  <main class="flex-1 min-w-0">
    @include('admin.partials.topbar')

    <div class="p-4 sm:p-6">
      @yield('dashboard_content')
    </div>
  </main>

</div>
@endsection