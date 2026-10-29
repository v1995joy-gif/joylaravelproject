<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\RentalItem;
use Illuminate\Http\Request;

class RentalItemController extends Controller
{
    public function categories()
    {
        $categories = ItemCategory::withCount([
            'rentalItems' => function ($query) {
                $query->where('status', 'Available');
            },
        ])->latest()->get();

        return view('customer.item-categories.index', compact('categories'));
    }

    public function index(Request $request)
    {
        $items = RentalItem::with('category')
            ->withSum(['reservations as committed_quantity' => function ($query) {
                $query->whereIn('status', ['Pending', 'Confirmed']);
            }], 'quantity')
            ->where('status', 'Available')
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category'));
            })
            ->latest()
            ->get()
            ->map(function ($item) {
                $item->remaining_quantity = max(
                    0,
                    (int) $item->quantity - (int) $item->committed_quantity
                );

                return $item;
            });

        $selectedCategory = $request->filled('category')
            ? ItemCategory::find($request->integer('category'))
            : null;

        return view('customer.rental-items.index', compact('items', 'selectedCategory'));
    }

    public function show($id)
    {
        $item = RentalItem::with('category')
            ->where('status', 'Available')
            ->findOrFail($id);

        $remainingQuantity = $item->remainingQuantity();

        return view('customer.rental-items.show', compact('item', 'remainingQuantity'));
    }

    public function create($id)
    {
        $item = RentalItem::where('status', 'Available')
            ->findOrFail($id);

        $remainingQuantity = $item->remainingQuantity();

        if ($remainingQuantity < 1) {
            return redirect()
                ->route('customer.rental-items.index')
                ->with('error', 'This item has no remaining units available to reserve.');
        }

        return view(
            'customer.reservations.index',
            compact('item', 'remainingQuantity')
        );
    }
}