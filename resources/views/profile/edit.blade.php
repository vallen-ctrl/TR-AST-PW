<x-app-layout>
    <div class="mx-auto max-w-4xl">
        <div class="mb-6">
            <span class="neo-badge bg-[#8A2BE2] text-white">Profil</span>
            <h1 class="mt-3 font-display text-3xl font-extrabold leading-tight sm:text-4xl">Pengaturan akun</h1>
            <p class="mt-1 font-hand text-lg text-black/70">Kelola data diri dan keamanan akunmu.</p>
        </div>

        <div class="space-y-6">
            <div class="neo-card p-5 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="neo-card p-5 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="neo-card p-5 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
