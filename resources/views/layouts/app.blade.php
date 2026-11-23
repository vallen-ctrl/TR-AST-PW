<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $pageTitle = match (true) {
                request()->routeIs('dashboard') => 'Dashboard',
                request()->routeIs('profile.*') => 'Profil',
                default => 'Edu.com',
            };
        @endphp

        <title>{{ $pageTitle }} — Edu.com</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=baloo-2:400,600,700,800|patrick-hand:400" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="neo-dots flex min-h-screen w-full flex-col font-hand text-black antialiased">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="flex-1 px-4 pb-12 pt-6 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="border-t-[3px] border-black bg-neo-cream px-4 py-4 text-center">
            <p class="font-hand text-sm text-black/70">
                ©2026 <span class="font-display font-extrabold text-black">Edu.com</span> — Beasiswa untuk semua.
            </p>
        </footer>
    </body>
</html>
