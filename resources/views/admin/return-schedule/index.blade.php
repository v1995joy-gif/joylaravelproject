<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Return Schedule</title>

    <style>
        body {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            margin: 40px;
            background: var(--neo-field);
        }

        .container {
            background: var(--neo-bg);
            padding: 25px;
            border-radius: 10px;
        }

        h1 {
            margin-bottom: 8px;
        }

        .description {
            color: var(--event-muted);
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid var(--event-line);
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: var(--neo-field);
        }

        button {
            padding: 8px 12px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .notify-button {
            background: rgba(232,201,138,0.14);
            color: white;
        }

        .notify-again-button {
            background: rgba(232,201,138,0.14);
            color: white;
        }

        .notified {
            color: var(--event-ok);
            font-weight: bold;
        }

        .not-notified {
            color: var(--event-muted);
        }

        .notification-time {
            display: block;
            margin-top: 4px;
            color: var(--event-muted);
            font-size: 12px;
        }

        .empty {
            padding: 25px;
            text-align: center;
            color: var(--event-muted);
        }

        .success {
            color: var(--event-ok);
            margin-bottom: 15px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: var(--event-ink);
        }
    </style>
</head>

<body style="margin-left: 300px;">

    @include('layouts.navigation')

<div class="container">

    <a
        href="{{ route('admin.reservations.index') }}"
        class="back-link"
    >
        ← Back to Reservation Management
    </a>

    <h1>Return Schedule</h1>

    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif

    @if (session('error'))
        <p class="error">
            {{ session('error') }}
        </p>
    @endif

    @if ($reservations->count())

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="border-b border-[var(--event-line)]" style="background-color: rgba(232, 201, 138, 0.12);">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Customer</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Item</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Quantity</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Event Date</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Return Date</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Return Status</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Return Notification Status</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[var(--event-line)]">
                        @foreach ($reservations as $reservation)
                            <tr class="hover:bg-black/5 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold" style="color: var(--event-ink);">
                                    {{ $reservation->customer->name ?? 'Unknown Customer' }}
                                </td>

                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->rentalItem->name ?? 'Unknown Item' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-center font-medium" style="color: var(--event-ink);">
                                    {{ $reservation->quantity }}
                                </td>

                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->event_date ? $reservation->event_date->format('M d, Y') : 'Not Set' }}
                                </td>

                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->return_date ? $reservation->return_date->format('M d, Y') : 'Not Set' }}
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    @if ($reservation->returned_at)
                                        <span class="notified">Returned</span>
                                        <span class="notification-time">
                                            {{ $reservation->returned_at->format('M d, Y h:i A') }}
                                        </span>
                                    @else
                                        <span class="not-notified">Awaiting return</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    @if ($reservation->return_notification_sent_at)
                                        <span class="notified">✓ Notified</span>
                                        <span class="notification-time">
                                            {{ $reservation->return_notification_sent_at->format('M d, Y h:i A') }}
                                        </span>

                                        @if ($reservation->customer && $reservation->customer->email)
                                            <form action="{{ route('admin.reservations.send-return-reminder', $reservation->id) }}" method="POST" class="mt-1">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="notify-again-button inline-flex items-center px-3 py-1.5 rounded-lg text-white text-xs font-semibold shadow-sm hover:shadow-md transition-all"
                                                    style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);"
                                                    onclick="return confirm('Send another return schedule notification to this customer?');"
                                                >
                                                    Notify Again
                                                </button>
                                            </form>
                                        @endif
                                    @elseif ($reservation->customer && $reservation->customer->email)
                                        <span class="not-notified">Not Yet Notified</span>
                                        <form action="{{ route('admin.reservations.send-return-reminder', $reservation->id) }}" method="POST" class="mt-1">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="notify-button inline-flex items-center px-3 py-1.5 rounded-lg text-white text-xs font-semibold shadow-sm hover:shadow-md transition-all"
                                                style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);"
                                                onclick="return confirm('Send return schedule notification to this customer?');"
                                            >
                                                Notify Customer
                                            </button>
                                        </form>
                                    @else
                                        <span class="not-notified">No Email</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    @if ($reservation->returned_at || $reservation->status === 'Returned')
                                        <span class="notified">✓ Completed</span>
                                    @elseif ($reservation->status === 'Confirmed')
                                        <form action="{{ route('admin.return-schedule.mark-returned', $reservation->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center px-4 py-2 rounded-lg text-white text-sm font-bold shadow-sm hover:shadow-md transition-all"
                                                style="background: linear-gradient(135deg, #c9a35f 0%, #bb8c2c 100%);"
                                                onclick="return confirm('Confirm that all rented items were returned successfully?');"
                                            >
                                                Mark Returned
                                            </button>
                                        </form>
                                    @else
                                        <span class="not-notified">Unavailable</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else

        <div class="empty">
            No return schedules found.
        </div>

    @endif

</div>

</body>
</html>