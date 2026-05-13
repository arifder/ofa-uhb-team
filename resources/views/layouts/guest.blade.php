<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="OFA-UHB — Sistem pengelolaan kas dan notulensi rapat untuk mendukung administrasi fakultas dan dosen secara terintegrasi.">
        <meta name="theme-color" content="#0a0f1e">

        <title>{{ config('app.name', 'OFA-UHB') }} — {{ $title ?? 'Login' }}</title>

        <!-- Fonts: Syne (display) + Plus Jakarta Sans (body) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Vite: CSS + JS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="antialiased min-h-screen ofa-body">
        {{ $slot }}

        @livewireScripts
    </body>
</html>
