<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="app-name" content="{{ config('app.name') }}">
    <meta name="theme-color" content="#00b4a4"/>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon.ico') }}">

    <title>
        @hasSection('title')
            @yield('title') |
        @endif
        {{ config('app.name') }}
    </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/tailwind.css', 'assets/admin') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css', 'assets/admin') }}">
    @stack('css')
</head>
<body class="antialiased">

<noscript>You need to enable JavaScript to run this app.</noscript>

@inertia

@routes()

<script>
    window.i18n = @json($trans);
    window.locale = "{{ $locale }}"
</script>
<script src="{{ mix('js/manifest.js', 'assets/admin') }}" defer></script>
<script src="{{ mix('js/vendor.js', 'assets/admin') }}" defer></script>
<script src="{{ mix('js/app.js', 'assets/admin') }}" defer></script>
@stack('js')

</body>
</html>
