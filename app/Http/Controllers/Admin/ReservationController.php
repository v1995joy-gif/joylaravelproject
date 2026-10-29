<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReservationReminderMail;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Mail\ReturnScheduleReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with([
            'customer',
            'rentalItem',
            'customizationRequest.package',
        ])
            ->latest()
            ->get();

        return view('admin.reservations.index', compact('reservations'));
    }

    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Cancelled',
        ]);

        // Only legal transitions — can't un-Confirm a Cancelled row past
        // stock checks, or move a Returned reservation backwards.
        $allowedTransitions = [
            'Pending' => ['Confirmed', 'Cancelled'],
            'Confirmed' => ['Cancelled'],
            'Cancelled' => [],
            'Returned' => [],
        ];

        $previousStatus = $reservation->status;

        if (
            ! array_key_exists($previousStatus, $allowedTransitions)
            || ! in_array($request->status, $allowedTransitions[$previousStatus], true)
        ) {
            return back()->withErrors([
                'status' => "Cannot move a {$previousStatus} reservation to {$request->status}.",
            ]);
        }

        $reservation->status = $request->status;
        $reservation->save();

        $notificationSent = false;

        if ($previousStatus !== 'Confirmed' && $reservation->status === 'Confirmed') {
            $reservation->load(['customer', 'rentalItem']);

            if ($reservation->customer?->email) {
                try {
                    Mail::to($reservation->customer->email)
                        ->send(new ReservationReminderMail($reservation, 'Reservation Confirmed'));
                    $reservation->forceFill([
                        'reservation_notification_sent_at' => now(),
                    ])->save();
                    $notificationSent = true;
                } catch (\Throwable $e) {
                    report($e);
                    $message = 'Reservation confirmed, but the notification email could not be sent.';
                }
            }
        }

        $message ??= $notificationSent
            ? 'Reservation confirmed and notification sent successfully.'
            : 'Reservation status updated successfully!';

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', $message);
    }

    public function sendReminder($id)
    {
        $reservation = Reservation::with([
            'customer',
            'rentalItem',
        ])->findOrFail($id);

        if (!$reservation->customer || !$reservation->customer->email) {
            return redirect()
                ->route('admin.reservations.index')
                ->with('error', 'This customer does not have a valid email address.');
        }

        try {
            Mail::to($reservation->customer->email)
                ->send(new ReservationReminderMail($reservation));
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('admin.reservations.index')
                ->with('error', 'The reminder email could not be sent.');
        }

        $reservation->forceFill([
            'reservation_notification_sent_at' => now(),
        ])->save();

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Reservation reminder sent successfully!');
    }

    public function sendReturnReminder($id)
    {
        $reservation = Reservation::with([
            'customer',
            'rentalItem',
        ])->findOrFail($id);

        if (!$reservation->customer || !$reservation->customer->email) {
            return redirect()
                ->route('admin.reservations.index')
                ->with('error', 'This customer does not have a valid email address.');
        }

        if (!$reservation->return_date) {
            return redirect()
                ->route('admin.reservations.index')
                ->with('error', 'This reservation does not have a return date.');
        }

        try {
            Mail::to($reservation->customer->email)
                ->send(new ReturnScheduleReminderMail($reservation));
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('admin.reservations.index')
                ->with('error', 'The return schedule reminder could not be sent.');
        }

        $reservation->forceFill([
            'return_notification_sent_at' => now(),
        ])->save();

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Return schedule reminder sent successfully!');
    }

   public function returnSchedule()
{
    $reservations = Reservation::with([
        'customer',
        'rentalItem',
    ])
    ->whereNotNull('return_date')
    ->orderBy('return_date')
    ->get();

    return view(
        'admin.return-schedule.index',
        compact('reservations')
    );
}

    public function markReturned($id)
    {
        DB::transaction(function () use ($id) {
            $reservation = Reservation::lockForUpdate()->findOrFail($id);

            if ($reservation->status === 'Returned' || $reservation->returned_at) {
                abort(422, 'This reservation has already been marked as returned.');
            }

            if ($reservation->status !== 'Confirmed') {
                abort(422, 'Only confirmed reservations can be marked as returned.');
            }

            if (!$reservation->return_date) {
                abort(422, 'This reservation does not have a scheduled return date.');
            }

            $reservation->forceFill([
                'status' => 'Returned',
                'returned_at' => now(),
            ])->save();
        });

        return redirect()
            ->route('admin.return-schedule.index')
            ->with('success', 'Rental return recorded successfully. The item quantity is available again.');
    }
}
