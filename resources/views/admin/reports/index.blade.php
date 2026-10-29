<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Reports & Transactions') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Business Intelligence</p>
                <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Reports &amp; Transactions</h1>
            </div>
            <span class="rounded-full border border-yellow-100 bg-yellow-50 px-4 py-2 text-sm font-semibold text-yellow-700">
                {{ $start->format('M d') }} – {{ $end->format('M d, Y') }}
            </span>
        </div>

        <form
            method="GET"
            action="{{ route('admin.reports.index') }}"
            class="mb-8 flex flex-col items-end justify-between gap-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:flex-row"
        >
            <div class="flex w-full flex-col gap-6 sm:flex-row md:w-auto">
                <div>
                    <label for="period" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Report Period</label>
                    <select
                        id="period"
                        name="period"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50/50 px-4 py-2.5 font-medium focus:border-pink-500 focus:ring-2 focus:ring-pink-200 sm:w-48"
                    >
                        <option value="weekly" @selected($period === 'weekly')>Weekly</option>
                        <option value="monthly" @selected($period === 'monthly')>Monthly</option>
                    </select>
                </div>
                <div>
                    <label for="date" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Week or Month Containing</label>
                    <input
                        id="date"
                        type="date"
                        name="date"
                        value="{{ $selectedDate }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-gray-50/50 px-4 py-2.5 font-medium focus:border-pink-500 focus:ring-2 focus:ring-pink-200 sm:w-48"
                    >
                </div>
            </div>
            <button
                type="submit"
                class="w-full rounded-lg px-6 py-2.5 font-medium text-white shadow-sm transition-all hover:shadow-md md:w-auto"
                style="background: linear-gradient(135deg, #f97316 0%, #ec4899 100%);"
            >
                Generate Report
            </button>
        </form>

        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <article class="relative overflow-hidden rounded-2xl p-6 text-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl" style="background: linear-gradient(135deg, #4a1525 0%, #8c2e48 100%);">
                <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-pink-200">Confirmed Revenue</p>
                <h3 class="mb-2 text-3xl font-bold">₱{{ number_format($revenueCents / 100, 2) }}</h3>
                <p class="text-xs text-pink-100">Confirmed payments and saved quotes</p>
            </article>

            <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">Reservations</p>
                <h3 class="mb-2 text-3xl font-bold text-gray-900">{{ $reservationSummary['total'] }}</h3>
                <p class="text-xs text-gray-500">{{ $reservationSummary['confirmed'] }} confirmed / {{ $reservationSummary['pending'] }} pending</p>
            </article>

            <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">Payments</p>
                <h3 class="mb-2 text-3xl font-bold text-gray-900">{{ $paymentSummary['total'] }}</h3>
                <p class="text-xs text-gray-500">{{ $paymentSummary['pending'] }} pending / {{ $paymentSummary['rejected'] }} rejected</p>
            </article>

            <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">Requested New Units</p>
                <h3 class="mb-2 text-3xl font-bold text-gray-900">{{ $newItemUnits }}</h3>
                <p class="line-clamp-2 text-xs text-gray-500">Quote or expense can be added from request details</p>
            </article>
        </div>

        <section class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-start justify-between">
                <div>
                    <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Profit &amp; Loss Analysis</p>
                    <h2 class="text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Financial position for this period</h2>
                </div>
                <span class="rounded-full border border-yellow-100 bg-yellow-50 px-3 py-1 text-xs font-bold text-yellow-700">
                    {{ $newItemCostCents || $quotedRevenueCents ? 'Costing included' : 'Cost data required' }}
                </span>
            </div>

            <div class="mb-8 rounded-xl p-4" style="background-color: var(--neo-field); border: 1px solid var(--event-line);">
                <strong class="mb-1 block text-sm" style="color: var(--event-ink);">Profit/loss is not overstated.</strong>
                <p class="text-sm leading-relaxed" style="color: var(--event-muted);">
                    Confirmed payments and saved quotes are reported as revenue. Saved expenses for non-existing items are reported as costs; blank quote or expense fields remain excluded.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div>
                    <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">Reported Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-900">₱{{ number_format($revenueCents / 100, 2) }}</h3>
                </div>
                <div>
                    <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">Known Costs Recorded</p>
                    <h3 class="text-2xl font-bold text-gray-900">₱{{ number_format($newItemCostCents / 100, 2) }}</h3>
                </div>
                <div>
                    <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">Reported Profit/Loss</p>
                    <h3 class="text-2xl font-bold text-gray-900">₱{{ number_format(($revenueCents - $newItemCostCents) / 100, 2) }}</h3>
                </div>
            </div>
        </section>

        <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <section class="flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Transactions</p>
                        <h2 class="text-lg font-bold text-gray-900">Payment Activity</h2>
                    </div>
                    <a href="{{ route('admin.payments.index') }}" class="text-sm font-semibold text-pink-600 hover:text-pink-700">Open payments</a>
                </div>

                @if ($transactions->count())
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[620px] border-collapse text-left">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Date</th>
                                    <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Customer</th>
                                    <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Transaction</th>
                                    <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Type</th>
                                    <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Amount</th>
                                    <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--event-line)]">
                                @foreach ($transactions->take(8) as $transaction)
                                    <tr class="hover:bg-black/5">
                                        <td class="px-3 py-3 text-sm" style="color: var(--event-ink);">{{ $transaction['date']?->format('M d, Y') ?? 'Undated' }}</td>
                                        <td class="px-3 py-3 text-sm" style="color: var(--event-ink);">{{ $transaction['customer'] }}</td>
                                        <td class="px-3 py-3 text-sm" style="color: var(--event-ink);">{{ $transaction['description'] }}</td>
                                        <td class="px-3 py-3 text-sm" style="color: var(--event-ink);">{{ $transaction['type'] }}</td>
                                        <td class="px-3 py-3 text-sm font-medium" style="color: var(--event-ink);">
                                            {{ $transaction['type'] === 'Cost' ? '-' : '+' }}₱{{ number_format($transaction['amount_cents'] / 100, 2) }}
                                        </td>
                                        <td class="px-3 py-3 text-sm" style="color: var(--event-ink);">{{ $transaction['status'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-1 items-center justify-center rounded-xl border border-dashed border-gray-200 bg-gray-50/50 p-8">
                        <p class="text-center text-sm text-gray-500">No transactions were recorded in this period.</p>
                    </div>
                @endif
            </section>

            <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Inventory Demand</p>
                        <h2 class="text-lg font-bold text-gray-900">Existing Items</h2>
                    </div>
                    <span class="rounded-full border border-green-100 bg-green-50 px-3 py-1 text-xs font-bold text-green-700">{{ $reservationSummary['units'] }} units</span>
                </div>

                @if ($existingItems->count())
                    <div class="divide-y divide-[var(--event-line)]">
                        @foreach ($existingItems->take(8) as $item)
                            <div class="flex items-center justify-between gap-4 py-3">
                                <div>
                                    <strong class="block text-sm" style="color: var(--event-ink);">{{ $item['name'] }}</strong>
                                    <span class="text-xs" style="color: var(--event-muted);">{{ $item['source'] }} / {{ $item['quantity'] }} units</span>
                                </div>
                                <strong class="whitespace-nowrap text-sm" style="color: var(--event-ink);">₱{{ number_format($item['value_cents'] / 100, 2) }}</strong>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50/50 p-8">
                        <p class="text-center text-sm text-gray-500">No existing item activity was found.</p>
                    </div>
                @endif
            </section>
        </div>

        <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-start justify-between gap-4">
                <div>
                    <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Demand Without Inventory</p>
                    <h2 class="text-lg font-bold text-gray-900">Non-existing Item Requests</h2>
                </div>
                <span class="rounded-full border border-yellow-100 bg-yellow-50 px-3 py-1 text-xs font-bold text-yellow-700">
                    {{ $quotedRevenueCents || $newItemCostCents ? 'Some items priced' : 'Not priced' }}
                </span>
            </div>
            <p class="mb-6 max-w-2xl text-sm text-gray-500">Saved quotes are treated as revenue and saved expenses as cost. Values are per unit and multiplied by the requested quantity.</p>

            @if ($newItemRequests->count())
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($newItemRequests as $item)
                        <article class="rounded-xl border border-[var(--event-line)] p-4" style="background-color: var(--neo-field);">
                            <strong class="block text-sm" style="color: var(--event-ink);">{{ $item['name'] }}</strong>
                            <span class="mt-1 block text-xs" style="color: var(--event-muted);">{{ $item['quantity'] }} units across {{ $item['requests'] }} request(s)</span>
                            <span class="mt-3 block text-sm" style="color: var(--event-ink);">Quote: ₱{{ number_format($item['quoted_cents'] / 100, 2) }}</span>
                            <span class="block text-sm" style="color: var(--event-ink);">Expense: ₱{{ number_format($item['expense_cents'] / 100, 2) }}</span>
                            <small class="mt-2 block" style="color: var(--event-muted);">{{ $item['status'] }}</small>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50/50 p-8">
                    <p class="text-center text-sm text-gray-500">No non-existing item requests were created in this period.</p>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
