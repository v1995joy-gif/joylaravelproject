<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Reserve {{ $package->name }}</title>
    <style>
        body { margin: 40px; margin-left: 300px; background: var(--neo-field); color: var(--event-ink); font-family: 'DM Sans', 'Segoe UI', sans-serif; }
        .container { max-width: 760px; padding: 28px; background: var(--neo-field); bordervar(--event-line); border-radius: 20px; }
        .back-link { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; padding: 9px 13px; bordervar(--event-line); border-radius: 8px; background: var(--neo-field); color: var(--event-gold); font-weight: bold; text-decoration: none; transition: background 160ms ease, border-color 160ms ease, transform 160ms ease; }
        .back-link:hover { border-color: var(--event-gold-dark); background: rgba(232,201,138,0.07); transform: translateX(-2px); }
        .back-link:focus-visible { outline: 3px solid rgba(227, 83, 47, 0.25); outline-offset: 2px; }
        h1 { margin-top: 0; }
        .package-summary { padding: 16px; margin: 20px 0; border-radius: 10px; background: rgba(232,201,138,0.07); }
        .package-summary p { margin: 8px 0; }
        label { display: block; margin: 16px 0 6px; font-weight: bold; }
        input, textarea { width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #d9c4ce; border-radius: 6px; font: inherit; }
        textarea { min-height: 100px; resize: vertical; }
        .error-box { padding: 12px 16px; color: var(--event-danger); background: rgba(237,61,129,0.10); border-radius: 6px; }
        button { margin-top: 22px; padding: 12px 18px; border: 0; border-radius: 6px; color: #fff; background: rgba(237,61,129,0.20); cursor: pointer; font: inherit; }
        @media (max-width: 760px) { body { margin: 20px; } .container { padding: 20px; } }
    </style>
</head>
<body>
    @include('layouts.navigation')

    <main class="container">
        <a href="{{ route('customer.packages.show', $package->id) }}" class="back-link">← Back to Package Details</a>
        <h1>Reserve {{ $package->name }}</h1>

        <div class="package-summary">
            <p><strong>Event Type:</strong> {{ $package->event_type }}</p>
            <p><strong>Package Price:</strong> ₱{{ number_format($package->price, 2) }}</p>
            <p><strong>Included Items:</strong> {{ $package->packageItems->count() }}</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.packages.reserve.store', $package->id) }}" method="POST">
            @csrf

            <label for="reservation_date">Reservation Date</label>
            <input type="date" id="reservation_date" name="reservation_date" value="{{ old('reservation_date') }}" required>

            <label for="event_date">Event Date</label>
            <input type="date" id="event_date" name="event_date" value="{{ old('event_date') }}" required>

            <label for="return_date">Return Date</label>
            <input type="date" id="return_date" name="return_date" value="{{ old('return_date') }}" required>

            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" placeholder="Enter any additional notes...">{{ old('notes') }}</textarea>

            <button type="submit">Submit Package Reservation</button>
        </form>
    </main>
</body>
</html>