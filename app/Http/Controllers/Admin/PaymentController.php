<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with([
            'reservation.customer',
            'reservation.rentalItem',
            'reservation.payments',
            'reservation.customizationRequest.package',
        ])
        ->latest()
        ->get();

        $payments->each(function (Payment $payment): void {
            $reservation = $payment->reservation;
            $totalDueCents = (int) round(($reservation?->totalDueAmount() ?? 0) * 100);
            $paidCents = $reservation->payments
                ->where('status', 'Confirmed')
                ->sum(fn (Payment $reservationPayment): int => (int) round(((float) $reservationPayment->amount) * 100));

            $payment->setAttribute('total_due_cents', $totalDueCents);
            $payment->setAttribute('paid_cents', $paidCents);
            $payment->setAttribute('balance_cents', max(0, $totalDueCents - $paidCents));
        });

        return view('admin.payments.index', compact('payments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Rejected',
        ]);

        $payment = Payment::with(['reservation.customer', 'reservation.rentalItem'])
            ->findOrFail($id);

        $previousStatus = $payment->status;
        $payment->status = $request->status;
        $payment->save();

        $notificationSent = false;

        if ($previousStatus !== 'Confirmed' && $payment->status === 'Confirmed') {
            if ($payment->reservation?->customer?->email) {
                $payment->load('reservation.payments');

                try {
                    Mail::to($payment->reservation->customer->email)
                        ->send(new PaymentReceiptMail($payment));

                    $payment->forceFill([
                        'payment_notification_sent_at' => now(),
                    ])->save();
                    $notificationSent = true;
                } catch (\Throwable $e) {
                    report($e);
                    $message = 'Payment confirmed, but the receipt email could not be sent.';
                }
            }
        }

        $message ??= $notificationSent
            ? 'Payment confirmed and receipt notification sent successfully.'
            : 'Payment status updated successfully!';

        return redirect()
            ->route('admin.payments.index')
            ->with('success', $message);
    }

}