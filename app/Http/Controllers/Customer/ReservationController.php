<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\RentalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    
    public function index()
{
    $reservations = Reservation::with([
    'rentalItem',
    'appointment',
    'customizationRequest.package',
])
        ->where('customer_id', auth()->id())
        ->latest()
        ->get();

    $packageReservations = $reservations
        ->filter(fn (Reservation $reservation) => $reservation->customizationRequest?->package_id !== null)
        ->values();
    $itemReservations = $reservations
        ->reject(fn (Reservation $reservation) => $reservation->customizationRequest?->package_id !== null)
        ->values();

    return view('customer.reservations.list', compact('itemReservations', 'packageReservations'));
}
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rental_item_id' => 'required|exists:rental_items,id',
            'quantity' => 'required|integer|min:1',
            'reservation_date' => 'required|date|after_or_equal:today',
            'event_date' => 'required|date|after_or_equal:reservation_date',
            'return_date' => 'required|date|after_or_equal:event_date',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            // Lock the item row so the check-then-insert can't oversell.
            $item = RentalItem::where('status', 'Available')
                ->lockForUpdate()
                ->findOrFail($validated['rental_item_id']);

            $remaining = $item->remainingQuantity(
                $validated['event_date'],
                $validated['return_date'],
            );

            if ($validated['quantity'] > $remaining) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'quantity' => $remaining < 1
                            ? 'This item has no remaining units available for those dates.'
                            : 'Only '.$remaining.' unit(s) remaining for those dates.',
                    ]);
            }

            Reservation::create([
                'customer_id' => auth()->id(),
                'rental_item_id' => $item->id,
                'quantity' => $validated['quantity'],
                'reservation_date' => $validated['reservation_date'],
                'event_date' => $validated['event_date'],
                'return_date' => $validated['return_date'],
                'status' => 'Pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            return redirect()
                ->route('customer.rental-items.index')
                ->with('success', 'Reservation submitted successfully!');
        });
    }

    public function destroy($id)
    {
        // Pending = customer cancellation; Returned = record cleanup.
        $reservation = Reservation::where('customer_id', auth()->id())
            ->whereIn('status', ['Pending', 'Returned'])
            ->findOrFail($id);

        $cancelled = $reservation->status === 'Pending';
        $reservation->delete();

        return redirect()
            ->route('customer.reservations.index')
            ->with('success', $cancelled
                ? 'Reservation cancelled successfully.'
                : 'Returned reservation record deleted successfully.');
    }
}