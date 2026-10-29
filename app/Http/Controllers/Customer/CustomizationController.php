<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomizationRequest;
use App\Models\CustomizationRequestItem;
use App\Models\CustomizationRequestNewItem;
use App\Models\Package;
use App\Models\RentalItem;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomizationController extends Controller
{
    public function index(Request $request)
    {
        $requests = CustomizationRequest::with([
            'package',
            'items.rentalItem',
            'items.packageItem.rentalItem',
            'newItems',
        ])
        ->where('customer_id', $request->user()->id)
        ->latest()
        ->get();

        return view(
            'customer.customizations.index',
            compact('requests')
        );
    }

    public function show(Request $request, $id)
    {
        $customizationRequest = CustomizationRequest::with([
            'package',
            'items.rentalItem',
            'items.packageItem.rentalItem',
            'newItems',
        ])
        ->where('customer_id', $request->user()->id)
        ->findOrFail($id);

        return view(
            'customer.customizations.show',
            compact('customizationRequest')
        );
    }

    public function create($packageId)
    {
        $package = Package::with([
            'packageItems.rentalItem.category',
        ])
        ->where('status', 'Active')
        ->findOrFail($packageId);

        $rentalItems = RentalItem::with('category')
            ->where('status', 'Available')
            ->orderBy('name')
            ->get();

        $rentalItemOptions = $rentalItems->map(fn (RentalItem $item) => [
            'id' => $item->id,
            'name' => $item->name,
            'category' => $item->category->name ?? 'No Category',
            'rentalPrice' => (float) $item->rental_price,
        ])->values();

        return view(
            'customer.customizations.create',
            compact('package', 'rentalItems', 'rentalItemOptions')
        );
    }

    public function store(Request $request, $packageId)
    {
        $package = Package::with('packageItems.rentalItem')
            ->where('status', 'Active')
            ->findOrFail($packageId);

        $validated = $request->validate([
            'reservation_date' => 'required|date|after_or_equal:today',
            'event_date' => 'required|date|after_or_equal:reservation_date',
            'return_date' => 'required|date|after_or_equal:event_date',
            'notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.rental_item_id' => 'required|exists:rental_items,id|distinct',
            'items.*.package_item_id' => 'nullable|integer|distinct|exists:package_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.size' => 'nullable|string|max:100',
            'items.*.color' => 'nullable|string|max:100',

            'customization_details' => 'required|string',
        ]);

        $allPackageItemIds = $package->packageItems
            ->modelKeys();
        $submittedPackageItemIds = collect($validated['items'] ?? [])
            ->pluck('package_item_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->sort()
            ->values()
            ->all();

        sort($allPackageItemIds);
        if (
            array_diff($submittedPackageItemIds, $allPackageItemIds)
            || array_diff($allPackageItemIds, $submittedPackageItemIds)
        ) {
            throw ValidationException::withMessages([
                'items' => 'Choose one available rental item for every package piece listed.',
            ]);
        }

        foreach ($validated['items'] ?? [] as $index => $item) {
            if (empty($item['package_item_id'])) {
                continue;
            }

            $packageItem = $package->packageItems->firstWhere('id', $item['package_item_id']);

            if (! $packageItem) {
                throw ValidationException::withMessages([
                    "items.{$index}.rental_item_id" => 'The selected item does not belong to this package.',
                ]);
            }

            $validated['items'][$index]['quantity'] = $packageItem->quantity;
        }

        if (empty($validated['items'])) {
            return back()
                ->withErrors([
                    'items' => 'Please add at least one existing rental item.'
                ])
                ->withInput();
        }

        $customizationRequestId = DB::transaction(function () use ($validated, $package, $request) {
            $rentalItemsByIndex = [];
            $baseAmountCents = (int) round((float) $package->price * 100);
            $additionalAmountCents = 0;

            foreach ($validated['items'] ?? [] as $index => $item) {
                $rentalItem = RentalItem::query()
                    ->lockForUpdate()
                    ->find($item['rental_item_id']);

                if (
                    ! $rentalItem
                    || $rentalItem->status !== 'Available'
                    || $item['quantity'] > $rentalItem->remainingQuantity(
                        $validated['event_date'],
                        $validated['return_date'],
                    )
                ) {
                    throw ValidationException::withMessages([
                        "items.{$index}.rental_item_id" => 'One or more selected items are no longer available in the requested quantity for those dates.',
                    ]);
                }

                $rentalItemsByIndex[$index] = $rentalItem;

                if (empty($item['package_item_id'])) {
                    $additionalAmountCents += (int) round((float) $rentalItem->rental_price * 100)
                        * (int) $item['quantity'];
                }
            }

            $customizationRequest = CustomizationRequest::create([
                'customer_id' => $request->user()->id,
                'package_id' => $package->id,
                'request_type' => 'Package',
                'selected_item' => $package->name,
                'customization_details' => $validated['customization_details'],
                'package_total' => ($baseAmountCents + $additionalAmountCents) / 100,
                'status' => 'Pending',
            ]);

            $baseChargeAssigned = false;
            foreach ($validated['items'] ?? [] as $index => $item) {
                $isAdditional = empty($item['package_item_id']);
                $packageItem = $isAdditional
                    ? null
                    : $package->packageItems->firstWhere('id', $item['package_item_id']);

                CustomizationRequestItem::create([
                    'customization_request_id' => $customizationRequest->id,
                    'package_item_id' => $item['package_item_id'] ?? null,
                    'package_item_name' => $packageItem?->item_name ?? $packageItem?->rentalItem?->name,
                    'rental_item_id' => $item['rental_item_id'],
                    'quantity' => $item['quantity'],
                    'is_additional' => $isAdditional,
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                ]);

                $rentalItem = $rentalItemsByIndex[$index] ?? null;
                $amountDueCents = $isAdditional
                    ? (int) round((float) $rentalItem->rental_price * 100) * (int) $item['quantity']
                    : 0;

                if (! $baseChargeAssigned && ! $isAdditional) {
                    $amountDueCents += $baseAmountCents;
                    $baseChargeAssigned = true;
                }

                $itemPreferences = collect([
                    $packageItem
                        ? 'Package piece: ' . ($packageItem->item_name ?? $packageItem->rentalItem?->name ?? 'Included item')
                        : null,
                    isset($item['size']) ? 'Size: ' . $item['size'] : null,
                    isset($item['color']) ? 'Color: ' . $item['color'] : null,
                ])->filter()->implode('; ');

                $reservationNotes = collect([
                    'Package: ' . $package->name,
                    'Customization request: #' . $customizationRequest->id,
                    $itemPreferences ?: null,
                    $validated['notes'] ?? null,
                    'Customer preferences: ' . $validated['customization_details'],
                ])->filter()->implode("\n");

                Reservation::create([
                    'customer_id' => $request->user()->id,
                    'customization_request_id' => $customizationRequest->id,
                    'rental_item_id' => $rentalItem->id,
                    'quantity' => $item['quantity'],
                    'amount_due' => $amountDueCents / 100,
                    'reservation_date' => $validated['reservation_date'],
                    'event_date' => $validated['event_date'],
                    'return_date' => $validated['return_date'],
                    'status' => 'Pending',
                    'notes' => $reservationNotes,
                ]);
            }

            if (! $baseChargeAssigned) {
                $reservation = Reservation::where('customization_request_id', $customizationRequest->id)
                    ->orderBy('id')
                    ->first();
                $reservation?->increment('amount_due', $baseAmountCents / 100);
            }

            return $customizationRequest->id;
        });

        return redirect()
            ->route('customer.reservations.index')
            ->with('success', 'Package reservation request submitted successfully. Your selected items are pending admin review.')
            ->with('customization_request_id', $customizationRequestId);
    }

    public function portal()
{
    return view('customer.customizations.portal');
}

public function createCustomSet()
{
    $rentalItems = RentalItem::with('category')
        ->where('status', 'Available')
        ->orderBy('name')
        ->get();

    $rentalItemOptions = $rentalItems->map(function (RentalItem $item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'sizes' => $item->size_quantities ?? [],
            'colors' => $item->colors ?? [],
        ];
    })->values();

    return view(
        'customer.customizations.custom-set',
        compact('rentalItems', 'rentalItemOptions')
    );
}

public function createNewItemRequest()
{
    return view('customer.customizations.new-item-request');
}

public function storeCustomSet(Request $request)
{
    $validated = $request->validate([
        'items' => 'nullable|array',
        'items.*.rental_item_id' => 'required|exists:rental_items,id|distinct',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.size' => 'nullable|string|max:50',
        'items.*.color' => 'nullable|string|max:100',

        'customization_details' => 'required|string',
    ]);

    if (empty($validated['items'])) {
        return back()
            ->withErrors([
                'items' => 'Please add at least one existing rental item.'
            ])
            ->withInput();
    }

    DB::transaction(function () use ($validated, $request) {

        $customizationRequest = CustomizationRequest::create([
            'customer_id' => $request->user()->id,
            'package_id' => null,
            'request_type' => 'Custom Set',
            'selected_item' => 'Custom Set',
            'customization_details' => $validated['customization_details'],
            'status' => 'Pending',
        ]);

        foreach ($validated['items'] ?? [] as $index => $item) {
            CustomizationRequestItem::create([
                'customization_request_id' => $customizationRequest->id,
                'rental_item_id' => $item['rental_item_id'],
                'quantity' => $item['quantity'],
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
            ]);
        }

    });

    return redirect()
        ->route('customer.customizations.index')
        ->with('success', 'Custom set request submitted successfully.');
}

public function storeNewItemRequest(Request $request)
{
    $validated = $request->validate([
        'new_items' => 'required|array|min:1',
        'new_items.*.item_name' => 'required|string|max:255',
        'new_items.*.quantity' => 'required|integer|min:1',
        'new_items.*.description' => 'nullable|string',
        'new_items.*.reference_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    ]);

    foreach ($validated['new_items'] as $index => $newItem) {
        $validated['new_items'][$index]['reference_image_path'] =
            ! empty($newItem['reference_image'])
                ? $newItem['reference_image']->store('customization-reference-images', 'public')
                : null;
    }

    DB::transaction(function () use ($validated, $request) {
        $customizationRequest = CustomizationRequest::create([
            'customer_id' => $request->user()->id,
            'package_id' => null,
            'request_type' => 'New Item Request',
            'selected_item' => 'New Item Request',
            'customization_details' => 'See the requested item details below.',
            'status' => 'Pending',
        ]);

        foreach ($validated['new_items'] as $newItem) {
            CustomizationRequestNewItem::create([
                'customization_request_id' => $customizationRequest->id,
                'item_name' => $newItem['item_name'],
                'quantity' => $newItem['quantity'],
                'description' => $newItem['description'] ?? null,
                'reference_image_path' => $newItem['reference_image_path'] ?? null,
                'status' => 'Pending',
            ]);
        }
    });

    return redirect()
        ->route('customer.customizations.index')
        ->with('success', 'New item request submitted successfully.');
}
}