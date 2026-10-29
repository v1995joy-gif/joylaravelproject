<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageItem;
use App\Models\RentalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('packageItems.rentalItem')
            ->orderBy('name')
            ->get();

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function show($id)
    {
        $package = Package::with('packageItems.rentalItem.category')->findOrFail($id);

        return view('admin.packages.show', compact('package'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'status' => 'required|in:Active,Inactive',

            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('packages', 'public');
        }

        DB::transaction(function () use ($validated) {
            $package = Package::create([
                'name' => $validated['name'],
                'event_type' => $validated['event_type'],
                'price' => $validated['price'],
                'description' => $validated['description'] ?? null,
                'image' => $validated['image'] ?? null,
                'status' => $validated['status'],
            ]);

            foreach ($validated['items'] as $item) {
                PackageItem::create([
                    'package_id' => $package->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function edit($id)
    {
        $package = Package::with('packageItems.rentalItem')->findOrFail($id);
        $rentalItems = RentalItem::orderBy('name')->get();

        return view('admin.packages.edit', compact('package', 'rentalItems'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'status' => 'required|in:Active,Inactive',

            'items' => 'required|array|min:1',
            'items.*.rental_item_id' => 'nullable|exists:rental_items,id|distinct',
            'items.*.item_name' => 'nullable|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($validated['items'] as $index => $item) {
            if (empty($item['rental_item_id']) && empty($item['item_name'])) {
                return back()
                    ->withErrors(["items.{$index}.item_name" => 'Enter a package item name or select an inventory item.'])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($validated, $id, $request) {
            $package = Package::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($package->image) {
                    Storage::disk('public')->delete($package->image);
                }

                $validated['image'] = $request->file('image')->store('packages', 'public');
            }

            $package->update([
                'name' => $validated['name'],
                'event_type' => $validated['event_type'],
                'price' => $validated['price'],
                'description' => $validated['description'] ?? null,
                'image' => $validated['image'] ?? $package->image,
                'status' => $validated['status'],
            ]);

            $package->packageItems()->delete();

            foreach ($validated['items'] as $item) {
                PackageItem::create([
                    'package_id' => $package->id,
                    'rental_item_id' => $item['rental_item_id'] ?? null,
                    'item_name' => ! empty($item['rental_item_id']) ? null : $item['item_name'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);

        if ($package->image) {
            Storage::disk('public')->delete($package->image);
        }

        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}