<!doctype html>
<html lang="fa" dir="rtl" class="bg-ui-bg text-ui-text">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>
    @yield('title', env('APP_BRAND_DISPLAY', 'نیروان فێرگە'))
  </title>

  {{-- CSS (compiled tailwind output) --}}
  <link rel="stylesheet" href="{{ asset('assets/app.css') }}">

  {{-- Favicons --}}
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

  {{-- Icon library (local) --}}
  {{-- Example: Tabler Icons CSS local or your chosen icon set. Keep local. --}}
  <link rel="stylesheet" href="{{ asset('vendor/icons/icons.css') }}">

  <style>
    #global-preloader {
      position: fixed;
      inset: 0;
      background: #fff;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: opacity .35s ease, visibility .35s ease;
    }

    #global-preloader.is-hidden {
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
    }

    #global-preloader .logo-wrap {
      width: 86px;
      height: 86px;
      border-radius: 18px;
      display: grid;
      place-items: center;
      animation: preloaderPulse 1.2s ease-in-out infinite;
    }

    #global-preloader img {
      width: 72px;
      height: 72px;
      object-fit: contain;
    }

    @keyframes preloaderPulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(0.92); opacity: .72; }
    }

    @media (prefers-reduced-motion: reduce) {
      #global-preloader .logo-wrap { animation: none; }
    }
  </style>

  @stack('head')
</head>

<body class="min-h-screen bg-ui-bg text-ui-text">
  <div id="global-preloader" aria-label="در حال بارگذاری" role="status">
    <div class="logo-wrap">
      <img src="{{ asset('images/brand/logo.png') }}" alt="Loading">
    </div>
  </div>

  @yield('content')

  @livewireScripts

  {{-- Alpine (local) --}}
  <script src="{{ asset('vendor/alpine/alpine.min.js') }}" defer></script>

  {{-- Vendor JS (local) --}}
  <script src="{{ asset('vendor/select2/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
  <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('vendor/jalalidatepicker/jalalidatepicker.min.js') }}"></script>
  <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

  {{-- App JS (no import, local) --}}
  <script src="{{ asset('assets/app.js') }}"></script>

  {{-- Toastr flashes --}}
  @if(session('success'))
    <script>toastr.success(@json(session('success')))</script>
  @endif
  @if(session('error'))
    <script>toastr.error(@json(session('error')))</script>
  @endif
  @if(session('info'))
    <script>toastr.info(@json(session('info')))</script>
  @endif
  @if(session('warning'))
    <script>toastr.warning(@json(session('warning')))</script>
  @endif

  <script>
    (function () {
      const hidePreloader = () => {
        const preloader = document.getElementById('global-preloader');
        if (!preloader) return;
        preloader.classList.add('is-hidden');
        setTimeout(() => preloader.remove(), 450);
      };

      window.addEventListener('load', hidePreloader);
      setTimeout(hidePreloader, 2000);
    })();
  </script>

  @stack('scripts')
</body>
</html>
