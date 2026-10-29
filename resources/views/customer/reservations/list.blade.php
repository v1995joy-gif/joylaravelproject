<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('My Reservations') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                {{ session('success') }}
                @if (session('customization_request_id'))
                    <a
                        href="{{ route('customer.customizations.show', session('customization_request_id')) }}"
                        class="ml-1 font-semibold underline"
                    >
                        View package request #{{ session('customization_request_id') }}
                    </a>
                @endif
            </div>
        @endif

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm" aria-labelledby="package-reservations-heading">
            <div class="border-b border-[var(--event-line)] px-6 py-5" style="background-color: var(--neo-field);">
                <h2 id="package-reservations-heading" class="text-sm font-bold uppercase tracking-wider text-gray-700">Rental Package Reservations</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] border-collapse text-left">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Package</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Selected Item</th>
                            <th class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Qty</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Reservation Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Event Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Amount Due</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--event-line)]">
                        @forelse ($packageReservations as $reservation)
                            <tr class="transition-colors hover:bg-black/5">
                                <td class="px-6 py-4 text-sm font-semibold" style="color: var(--event-ink);">
                                    <a
                                        href="{{ route('customer.customizations.show', $reservation->customizationRequest->id) }}"
                                        class="text-pink-600 hover:text-pink-700 hover:underline"
                                    >
                                        {{ $reservation->customizationRequest->package?->name ?? 'Package' }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->rentalItem?->name ?? 'Item removed' }}
                                </td>
                                <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--event-ink);">
                                    {{ $reservation->quantity }}
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->reservation_date?->format('M d, Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->event_date?->format('M d, Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match ($reservation->status) {
                                            'Confirmed' => 'bg-green-100 text-green-700 border-green-200',
                                            'Cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                            'Pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'Returned' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-bold {{ $statusColor }}">
                                        {{ $reservation->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-semibold" style="color: var(--event-ink);">
                                    <span class="block">Package total: ₱{{ number_format($reservation->customizationRequest->package_total ?? 0, 2) }}</span>
                                    <span class="mt-1 block text-xs font-normal" style="color: var(--event-muted);">
                                        This item: ₱{{ number_format($reservation->totalDueAmount(), 2) }}
                                    </span>
                                </td>
                                <td class="max-w-[150px] truncate px-6 py-4 text-right text-sm text-gray-500" title="{{ $reservation->notes }}">
                                    {{ $reservation->notes ?? 'None' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center">
                                    <x-lucide-package-open class="mx-auto mb-3 text-gray-300" width="32" height="32" aria-hidden="true" />
                                    <p class="text-sm text-gray-500">No rental package reservations yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm" aria-labelledby="item-reservations-heading">
            <div class="border-b border-[var(--event-line)] px-6 py-5" style="background-color: var(--neo-field);">
                <h2 id="item-reservations-heading" class="text-sm font-bold uppercase tracking-wider text-gray-700">Rental Item Reservations</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] border-collapse text-left">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Item</th>
                            <th class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Qty</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Reservation Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Event Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Amount Due</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--event-line)]">
                        @forelse ($itemReservations as $reservation)
                            <tr class="transition-colors hover:bg-black/5">
                                <td class="px-6 py-4 text-sm font-semibold" style="color: var(--event-ink);">
                                    {{ $reservation->rentalItem?->name ?? 'Item removed' }}
                                </td>
                                <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--event-ink);">
                                    {{ $reservation->quantity }}
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->reservation_date?->format('M d, Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->event_date?->format('M d, Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match ($reservation->status) {
                                            'Confirmed' => 'bg-green-100 text-green-700 border-green-200',
                                            'Cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                            'Pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'Returned' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-bold {{ $statusColor }}">
                                        {{ $reservation->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-semibold" style="color: var(--event-ink);">
                                    ₱{{ number_format($reservation->totalDueAmount(), 2) }}
                                </td>
                                <td class="max-w-[150px] truncate px-6 py-4 text-right text-sm text-gray-500" title="{{ $reservation->notes }}">
                                    {{ $reservation->notes ?? 'None' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center">
                                    <x-lucide-calendar-x class="mx-auto mb-3 text-gray-300" width="32" height="32" aria-hidden="true" />
                                    <p class="text-sm text-gray-500">No rental item reservations yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
