<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $item->name }} | Inventory</title>
    <style>
        body {
            background: var(--neo-field);
            color: var(--event-ink);
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            margin: 0;
            padding: 24px;
        }

        .item-detail {
            background: var(--neo-bg);
            border: 1px solid var(--event-line);
            border-radius: 10px;
            box-sizing: border-box;
            margin: 0 auto;
            max-width: 1100px;
            padding: 24px;
        }

        .item-detail-header {
            align-items: flex-start;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .item-detail-header h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 25px;
            margin: 0 0 4px;
            overflow-wrap: anywhere;
        }

        .item-detail-code {
            color: var(--event-muted);
            font-size: 13px;
        }

        .item-detail-layout {
            align-items: start;
            display: grid;
            gap: 28px;
            grid-template-columns: minmax(220px, 360px) minmax(0, 1fr);
        }

        .item-detail-image,
        .item-detail-placeholder {
            aspect-ratio: 4 / 5;
            background: var(--neo-field);
            border: 1px solid var(--event-line);
            border-radius: 8px;
            display: grid;
            object-fit: contain;
            place-items: center;
            width: 100%;
        }

        .item-detail-placeholder {
            color: var(--event-muted);
        }

        .item-detail-list {
            display: grid;
            gap: 0 20px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin: 0;
        }

        .item-detail-field {
            border-bottom: 1px solid var(--event-line);
            min-width: 0;
            padding: 13px 0;
        }

        .item-detail-field-wide {
            grid-column: 1 / -1;
        }

        .item-detail-field dt {
            color: var(--event-muted);
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .item-detail-field dd {
            margin: 0;
            overflow-wrap: anywhere;
            white-space: pre-line;
        }

        .item-detail-sizes {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .item-detail-size {
            background: var(--neo-field);
            border: 1px solid var(--event-line);
            border-radius: 5px;
            font-size: 12px;
            padding: 5px 8px;
        }

        .item-detail-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 22px;
        }

        .item-detail-actions a {
            text-decoration: none;
        }

        @media (max-width: 760px) {
            body { padding: 14px; }
            .item-detail { padding: 16px; }
            .item-detail-layout { grid-template-columns: 1fr; }
            .item-detail-image, .item-detail-placeholder { max-height: 440px; }
            .item-detail-header { flex-direction: column; }
        }

        @media (max-width: 480px) {
            .item-detail-list { grid-template-columns: 1fr; }
            .item-detail-field-wide { grid-column: auto; }
        }
    </style>
</head>
<body style="margin-left:300px;">
    @include('layouts.navigation')

    <main class="item-detail">
        <header class="item-detail-header">
            <div>
                <h1>{{ $item->name }}</h1>
                <div class="item-detail-code">Item code: {{ $item->item_code }}</div>
            </div>
            <span class="status-badge status-badge-{{ \Illuminate\Support\Str::slug($inventory['status']) }}">{{ $inventory['status'] }}</span>
        </header>

        <div class="item-detail-layout">
            <div>
                @if ($item->image)
                    <img class="item-detail-image" src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                @else
                    <div class="item-detail-placeholder">No image uploaded</div>
                @endif
            </div>

            <div>
                <dl class="item-detail-list">
                    <div class="item-detail-field">
                        <dt>Category</dt>
                        <dd>{{ $item->category->name ?? 'No category' }}</dd>
                    </div>
                    <div class="item-detail-field">
                        <dt>Rental price</dt>
                        <dd>₱{{ number_format($item->rental_price, 2) }}</dd>
                    </div>
                    <div class="item-detail-field">
                        <dt>Total quantity</dt>
                        <dd>{{ $item->quantity }} units</dd>
                    </div>
                    <div class="item-detail-field">
                        <dt>Inventory status</dt>
                        <dd>{{ $inventory['status'] }}</dd>
                    </div>
                    <div class="item-detail-field item-detail-field-wide">
                        <dt>Description</dt>
                        <dd>{{ $item->description ?: 'No description provided.' }}</dd>
                    </div>
                    <div class="item-detail-field item-detail-field-wide">
                        <dt>Available colors</dt>
                        <dd>{{ $item->colors ? implode(', ', $item->colors) : 'No colors specified.' }}</dd>
                    </div>
                    <div class="item-detail-field item-detail-field-wide">
                        <dt>Quantity by size</dt>
                        <dd>
                            @if ($item->size_quantities)
                                <span class="item-detail-sizes">
                                    @foreach ($item->size_quantities as $size => $quantity)
                                        <span class="item-detail-size">{{ $size }}: {{ $quantity }}</span>
                                    @endforeach
                                </span>
                            @else
                                No size quantities specified.
                            @endif
                        </dd>
                    </div>
                </dl>

                <div class="item-detail-actions">
                    <a href="{{ route('admin.rental-items.index') }}" class="rental-item-action rental-item-action-view">Back to inventory</a>
                    <a href="{{ route('admin.rental-items.edit', $item->id) }}" class="rental-item-action rental-item-action-edit">Edit item</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>