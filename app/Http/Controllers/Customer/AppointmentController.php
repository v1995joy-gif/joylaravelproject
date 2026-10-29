<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BoutiqueClosure;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with([
            'reservation.rentalItem',
        ])
        ->where('customer_id', auth()->id())
        ->orderBy('appointment_date')
        ->orderBy('appointment_time')
        ->get();

        $reservationsAwaitingAppointment = Reservation::with('rentalItem')
            ->where('customer_id', auth()->id())
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->whereDoesntHave('appointment')
            ->latest()
            ->get();

        return view(
            'customer.appointments.index',
            compact('appointments', 'reservationsAwaitingAppointment')
        );
    }

    public function create($reservationId)
    {
        $reservation = Reservation::with('rentalItem')
            ->where('customer_id', auth()->id())
            ->findOrFail($reservationId);

        return view(
            'customer.appointments.create',
            compact('reservation')
        );
    }

    public function store(Request $request, $reservationId)
    {
        $reservation = Reservation::where('customer_id', auth()->id())
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->whereDoesntHave('appointment')
            ->findOrFail($reservationId);

        $validated = $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        if (BoutiqueClosure::whereDate('closure_date', $validated['appointment_date'])->exists()) {
            return back()
                ->withErrors(['appointment_date' => 'The boutique is closed on the selected date.'])
                ->withInput();
        }

        Appointment::create([
            'reservation_id' => $reservation->id,
            'customer_id' => auth()->id(),
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'status' => 'Pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('customer.appointments.index')
            ->with('success', 'Appointment request submitted successfully!');
    }

    public function destroy($id)
    {
        $appointment = Appointment::where('customer_id', auth()->id())
            ->findOrFail($id);

        $appointment->delete();

        return redirect()
            ->route('customer.appointments.index')
            ->with('success', 'Appointment record deleted successfully.');
    }
}