<!doctype html>
<html lang="fa" dir="rtl" class="bg-ui-bg text-ui-text">
<head>
<link rel="stylesheet" href="{{ asset('assets/app.css') }}">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ trim($__env->yieldContent('title', env('APP_BRAND_DISPLAY', 'نێروان فێرگە'))) }}</title>
  @vite(['resources/js/app.js'])
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest">
</head>
<body class="min-h-screen bg-ui-bg text-ui-text">
  {{ $slot ?? '' }}
  @yield('content')
</body>
</html>

