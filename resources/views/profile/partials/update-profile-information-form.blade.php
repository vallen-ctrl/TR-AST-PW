<section>
    <header>
        <h2 class="font-display text-xl font-extrabold text-black">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 font-hand text-base text-black/60">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-3 font-hand text-sm text-black/70">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="font-bold text-[#8A2BE2] underline decoration-2 underline-offset-2 hover:text-black focus:outline-none focus-visible:ring-2 focus-visible:ring-black">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 inline-block rounded-lg border-2 border-black bg-neo-lime px-3 py-1 font-hand text-sm text-black">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="rounded-lg border-2 border-black bg-neo-lime px-3 py-1 font-hand text-sm text-black"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
