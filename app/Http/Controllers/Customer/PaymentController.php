<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{   

    public function index()
{
    $reservations = Reservation::with([
        'rentalItem.category',
        'payments',
        'customizationRequest.package',
    ])
        ->where('customer_id', auth()->id())
        ->where('status', 'Confirmed')
        ->latest()
        ->get();

    return view('customer.payments.index', compact('reservations'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $reservation = Reservation::with('rentalItem', 'payments')
            ->where('customer_id', auth()->id())
            ->where('status', 'Confirmed')
            ->findOrFail($validated['reservation_id']);

        // Cap at the outstanding balance — no overpayment or paying dead rows.
        $totalDue = $reservation->totalDueAmount();
        $paid = $reservation->payments
            ->where('status', 'Confirmed')
            ->sum(fn ($p) => (float) $p->amount);
        $balance = round($totalDue - $paid, 2);

        if ($balance <= 0) {
            return back()->withInput()->withErrors([
                'amount' => 'This reservation is already fully paid.',
            ]);
        }

        if ((float) $validated['amount'] > $balance) {
            return back()->withInput()->withErrors([
                'amount' => 'Amount exceeds the remaining balance of ₱'.number_format($balance, 2).'.',
            ]);
        }

        Payment::create([
            'reservation_id' => $reservation->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'Pending',
            'reference_number' => $validated['reference_number'] ?? null,
            'payment_date' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('customer.payments.index')
            ->with('success', 'Payment submitted successfully!');
    }
}