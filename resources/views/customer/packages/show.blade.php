<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $package->name }} - Package Details</title>
    <style>
        body { margin: 40px; margin-left: 300px; background: var(--neo-field); color: var(--event-ink); font-family: 'DM Sans', 'Segoe UI', sans-serif; }
        .container { max-width: 900px; padding: 28px; background: var(--neo-field); bordervar(--event-line); border-radius: 20px; }
        .back-link { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; padding: 9px 13px; bordervar(--event-line); border-radius: 8px; background: var(--neo-field); color: var(--event-gold); font-weight: bold; text-decoration: none; transition: background 160ms ease, border-color 160ms ease, transform 160ms ease; }
        .back-link:hover { border-color: var(--event-gold-dark); background: rgba(232,201,138,0.07); transform: translateX(-2px); }
        .back-link:focus-visible { outline: 3px solid rgba(227, 83, 47, 0.25); outline-offset: 2px; }
        .package-image { display: block; width: 100%; max-height: 260px; margin-bottom: 22px; object-fit: cover; border-radius: 12px; background: rgba(237,61,129,0.08); }
        h1 { margin: 0 0 18px; }
        .package-info p { margin: 9px 0; }
        .package-info strong { color: var(--event-muted); }
        table { width: 100%; margin-top: 18px; border-collapse: collapse; }
        th, td { padding: 12px; bordervar(--event-line); text-align: left; }
        th { color: var(--event-gold-dark); background: rgba(232,201,138,0.07); font-size: 13px; text-transform: uppercase; }
        .empty { padding: 20px 0; color: var(--event-muted); }
        .actions { margin-top: 24px; }
        .actions a { display: inline-block; padding: 10px 16px; border-radius: 6px; color: #fff; text-decoration: none; }
        .reserve-btn { margin-right: 10px; background: linear-gradient(100deg, var(--event-gold), var(--event-pink)); color: #fff; }
        .customize-btn { background: var(--neo-field); color: var(--event-ink) !important; }
        @media (max-width: 760px) { body { margin: 20px; } .container { padding: 20px; } table { font-size: 14px; } }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <main class="container">
        <a href="{{ route('customer.packages.index') }}" class="back-link">&larr; Back to Packages</a>

        @if ($package->image)
            <img class="package-image" src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}">
        @endif

        <h1>{{ $package->name }}</h1>

        <div class="package-info">
            <p><strong>Event Type:</strong> {{ $package->event_type }}</p>
            <p><strong>Package Price:</strong> ₱{{ number_format($package->price, 2) }}</p>
            @if ($package->description)
                <p><strong>Description:</strong> {{ $package->description }}</p>
            @endif
            <p><strong>Status:</strong> {{ $package->status }}</p>
        </div>

        <h2>Included Package Items</h2>
        <p>Each row is an included package piece. When reserving, choose the available rental item for every piece; multi-piece outfits are listed as separate rows.</p>

        @if ($package->packageItems->count())
            <table>
                <thead>
                    <tr><th>Included Piece</th><th>Included Quantity</th></tr>
                </thead>
                <tbody>
                    @foreach ($package->packageItems as $packageItem)
                        <tr>
                            <td>{{ $packageItem->item_name ?? $packageItem->rentalItem?->name ?? 'Unknown Item' }}</td>
                            <td>{{ $packageItem->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty">No rental items are currently listed in this package.</p>
        @endif

        <div class="actions">
            <a href="{{ route('customer.customizations.create', $package->id) }}" class="customize-btn">Customize &amp; Reserve This Package</a>
        </div>
    </main>
</body>
</html>
