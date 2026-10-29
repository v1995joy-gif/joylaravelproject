<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemCategoryController extends Controller
{
    public function index()
    {
        $categories = ItemCategory::withSum('rentalItems as stock_quantity', 'quantity')
            ->latest()
            ->get();

        return view('admin.item-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.item-categories.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')
            ->store('category-images', 'public');
    }

    ItemCategory::create([
        'name' => $request->name,
        'description' => $request->description,
        'image' => $imagePath,
    ]);

    return redirect()
        ->route('admin.item-categories.index')
        ->with('success', 'Item category added successfully.');
}

    public function edit($id)
    {
        $category = ItemCategory::findOrFail($id);

        return view('admin.item-categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    ]);

    $category = ItemCategory::findOrFail($id);

    $imagePath = $category->image;

    if ($request->hasFile('image')) {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $imagePath = $request->file('image')
            ->store('category-images', 'public');
    }

    $category->update([
        'name' => $request->name,
        'description' => $request->description,
        'image' => $imagePath,
    ]);

    return redirect()
        ->route('admin.item-categories.index')
        ->with('success', 'Item category updated successfully.');
}

    public function destroy($id)
{
    $category = ItemCategory::findOrFail($id);

    // Deleting a category cascades through rental_items into reservations,
    // payments and appointments — refuse instead of wiping history.
    if ($category->rentalItems()->exists()) {
        return redirect()
            ->route('admin.item-categories.index')
            ->with('error', 'Cannot delete "' . $category->name . '" — it still has rental items. Reassign or remove them first.');
    }

    $category->delete();

    return redirect()
        ->route('admin.item-categories.index')
        ->with('success', 'Item category deleted successfully.');
}
}