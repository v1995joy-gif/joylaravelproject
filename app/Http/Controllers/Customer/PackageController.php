<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('packageItems.rentalItem')
            ->where('status', 'Active')
            ->latest()
            ->get();

        return view('customer.packages.index', compact('packages'));
    }

    public function show($id)
    {
        $package = Package::with('packageItems.rentalItem')
            ->where('status', 'Active')
            ->findOrFail($id);

        return view('customer.packages.show', compact('package'));
    }

    public function reserve($id)
    {
        $package = Package::with('packageItems.rentalItem')
            ->where('status', 'Active')
            ->findOrFail($id);

        return redirect()
            ->route('customer.customizations.create', $package->id)
            ->with('success', 'Choose your exact items and preferences to request this package.');
    }

    public function storeReservation($id)
    {
        $package = Package::with('packageItems')
            ->where('status', 'Active')
            ->findOrFail($id);

        return redirect()
            ->route('customer.customizations.create', $package->id)
            ->with('success', 'Choose your exact items and preferences to request this package.');
    }
}