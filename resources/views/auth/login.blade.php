<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-[color:var(--event-muted)] text-[#7a3d58] shadow-sm focus:ring-[color:var(--event-gold)]" name="remember">
                <span class="ms-2 text-sm text-[color:var(--event-muted)]">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-x-4 gap-y-2 mt-4">
            @if (Route::has('register'))
                <span class="text-sm text-[color:var(--event-muted)] whitespace-nowrap">
                    {{ __('New customer?') }}
                    <a class="underline text-[color:var(--event-gold-dark)] hover:text-[color:var(--event-ink)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[color:var(--event-gold)]" href="{{ route('register') }}">
                        {{ __('Create an account') }}
                    </a>
                </span>
            @endif

            @if (Route::has('password.request'))
                <a class="underline text-sm text-[color:var(--event-muted)] hover:text-[color:var(--event-ink)] whitespace-nowrap rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[color:var(--event-gold)]" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-1">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
