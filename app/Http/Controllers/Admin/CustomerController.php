<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomizationRequest;
use App\Models\CustomizationRequestNewItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->latest()
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function block($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->forceFill(['blocked_at' => now()])->save();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer account blocked successfully.');
    }

    public function unblock($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->forceFill(['blocked_at' => null])->save();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer account unblocked successfully.');
    }

    public function destroy($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        // Deleting a customer cascades into reservations, payments,
        // appointments and customization requests — block the account
        // instead of destroying financial history.
        if ($customer->reservations()->exists() || $customer->customizationRequests()->exists()) {
            return redirect()
                ->route('admin.customers.index')
                ->with('error', 'Cannot delete "' . $customer->name . '" — the account has reservation/payment history. Block it instead.');
        }

        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer and related records were permanently deleted.');
    }

    public function customizationRequests()
    {
        $requests = CustomizationRequest::with([
            'customer',
            'package',
            'items.rentalItem',
            'items.packageItem.rentalItem',
            'newItems',
        ])
        ->latest()
        ->get();

        return view(
            'admin.customers.customization-requests',
            compact('requests')
        );
    }

    public function customizationRequestShow($id)
    {
        $customizationRequest = CustomizationRequest::with([
            'customer',
            'package',
            'items.rentalItem',
            'items.packageItem.rentalItem',
            'newItems',
        ])->findOrFail($id);

        return view(
            'admin.customers.customization-request-show',
            compact('customizationRequest')
        );
    }

    public function destroyCustomizationRequest($id)
    {
        $customizationRequest = CustomizationRequest::with('newItems')->findOrFail($id);

        foreach ($customizationRequest->newItems as $newItem) {
            if ($newItem->reference_image_path) {
                Storage::disk('public')->delete($newItem->reference_image_path);
            }
        }

        $customizationRequest->delete();

        return redirect()
            ->route('admin.customization-requests.index')
            ->with('success', 'Customization request and related records were permanently deleted.');
    }

    public function updateRentalStatus(Request $request, $id)
    {
        $customizationRequest = CustomizationRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Confirmed,Cancelled,Completed',
        ]);

        // Legacy rows may carry a lowercase 'pending' default — normalize
        // so the transition map can still act on them.
        $currentStatus = ucfirst(strtolower((string) $customizationRequest->status));
        $newStatus = $request->status;

        $allowedTransitions = [
            'Pending' => ['Confirmed', 'Cancelled'],
            'Confirmed' => ['Completed', 'Cancelled'],
            'Completed' => [],
            'Cancelled' => [],
        ];

        if (
            !array_key_exists($currentStatus, $allowedTransitions) ||
            !in_array($newStatus, $allowedTransitions[$currentStatus], true)
        ) {
            return back()
                ->withErrors([
                    'status' => 'This status change is not allowed.'
                ]);
        }

        $customizationRequest->update([
            'status' => $newStatus,
        ]);

        return redirect()
            ->route('admin.customization-requests.show', $customizationRequest->id)
            ->with(
                'success',
                'Customization request status updated successfully.'
            );
    }

    public function editCustomizationStatus($id)
{
    $customizationRequest = CustomizationRequest::findOrFail($id);

    return view(
        'admin.customers.customization-request-status-edit',
        compact('customizationRequest')
    );
}

public function updateCustomizationStatusCorrection(Request $request, $id)
{
    $customizationRequest = CustomizationRequest::findOrFail($id);

    $validated = $request->validate([
        'status' => 'required|in:Pending,Confirmed,Completed,Cancelled',
        'correction_reason' => 'nullable|string|max:500',
    ]);

    $customizationRequest->update([
        'status' => $validated['status'],
        'correction_reason' => $validated['correction_reason'] ?? null,
    ]);

    return redirect()
        ->route('admin.customization-requests.show', $customizationRequest->id)
        ->with('success', 'Customization request status corrected successfully.');
}

    public function updateNewItemCosting(Request $request, $id)
    {
        $validated = $request->validate([
            'quoted_price' => 'nullable|numeric|min:0',
            'expense_price' => 'nullable|numeric|min:0',
        ]);

        $customizationRequestId = DB::transaction(function () use ($id, $validated) {
            $newItem = CustomizationRequestNewItem::query()
                ->lockForUpdate()
                ->findOrFail($id);
            $customizationRequest = CustomizationRequest::query()
                ->lockForUpdate()
                ->findOrFail($newItem->customization_request_id);

            $oldQuoteCents = $newItem->quoted_price === null
                ? 0
                : (int) round((float) $newItem->quoted_price * 100) * $newItem->quantity;
            $newQuote = $validated['quoted_price'] ?? null;
            $newQuoteCents = $newQuote === null
                ? 0
                : (int) round((float) $newQuote * 100) * $newItem->quantity;
            $quoteDeltaCents = $newQuoteCents - $oldQuoteCents;

            $newItem->update([
                'quoted_price' => $newQuote,
                'expense_price' => $validated['expense_price'] ?? null,
            ]);

            if ($customizationRequest->package_total !== null && $quoteDeltaCents !== 0) {
                $reservation = $customizationRequest->reservations()
                    ->with('rentalItem')
                    ->lockForUpdate()
                    ->orderBy('id')
                    ->first();

                $newPackageTotalCents = (int) round((float) $customizationRequest->package_total * 100)
                    + $quoteDeltaCents;

                if ($reservation) {
                    $newReservationDueCents = (int) round($reservation->totalDueAmount() * 100)
                        + $quoteDeltaCents;
                    $submittedPaymentsCents = (int) round((float) $reservation->payments()
                        ->where('status', '!=', 'Rejected')
                        ->sum('amount') * 100);

                    if ($newReservationDueCents < $submittedPaymentsCents) {
                        throw ValidationException::withMessages([
                            'quoted_price' => 'The updated package total cannot be lower than payments already submitted for this reservation.',
                        ]);
                    }

                    $reservation->amount_due = $newReservationDueCents / 100;
                    $reservation->save();
                }

                $customizationRequest->package_total = $newPackageTotalCents / 100;
                $customizationRequest->save();
            }

            return $customizationRequest->id;
        });

        return redirect()
            ->route('admin.customization-requests.show', $customizationRequestId)
            ->with('success', 'Quote and expense saved successfully.');
    }
}