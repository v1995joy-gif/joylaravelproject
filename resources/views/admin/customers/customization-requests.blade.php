<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Customization Requests</title>

    <style>
        body {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            margin: 40px;
            background: var(--neo-field);
        }

        h1 {
            margin-bottom: 20px;
        }

        .container {
            background: var(--neo-bg);
            padding: 25px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            bordervar(--event-line);
            text-align: left;
        }

        th {
            background: var(--neo-field);
        }

        .empty {
            padding: 20px;
            text-align: center;
        }
    </style>
</head>

<body style="margin-left: 300px;">

    @include('layouts.navigation')

    <div class="container">

        <h1>Customization Requests</h1>

        @if (session('success'))
    <p>
        {{ session('success') }}
    </p>
@endif

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Selected Item</th>
                    <th>Customization Details</th>
                    <th>Status</th>
                    <th>Update Status</th>
                    <th>Date Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>

                        <td>
                            {{ $request->customer->name ?? 'Unknown Customer' }}
                        </td>

                        <td>
                            {{ $request->selected_item }}
                        </td>

                        <td>
                            {{ $request->customization_details }}
                        </td>

                        <td>
                            <span class="status-badge status-badge-{{ strtolower($request->status) }}">
                                {{ match ($request->status) {
                                    'Pending' => 'Awaiting review',
                                    'Confirmed' => 'Request approved',
                                    'Completed' => 'Completed',
                                    'Cancelled' => 'Declined',
                                    default => $request->status,
                                } }}
                            </span>
</td>

<td>

    @if ($request->status === 'Pending')

        <form
            action="{{ route('admin.customization-requests.update-status', $request->id) }}"
            method="POST"
            style="display: inline-block; margin-right: 5px;"
        >
            @csrf
            @method('PATCH')

            <button
                type="submit"
                name="status"
                value="Confirmed"
                style="
                    padding: 8px 12px;
                    border: none;
                    border-radius: 5px;
                    background: rgba(232,201,138,0.14);
                    color: white;
                    cursor: pointer;
                "
            >
                Confirm
            </button>
        </form>

        <form
            action="{{ route('admin.customization-requests.update-status', $request->id) }}"
            method="POST"
            style="display: inline-block;"
        >
            @csrf
            @method('PATCH')

            <button
                type="submit"
                name="status"
                value="Cancelled"
                style="
                    padding: 8px 12px;
                    border: none;
                    border-radius: 5px;
                    background: rgba(237,61,129,0.20);
                    color: white;
                    cursor: pointer;
                "
            >
                Reject
            </button>
        </form>

    @elseif ($request->status === 'Confirmed')

        <form
            action="{{ route('admin.customization-requests.update-status', $request->id) }}"
            method="POST"
            style="display: inline-block; margin-right: 5px;"
        >
            @csrf
            @method('PATCH')

            <button
                type="submit"
                name="status"
                value="Completed"
                style="
                    padding: 8px 12px;
                    border: none;
                    border-radius: 5px;
                    background: rgba(232,201,138,0.14);
                    color: white;
                    cursor: pointer;
                "
            >
                Mark Completed
            </button>
        </form>

        <form
            action="{{ route('admin.customization-requests.update-status', $request->id) }}"
            method="POST"
            style="display: inline-block;"
        >
            @csrf
            @method('PATCH')

            <button
                type="submit"
                name="status"
                value="Cancelled"
                style="
                    padding: 8px 12px;
                    border: none;
                    border-radius: 5px;
                    background: rgba(237,61,129,0.20);
                    color: white;
                    cursor: pointer;
                "
            >
                Cancel
            </button>
        </form>

    @else

        <span class="status-badge status-badge-{{ strtolower($request->status) }}">
            {{ $request->status === 'Completed' ? 'Completed' : 'Declined' }}
        </span>

    @endif

</td>

<td>
    {{ $request->created_at->format('M d, Y') }}
</td>

<td>
    <a class="customization-view-action" href="{{ route('admin.customization-requests.show', $request->id) }}">
        View Details
    </a>

    <form action="{{ route('admin.customization-requests.destroy', $request->id) }}" method="POST" style="display:inline-block; margin-left:8px;">
        @csrf
        @method('DELETE')
        <button
            type="submit"
            class="customization-delete-action"
            onclick="return confirm('Permanently delete this request, its selected items, and uploaded reference images? This cannot be undone.');"
        >
            Delete
        </button>
    </form>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">
                            No customization requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2 style="margin-top:35px;">New Item Customization Requests</h2>

        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Customer</th>
                    <th>Requested Item</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Quote</th>
                    <th>Expense</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @php($hasNewItemRequests = $requests->contains(fn ($request) => $request->newItems->isNotEmpty()))
                @if ($hasNewItemRequests)
                    @foreach ($requests as $request)
                        @foreach ($request->newItems as $newItem)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->customer->name ?? 'Unknown Customer' }}</td>
                                <td>{{ $newItem->item_name }}</td>
                                <td>{{ $newItem->quantity }}</td>
                                <td>{{ $newItem->description ?? 'None' }}</td>
                                <td>{{ $newItem->status }}</td>
                                <td>{{ $newItem->quoted_price !== null ? '₱'.number_format($newItem->quoted_price * $newItem->quantity, 2) : 'Not set' }}</td>
                                <td>{{ $newItem->expense_price !== null ? '₱'.number_format($newItem->expense_price * $newItem->quantity, 2) : 'Not set' }}</td>
                                <td>
                                    <a class="customization-view-action" href="{{ route('admin.customization-requests.show', $request->id) }}">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="empty">No new item customization requests found.</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>

</body>
</html>