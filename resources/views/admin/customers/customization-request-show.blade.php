<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Customization Request Details</title>

    <style>
        body {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            background: var(--neo-field);
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: var(--neo-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: var(--event-ink);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
        }

        .info-box {
            background: var(--neo-field);
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-box p {
            margin: 8px 0;
        }

        .section {
            margin-top: 30px;
        }

        .section h2 {
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            bordervar(--event-line);
            text-align: left;
            vertical-align: top;
        }

        th {
            background: var(--neo-field);
        }

        .notes {
            background: var(--neo-field);
            padding: 15px;
            border-radius: 6px;
            white-space: pre-line;
        }

        .status-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .status-form select {
            padding: 9px;
            bordervar(--event-line);
            border-radius: 6px;
        }

        .status-form button {
            padding: 9px 14px;
            border: none;
            border-radius: 6px;
            background: rgba(232,201,138,0.14);
            color: white;
            cursor: pointer;
        }

        .reference-image img {
            max-width: 280px;
            max-height: 280px;
            border-radius: 8px;
            bordervar(--event-line);
        }

        .empty {
            color: var(--event-muted);
        }

        .success {
            background: rgba(159,216,174,0.10);
            color: var(--event-ok);
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .confirm-button {
    padding: 10px 14px;
    border: none;
    border-radius: 6px;
    background: rgba(232,201,138,0.14);
    color: white;
    cursor: pointer;
}

.cancel-button {
    padding: 10px 14px;
    border: none;
    border-radius: 6px;
    background: rgba(237,61,129,0.20);
    color: white;
    cursor: pointer;
}

.status-form {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}
    </style>
</head>

<body style="margin-left: 300px;">

    @include('layouts.navigation')

<div class="container">

    <a
        href="{{ route('admin.customization-requests.index') }}"
        class="back-link"
    >
        ← Back to Customization Requests
    </a>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="success" style="background: rgba(237,61,129,0.10); color: var(--event-danger);">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="header">

        <h1>
            Customization Request #{{ $customizationRequest->id }}
        </h1>

        <form
    action="{{ route('admin.customization-requests.update-status', $customizationRequest->id) }}"
    method="POST"
    class="status-form"
>
    @csrf
    @method('PATCH')

    @if ($customizationRequest->status === 'Pending')

        <button
            type="submit"
            name="status"
            value="Confirmed"
            class="confirm-button"
        >
            Confirm Request
        </button>

        <button
            type="submit"
            name="status"
            value="Cancelled"
            class="cancel-button"
        >
            Reject Request
        </button>

    @elseif ($customizationRequest->status === 'Confirmed')

        <button
            type="submit"
            name="status"
            value="Completed"
            class="confirm-button"
        >
            Mark Completed
        </button>

        <button
            type="submit"
            name="status"
            value="Cancelled"
            class="cancel-button"
        >
            Cancel Request
        </button>

    @else

        <strong>
            Status: {{ $customizationRequest->status }}
        </strong>

    @endif

</form>

    </div>

    <div class="info-box">

        <p>
            <strong>Customer:</strong>
            {{ $customizationRequest->customer->name ?? 'Unknown Customer' }}
        </p>

        <p>
            <strong>Package:</strong>
            {{ $customizationRequest->package->name ?? ($customizationRequest->request_type === 'New Item Request' ? 'New Item Request' : 'Custom Set') }}
        </p>

        <p>
            <strong>Request Type:</strong>
            {{ $customizationRequest->request_type }}
        </p>

        <p>
            <strong>Date Submitted:</strong>
            {{ $customizationRequest->created_at->format('M d, Y h:i A') }}
        </p>

        @if ($customizationRequest->package_total !== null)
            <p>
                <strong>Package total (including priced additions):</strong>
                ₱{{ number_format($customizationRequest->package_total, 2) }}
            </p>
        @endif

        <p>
    <a
        href="{{ route('admin.customization-requests.status.edit', $customizationRequest->id) }}"
    >
        Edit Status
    </a>
</p>

    </div>

    <div class="section">

        <h2>Existing Rental Items</h2>

        @if ($customizationRequest->items->count() > 0)

            <table>

                <thead>
                    <tr>
                        <th>Package Piece</th>
                        <th>Rental Item</th>
                        <th>Quantity</th>
                        <th>Size</th>
                        <th>Color</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($customizationRequest->items as $item)

                        <tr>
                            <td>
                                {{ $item->package_item_name ?? $item->packageItem?->item_name ?? $item->packageItem?->rentalItem?->name ?? ($item->is_additional ? 'Additional item' : '—') }}
                            </td>
                            <td>
                                {{ $item->rentalItem->name ?? 'Unknown Item' }}
                                @if ($item->is_additional)
                                    (Additional)
                                @endif
                            </td>

                            <td>
                                {{ $item->quantity }}
                            </td>

                            <td>{{ $item->size ?? 'Not specified' }}</td>

                            <td>{{ $item->color ?? 'Not specified' }}</td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <p class="empty">
                No existing rental items requested.
            </p>

        @endif

    </div>

    <div class="section">

        <h2>New Item Requests</h2>

        @if ($customizationRequest->newItems->count() > 0)

            <table>

                <thead>
                    <tr>
                        <th>Requested Item</th>
                        <th>Quantity</th>
                        <th>Description</th>
                        <th>Reference Image</th>
                        <th>Status</th>
                        <th>Quote / Expense</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($customizationRequest->newItems as $newItem)

                        <tr>

                            <td>
                                {{ $newItem->item_name }}
                            </td>

                            <td>
                                {{ $newItem->quantity }}
                            </td>

                            <td>
                                {{ $newItem->description ?? 'No description provided.' }}
                            </td>

                            <td>

                                @if ($newItem->reference_image_path)

                                    <div class="reference-image">

                                        <img
                                            src="{{ asset('storage/' . $newItem->reference_image_path) }}"
                                            alt="Customer reference image"
                                        >

                                    </div>

                                @else

                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:10px;background:rgba(237,61,129,0.08);color:var(--event-muted);"><x-lucide-shirt width="24" height="24" /></span>

                                @endif

                            </td>

                            <td>
                                {{ $newItem->status }}
                            </td>

                            <td>
                                <form
                                    action="{{ route('admin.customization-request-items.costing', $newItem->id) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <label for="quoted-price-{{ $newItem->id }}">Quote per unit</label>
                                    <input
                                        id="quoted-price-{{ $newItem->id }}"
                                        type="number"
                                        name="quoted_price"
                                        min="0"
                                        step="0.01"
                                        value="{{ $newItem->quoted_price }}"
                                    >

                                    <label for="expense-price-{{ $newItem->id }}">Expense per unit</label>
                                    <input
                                        id="expense-price-{{ $newItem->id }}"
                                        type="number"
                                        name="expense_price"
                                        min="0"
                                        step="0.01"
                                        value="{{ $newItem->expense_price }}"
                                    >

                                    <button type="submit">Save amounts</button>
                                </form>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <p class="empty">
                No new item requests.
            </p>

        @endif

    </div>

    <div class="section">

        <h2>Customization Details</h2>

        <div class="notes">
            {{ $customizationRequest->customization_details }}
        </div>

    </div>

</div>

</body>
</html>