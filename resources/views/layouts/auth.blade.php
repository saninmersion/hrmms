<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700&display=swap"
          rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/tailwind.css', 'assets/auth') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css', 'assets/auth') }}">
    @stack('css')
</head>
<body class="bg-grey-3 font-sans text-base-1 antialiased auth-layout">

<x-auth.wrapper>

    <x-slot name="logo">
        <x-common.logo/>
    </x-slot>

    <x-auth.errors/>
    <x-auth.success/>

    {{ $slot }}

</x-auth.wrapper>

<script src="{{ mix('js/manifest.js', 'assets/auth') }}" defer></script>
<script src="{{ mix('js/app.js', 'assets/auth') }}" defer></script>
@stack('js')

</body>
</html>
