<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Maribel Gowns Boutique') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link
            href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|playfair-display:500,600,700,800"
            rel="stylesheet"
        />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased admin-body">

        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content Area -->
        <div class="admin-main" style="
            margin-left: 260px;
            min-height: 100vh;
            width: calc(100% - 260px);
        ">
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

    </body>
</html>