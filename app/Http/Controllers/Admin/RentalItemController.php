<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalItem;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RentalItemController extends Controller
{
    public function index()
    {
        $items = RentalItem::with([
            'category',
            'reservations' => fn ($query) => $query
                ->whereIn('status', ['Pending', 'Confirmed'])
                ->whereNull('returned_at'),
        ])->latest()->get();

        $items->each(function (RentalItem $item): void {
            $item->setAttribute('inventory_breakdown', $item->inventoryBreakdown());
        });

        $inventorySummary = [
            'items' => $items->count(),
            'total' => $items->sum(fn (RentalItem $item) => $item->inventory_breakdown['total']),
            'available' => $items->sum(fn (RentalItem $item) => $item->inventory_breakdown['available']),
            'reserved' => $items->sum(fn (RentalItem $item) => $item->inventory_breakdown['reserved']),
            'rented' => $items->sum(fn (RentalItem $item) => $item->inventory_breakdown['rented']),
            'not_returned' => $items->sum(fn (RentalItem $item) => $item->inventory_breakdown['not_returned']),
            'maintenance' => $items->sum(fn (RentalItem $item) => $item->inventory_breakdown['maintenance']),
        ];

        $categories = ItemCategory::all();

        return view('admin.rental-items.index', compact('items', 'categories', 'inventorySummary'));
    }

    public function create()
    {
        $categories = ItemCategory::all();

        return view('admin.rental-items.create', compact('categories'));
    }

    public function show($id)
    {
        $item = RentalItem::with('category')->findOrFail($id);
        $inventory = $item->inventoryBreakdown();

        return view('admin.rental-items.show', compact('item', 'inventory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code' => 'required|string|max:255|unique:rental_items,item_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'category_id' => 'required|exists:item_categories,id',
            'rental_price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Maintenance,Rented,Reserved',
            'quantity' => 'required|integer|min:1',
            'size_names' => 'nullable|array',
            'size_names.*' => 'nullable|string|max:50',
            'size_quantities' => 'nullable|array',
            'size_quantities.*' => 'nullable|integer|min:0',
            'colors' => 'nullable|string|max:1000',
        ]);

        $validated = $this->normalizeOptions($validated);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rental-items', 'public');
        }

        RentalItem::create($validated);

        return redirect()
            ->route('admin.rental-items.index')
            ->with('success', 'Rental item added successfully!');
    }

    public function edit($id)
{
    $item = RentalItem::findOrFail($id);
    $categories = ItemCategory::all();

    return view('admin.rental-items.edit', compact('item', 'categories'));
}

public function update(Request $request, $id)
{
    $item = RentalItem::findOrFail($id);

    $validated = $request->validate([
        'item_code' => 'required|string|max:255|unique:rental_items,item_code,' . $id,
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        'category_id' => 'required|exists:item_categories,id',
        'rental_price' => 'required|numeric|min:0',
        'status' => 'required|in:Available,Maintenance,Rented,Reserved',
        'quantity' => 'required|integer|min:1',
        'size_names' => 'nullable|array',
        'size_names.*' => 'nullable|string|max:50',
        'size_quantities' => 'nullable|array',
        'size_quantities.*' => 'nullable|integer|min:0',
        'colors' => 'nullable|string|max:1000',
    ]);

    $validated = $this->normalizeOptions($validated);

    $previousImage = $item->image;
    $newImagePath = null;

    if ($request->hasFile('image')) {
        $newImagePath = $request->file('image')->store('rental-items', 'public');

        if (! $newImagePath) {
            return back()
                ->withInput()
                ->withErrors(['image' => 'The new image could not be saved. Please try again.']);
        }

        $validated['image'] = $newImagePath;
    }

    $item->update($validated);

    if ($newImagePath && $previousImage && $previousImage !== $newImagePath) {
        Storage::disk('public')->delete($previousImage);
    }

    return redirect()
        ->route('admin.rental-items.index')
        ->with('success', 'Rental item updated successfully!');
}

public function destroy($id)
{
    $item = RentalItem::findOrFail($id);

    // Deleting an item cascades into its reservations, payments and
    // appointments — refuse while history exists.
    if ($item->reservations()->exists()) {
        return redirect()
            ->route('admin.rental-items.index')
            ->with('error', 'Cannot delete "' . $item->name . '" — it has reservation/payment history. Set it to an inactive status instead.');
    }

    if ($item->image) {
        Storage::disk('public')->delete($item->image);
    }

    $item->delete();

    return redirect()
        ->route('admin.rental-items.index')
        ->with('success', 'Rental item deleted successfully!');
}

private function normalizeOptions(array $validated): array
{
    // Zip name↔quantity pairs explicitly — a ragged input pair can't
    // silently zero out a size anymore.
    $sizes = collect($validated['size_names'] ?? [])
        ->zip($validated['size_quantities'] ?? [])
        ->mapWithKeys(fn ($pair) => [trim((string) ($pair[0] ?? '')) => (int) ($pair[1] ?? 0)])
        ->filter(fn ($quantity, $size) => $size !== '' && $quantity > 0)
        ->all();

    if ($sizes) {
        $validated['quantity'] = array_sum($sizes);
    }

    $validated['size_quantities'] = $sizes ?: null;
    unset($validated['size_names']);
    $validated['colors'] = collect(preg_split('/[,\n]+/', $validated['colors'] ?? ''))
        ->map(fn ($color) => trim($color))
        ->filter()
        ->unique()
        ->values()
        ->all() ?: null;

    return $validated;
}
}