<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $pageTitle = match (true) {
                request()->routeIs('login') => 'Masuk',
                request()->routeIs('register') => 'Daftar',
                request()->routeIs('password.*') => 'Atur Ulang Password',
                default => 'Beasiswa Impianmu',
            };
        @endphp

        <title>{{ $pageTitle }} — Edu.com</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=baloo-2:400,600,700,800|patrick-hand:400" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="neo-dots min-h-screen font-hand text-black antialiased">
        <div class="mx-auto flex min-h-screen w-full max-w-6xl flex-col px-4 py-6 sm:px-6 sm:py-10">
            {{-- Header --}}
            <header class="flex items-center justify-between gap-4">
                <a href="/" class="group inline-flex items-center gap-3 focus:outline-none focus-visible:ring-4 focus-visible:ring-black/30">
                    <x-application-logo class="h-12 w-12 transition group-hover:-rotate-6" />
                    <span class="font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Edu<span class="text-[#8A2BE2]">.</span>com</span>
                </a>

                <div class="flex items-center gap-2 sm:gap-3">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="rounded-xl border-[3px] border-black bg-white px-3 py-1.5 font-hand text-base shadow-[3px_3px_0_0_#000] transition hover:-translate-y-0.5 hover:bg-[#B8FF00] focus:outline-none focus-visible:ring-4 focus-visible:ring-black/30 sm:px-4">
                            Masuk
                        </a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="rounded-xl border-[3px] border-black bg-[#8A2BE2] px-3 py-1.5 font-hand text-base text-white shadow-[3px_3px_0_0_#000] transition hover:-translate-y-0.5 hover:bg-[#7A1FD0] focus:outline-none focus-visible:ring-4 focus-visible:ring-black/30 sm:px-4">
                            Daftar
                        </a>
                    @endif
                </div>
            </header>

            {{-- Konten --}}
            <main class="flex flex-1 items-center justify-center py-8 sm:py-12">
                <div class="grid w-full max-w-4xl overflow-hidden rounded-[32px] border-[3px] border-black bg-neo-cream shadow-[10px_10px_0_0_#000] lg:grid-cols-[1.05fr_1fr]">

                    {{-- Panel kiri (dekoratif) --}}
                    <aside class="relative hidden flex-col justify-between gap-10 overflow-hidden border-r-[3px] border-black bg-[#8A2BE2] p-8 text-white lg:flex">
                        <span class="pointer-events-none absolute -right-10 -top-10 h-36 w-36 rounded-full border-[3px] border-black bg-[#B8FF00]"></span>
                        <span class="pointer-events-none absolute -bottom-8 -left-8 h-28 w-28 rounded-full border-[3px] border-black bg-[#F7941D]"></span>
                        <span class="pointer-events-none absolute bottom-24 right-8 h-10 w-10 rotate-12 rounded-lg border-[3px] border-black bg-white"></span>

                        <div class="relative">
                            <span class="neo-badge bg-[#B8FF00] text-black">Beasiswa 2026</span>
                            <h1 class="mt-5 font-display text-[2.6rem] font-extrabold leading-[1.05]">
                                Satu akun,<br>
                                sejuta <span class="text-[#B8FF00]">peluang</span> beasiswa.
                            </h1>
                            <p class="mt-4 max-w-xs font-hand text-lg leading-snug text-white/85">
                                Temukan, bandingkan, dan ajukan beasiswa favoritmu tanpa ribet.
                            </p>
                        </div>

                        <ul class="relative space-y-3">
                            @foreach ([
                                'Rekomendasi sesuai profilmu',
                                'Pantau status pengajuan real-time',
                                'Pengingat deadline otomatis',
                            ] as $benefit)
                                <li class="flex items-center gap-3 rounded-xl border-2 border-black bg-white px-3 py-2 text-black shadow-[3px_3px_0_0_#000]">
                                    <span class="grid h-6 w-6 shrink-0 place-items-center rounded-md border-2 border-black bg-[#B8FF00] font-display text-sm font-extrabold">✓</span>
                                    <span class="font-hand text-base">{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </aside>

                    {{-- Panel form --}}
                    <section class="p-6 sm:p-10">
                        <a href="/" class="mb-6 inline-flex items-center gap-2 lg:hidden">
                            <x-application-logo class="h-10 w-10" />
                            <span class="font-display text-2xl font-extrabold">Edu.com</span>
                        </a>

                        {{ $slot }}
                    </section>
                </div>
            </main>

            {{-- Footer --}}
            <footer class="pt-2 text-center">
                <p class="mx-auto inline-block rounded-full border-2 border-black bg-white/85 px-4 py-1 font-hand text-sm text-black/70">
                    ©2026 Edu.com — Semangat cari beasiswa, gaya neo-brutalism.
                </p>
            </footer>
        </div>
    </body>
</html>
