<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('My Customizations') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            @forelse ($requests as $request)
                <article class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="flex flex-col justify-between gap-4 border-b border-[var(--event-line)] px-6 py-5 sm:flex-row sm:items-center" style="background-color: var(--neo-field);">
                        <div>
                            <div class="mb-1 flex flex-wrap items-center gap-3">
                                <h2 class="text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Request #{{ $request->id }}</h2>
                                @php
                                    $requestStatus = strtolower($request->status);
                                    $requestStatusColor = match (true) {
                                        str_contains($requestStatus, 'approv'),
                                        str_contains($requestStatus, 'confirm'),
                                        str_contains($requestStatus, 'complete') => 'bg-green-100 text-green-700 border-green-200',
                                        str_contains($requestStatus, 'reject'),
                                        str_contains($requestStatus, 'cancel') => 'bg-red-100 text-red-700 border-red-200',
                                        str_contains($requestStatus, 'pending') => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-bold {{ $requestStatusColor }}">
                                    {{ $request->status }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">Submitted on {{ $request->created_at?->format('M d, Y h:i A') ?? '—' }}</p>
                        </div>

                        <div class="sm:text-right">
                            <p class="mb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">Package</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $request->package?->name ?? ($request->request_type === 'New Item Request' ? 'New Item Request' : ($request->selected_item ?: 'Custom Set')) }}
                            </p>
                            @if ($request->package_total !== null)
                                <p class="mt-1 text-sm font-bold text-gray-800">Package total: ₱{{ number_format($request->package_total, 2) }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8 p-6 lg:grid-cols-2">
                        <div class="space-y-6">
                            <section>
                                <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-600">Existing Rental Items</h3>
                                @if ($request->items->isNotEmpty())
                                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                                        <table class="w-full min-w-[420px] border-collapse text-left">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Package Piece</th>
                                                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Item</th>
                                                    <th class="px-4 py-3 text-center text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-[var(--event-line)]">
                                                @foreach ($request->items as $item)
                                                    <tr>
                                                        <td class="px-4 py-3 text-sm text-gray-700">
                                                            {{ $item->package_item_name ?? $item->packageItem?->item_name ?? $item->packageItem?->rentalItem?->name ?? ($item->is_additional ? 'Additional item' : '—') }}
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $item->rentalItem?->name ?? 'Unknown Item' }}</td>
                                                        <td class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ $item->quantity }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-4 text-sm text-gray-500">No existing rental items requested.</p>
                                @endif
                            </section>

                            <section>
                                <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-600">New Item Requests</h3>
                                @if ($request->newItems->isNotEmpty())
                                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                                        <table class="w-full min-w-[480px] border-collapse text-left">
                                            <thead>
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Requested Item</th>
                                                    <th class="px-4 py-3 text-center text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Qty</th>
                                                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-[var(--event-line)]">
                                                @foreach ($request->newItems as $newItem)
                                                    <tr>
                                                        <td class="px-4 py-3 text-sm text-gray-700">
                                                            <p class="font-medium">{{ $newItem->item_name }}</p>
                                                            <p class="mt-1 text-xs text-gray-500">{{ $newItem->description ?? 'No description provided.' }}</p>
                                                            @if ($newItem->reference_image_path)
                                                                <a href="{{ asset('storage/' . $newItem->reference_image_path) }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-block">
                                                                    <img
                                                                        src="{{ asset('storage/' . $newItem->reference_image_path) }}"
                                                                        alt="Reference for {{ $newItem->item_name }}"
                                                                        class="h-16 w-16 rounded-lg border border-gray-200 object-cover"
                                                                    >
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ $newItem->quantity }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $newItem->status }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-4 text-sm text-gray-500">No new item requests.</p>
                                @endif
                            </section>
                        </div>

                        <section>
                            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-600">Customization Details</h3>
                            <div class="h-full rounded-xl border border-pink-100 bg-pink-50/50 p-5">
                                <p class="whitespace-pre-wrap text-sm leading-relaxed text-gray-700">{{ $request->customization_details ?: 'No details provided.' }}</p>
                                <a
                                    href="{{ route('customer.customizations.show', $request->id) }}"
                                    class="mt-5 inline-flex items-center rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm transition-opacity hover:opacity-90"
                                    style="background: linear-gradient(100deg, var(--event-gold), var(--event-pink));"
                                >
                                    View Details
                                </a>
                            </div>
                        </section>
                    </div>
                </article>
            @empty
                <div class="flex flex-col items-center justify-center rounded-2xl border border-gray-100 bg-white p-12 text-center shadow-sm">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                        <x-lucide-scissors width="32" height="32" aria-hidden="true" />
                    </div>
                    <h2 class="mb-1 text-lg font-bold text-gray-900">No customization requests</h2>
                    <p class="text-sm text-gray-500">You haven't submitted any custom requests yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
