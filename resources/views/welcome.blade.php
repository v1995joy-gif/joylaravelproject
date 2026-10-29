<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Maribel Gowns Boutique') }} — Gowns Boutique</title>
    @include('partials.theme-script')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700|playfair-display:500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            color-scheme: light;
            --ink: #241321;
            --muted: #795b68;
            --plum: #4a1d37;
            --wine: #701d46;
            --gold: #b98a2e;
            --champagne: #b98a2e;
            --pink: #ed3d81;
            --neo-bg: #eae0e4;
            --neo-field: #e3d8dd;
            --neo-raise: -10px -10px 24px rgba(255,255,255,0.95), 12px 12px 30px rgba(74,29,55,0.16);
            --neo-raise-sm: -5px -5px 14px rgba(255,255,255,0.9), 7px 7px 18px rgba(74,29,55,0.12);
            --neo-inset-sm: inset 3px 3px 9px rgba(74,29,55,0.12), inset -3px -3px 9px rgba(255,255,255,0.7);
            --neo-dark-raise: 0 6px 18px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.08);
            --neo-dark-inset: inset 0 2px 10px rgba(0,0,0,0.4);
            --body-bg: radial-gradient(1100px 620px at 88% -10%, rgba(185,138,46,0.10), transparent 60%),
                radial-gradient(900px 540px at -8% 112%, rgba(112,29,70,0.08), transparent 55%),
                linear-gradient(160deg, #f2eaee, #eae0e4 55%, #e6d8de);
        }

        html.dark {
            color-scheme: dark;
            --ink: #f3e9f0;
            --muted: #b393a6;
            --plum: #e8c98a;
            --wine: #d9ab4e;
            --gold: #d9ab4e;
            --champagne: #e8c98a;
            --pink: #ed3d81;
            --neo-bg: rgba(42, 22, 37, 0.62);
            --neo-field: rgba(255,255,255,0.05);
            --neo-raise: 0 18px 44px rgba(7,2,6,0.55), inset 0 1px 0 rgba(255,255,255,0.07);
            --neo-raise-sm: 0 8px 22px rgba(7,2,6,0.45), inset 0 1px 0 rgba(255,255,255,0.06);
            --neo-inset-sm: inset 0 2px 8px rgba(0,0,0,0.4);
            --neo-dark-raise: 0 6px 18px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.08);
            --neo-dark-inset: inset 0 2px 10px rgba(0,0,0,0.55);
            --body-bg: radial-gradient(1100px 620px at 88% -10%, rgba(112,29,70,0.38), transparent 60%),
                radial-gradient(900px 540px at -8% 112%, rgba(185,138,46,0.13), transparent 55%),
                linear-gradient(160deg, #170a14, #241321 55%, #190c15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--body-bg);
            background-attachment: fixed;
            color: var(--ink);
        }

        .display { font-family: 'Playfair Display', serif; }

        /* ---------- header ---------- */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            min-height: 64px;
            padding: 10px 6vw;
            background: rgba(255, 255, 255, 0.80);
            -webkit-backdrop-filter: blur(12px);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(236, 72, 153, 0.12);
            box-shadow: 0 8px 22px rgba(74, 29, 55, 0.1);
            transition: background 0.2s ease, box-shadow 0.2s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--ink);
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            object-fit: contain;
            border-radius: 50%;
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
        }

        .brand-sub {
            font-size: 10px;
            letter-spacing: 0.15em;
            color: var(--gold);
            font-weight: 700;
            margin-top: 4px;
        }

        .site-nav { display: flex; align-items: center; gap: 20px; }

        .site-nav > a:not(.btn) {
            text-decoration: none;
            color: var(--ink);
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.02em;
            padding: 8px 12px;
            border-radius: 999px;
            transition: box-shadow 0.2s ease;
        }

        .site-nav > a:not(.btn):hover {
            color: var(--plum);
            box-shadow: var(--neo-inset-sm);
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--champagne), var(--pink));
            color: #fff !important;
            box-shadow: 0 10px 26px rgba(7,2,6,0.45), 0 0 22px rgba(237,61,129,0.28);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(7,2,6,0.5), 0 0 30px rgba(237,61,129,0.4);
        }

        .btn-primary:active {
            transform: none;
            box-shadow: var(--neo-inset-sm);
        }

        .btn-ghost {
            color: var(--plum) !important;
            background: var(--neo-bg);
            box-shadow: var(--neo-raise-sm);
        }

        .btn-ghost:hover {
            color: var(--gold) !important;
            box-shadow: 0 10px 24px rgba(7,2,6,0.45), 0 0 16px rgba(232,201,138,0.18);
        }

        .btn-ghost:active { box-shadow: var(--neo-inset-sm); }

        .theme-toggle {
            align-items: center;
            background: var(--neo-bg);
            border: 1px solid rgba(74,29,55,0.12);
            border-radius: 999px;
            box-shadow: var(--neo-raise-sm);
            color: var(--plum);
            cursor: pointer;
            display: inline-flex;
            height: 38px;
            justify-content: center;
            padding: 0;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
            width: 38px;
        }
        .theme-toggle:hover { transform: translateY(-1px); box-shadow: var(--neo-raise); }
        .theme-toggle:active { box-shadow: var(--neo-inset-sm); transform: none; }
        .theme-toggle-moon { display: inline-flex; }
        .theme-toggle-sun { display: none; }
        html.dark .theme-toggle-moon { display: none; }
        html.dark .theme-toggle-sun { display: inline-flex; }
        html.dark .theme-toggle { border-color: rgba(232,201,138,0.2); }
        html.dark .site-header {
            background: rgba(22, 10, 19, 0.62);
            border-bottom-color: rgba(232, 201, 138, 0.16);
            box-shadow: 0 10px 24px rgba(7, 2, 6, 0.4);
        }

        /* ---------- hero ---------- */
        .hero {
            min-height: 82vh;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            align-items: center;
            gap: 5vw;
            padding: 120px 6vw 60px;
            position: relative;
            isolation: isolate;
            background:
                linear-gradient(rgba(242, 234, 238, 0.84), rgba(234, 224, 228, 0.86)),
                url('{{ asset('images/maribel-landing-background.png') }}') center / cover no-repeat;
        }

        html.dark .hero {
            background-image:
                linear-gradient(rgba(23, 10, 20, 0.84), rgba(36, 19, 33, 0.88)),
                url('{{ asset('images/maribel-landing-background.png') }}');
        }

        .kicker {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3em;
            color: var(--gold);
            text-transform: uppercase;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .kicker::before {
            content: '';
            width: 44px;
            height: 2px;
            background: var(--champagne);
            border-radius: 2px;
            box-shadow: var(--neo-inset-sm);
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(42px, 5.4vw, 76px);
            font-weight: 700;
            line-height: 1.08;
            margin-bottom: 24px;
        }

        .hero h1 em {
            font-style: italic;
            color: var(--wine);
        }

        .hero p {
            font-size: 17px;
            line-height: 1.8;
            color: var(--muted);
            max-width: 520px;
            margin-bottom: 38px;
        }

        .hero-cta { display: flex; gap: 16px; flex-wrap: wrap; }

        .hero-art {
            position: relative;
            display: flex;
            justify-content: center;
        }

        .hero-frame {
            width: min(420px, 80%);
            aspect-ratio: 3 / 4;
            border-radius: 200px 200px 24px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: var(--neo-bg);
            overflow: hidden;
            -webkit-backdrop-filter: blur(14px);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(232, 201, 138, 0.16);
            box-shadow: var(--neo-raise);
        }

        .hero-frame::before {
            content: '';
            position: absolute;
            inset: 16px;
            border-radius: 190px 190px 16px 16px;
            box-shadow: var(--neo-inset-sm);
            pointer-events: none;
            z-index: 1;
        }

        .hero-frame .hero-image {
            position: absolute;
            inset: 16px;
            width: calc(100% - 32px);
            height: calc(100% - 32px);
            border-radius: 190px 190px 16px 16px;
            object-fit: cover;
            object-position: center;
        }

        .hero-badge {
            position: absolute;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            width: max-content;
            max-width: calc(100% - 32px);
            background: rgba(255, 255, 255, 0.90);
            -webkit-backdrop-filter: blur(8px);
            backdrop-filter: blur(8px);
            color: #374151;
            padding: 10px 24px;
            border: 1px solid rgba(236, 72, 153, 0.12);
            border-radius: 999px;
            font-size: 11px;
            letter-spacing: 0.12em;
            font-weight: 800;
            box-shadow: 0 10px 24px rgba(74, 29, 55, 0.16);
            z-index: 20;
        }

        /* ---------- sections ---------- */
        section { padding: 84px 6vw; }
        section[id] { scroll-margin-top: 90px; }

        .section-head { text-align: center; margin-bottom: 52px; }

        .section-head .eyebrow {
            display: inline-block;
            font-size: 11px;
            letter-spacing: 0.3em;
            font-weight: 700;
            color: var(--gold);
            text-transform: uppercase;
            padding: 8px 20px;
            border-radius: 999px;
            box-shadow: var(--neo-inset-sm);
        }

        .section-head h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(30px, 3.4vw, 44px);
            font-weight: 700;
            margin-top: 18px;
        }

        .section-head p {
            color: var(--muted);
            max-width: 560px;
            margin: 14px auto 0;
            line-height: 1.7;
        }

        /* collection */
        .category-chips {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            max-width: 1200px;
            margin: 0 auto 44px;
        }

        .chip {
            padding: 10px 22px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            color: var(--plum);
            background: var(--neo-bg);
            box-shadow: var(--neo-inset-sm);
            text-align: center;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            align-items: stretch;
            gap: 24px;
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
        }

        .item-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: var(--neo-bg);
            -webkit-backdrop-filter: blur(14px);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(232, 201, 138, 0.14);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: var(--neo-raise);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 22px 44px rgba(7,2,6,0.55), 0 0 22px rgba(232,201,138,0.12);
        }

        .item-card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-radius: 18px;
            padding: 12px 12px 0;
            box-sizing: border-box;
        }

        .collection-card-trigger {
            cursor: pointer;
        }

        .collection-card-trigger:focus-visible {
            outline: 3px solid var(--pink);
            outline-offset: 4px;
        }

        .collection-signup-dialog {
            width: min(440px, calc(100% - 32px));
            max-width: none;
            margin: auto;
            padding: 32px;
            border: 1px solid rgba(236, 72, 153, 0.18);
            border-radius: 24px;
            background: var(--neo-bg);
            color: var(--ink);
            box-shadow: var(--neo-raise);
        }

        .collection-signup-dialog::backdrop {
            background: rgba(36, 19, 33, 0.48);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
        }

        .collection-signup-dialog h2 {
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
            font-size: 26px;
        }

        .collection-signup-dialog p {
            margin-bottom: 24px;
            color: var(--muted);
            line-height: 1.65;
        }

        .collection-signup-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .collection-signup-actions button {
            padding: 10px 18px;
            border: 1px solid rgba(74, 29, 55, 0.14);
            border-radius: 999px;
            background: transparent;
            color: var(--ink);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .collection-signup-actions .signup-continue {
            border: 0;
            background: linear-gradient(135deg, var(--champagne), var(--pink));
            color: #fff;
            text-decoration: none;
        }

        .item-card .placeholder {
            width: calc(100% - 24px);
            margin: 12px 12px 0;
            height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--neo-bg);
            border-radius: 18px;
            box-shadow: var(--neo-inset-sm);
            font-family: 'Playfair Display', serif;
            font-size: 44px;
            color: var(--champagne);
        }

        .item-body {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 18px 20px 22px;
        }

        .item-cat {
            font-size: 10px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 700;
        }

        .item-name {
            font-family: 'Playfair Display', serif;
            font-size: 19px;
            font-weight: 600;
            margin: 7px 0 4px;
            min-height: 2.4em;
        }

        .item-price { margin-top: auto; padding-top: 8px; font-size: 14px; color: var(--muted); }
        .item-price strong { color: var(--plum); font-size: 15px; }

        /* services */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 28px;
        }

        .service-card {
            border-radius: 22px;
            padding: 38px 30px;
            background: var(--neo-bg);
            -webkit-backdrop-filter: blur(14px);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(232, 201, 138, 0.14);
            box-shadow: var(--neo-raise);
        }

        .service-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .service-icon {
            align-items: center;
            background: var(--neo-bg);
            border-radius: 12px;
            box-shadow: var(--neo-inset-sm);
            color: var(--gold);
            display: inline-flex;
            flex: 0 0 40px;
            height: 40px;
            justify-content: center;
            width: 40px;
        }

        .service-card p { color: var(--muted); line-height: 1.7; font-size: 15px; }

        /* steps */
        .steps {
            background: linear-gradient(160deg, #2d1725, #4a1d37 60%, #5d2350);
            color: #fff;
        }

        .steps .section-head h2 { color: #fff; }
        .steps .section-head p { color: #d9b7c8; }

        .steps .section-head .eyebrow {
            color: var(--champagne);
            box-shadow: var(--neo-dark-inset);
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 26px;
        }

        .step {
            border-radius: 18px;
            padding: 30px 24px;
            background: rgba(255, 255, 255, 0.045);
            box-shadow: var(--neo-dark-raise);
        }

        .step-num {
            align-items: center;
            display: flex;
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            color: var(--champagne);
            font-weight: 700;
            justify-content: space-between;
        }

        .step-icon {
            align-items: center;
            background: var(--neo-field);
            border-radius: 10px;
            box-shadow: var(--neo-dark-inset);
            color: var(--champagne);
            display: inline-flex;
            height: 34px;
            justify-content: center;
            width: 34px;
        }

        .step h3 { font-size: 16px; margin: 12px 0 8px; letter-spacing: 0.04em; }
        .step p { color: #d9b7c8; font-size: 14px; line-height: 1.65; }

        /* footer */
        .site-footer {
            background: #0f060d;
            color: #cfa9bb;
            text-align: center;
            padding: 44px 6vw;
            box-shadow: inset 0 12px 24px rgba(0, 0, 0, 0.4);
        }

        .site-footer .footer-logo { width: 120px; height: 120px; object-fit: contain; border-radius: 50%; }
        .site-footer .brand-sub { color: var(--champagne); }
        .site-footer .fine { margin-top: 20px; font-size: 13px; }

        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; text-align: center; }
            .kicker { justify-content: center; }
            .kicker::before { display: none; }
            .hero p { margin-left: auto; margin-right: auto; }
            .hero-cta { justify-content: center; }
            .hero-art { order: -1; }
            .hero-frame { width: min(300px, 70%); }
            .hero-badge { left: 50%; transform: translateX(-50%); }
            .site-nav { gap: 16px; }
            .items-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; }
        }

        @media (max-width: 640px) {
            .site-header { padding: 10px 5vw; flex-wrap: wrap; row-gap: 8px; }
            .site-nav { gap: 10px; flex-wrap: wrap; justify-content: flex-end; }
            .site-nav a[href^="#"] { display: none; }
            .btn { padding: 10px 20px; }
            .hero { padding-top: 145px; }
            .items-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
            .item-card img,
            .item-card .placeholder { height: 200px; }
            .item-body { padding: 14px; }
            .item-name { font-size: 17px; }
        }

        @media (max-width: 420px) {
            .items-grid { grid-template-columns: 1fr; }
            .item-card img,
            .item-card .placeholder { height: 240px; }
        }
    </style>
</head>
<body>

<header class="site-header">
    <a href="/" class="brand">
        <img class="brand-mark" src="{{ asset('images/maribel-gowns-boutique.png') }}" alt="">
        <span>
            <span class="brand-name">Maribel Gowns</span>
            <span class="brand-sub" style="display:block;">BOUTIQUE &amp; FITTING</span>
        </span>
    </a>

    <nav class="site-nav">
        <a href="#collection">Collection</a>
        <a href="#services">Services</a>
        <a href="#how-it-works">How It Works</a>
        <x-theme-toggle />
        @auth
            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
        @else
            <a href="{{ route('login') }}">Log in</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        @endauth
    </nav>
</header>

<section class="hero">
    <div>
        <p class="kicker">Formal Wear · Rental · Custom Fittings</p>
        <h1>Rent the look.<br><em>Own the moment.</em></h1>
        <p>
            Maribel Gowns Boutique is a boutique rental house for gowns, tuxedos, and
            formal wear. Browse the collection, tailor pieces to your fit with
            custom requests, and reserve everything for your event — in one place.
        </p>
        <div class="hero-cta">
            <a href="#collection" class="btn btn-primary">Browse the Collection</a>
            <a href="{{ route('register') }}" class="btn btn-ghost">Create an Account</a>
        </div>
    </div>

    <div class="hero-art">
        <div class="hero-frame">
            <img class="hero-image" src="{{ asset('images/maribel-landing-frame.png') }}" alt="Formal gowns and a tuxedo displayed in a boutique">
            <span class="hero-badge">Fit for your occasion</span>
        </div>
    </div>
</section>

<section id="collection">
    <div class="section-head">
        <p class="eyebrow">The Collection</p>
        <h2>Pieces ready for your event</h2>
        <p>A rotating wardrobe of formal apparel — reserved, fitted, and returned with ease.</p>
    </div>

    @if ($categories->isNotEmpty())
        <div class="category-chips">
            @foreach ($categories as $category)
                <span class="chip">{{ $category->name }}</span>
            @endforeach
        </div>
    @endif

    @if ($items->isNotEmpty())
        <div class="items-grid">
            @foreach ($items as $item)
                <div
                    class="item-card collection-card-trigger"
                    role="button"
                    tabindex="0"
                    data-item-name="{{ $item->name }}"
                    data-item-url="{{ route('customer.rental-items.show', ['id' => $item->id]) }}"
                    aria-label="View {{ $item->name }}, ₱{{ number_format((float) $item->rental_price, 2) }} per rental"
                >
                    @if ($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                    @else
                        <div class="placeholder" aria-hidden="true"><x-lucide-shirt width="56" height="56" /></div>
                    @endif
                    <div class="item-body">
                        <p class="item-cat">{{ $item->category->name ?? 'Formal Wear' }}</p>
                        <h3 class="item-name">{{ $item->name }}</h3>
                        <p class="item-price"><strong>₱{{ number_format((float) $item->rental_price, 2) }}</strong> / rental</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p style="text-align:center;color:var(--muted);">New pieces are being fitted for the collection — check back soon.</p>
    @endif
</section>

<dialog id="collection-signup-dialog" class="collection-signup-dialog" aria-labelledby="collection-signup-title">
        <h2 id="collection-signup-title">
            @guest
                Create an account to continue
            @else
                Item details
            @endguest
        </h2>
        <p>
            @guest
                Join Maribel Gowns Boutique to view item details and make a reservation.
            @else
                View details and reservation options for
            @endguest
            <span id="collection-signup-item"></span>
        </p>
        <div class="collection-signup-actions">
            <button type="button" onclick="document.getElementById('collection-signup-dialog').close()">Maybe later</button>
            @guest
                <a class="btn btn-primary signup-continue" href="{{ route('register') }}">Sign up</a>
            @else
                <a id="collection-item-details-link" class="btn btn-primary signup-continue" href="#">View Item</a>
            @endguest
        </div>
</dialog>

<script>
    document.addEventListener('click', (event) => {
        const collectionCard = event.target.closest('.collection-card-trigger');
        if (!collectionCard) {
            return;
        }

        const dialog = document.getElementById('collection-signup-dialog');
        document.getElementById('collection-signup-item').textContent = collectionCard.dataset.itemName || '';

        @auth
            document.getElementById('collection-item-details-link').href = collectionCard.dataset.itemUrl;
        @endauth

        if (!dialog.open) {
            dialog.showModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        const collectionCard = event.target.closest('.collection-card-trigger');
        if (!collectionCard || !['Enter', ' '].includes(event.key)) {
            return;
        }

        event.preventDefault();
        collectionCard.click();
    });
</script>

<section id="services">
    <div class="section-head">
        <p class="eyebrow">What We Do</p>
        <h2>Boutique service, start to finish</h2>
    </div>

    <div class="services-grid">
        <div class="service-card">
            <h3><span class="service-icon"><x-lucide-shirt width="20" height="20" /></span>Apparel Rental</h3>
            <p>Browse gowns, tuxedos, and formal pieces, then reserve them for the exact dates of your occasion.</p>
        </div>
        <div class="service-card">
            <h3><span class="service-icon"><x-lucide-scissors width="20" height="20" /></span>Customization</h3>
            <p>Send fitting and alteration requests on your chosen pieces so each look arrives tailored to you.</p>
        </div>
        <div class="service-card">
            <h3><span class="service-icon"><x-lucide-calendar-check width="20" height="20" /></span>Reservations &amp; Appointments</h3>
            <p>Book fitting appointments and manage reservations, payments, and schedules from your account.</p>
        </div>
    </div>
</section>

<section id="how-it-works" class="steps">
    <div class="section-head">
        <p class="eyebrow">How It Works</p>
        <h2>From browsing to ballroom</h2>
        <p>Four steps between you and the perfect fit.</p>
    </div>

    <div class="steps-grid">
        <div class="step">
            <p class="step-num">01 <span class="step-icon"><x-lucide-user width="18" height="18" /></span></p>
            <h3>Create your account</h3>
            <p>Register and verify your email with a one-time code — your wardrobe awaits.</p>
        </div>
        <div class="step">
            <p class="step-num">02 <span class="step-icon"><x-lucide-shopping-bag width="18" height="18" /></span></p>
            <h3>Choose your pieces</h3>
            <p>Explore the catalog and pick apparel matched to your event and style.</p>
        </div>
        <div class="step">
            <p class="step-num">03 <span class="step-icon"><x-lucide-ruler width="18" height="18" /></span></p>
            <h3>Fit &amp; reserve</h3>
            <p>Request customizations, book a fitting appointment, and lock in your dates.</p>
        </div>
        <div class="step">
            <p class="step-num">04 <span class="step-icon"><x-lucide-package-check width="18" height="18" /></span></p>
            <h3>Wear &amp; return</h3>
            <p>Step out in style — then return the pieces when the event wraps.</p>
        </div>
    </div>
</section>

<footer class="site-footer">
    <img class="footer-logo" src="{{ asset('images/maribel-gowns-boutique.png') }}" alt="Maribel Gowns Boutique">
    <p class="fine">&copy; {{ date('Y') }} {{ config('app.name', 'Maribel Gowns Boutique') }}. All rights reserved.</p>
</footer>

</body>
</html>
