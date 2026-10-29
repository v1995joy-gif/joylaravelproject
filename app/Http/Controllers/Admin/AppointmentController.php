<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BoutiqueClosure;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with([
            'customer',
            'reservation.rentalItem'
        ])
        ->orderBy('appointment_date')
        ->orderBy('appointment_time')
        ->get();

        $closures = BoutiqueClosure::orderBy('closure_date')->get();
        $calendarEvents = $appointments->map(fn (Appointment $appointment) => [
            'id' => 'appointment-' . $appointment->id,
            'title' => ($appointment->customer?->name ?? 'Customer')
                . ' - '
                . ($appointment->reservation?->rentalItem?->name ?? 'Item'),
            'start' => $appointment->appointment_date->format('Y-m-d')
                . 'T'
                . substr($appointment->appointment_time, 0, 8),
            'extendedProps' => [
                'type' => 'appointment',
                'status' => $appointment->status,
            ],
        ])->concat($closures->map(fn (BoutiqueClosure $closure) => [
            'id' => 'closure-' . $closure->id,
            'title' => $closure->reason ?: 'Boutique Closed',
            'start' => $closure->closure_date->format('Y-m-d'),
            'allDay' => true,
            'display' => 'background',
            'backgroundColor' => 'rgba(239, 68, 68, 0.28)',
            'extendedProps' => [
                'type' => 'closure',
                'closureId' => $closure->id,
                'reason' => $closure->reason,
            ],
        ]))->values();

        return view('admin.appointments.index', compact('appointments', 'closures', 'calendarEvents'));
    }

    public function storeClosure(Request $request)
    {
        $validated = $request->validate([
            'closure_date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        if (BoutiqueClosure::whereDate('closure_date', $validated['closure_date'])->exists()) {
            return back()
                ->withErrors(['closure_date' => 'The boutique is already marked closed on this date.'])
                ->withInput();
        }

        BoutiqueClosure::create($validated);

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Boutique closure added successfully.');
    }

    public function updateClosure(Request $request, $id)
    {
        $validated = $request->validate([
            'closure_date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        $closure = BoutiqueClosure::findOrFail($id);
        $duplicateDate = BoutiqueClosure::whereDate('closure_date', $validated['closure_date'])
            ->where('id', '!=', $closure->id)
            ->exists();

        if ($duplicateDate) {
            return back()
                ->withErrors(['closure_date' => 'The boutique is already marked closed on this date.'])
                ->withInput();
        }

        $closure->update($validated);

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Boutique closure updated successfully.');
    }

    public function reopenClosure($id)
    {
        $closure = BoutiqueClosure::findOrFail($id);
        $closure->delete();

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Boutique reopened for that date.');
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Confirmed,Declined,Completed,Cancelled',
        ]);

        $appointment = Appointment::findOrFail($id);
        $allowedTransitions = [
            'Pending' => ['Confirmed', 'Declined'],
            'Confirmed' => ['Completed', 'Cancelled'],
            'Completed' => [],
            'Cancelled' => [],
            'Declined' => [],
        ];

        if (
            ! array_key_exists($appointment->status, $allowedTransitions)
            || ! in_array($validated['status'], $allowedTransitions[$appointment->status], true)
        ) {
            return back()->withErrors([
                'status' => "Cannot move a {$appointment->status} appointment to {$validated['status']}.",
            ]);
        }

        $appointment->update(['status' => $validated['status']]);

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', match ($validated['status']) {
                'Confirmed' => 'Appointment confirmed.',
                'Declined' => 'Appointment request declined.',
                'Completed' => 'Appointment marked as completed.',
                'Cancelled' => 'Appointment cancelled.',
                default => 'Appointment status updated successfully.',
            });
    }

    public function updateSchedule(Request $request, $id)
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
        ]);

        $appointment = Appointment::findOrFail($id);

        if (! in_array($appointment->status, ['Pending', 'Confirmed'], true)) {
            return back()->withErrors([
                'schedule' => 'Only pending or confirmed appointments can be rescheduled.',
            ]);
        }

        if (BoutiqueClosure::whereDate('closure_date', $validated['appointment_date'])->exists()) {
            return back()->withErrors([
                'appointment_date' => 'The boutique is closed on the selected date.',
            ]);
        }

        $appointment->update($validated);

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment schedule updated successfully.');
    }
}