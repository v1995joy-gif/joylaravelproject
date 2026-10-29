<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomizationRequest;
use App\Models\Payment;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'period' => ['nullable', 'in:weekly,monthly'],
            'date' => ['nullable', 'date'],
        ]);

        $period = $validated['period'] ?? 'monthly';
        $anchor = isset($validated['date'])
            ? Carbon::parse($validated['date'])
            : Carbon::today();

        [$start, $end] = $period === 'weekly'
            ? [$anchor->copy()->startOfWeek(), $anchor->copy()->endOfWeek()]
            : [$anchor->copy()->startOfMonth(), $anchor->copy()->endOfMonth()];

        $reservations = Reservation::with(['customer', 'rentalItem'])
            ->whereBetween('reservation_date', [$start->toDateString(), $end->toDateString()])
            ->latest('reservation_date')
            ->get();

        $payments = Payment::with(['reservation.customer', 'reservation.rentalItem'])
            ->whereNotNull('payment_date')
            ->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
            ->latest('payment_date')
            ->get();

        $customizationRequests = CustomizationRequest::with(['customer', 'items.rentalItem', 'items.packageItem', 'newItems'])
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->latest()
            ->get();

        $confirmedPayments = $payments->where('status', 'Confirmed');
        $reservationSummary = [
            'total' => $reservations->count(),
            'confirmed' => $reservations->where('status', 'Confirmed')->count(),
            'pending' => $reservations->where('status', 'Pending')->count(),
            'cancelled' => $reservations->where('status', 'Cancelled')->count(),
            'units' => (int) $reservations
                ->whereIn('status', ['Pending', 'Confirmed'])
                ->sum(fn (Reservation $reservation): int => (int) ($reservation->quantity ?? 1)),
        ];

        $paymentSummary = [
            'total' => $payments->count(),
            'confirmed' => $confirmedPayments->count(),
            'pending' => $payments->where('status', 'Pending')->count(),
            'rejected' => $payments->where('status', 'Rejected')->count(),
        ];

        $existingItems = [];
        foreach ($reservations->where('status', 'Confirmed') as $reservation) {
            $item = $reservation->rentalItem;
            if (!$item) {
                continue;
            }

            $key = 'reservation-'.$item->id;
            $existingItems[$key] ??= [
                'name' => $item->name,
                'source' => 'Confirmed reservation',
                'quantity' => 0,
                'value_cents' => 0,
            ];
            $quantity = (int) ($reservation->quantity ?? 1);
            $existingItems[$key]['quantity'] += $quantity;
            $existingItems[$key]['value_cents'] += $quantity * $this->toCents($item->rental_price);
        }

        $newItemRequests = [];
        $newItemUnits = 0;
        $quotedRevenueCents = 0;
        $newItemCostCents = 0;
        $transactions = $payments->map(function (Payment $payment): array {
            return [
                'date' => $payment->payment_date,
                'customer' => $payment->reservation?->customer?->name ?? 'Unknown customer',
                'description' => $payment->reservation?->rentalItem?->name ?? 'Rental payment',
                'type' => 'Payment',
                'amount_cents' => $this->toCents($payment->amount),
                'status' => $payment->status,
            ];
        });

        foreach ($customizationRequests->where('status', '!=', 'Cancelled') as $customizationRequest) {
            foreach ($customizationRequest->newItems as $newItem) {
                $quantity = (int) ($newItem->quantity ?? 1);
                $newItemUnits += $quantity;
                $key = mb_strtolower(trim($newItem->item_name));
                $newItemRequests[$key] ??= [
                    'name' => $newItem->item_name,
                    'quantity' => 0,
                    'requests' => 0,
                    'status' => $newItem->status,
                    'quoted_cents' => 0,
                    'expense_cents' => 0,
                ];
                $newItemRequests[$key]['quantity'] += $quantity;
                $newItemRequests[$key]['requests']++;

                $quotedCents = $newItem->quoted_price !== null
                    ? $this->toCents($newItem->quoted_price) * $quantity
                    : null;
                $expenseCents = $newItem->expense_price !== null
                    ? $this->toCents($newItem->expense_price) * $quantity
                    : null;

                if ($quotedCents !== null) {
                    $quotedRevenueCents += $quotedCents;
                    $newItemRequests[$key]['quoted_cents'] += $quotedCents;
                    $transactions->push([
                        'date' => $newItem->created_at,
                        'customer' => $customizationRequest->customer?->name ?? 'Unknown customer',
                        'description' => $newItem->item_name.' quote',
                        'type' => 'Revenue',
                        'amount_cents' => $quotedCents,
                        'status' => 'Quoted',
                    ]);
                }

                if ($expenseCents !== null) {
                    $newItemCostCents += $expenseCents;
                    $newItemRequests[$key]['expense_cents'] += $expenseCents;
                    $transactions->push([
                        'date' => $newItem->created_at,
                        'customer' => $customizationRequest->customer?->name ?? 'Unknown customer',
                        'description' => $newItem->item_name.' expense',
                        'type' => 'Cost',
                        'amount_cents' => $expenseCents,
                        'status' => 'Recorded',
                    ]);
                }
            }

            foreach ($customizationRequest->items as $requestedItem) {
                $item = $requestedItem->rentalItem;
                if (!$item) {
                    continue;
                }

                $key = 'custom-'.$item->id;
                $existingItems[$key] ??= [
                    'name' => $item->name,
                    'source' => 'Customization request',
                    'quantity' => 0,
                    'value_cents' => 0,
                ];
                $quantity = (int) ($requestedItem->quantity ?? 1);
                $existingItems[$key]['quantity'] += $quantity;
                $existingItems[$key]['value_cents'] += $quantity * $this->toCents($item->rental_price);
            }
        }

        $confirmedPaymentRevenueCents = $confirmedPayments->sum(
            fn (Payment $payment): int => $this->toCents($payment->amount)
        );
        $revenueCents = $confirmedPaymentRevenueCents + $quotedRevenueCents;

        return view('admin.reports.index', [
            'period' => $period,
            'selectedDate' => $anchor->toDateString(),
            'start' => $start,
            'end' => $end,
            'reservations' => $reservations,
            'payments' => $payments,
            'customizationRequests' => $customizationRequests,
            'reservationSummary' => $reservationSummary,
            'paymentSummary' => $paymentSummary,
            'revenueCents' => $revenueCents,
            'confirmedPaymentRevenueCents' => $confirmedPaymentRevenueCents,
            'quotedRevenueCents' => $quotedRevenueCents,
            'newItemCostCents' => $newItemCostCents,
            'transactions' => $transactions->sortByDesc('date')->values(),
            'existingItems' => collect($existingItems)->sortByDesc('value_cents')->values(),
            'newItemRequests' => collect($newItemRequests)->sortByDesc('quantity')->values(),
            'newItemUnits' => $newItemUnits,
        ]);
    }

    private function toCents(mixed $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }
}
