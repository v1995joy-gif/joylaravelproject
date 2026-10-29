<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Notification & Reminder Management') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-[var(--event-line)] px-6 py-5" style="background-color: var(--neo-field);">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Reservation Reminders</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] border-collapse text-left">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Item</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Event Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Reservation Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Notification Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--event-line)]">
                        @forelse ($reservations as $reservation)
                            <tr class="transition-colors hover:bg-black/5">
                                <td class="px-6 py-4 text-sm font-semibold" style="color: var(--event-ink);">{{ $reservation->customer?->name ?? 'Unknown Customer' }}</td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">{{ $reservation->rentalItem?->name ?? 'Unknown Item' }}</td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">{{ $reservation->event_date?->format('M d, Y') ?? 'Not Set' }}</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge status-badge-{{ strtolower($reservation->status) }}">
                                        {{ match ($reservation->status) {
                                            'Pending' => 'Awaiting confirmation',
                                            'Confirmed' => 'Confirmed',
                                            'Cancelled' => 'Cancelled',
                                            'Returned' => 'Returned',
                                            default => $reservation->status,
                                        } }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    @if ($reservation->reservation_notification_sent_at)
                                        <span class="font-semibold" style="color: var(--event-ok);">
                                            Automatically sent {{ $reservation->reservation_notification_sent_at->format('M d, Y h:i A') }}
                                        </span>
                                    @elseif ($reservation->status === 'Confirmed' && $reservation->customer?->email)
                                        <span style="color: var(--event-muted);">Automatic notification pending</span>
                                    @elseif ($reservation->status === 'Confirmed')
                                        <span style="color: var(--event-muted);">No customer email</span>
                                    @else
                                        <span style="color: var(--event-muted);">Waiting for confirmation</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No reservations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($reservations->hasPages())
                <div class="border-t border-[var(--event-line)] px-6 py-4">{{ $reservations->links() }}</div>
            @endif
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-[var(--event-line)] px-6 py-5" style="background-color: var(--neo-field);">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Payment Receipt Notifications</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] border-collapse text-left">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Item</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Payment Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Notification Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--event-line)]">
                        @forelse ($payments as $payment)
                            <tr class="transition-colors hover:bg-black/5">
                                <td class="px-6 py-4 text-sm font-semibold" style="color: var(--event-ink);">{{ $payment->reservation?->customer?->name ?? 'Unknown Customer' }}</td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">{{ $payment->reservation?->rentalItem?->name ?? 'Unknown Item' }}</td>
                                <td class="px-6 py-4 text-sm font-medium" style="color: var(--event-ink);">₱{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge status-badge-{{ strtolower($payment->status) }}">
                                        {{ match ($payment->status) {
                                            'Pending' => 'Awaiting review',
                                            'Confirmed' => 'Payment confirmed',
                                            'Rejected' => 'Payment declined',
                                            default => $payment->status,
                                        } }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    @if ($payment->payment_notification_sent_at)
                                        <span class="font-semibold" style="color: var(--event-ok);">Receipt sent {{ $payment->payment_notification_sent_at->format('M d, Y h:i A') }}</span>
                                    @elseif ($payment->status === 'Confirmed' && $payment->reservation?->customer?->email)
                                        <span style="color: var(--event-muted);">Automatic receipt pending</span>
                                    @elseif ($payment->status === 'Confirmed')
                                        <span style="color: var(--event-muted);">No customer email</span>
                                    @else
                                        <span style="color: var(--event-muted);">Waiting for payment confirmation</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No payment records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($payments->hasPages())
                <div class="border-t border-[var(--event-line)] px-6 py-4">{{ $payments->links() }}</div>
            @endif
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-[var(--event-line)] px-6 py-5" style="background-color: var(--neo-field);">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Return Schedule Notifications</h3>
                <p class="mt-1 text-xs text-gray-500">Notify customers about their scheduled rental return date.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] border-collapse text-left">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Item</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Quantity</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Return Date</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Notification Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--event-line)]">
                        @forelse ($returnSchedules as $reservation)
                            <tr class="transition-colors hover:bg-black/5">
                                <td class="px-6 py-4 text-sm font-semibold" style="color: var(--event-ink);">{{ $reservation->customer?->name ?? 'Unknown Customer' }}</td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">{{ $reservation->rentalItem?->name ?? 'Unknown Item' }}</td>
                                <td class="px-6 py-4 text-center text-sm" style="color: var(--event-ink);">{{ $reservation->quantity }}</td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">{{ $reservation->return_date?->format('M d, Y') ?? 'Not Set' }}</td>
                                <td class="px-6 py-4 text-right text-sm">
                                    @if ($reservation->return_notification_sent_at)
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="font-semibold" style="color: var(--event-ok);">✓ Notified</span>
                                            <span class="text-xs" style="color: var(--event-muted);">{{ $reservation->return_notification_sent_at->format('M d, Y h:i A') }}</span>
                                            @if ($reservation->customer?->email)
                                                <form action="{{ route('admin.reservations.send-return-reminder', $reservation->id) }}" method="POST" class="mt-1">
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="rounded-lg px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-all hover:shadow-md"
                                                        style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);"
                                                        onclick="return confirm('Send another return schedule notification to this customer?');"
                                                    >
                                                        Notify Again
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @elseif ($reservation->customer?->email)
                                        <div class="flex flex-col items-end gap-1">
                                            <span style="color: var(--event-muted);">Not Yet Notified</span>
                                            <form action="{{ route('admin.reservations.send-return-reminder', $reservation->id) }}" method="POST" class="mt-1">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="rounded-lg px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-all hover:shadow-md"
                                                    style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);"
                                                    onclick="return confirm('Send return schedule notification to this customer?');"
                                                >
                                                    Notify Customer
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span style="color: var(--event-muted);">No Email</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No return schedules found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
