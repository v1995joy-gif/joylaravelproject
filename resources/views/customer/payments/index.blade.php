<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('My Payments') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status" aria-live="polite">
                {{ session('success') }}
            </div>
        @endif

        @if ($reservations->isNotEmpty())
            @foreach ($reservations as $reservation)
                @php
                    $totalDue = $reservation->totalDueAmount();
                    $paidAmount = (float) $reservation->payments
                        ->where('status', 'Confirmed')
                        ->sum(fn ($payment) => (float) $payment->amount);
                    $balance = max(0, round($totalDue - $paidAmount, 2));
                    $hasUnrejectedPayments = $reservation->payments
                        ->where('status', '!=', 'Rejected')
                        ->isNotEmpty();
                @endphp

                <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm" aria-labelledby="reservation-{{ $reservation->id }}-heading">
                    <div class="flex flex-col justify-between gap-4 border-b border-[var(--event-line)] px-6 py-5 sm:flex-row sm:items-center" style="background-color: var(--neo-field);">
                        <div>
                            <h2 id="reservation-{{ $reservation->id }}-heading" class="text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                                {{ $reservation->rentalItem?->name ?? 'Item removed' }}
                            </h2>
                            <div class="mt-1 flex flex-wrap gap-x-5 gap-y-1 text-sm text-gray-500">
                                <span>Event: {{ $reservation->event_date?->format('M d, Y') ?? '—' }}</span>
                                <span>Reservation #{{ $reservation->id }}</span>
                            </div>
                        </div>
                        <span class="inline-flex w-fit items-center rounded-full border border-green-200 bg-green-100 px-2.5 py-1 text-xs font-bold text-green-700">
                            {{ $reservation->status }}
                        </span>
                    </div>

                    <div class="space-y-6 p-6">
                        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                                <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Item Code</span>
                                <strong class="text-sm text-gray-900">{{ $reservation->rentalItem?->item_code ?? '—' }}</strong>
                            </div>
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                                <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Category</span>
                                <strong class="text-sm text-gray-900">{{ $reservation->rentalItem?->category?->name ?? 'Uncategorized' }}</strong>
                            </div>
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                                <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Unit Price</span>
                                <strong class="text-sm text-gray-900">₱{{ number_format($reservation->rentalItem?->rental_price ?? 0, 2) }}</strong>
                            </div>
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                                <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-500">Quantity</span>
                                <strong class="text-sm text-gray-900">{{ $reservation->quantity }}</strong>
                            </div>
                        </div>

                        <div class="rounded-xl border border-[var(--event-line)] p-4" style="background-color: rgba(232, 201, 138, 0.08);">
                            @if ($reservation->customizationRequest)
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $reservation->customizationRequest->package?->name ?? 'Package' }} total:
                                    ₱{{ number_format($reservation->customizationRequest->package_total ?? $totalDue, 2) }}
                                </p>
                                <p class="mt-1 text-sm text-gray-600">
                                    Amount due for this item: <strong class="text-gray-800">₱{{ number_format($totalDue, 2) }}</strong>
                                </p>
                            @else
                                <p class="text-sm font-semibold text-gray-800">
                                    Total rental amount: ₱{{ number_format($totalDue, 2) }}
                                </p>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-x-6 gap-y-1 border-t border-[var(--event-line)] pt-3 text-sm">
                                <span class="text-gray-600">Paid: <strong class="text-gray-900">₱{{ number_format($paidAmount, 2) }}</strong></span>
                                <span class="text-gray-600">Balance: <strong class="text-gray-900">₱{{ number_format($balance, 2) }}</strong></span>
                            </div>
                            @if ($reservation->rentalItem?->description)
                                <p class="mt-3 text-sm text-gray-500">{{ $reservation->rentalItem->description }}</p>
                            @endif
                        </div>

                        @if ($totalDue > 0 && ! $hasUnrejectedPayments && $balance > 0)
                            <form class="grid grid-cols-1 gap-4 md:grid-cols-2" action="{{ route('customer.payments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                                @if ($errors->any() && old('reservation_id') == $reservation->id)
                                    <div class="col-span-full rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif

                                <div>
                                    <label for="amount-{{ $reservation->id }}" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-600">Payment Amount</label>
                                    <input
                                        id="amount-{{ $reservation->id }}"
                                        type="number"
                                        name="amount"
                                        step="0.01"
                                        min="0.01"
                                        max="{{ number_format($balance, 2, '.', '') }}"
                                        value="{{ old('reservation_id') == $reservation->id ? old('amount') : number_format($balance, 2, '.', '') }}"
                                        required
                                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-100"
                                    >
                                </div>

                                <div>
                                    <label for="method-{{ $reservation->id }}" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-600">Payment Method</label>
                                    <select
                                        id="method-{{ $reservation->id }}"
                                        name="payment_method"
                                        required
                                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-100"
                                    >
                                        <option value="">Select method</option>
                                        <option value="Cash" @selected(old('reservation_id') == $reservation->id && old('payment_method') === 'Cash')>Cash</option>
                                        <option value="GCash" @selected(old('reservation_id') == $reservation->id && old('payment_method') === 'GCash')>GCash</option>
                                        <option value="Bank Transfer" @selected(old('reservation_id') == $reservation->id && old('payment_method') === 'Bank Transfer')>Bank Transfer</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="reference-{{ $reservation->id }}" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-600">Reference Number</label>
                                    <input
                                        id="reference-{{ $reservation->id }}"
                                        type="text"
                                        name="reference_number"
                                        value="{{ old('reservation_id') == $reservation->id ? old('reference_number') : '' }}"
                                        placeholder="Optional"
                                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-100"
                                    >
                                </div>

                                <div>
                                    <label for="notes-{{ $reservation->id }}" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-600">Notes</label>
                                    <textarea
                                        id="notes-{{ $reservation->id }}"
                                        name="notes"
                                        rows="2"
                                        placeholder="Optional payment notes"
                                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-100"
                                    >{{ old('reservation_id') == $reservation->id ? old('notes') : '' }}</textarea>
                                </div>

                                <div class="md:col-span-2">
                                    <button
                                        type="submit"
                                        class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-opacity hover:opacity-90"
                                        style="background: linear-gradient(100deg, var(--event-gold), var(--event-pink));"
                                    >
                                        Submit Payment
                                    </button>
                                </div>
                            </form>
                        @elseif ($totalDue <= 0 && $reservation->customizationRequest)
                            <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                                No separate payment is due for this item. Its amount is included in the package total.
                            </div>
                        @elseif ($balance <= 0)
                            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                                This reservation is fully paid.
                            </div>
                        @else
                            <div class="rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                                A payment for this reservation has been submitted and is being reviewed.
                            </div>
                        @endif

                        @if ($reservation->payments->isNotEmpty())
                            <div class="border-t border-[var(--event-line)] pt-5">
                                <h3 class="mb-3 text-sm font-bold uppercase tracking-wider text-gray-700">Payment Records</h3>
                                <div class="overflow-x-auto rounded-xl border border-gray-100">
                                    <table class="w-full min-w-[760px] border-collapse text-left">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Amount</th>
                                                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Method</th>
                                                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status</th>
                                                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Reference</th>
                                                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Date</th>
                                                <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-[var(--event-line)]">
                                            @foreach ($reservation->payments as $payment)
                                                @php
                                                    $paymentStatusColor = match ($payment->status) {
                                                        'Confirmed' => 'bg-green-100 text-green-700 border-green-200',
                                                        'Rejected' => 'bg-red-100 text-red-700 border-red-200',
                                                        'Pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                        default => 'bg-gray-100 text-gray-700 border-gray-200',
                                                    };
                                                @endphp
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-semibold text-gray-800">₱{{ number_format($payment->amount, 2) }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $payment->payment_method }}</td>
                                                    <td class="px-4 py-3">
                                                        <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-bold {{ $paymentStatusColor }}">
                                                            {{ $payment->status }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $payment->reference_number ?? 'None' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $payment->payment_date?->format('M d, Y') ?? 'None' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $payment->notes ?? 'None' }}</td>
                                                </tr>
                                                @if ($payment->status === 'Confirmed')
                                                    <tr>
                                                        <td colspan="6" class="bg-gray-50/70 p-4">
                                                            <div class="rounded-xl border border-dashed border-pink-200 bg-white p-4">
                                                                <h4 class="mb-2 text-sm font-bold text-gray-900">Payment Receipt</h4>
                                                                <p class="text-sm text-gray-600"><strong>Receipt:</strong> PAY-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</p>
                                                                <p class="mt-1 text-sm text-gray-600"><strong>Customer:</strong> {{ auth()->user()->name }}</p>
                                                                <p class="mt-1 text-sm text-gray-600"><strong>Amount:</strong> ₱{{ number_format($payment->amount, 2) }}</p>
                                                                <p class="mt-1 text-sm text-gray-600"><strong>Payment date:</strong> {{ $payment->payment_date?->format('M d, Y') ?? 'None' }}</p>
                                                                <button
                                                                    class="mt-3 inline-flex items-center rounded-lg bg-gray-800 px-3 py-2 text-sm font-semibold text-white transition-colors hover:bg-gray-700"
                                                                    type="button"
                                                                    onclick="window.print()"
                                                                >
                                                                    Print Receipt
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
            @endforeach
        @else
            <div class="flex flex-col items-center justify-center rounded-2xl border border-gray-100 bg-white p-12 text-center shadow-sm">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                    <x-lucide-wallet width="32" height="32" aria-hidden="true" />
                </div>
                <h2 class="mb-1 text-lg font-bold text-gray-900">No payments available</h2>
                <p class="text-sm text-gray-500">You do not have any confirmed reservations available for payment.</p>
            </div>
        @endif
    </div>
</x-app-layout>
