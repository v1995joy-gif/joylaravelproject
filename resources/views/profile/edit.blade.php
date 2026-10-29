<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[color:var(--event-ink)] leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-[color:var(--neo-panel)] shadow-[0_18px_44px_rgba(0,0,0,0.45),inset_0_1px_0_rgba(232,201,138,0.12)] sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-[color:var(--neo-panel)] shadow-[0_18px_44px_rgba(0,0,0,0.45),inset_0_1px_0_rgba(232,201,138,0.12)] sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-[color:var(--neo-panel)] shadow-[0_18px_44px_rgba(0,0,0,0.45),inset_0_1px_0_rgba(232,201,138,0.12)] sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
