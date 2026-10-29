<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Payments') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-yellow-600">Finance</p>
                <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Payment Management</h1>
                <p class="mt-1 text-sm text-gray-500">Review customer receipts, payment status, and outstanding balances.</p>
            </div>
            <span class="rounded-full border border-yellow-100 bg-yellow-50 px-4 py-2 text-sm font-semibold text-yellow-700">
                {{ $payments->count() }} records
            </span>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-[var(--event-line)] px-6 py-5" style="background-color: var(--neo-field);">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-700">Payment Records</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1300px] border-collapse text-left">
                    <thead>
                        <tr class="border-b border-[var(--event-line)]" style="background-color: rgba(232, 201, 138, 0.12);">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Rental Item</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Event Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Total Due</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Paid</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Balance</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Payment Method</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Status / Action</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Reference Number</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Payment Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="background-color: rgba(232, 201, 138, 0.12) !important; color: var(--event-muted) !important; border-bottom: 1px solid var(--event-line) !important; box-shadow: none !important;">Notes</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[var(--event-line)]">
                        @forelse ($payments as $payment)
                            <tr class="transition-colors hover:bg-black/5">
                                <td class="px-6 py-4 text-sm font-medium" style="color: var(--event-ink);">
                                    {{ $payment->id }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold" style="color: var(--event-ink);">
                                    {{ $payment->reservation?->customer?->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $payment->reservation?->rentalItem?->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $payment->reservation?->event_date?->format('M d, Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium" style="color: var(--event-ink);">
                                    ₱{{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    ₱{{ number_format($payment->total_due_cents / 100, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-green-600">
                                    ₱{{ number_format($payment->paid_cents / 100, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-red-600">
                                    ₱{{ number_format($payment->balance_cents / 100, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $payment->payment_method }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form
                                        action="{{ route('admin.payments.update-status', $payment->id) }}"
                                        method="POST"
                                        class="inline-block w-full max-w-[180px]"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <label
                                            for="payment-status-{{ $payment->id }}"
                                            class="mb-1 block text-left text-[10px] font-bold uppercase tracking-wider text-gray-400"
                                        >
                                            Update payment
                                        </label>
                                        <select
                                            id="payment-status-{{ $payment->id }}"
                                            name="status"
                                            onchange="this.form.submit()"
                                            aria-label="Update payment status"
                                            class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white py-1.5 pl-3 pr-8 text-sm shadow-sm transition-colors hover:border-gray-400 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-200"
                                        >
                                            <option value="Pending" @selected($payment->status === 'Pending')>Awaiting review</option>
                                            <option value="Confirmed" @selected($payment->status === 'Confirmed')>Payment confirmed</option>
                                            <option value="Rejected" @selected($payment->status === 'Rejected')>Payment declined</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $payment->reference_number ?? 'None' }}
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-ink);">
                                    {{ $payment->payment_date?->format('M d, Y') ?? 'None' }}
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: var(--event-muted);">
                                    {{ $payment->notes ?? 'None' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="p-12 text-center">
                                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400">
                                        <x-lucide-wallet width="32" height="32" aria-hidden="true" />
                                    </div>
                                    <h3 class="mb-1 text-lg font-bold text-gray-900">No payments found</h3>
                                    <p class="text-sm text-gray-500">When customers submit payments, they will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
