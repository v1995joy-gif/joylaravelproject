<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Customization Request Details') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <div>
            <a
                href="{{ route('customer.customizations.index') }}"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 transition-colors hover:text-pink-600"
            >
                <x-lucide-arrow-left width="16" height="16" aria-hidden="true" />
                Back to My Customization Requests
            </a>
        </div>

        @php
            $requestStatus = strtolower($customizationRequest->status);
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

        <article class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
            <div class="flex flex-col justify-between gap-4 border-b border-[var(--event-line)] px-6 py-6 sm:flex-row sm:items-center sm:px-8" style="background-color: var(--neo-field);">
                <div>
                    <div class="mb-2 flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Request #{{ $customizationRequest->id }}</h1>
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold {{ $requestStatusColor }}">
                            {{ $customizationRequest->status }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">Submitted on {{ $customizationRequest->created_at?->format('M d, Y h:i A') ?? '—' }}</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white/60 p-3 sm:text-right">
                    <p class="mb-1 text-[10px] font-bold uppercase tracking-wider text-gray-500">Package Type</p>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ $customizationRequest->package?->name ?? ($customizationRequest->request_type === 'New Item Request' ? 'New Item Request' : ($customizationRequest->selected_item ?: 'Custom Set')) }}
                    </p>
                    @if ($customizationRequest->package_total !== null)
                        <p class="mt-1 text-sm font-bold text-gray-800">Package total: ₱{{ number_format($customizationRequest->package_total, 2) }}</p>
                    @endif
                </div>
            </div>

            <div class="space-y-8 p-6 sm:p-8">
                <section aria-labelledby="existing-items-heading">
                    <h2 id="existing-items-heading" class="mb-4 border-b border-gray-100 pb-2 text-sm font-bold uppercase tracking-wider text-gray-800">Existing Rental Items</h2>
                    @if ($customizationRequest->items->isNotEmpty())
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full min-w-[700px] border-collapse text-left">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Package Piece</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">{{ $customizationRequest->request_type === 'Package' ? 'Selected Item' : 'Rental Item' }}</th>
                                        <th class="px-6 py-3 text-center text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Quantity</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Size</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Color</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--event-line)] bg-white">
                                    @foreach ($customizationRequest->items as $item)
                                        <tr class="transition-colors hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ $item->package_item_name ?? $item->packageItem?->item_name ?? $item->packageItem?->rentalItem?->name ?? ($item->is_additional ? 'Additional item' : '—') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                                {{ $item->rentalItem?->name ?? 'Unknown Item' }}
                                                @if ($item->is_additional)
                                                    <span class="text-xs font-normal text-gray-500">(Additional)</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center text-sm font-medium text-gray-800">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->size ?: 'Not specified' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->color ?: 'Not specified' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-4 text-sm text-gray-500">No existing rental items were requested.</p>
                    @endif
                </section>

                <section aria-labelledby="new-items-heading">
                    <h2 id="new-items-heading" class="mb-4 border-b border-gray-100 pb-2 text-sm font-bold uppercase tracking-wider text-gray-800">New Item Requests</h2>
                    @if ($customizationRequest->newItems->isNotEmpty())
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full min-w-[760px] border-collapse text-left">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Requested Item</th>
                                        <th class="px-6 py-3 text-center text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Quantity</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Details</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Reference Image</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--event-line)] bg-white">
                                    @foreach ($customizationRequest->newItems as $newItem)
                                        <tr class="transition-colors hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $newItem->item_name }}</td>
                                            <td class="px-6 py-4 text-center text-sm font-medium text-gray-800">{{ $newItem->quantity }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $newItem->description ?: 'No description provided.' }}</td>
                                            <td class="px-6 py-4">
                                                @if ($newItem->reference_image_path)
                                                    <a href="{{ asset('storage/' . $newItem->reference_image_path) }}" target="_blank" rel="noopener noreferrer">
                                                        <img
                                                            src="{{ asset('storage/' . $newItem->reference_image_path) }}"
                                                            alt="Reference for {{ $newItem->item_name }}"
                                                            class="h-16 w-16 rounded-lg border border-gray-200 object-cover"
                                                        >
                                                    </a>
                                                @else
                                                    <span class="text-sm text-gray-500">None</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $newItem->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-4 text-center text-sm text-gray-500">No new item requests.</p>
                    @endif
                </section>

                <section aria-labelledby="customization-details-heading">
                    <h2 id="customization-details-heading" class="mb-4 border-b border-gray-100 pb-2 text-sm font-bold uppercase tracking-wider text-gray-800">Customization Details</h2>
                    <div class="rounded-xl border border-pink-100 bg-pink-50 p-6">
                        <p class="whitespace-pre-wrap text-sm leading-relaxed text-gray-800">
                            {{ $customizationRequest->customization_details ?: 'No details provided.' }}
                        </p>
                    </div>
                </section>
            </div>
        </article>
    </div>
</x-app-layout>
