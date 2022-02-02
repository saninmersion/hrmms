<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="app-name" content="{{ config('app.name') }}">
    <meta name="theme-color" content="#00b4a4"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon.ico') }}">

    <title>
        @hasSection('title')
            @yield('title') |
        @endif
        {{ trans('general.app-name') }}
    </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/tailwind.css', 'assets/front') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css', 'assets/front') }}">
    @stack('css')

    <style>
        [v-cloak] > * {
            display: none;
        }

        [v-cloak]::before {
            content: "{{ trans('general.loading') }}";
        }
    </style>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-FPDQGCZLNT"></script>
    <script>
        window.dataLayer = window.dataLayer || []

        function gtag() {dataLayer.push(arguments)}

        gtag("js", new Date())

        gtag("config", "G-FPDQGCZLNT")
    </script>
</head>
<body class="antialiased {{app()->getLocale()}}">

<noscript>You need to enable JavaScript to run this app.</noscript>

<main id="app">
    <x-front.header/>

    <main class="content mb-8">
        {{ $slot }}
    </main>
</main>

<script>
    window.i18n = @json($trans);
    window.locale = "{{ $locale }}"
</script>

<script src="{{ mix('js/manifest.js', 'assets/front') }}" defer></script>
<script src="{{ mix('js/vendor.js', 'assets/front') }}" defer></script>
<script src="{{ mix('js/app.js', 'assets/front') }}" defer></script>
@stack('js')

</body>
</html>
