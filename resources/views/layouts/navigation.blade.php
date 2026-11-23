@php
    $user = auth()->user();
    $initial = $user ? mb_strtoupper(mb_substr($user->name, 0, 1)) : '?';
@endphp

<nav x-data="{ open: false }" class="relative border-b-[3px] border-black">
    <div class="flex items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-8">

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="group flex shrink-0 items-center gap-3 focus:outline-none focus-visible:ring-4 focus-visible:ring-black/30">
            <x-application-logo class="h-11 w-11 transition group-hover:-rotate-6" />
            <span class="font-display text-2xl font-extrabold tracking-tight sm:text-[1.7rem]">
                Edu<span class="text-[#8A2BE2]">.</span>com
            </span>
        </a>

        <!-- {{-- Menu desktop --}}
        <div class="hidden items-center gap-1 lg:flex xl:gap-2">
            <a href="{{ route('dashboard') }}"
               class="neo-nav-link {{ request()->routeIs('dashboard') ? 'neo-nav-link-active' : '' }}">
                Rekomendasi
            </a>
            <a href="{{ route('dashboard') }}" class="neo-nav-link">Beasiswa</a>
            <a href="{{ route('dashboard') }}" class="neo-nav-link">Status Pengajuan</a>
            <a href="{{ route('dashboard') }}" class="neo-nav-link">Kalender</a>
        </div> -->

        {{-- Aksi kanan --}}
        <div class="hidden items-center gap-2.5 md:flex">
            <form method="GET" action="{{ route('dashboard') }}" class="relative hidden xl:block">
                @foreach (['program', 'lokasi', 'semester'] as $filter)
                    @if (request($filter))
                        <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                    @endif
                @endforeach

                <label for="nav-search" class="sr-only">Cari beasiswa</label>
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m20 20-3.5-3.5" />
                </svg>
                <input id="nav-search"
                       type="search"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Cari beasiswa impianmu..."
                       class="w-56 rounded-full border-[3px] border-black bg-white py-2 pl-10 pr-4 font-hand text-base placeholder:text-black/40 focus:outline-none focus:ring-4 focus:ring-[#8A2BE2]/30">
            </form>

            <a href="{{ route('dashboard') }}"
               class="grid h-10 w-10 place-items-center rounded-full border-[3px] border-black bg-white shadow-[2px_2px_0_0_#000] transition hover:-translate-y-0.5 hover:bg-[#B8FF00] focus:outline-none focus-visible:ring-4 focus-visible:ring-black/30"
               title="Notifikasi">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 9a6 6 0 1 1 12 0c0 4 1.5 5.5 1.5 5.5h-15S6 13 6 9Z" />
                    <path d="M10 18.5a2.2 2.2 0 0 0 4 0" />
                </svg>
            </a>

            <x-dropdown align="right" width="56" content-classes="p-1.5 bg-neo-cream">
                <x-slot name="trigger">
                    <button type="button"
                            class="flex items-center gap-2 rounded-full border-[3px] border-black bg-white py-1 pl-1 pr-2 shadow-[2px_2px_0_0_#000] transition hover:-translate-y-0.5 hover:bg-[#B8FF00] focus:outline-none focus-visible:ring-4 focus-visible:ring-black/30"
                            title="{{ $user?->name }}">
                        <span class="grid h-8 w-8 place-items-center rounded-full border-2 border-black bg-[#8A2BE2] font-display text-sm font-extrabold text-white">
                            {{ $initial }}
                        </span>
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.3 7.3a1 1 0 0 1 1.4 0L10 10.6l3.3-3.3a1 1 0 1 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 0-1.4Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="mb-1 border-b-2 border-black/10 px-4 pb-2 pt-1">
                        <p class="font-display text-sm font-extrabold leading-tight">{{ $user?->name }}</p>
                        <p class="truncate font-hand text-xs text-black/60">{{ $user?->email }}</p>
                    </div>

                    <x-dropdown-link :href="route('profile.edit')">Profil saya</x-dropdown-link>
                    <x-dropdown-link :href="route('dashboard')">Status pengajuan</x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t-2 border-black/10 pt-1">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                         onclick="event.preventDefault(); this.closest('form').submit();">
                            Keluar
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        {{-- Hamburger --}}
        <button type="button"
                @click="open = ! open"
                class="grid h-10 w-10 place-items-center rounded-xl border-[3px] border-black bg-white shadow-[3px_3px_0_0_#000] transition hover:bg-[#B8FF00] focus:outline-none focus-visible:ring-4 focus-visible:ring-black/30 lg:hidden"
                :aria-expanded="open"
                aria-label="Buka menu">
            <svg x-show="!open" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true">
                <path d="M4 7h16M4 12h16M4 17h16" />
            </svg>
            <svg x-show="open" x-cloak class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true">
                <path d="m6 6 12 12M18 6 6 18" />
            </svg>
        </button>
    </div>

    {{-- Menu mobile --}}
    <div x-show="open" x-cloak x-transition.opacity
         class="border-t-[3px] border-black bg-neo-cream px-4 py-4 lg:hidden">
        <div class="mb-4 flex items-center gap-3 rounded-xl border-2 border-black bg-white px-3 py-2">
            <span class="grid h-9 w-9 place-items-center rounded-full border-2 border-black bg-[#8A2BE2] font-display text-sm font-extrabold text-white">
                {{ $initial }}
            </span>
            <div class="min-w-0">
                <p class="truncate font-display text-sm font-extrabold leading-tight">{{ $user?->name }}</p>
                <p class="truncate font-hand text-xs text-black/60">{{ $user?->email }}</p>
            </div>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="relative mb-4">
            <label for="mobile-search" class="sr-only">Cari beasiswa</label>
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7" />
                <path d="m20 20-3.5-3.5" />
            </svg>
            <input id="mobile-search"
                   type="search"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Cari beasiswa..."
                   class="w-full rounded-full border-[3px] border-black bg-white py-2 pl-10 pr-4 font-hand text-base placeholder:text-black/40 focus:outline-none focus:ring-4 focus:ring-[#8A2BE2]/30">
        </form>

        <div class="space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Rekomendasi</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')">Beasiswa</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')">Status Pengajuan</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')">Kalender</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.edit')">Profil saya</x-responsive-nav-link>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-3 border-t-2 border-black/10 pt-3">
            @csrf
            <button type="submit"
                    class="w-full rounded-lg border-2 border-black bg-[#FF8A80] px-3 py-2 text-left font-hand text-base shadow-[2px_2px_0_0_#000] transition hover:bg-[#ff7266] focus:outline-none focus-visible:ring-4 focus-visible:ring-black/20">
                Keluar
            </button>
        </form>
    </div>
</nav>
