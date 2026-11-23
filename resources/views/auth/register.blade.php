<x-guest-layout>
    <div class="mb-7">
        <span class="neo-badge bg-[#F7941D] text-black">Daftar</span>
        <h2 class="mt-3 font-display text-3xl font-extrabold leading-tight sm:text-4xl">
            Buat akun baru 🚀
        </h2>
        <p class="mt-2 font-hand text-lg text-black/70">
            Gratis, cepat, dan langsung bisa cari beasiswa.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama lengkap" />

            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4.5 20a7.5 7.5 0 0 1 15 0" />
                </svg>

                <x-text-input id="name"
                              class="pl-12"
                              type="text"
                              name="name"
                              :value="old('name')"
                              placeholder="Nama kamu"
                              required
                              autofocus
                              autocomplete="name" />
            </div>

            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />

            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2.5" />
                    <path d="m4 7 8 6 8-6" />
                </svg>

                <x-text-input id="email"
                              class="pl-12"
                              type="email"
                              name="email"
                              :value="old('email')"
                              placeholder="nama@email.com"
                              required
                              autocomplete="username" />
            </div>

            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <x-input-label for="password" value="Password" />

            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="4" y="10" width="16" height="11" rx="2.5" />
                    <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                </svg>

                <x-text-input id="password"
                              class="pl-12 pr-14"
                              type="password"
                              x-bind:type="show ? 'text' : 'password'"
                              name="password"
                              placeholder="Minimal 8 karakter"
                              required
                              autocomplete="new-password" />

                <button type="button"
                        @click="show = !show"
                        class="absolute right-2 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-lg border-2 border-black bg-white transition hover:bg-[#B8FF00] focus:outline-none focus-visible:ring-2 focus-visible:ring-black"
                        :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'">
                    <svg x-show="!show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg x-show="show" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3l18 18" />
                        <path d="M10.6 6.2A9.9 9.9 0 0 1 12 6c6 0 9.5 6 9.5 6a17 17 0 0 1-3 3.7" />
                        <path d="M6.4 7.6A16.7 16.7 0 0 0 2.5 12S6 18 12 18c1.2 0 2.3-.2 3.3-.6" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi password" />

            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="4" y="10" width="16" height="11" rx="2.5" />
                    <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                    <path d="m9.5 15.5 1.8 1.8 3.2-3.6" />
                </svg>

                <x-text-input id="password_confirmation"
                              class="pl-12"
                              type="password"
                              name="password_confirmation"
                              placeholder="Ulangi password"
                              required
                              autocomplete="new-password" />
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <p class="rounded-xl border-2 border-black bg-[#EEF1F8] px-3 py-2 font-hand text-sm leading-snug text-black/75">
            Dengan mendaftar, kamu setuju dengan syarat &amp; ketentuan Edu.com.
        </p>

        <x-primary-button class="w-full py-3.5 text-lg">
            Daftar sekarang
            <span aria-hidden="true">→</span>
        </x-primary-button>

        <p class="text-center font-hand text-base text-black/80">
            Sudah punya akun?
            <a href="{{ route('login') }}"
               class="font-bold text-[#8A2BE2] underline decoration-2 underline-offset-2 hover:text-black focus:outline-none focus-visible:ring-2 focus-visible:ring-black">
                Masuk di sini
            </a>
        </p>
    </form>
</x-guest-layout>
