<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $package->name }} - Package Details</title>
</head>
<body class="admin-package-details-page">
    @include('layouts.navigation')

    <main class="admin-page package-details-page">
        <a href="{{ route('admin.packages.index') }}" class="package-back-link">Back to Packages</a>

        <div class="page-heading">
            <div>
                <p class="page-kicker">Package details</p>
                <h1>{{ $package->name }}</h1>
                <p class="page-description">Review the item types and quantities included in this package.</p>
            </div>
        </div>

        <section class="package-summary-panel">
            <div><span>Event type</span><strong>{{ $package->event_type }}</strong></div>
            <div><span>Package price</span><strong>₱{{ number_format($package->price, 2) }}</strong></div>
            <div><span>Status</span><strong class="status-badge status-badge-{{ strtolower($package->status) }}">{{ $package->status === 'Active' ? 'Available for booking' : 'Not available for booking' }}</strong></div>
            <div><span>Included items</span><strong>{{ $package->packageItems->count() }}</strong></div>
        </section>

        @if ($package->description)
            <section class="package-description-panel">
                <p class="page-kicker">Description</p>
                <p>{{ $package->description }}</p>
            </section>
        @endif

        <section class="report-panel package-items-panel">
            <div class="report-panel-heading">
                <div>
                    <p class="page-kicker">Package contents</p>
                    <h2>Included Package Pieces</h2>
                    <p>Each row is a separate choice the customer must make when reserving this package.</p>
                </div>
            </div>

            @if ($package->packageItems->count())
                <div class="report-table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Included Piece</th>
                                <th>Category</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($package->packageItems as $packageItem)
                                <tr>
                                    <td>{{ $packageItem->item_name ?? $packageItem->rentalItem?->name ?? 'Unknown Item' }}</td>
                                    <td>{{ $packageItem->rentalItem?->category?->name ?? 'Customer selects' }}</td>
                                    <td>{{ $packageItem->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="empty">No rental items are currently listed in this package.</p>
            @endif
        </section>
    </main>
</body>
</html>
