<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Security Headers -->
        <meta http-equiv="X-Frame-Options" content="DENY">
        <meta http-equiv="X-Content-Type-Options" content="nosniff">
        <meta http-equiv="X-XSS-Protection" content="1; mode=block">
        <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
        <meta http-equiv="Permissions-Policy" content="accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()">

        <!-- Block Search Engines, Bots, and AI -->
        <meta name="robots" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="googlebot" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="bingbot" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="crawler" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="slurp" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="duckduckbot" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="baiduspider" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="yandexbot" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="facebookbot" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="twitterbot" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="GPTBot" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="anthropic-ai" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="claude-ai" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="cohere-ai" content="noindex, nofollow, noarchive, noimageindex">
        <meta name="CCBot" content="noindex, nofollow, noarchive, noimageindex">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-900">
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
