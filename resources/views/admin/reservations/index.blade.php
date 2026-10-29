<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservation Management') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium flex items-center gap-2" role="status">
                <x-lucide-check-circle width="18" height="18" aria-hidden="true" />
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm font-medium flex items-center gap-2" role="alert">
                <x-lucide-alert-circle width="18" height="18" aria-hidden="true" />
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">All Reservations</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1100px]">
                    <thead>
                        <tr class="border-b border-[var(--event-line)]" style="background-color: rgba(232, 201, 138, 0.12);">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left w-16" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">ID</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Customer</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Item</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Package Request</th>
                            <th class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-center" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Qty</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Reservation Date</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Event Date</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Return Date</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-left" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right w-48" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Action</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border: 0 !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Notes</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[var(--event-line)]">
                        @forelse ($reservations as $reservation)
                            <tr class="hover:bg-black/5 transition-colors group">
                                <td class="px-6 py-4 text-sm font-medium" style="color: var(--event-ink);">
                                    {{ $reservation->id }}
                                </td>

                                <td class="px-6 py-4 text-sm font-semibold" style="color: var(--event-ink);">
                                    {{ $reservation->customer?->name ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->rentalItem?->name ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    @if ($reservation->customizationRequest)
                                        <a href="{{ route('admin.customization-requests.show', $reservation->customizationRequest->id) }}" class="text-pink-600 hover:text-pink-700 font-medium hover:underline">
                                            {{ $reservation->customizationRequest->package?->name ?? 'Package' }}
                                            <span class="block text-xs text-gray-500 mt-0.5">Req #{{ $reservation->customizationRequest->id }}</span>
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--event-ink);">
                                    {{ $reservation->quantity }}
                                </td>

                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->reservation_date->format('M d, Y') }}
                                </td>

                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->event_date->format('M d, Y') }}
                                </td>

                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $reservation->return_date ? $reservation->return_date->format('M d, Y') : 'Not Set' }}
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'confirmed' => 'bg-green-100 text-green-700 border-green-200',
                                            'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                            'returned' => 'bg-gray-100 text-gray-700 border-gray-200',
                                        ];
                                        $badgeColor = $statusColors[strtolower($reservation->status)] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeColor }}">
                                        {{ match ($reservation->status) {
                                            'Pending' => 'Awaiting confirmation',
                                            'Confirmed' => 'Confirmed booking',
                                            'Cancelled' => 'Cancelled',
                                            'Returned' => 'Items returned',
                                            default => $reservation->status,
                                        } }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    @if ($reservation->status === 'Pending')
                                        <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" method="POST" class="inline-block w-full max-w-[180px]">
                                            @csrf
                                            @method('PATCH')
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 text-left" for="reservation-status-{{ $reservation->id }}">Change status</label>
                                            <select
                                                id="reservation-status-{{ $reservation->id }}"
                                                name="status"
                                                onchange="this.form.submit()"
                                                aria-label="Change reservation status"
                                                class="block w-full pl-3 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-500 bg-white shadow-sm cursor-pointer hover:border-gray-400 transition-colors"
                                            >
                                                <option value="Pending" selected>Awaiting confirmation</option>
                                                <option value="Confirmed">Confirm Booking</option>
                                                <option value="Cancelled">Cancel</option>
                                            </select>
                                        </form>
                                    @else
                                        <div class="inline-flex flex-col items-end gap-1">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status locked</span>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[var(--event-line)] text-xs font-semibold" style="background-color: var(--neo-field); color: var(--event-muted);">
                                                <x-lucide-lock width="12" height="12" class="text-gray-400" aria-hidden="true" />
                                                {{ match ($reservation->status) {
                                                    'Confirmed' => 'Confirmed booking',
                                                    'Cancelled' => 'Cancelled',
                                                    'Returned' => 'Items returned',
                                                    default => $reservation->status,
                                                } }}
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-right max-w-[150px] truncate" style="color: var(--event-muted);" title="{{ $reservation->notes }}">
                                    {{ $reservation->notes ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="p-12 text-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 mb-4 mx-auto">
                                        <x-lucide-calendar-x width="32" height="32" aria-hidden="true" />
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">No reservations found</h3>
                                    <p class="text-sm text-gray-500">When customers book items, they will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>
