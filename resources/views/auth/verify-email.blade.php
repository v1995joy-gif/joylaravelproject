<x-guest-layout>
    <div class="mb-4 text-sm text-[color:var(--event-muted)]">
        {{ __('Thanks for signing up! We emailed a 6-digit verification code to your address. Enter it below to verify your account.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new code has been sent to your email address.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.otp') }}">
        @csrf

        <div>
            <x-input-label for="otp_code" :value="__('Verification Code')" />
            <x-text-input id="otp_code" class="block mt-1 w-full tracking-widest" type="text" name="otp_code" inputmode="numeric" maxlength="6" required autofocus autocomplete="one-time-code" />
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-primary-button>
                {{ __('Verify Email') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="underline text-sm text-[color:var(--event-muted)] hover:text-[color:var(--event-ink)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[color:var(--event-gold)]">
                {{ __('Resend Code') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-[color:var(--event-muted)] hover:text-[color:var(--event-ink)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[color:var(--event-gold)]">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
