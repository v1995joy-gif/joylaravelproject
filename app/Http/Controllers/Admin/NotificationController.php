<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;

class NotificationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with([
            'customer',
            'rentalItem',
        ])
        ->latest()
        ->paginate(15, ['*'], 'res_page');

        $payments = Payment::with([
            'reservation.customer',
            'reservation.rentalItem',
        ])
        ->latest()
        ->paginate(15, ['*'], 'pay_page');

        $returnSchedules = Reservation::with([
            'customer',
            'rentalItem',
        ])
        ->whereNotNull('return_date')
        ->orderBy('return_date')
        ->get();

        return view('admin.notifications.index', compact(
            'reservations',
            'payments',
            'returnSchedules'
        ));
    }
}