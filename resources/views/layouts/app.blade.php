<!doctype html>
<html lang="fa" dir="rtl" class="bg-ui-bg text-ui-text">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ trim($__env->yieldContent('title', env('APP_BRAND_DISPLAY', 'نێروان فێرگە'))) }}</title>

  {{-- App CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/app.css') }}">

  {{-- PWA / icons --}}
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('site.webmanifest') }}">

  @livewireStyles

  {{-- Vendor CSS (local) --}}
  <link rel="stylesheet" href="{{ asset('vendor/animate/animate.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/jalalidatepicker/jalalidatepicker.min.css') }}">

  @stack('head')
</head>

<body class="min-h-screen bg-ui-bg text-ui-text">
  {{-- Prefer slot for component layouts; fallback to sections --}}
  {{ $slot ?? '' }}
  @yield('content')

  @livewireScripts

  {{-- App init --}}
  <script src="{{ asset('assets/app.js') }}"></script>

  {{-- Vendor JS (local) --}}
  <script src="{{ asset('vendor/select2/jquery.min.js') }}"></script>

  <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>

  <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('vendor/jalalidatepicker/jalalidatepicker.min.js') }}"></script>

  <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

  <script src="{{ asset('vendor/alpine/alpine.min.js') }}" defer></script>

  {{-- App init MUST be last --}}
  <script src="{{ asset('assets/app.js') }}"></script>

  @stack('scripts')

</body>
</html>