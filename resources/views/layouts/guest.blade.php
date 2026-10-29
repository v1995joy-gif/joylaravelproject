<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Maribel Gowns Boutique') }}</title>

        @include('partials.theme-script')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|playfair-display:500,600,700,800" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-[color:var(--event-ink)] antialiased">
        <x-theme-toggle class="theme-toggle-floating" />
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0 px-4 py-10">
            <a href="/" class="flex flex-col items-center gap-2 no-underline mb-5">
                <span class="flex items-center justify-center w-16 h-16 rounded-full bg-white shadow-lg">
                    <x-application-logo class="w-16 h-16 rounded-full object-contain" />
                </span>
                <span class="flex flex-col items-center">
                    <span class="font-display text-xl font-bold text-[color:var(--event-ink)] tracking-wide">Event Apparel</span>
                    <span class="text-[0.6rem] font-semibold tracking-[0.2em] text-[color:var(--event-gold-dark)] mt-1">RENTAL &amp; FITTING</span>
                </span>
            </a>

            <div class="w-full sm:max-w-md px-8 py-8 overflow-hidden rounded-3xl" style="background:var(--neo-bg);border:1px solid rgba(232,201,138,0.16);box-shadow:0 18px 44px rgba(7,2,6,0.55),inset 0 1px 0 rgba(255,255,255,0.07);backdrop-filter:blur(16px);">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs tracking-[0.2em] text-[color:var(--event-muted)]">GOWNS &middot; TUXEDOS &middot; CUSTOM FITTINGS</p>
        </div>
    </body>
</html>
