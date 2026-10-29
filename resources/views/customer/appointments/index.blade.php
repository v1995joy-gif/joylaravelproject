<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('My Appointments') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($reservationsAwaitingAppointment->isNotEmpty())
            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm" aria-labelledby="book-appointment-heading">
                <div class="border-b border-[var(--event-line)] px-6 py-5" style="background-color: var(--neo-field);">
                    <h2 id="book-appointment-heading" class="text-sm font-bold uppercase tracking-wider text-gray-700">Book an Appointment</h2>
                    <p class="mt-1 text-sm text-gray-500">Choose an eligible reservation to request a fitting.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px] border-collapse text-left">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Item</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Event Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Reservation Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--event-line)]">
                            @foreach ($reservationsAwaitingAppointment as $reservation)
                                <tr class="transition-colors hover:bg-black/5">
                                    <td class="px-6 py-4 text-sm font-semibold" style="color: var(--event-ink);">
                                        {{ $reservation->rentalItem?->name ?? 'Unknown Item' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                        {{ $reservation->event_date?->format('M d, Y') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $reservationStatusColor = $reservation->status === 'Confirmed'
                                                ? 'bg-green-100 text-green-700 border-green-200'
                                                : 'bg-yellow-100 text-yellow-700 border-yellow-200';
                                        @endphp
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-bold {{ $reservationStatusColor }}">
                                            {{ $reservation->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($reservation->status === 'Returned')
                                            <form method="POST" action="{{ route('customer.reservations.destroy', $reservation->id) }}" onsubmit="return confirm('Delete this returned reservation record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-200"
                                                >
                                                    Delete Record
                                                </button>
                                            </form>
                                        @else
                                            <a
                                                href="{{ route('customer.appointments.create', $reservation->id) }}"
                                                class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold text-white shadow-sm transition-opacity hover:opacity-90"
                                                style="background: linear-gradient(100deg, var(--event-gold), var(--event-pink));"
                                            >
                                                Book Appointment
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm" aria-labelledby="scheduled-appointments-heading">
            <div class="border-b border-[var(--event-line)] px-6 py-5" style="background-color: var(--neo-field);">
                <h2 id="scheduled-appointments-heading" class="text-sm font-bold uppercase tracking-wider text-gray-700">Scheduled Appointments</h2>
                <p class="mt-1 text-sm text-gray-500">Review your requested fitting dates, times, and status.</p>
            </div>

            @if ($appointments->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px] border-collapse text-left">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Item</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Appointment Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Appointment Time</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--event-line)]">
                            @foreach ($appointments as $appointment)
                                <tr class="transition-colors hover:bg-black/5">
                                    <td class="px-6 py-4 text-sm font-semibold" style="color: var(--event-ink);">
                                        {{ $appointment->reservation?->rentalItem?->name ?? 'Unknown Item' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                        {{ $appointment->appointment_date?->format('M d, Y') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                        {{ \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $appointmentStatusColor = match ($appointment->status) {
                                                'Confirmed' => 'bg-green-100 text-green-700 border-green-200',
                                                'Completed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'Cancelled', 'Declined' => 'bg-red-100 text-red-700 border-red-200',
                                                'Pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                default => 'bg-gray-100 text-gray-700 border-gray-200',
                                            };
                                            $appointmentStatusLabel = match ($appointment->status) {
                                                'Pending' => 'Awaiting confirmation',
                                                'Confirmed' => 'Confirmed',
                                                'Completed' => 'Completed',
                                                'Cancelled' => 'Cancelled',
                                                'Declined' => 'Declined',
                                                default => $appointment->status,
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-bold {{ $appointmentStatusColor }}">
                                            {{ $appointmentStatusLabel }}
                                        </span>
                                    </td>
                                    <td class="max-w-[200px] whitespace-pre-line px-6 py-4 text-sm text-gray-500">
                                        {{ $appointment->notes ?? 'None' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <x-lucide-calendar-days class="mx-auto mb-3 text-gray-300" width="32" height="32" aria-hidden="true" />
                    <p class="text-sm font-semibold text-gray-700">No appointment requests yet.</p>
                    <p class="mt-1 text-sm text-gray-500">
                        @if ($reservationsAwaitingAppointment->isNotEmpty())
                            Choose <strong>Book Appointment</strong> above to request a fitting schedule.
                        @else
                            Once you have an eligible reservation, you can request a fitting appointment here.
                        @endif
                    </p>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
