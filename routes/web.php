<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ItemCategoryController;
use App\Http\Controllers\Admin\RentalItemController;
use App\Http\Controllers\Customer\RentalItemController as CustomerRentalItemController;
use App\Http\Controllers\Customer\ReservationController as CustomerReservationController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\PackageController as CustomerPackageController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Customer\CustomizationController;
use App\Http\Controllers\Customer\AppointmentController as CustomerAppointmentController;
use App\Models\BoutiqueClosure;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome', [
        'items' => \App\Models\RentalItem::with('category')
            ->where('status', 'Available')
            ->latest()
            ->take(8)
            ->get(),
        'categories' => \App\Models\ItemCategory::orderBy('name')->take(8)->get(),
    ]);
});

Route::get('/dashboard', function () {
    $closures = BoutiqueClosure::orderBy('closure_date')
        ->get()
        ->map(fn (BoutiqueClosure $closure) => [
            'id' => 'closure-' . $closure->id,
            'start' => $closure->closure_date->format('Y-m-d'),
            'allDay' => true,
            'display' => 'background',
            'backgroundColor' => 'rgba(239, 68, 68, 0.3)',
        ])
        ->values();

    return view('dashboard', compact('closures'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified', 'admin'])
  ->name('admin.dashboard');

Route::get('/admin/customers', [CustomerController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customers.index');

Route::patch('/admin/customers/{id}/block', [CustomerController::class, 'block'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customers.block');

Route::patch('/admin/customers/{id}/unblock', [CustomerController::class, 'unblock'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customers.unblock');

Route::delete('/admin/customers/{id}', [CustomerController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customers.destroy');

Route::get('/admin/customization-requests', [CustomerController::class, 'customizationRequests'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customization-requests.index');

Route::patch('/admin/customization-requests/{id}/status', [CustomerController::class, 'updateRentalStatus'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customization-requests.update-status');

Route::delete('/admin/customization-requests/{id}', [CustomerController::class, 'destroyCustomizationRequest'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customization-requests.destroy');

Route::get('/admin/customization-requests/{id}', [CustomerController::class, 'customizationRequestShow'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customization-requests.show');

Route::get('/admin/item-categories', [ItemCategoryController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.item-categories.index');

Route::get('/admin/item-categories/create', [ItemCategoryController::class, 'create'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.item-categories.create');

Route::post('/admin/item-categories', [ItemCategoryController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.item-categories.store');

Route::get('/admin/item-categories/{id}/edit', [ItemCategoryController::class, 'edit'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.item-categories.edit');

Route::patch('/admin/item-categories/{id}', [ItemCategoryController::class, 'update'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.item-categories.update');

Route::delete('/admin/item-categories/{id}', [ItemCategoryController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.item-categories.destroy');

Route::get('/admin/rental-items', [RentalItemController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.rental-items.index');

Route::get('/admin/rental-items/create', [RentalItemController::class, 'create'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.rental-items.create');

Route::post('/admin/rental-items', [RentalItemController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.rental-items.store');

Route::get('/admin/rental-items/{id}', [RentalItemController::class, 'show'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.rental-items.show');

Route::get('/admin/rental-items/{id}/edit', [RentalItemController::class, 'edit'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.rental-items.edit');

Route::patch('/admin/rental-items/{id}', [RentalItemController::class, 'update'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.rental-items.update');

Route::delete('/admin/rental-items/{id}', [RentalItemController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.rental-items.destroy');

Route::get('/admin/reservations', [ReservationController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.reservations.index');

Route::patch('/admin/reservations/{id}/status', [ReservationController::class, 'updateStatus'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.reservations.update-status');

Route::get('/admin/appointments', [AppointmentController::class,'index'])
    ->middleware(['auth','verified','admin'])
    ->name('admin.appointments.index');

Route::post('/admin/appointments/closure', [AppointmentController::class, 'storeClosure'])
    ->middleware(['auth','verified','admin'])
    ->name('admin.appointments.store-closure');

Route::patch('/admin/appointments/closure/{id}', [AppointmentController::class, 'updateClosure'])
    ->middleware(['auth','verified','admin'])
    ->name('admin.appointments.update-closure');

Route::delete('/admin/appointments/closure/{id}', [AppointmentController::class, 'reopenClosure'])
    ->middleware(['auth','verified','admin'])
    ->name('admin.appointments.reopen-closure');

Route::patch('/admin/appointments/{id}/status', [AppointmentController::class,'updateStatus'])
    ->middleware(['auth','verified','admin'])
    ->name('admin.appointments.update-status');

Route::patch('/admin/appointments/{id}/schedule', [AppointmentController::class,'updateSchedule'])
    ->middleware(['auth','verified','admin'])
    ->name('admin.appointments.update-schedule');

Route::get('/admin/payments', [AdminPaymentController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.payments.index');

Route::get('/admin/reports', [ReportController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.reports.index');

Route::get('/admin/packages', [PackageController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.packages.index');

Route::get('/admin/packages/create', [PackageController::class, 'create'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.packages.create');

Route::get('/admin/packages/{id}', [PackageController::class, 'show'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.packages.show');

Route::post('/admin/packages', [PackageController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.packages.store');

Route::get('/admin/packages/{id}/edit', [PackageController::class, 'edit'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.packages.edit');

Route::patch('/admin/packages/{id}', [PackageController::class, 'update'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.packages.update');

Route::delete('/admin/packages/{id}', [PackageController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.packages.destroy');

Route::patch('/admin/payments/{id}/status', [AdminPaymentController::class, 'updateStatus'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.payments.update-status');

Route::get('/customer/rental-items', [CustomerRentalItemController::class, 'index'])
    ->middleware(['auth','verified'])
    ->name('customer.rental-items.index');

Route::get('/customer/rental-items/{id}', [CustomerRentalItemController::class, 'show'])
    ->middleware(['auth','verified'])
    ->name('customer.rental-items.show');

Route::get('/customer/item-categories', [CustomerRentalItemController::class, 'categories'])
    ->middleware(['auth','verified'])
    ->name('customer.item-categories.index');

Route::get('/customer/packages', [CustomerPackageController::class, 'index'])
    ->middleware(['auth','verified'])
    ->name('customer.packages.index');

Route::get('/customer/packages/{id}/reserve', [CustomerPackageController::class, 'reserve'])
    ->middleware(['auth','verified'])
    ->name('customer.packages.reserve');

Route::post('/customer/packages/{id}/reserve', [CustomerPackageController::class, 'storeReservation'])
    ->middleware(['auth','verified'])
    ->name('customer.packages.reserve.store');

Route::get('/customer/rental-items/{id}/reserve', [CustomerRentalItemController::class, 'create'])
    ->middleware(['auth','verified'])
    ->name('customer.rental-items.reserve');

Route::post('/customer/reservations', [CustomerReservationController::class, 'store'])
    ->middleware(['auth','verified'])
    ->name('customer.reservations.store');

Route::delete('/customer/reservations/{id}', [CustomerReservationController::class, 'destroy'])
    ->middleware(['auth','verified'])
    ->name('customer.reservations.destroy');

Route::get('/customer/reservations', [CustomerReservationController::class, 'index'])
    ->middleware(['auth','verified'])
    ->name('customer.reservations.index');

Route::get('/customer/payments', [CustomerPaymentController::class, 'index'])
    ->middleware(['auth','verified'])
    ->name('customer.payments.index');

Route::post('/customer/payments', [CustomerPaymentController::class, 'store'])
    ->middleware(['auth','verified'])
    ->name('customer.payments.store');

Route::get('/customer/packages/{id}', [CustomerPackageController::class, 'show'])
    ->middleware(['auth','verified'])
    ->name('customer.packages.show');

Route::get('/customer/packages/{packageId}/customize', [CustomizationController::class, 'create'])
    ->middleware(['auth','verified'])
    ->name('customer.customizations.create');

Route::post('/customer/packages/{packageId}/customize', [CustomizationController::class, 'store'])
    ->middleware(['auth','verified'])
    ->name('customer.customizations.store');

Route::get('/customer/customizations', [CustomizationController::class, 'index'])
    ->middleware(['auth','verified'])
    ->name('customer.customizations.index');

Route::get('/customer/customizations/{id}', [CustomizationController::class, 'show'])
    ->middleware(['auth','verified'])
    ->name('customer.customizations.show');

Route::get('/customer/reservations/{reservationId}/appointment', [CustomerAppointmentController::class, 'create'])
    ->middleware(['auth','verified'])
    ->name('customer.appointments.create');

Route::post('/customer/reservations/{reservationId}/appointment', [CustomerAppointmentController::class, 'store'])
    ->middleware(['auth','verified'])
    ->name('customer.appointments.store');

Route::get('/customer/appointments', [CustomerAppointmentController::class, 'index'])
    ->middleware(['auth','verified'])
    ->name('customer.appointments.index');

Route::delete('/customer/appointments/{id}', [CustomerAppointmentController::class, 'destroy'])
    ->middleware(['auth','verified'])
    ->name('customer.appointments.destroy');

Route::get('/admin/customization-requests/{id}/status/edit', [CustomerController::class, 'editCustomizationStatus'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customization-requests.status.edit');

Route::patch('/admin/customization-requests/{id}/status/correction', [CustomerController::class, 'updateCustomizationStatusCorrection'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customization-requests.status.correction');

Route::patch('/admin/customization-request-items/{id}/costing', [CustomerController::class, 'updateNewItemCosting'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.customization-request-items.costing');

Route::post('/admin/reservations/{id}/send-reminder', [ReservationController::class, 'sendReminder'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.reservations.send-reminder');

Route::post('/admin/reservations/{id}/send-return-reminder', [ReservationController::class, 'sendReturnReminder'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.reservations.send-return-reminder');

Route::get('/admin/return-schedule', [ReservationController::class, 'returnSchedule'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.return-schedule.index');

Route::patch('/admin/return-schedule/{id}/returned', [ReservationController::class, 'markReturned'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.return-schedule.mark-returned');

Route::get('/admin/notifications', [NotificationController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.notifications.index');

Route::get('/customer/customization', [CustomizationController::class, 'portal'])
    ->middleware(['auth','verified'])
    ->name('customer.customization.portal');

Route::get('/customer/customization/request-new-item', [CustomizationController::class, 'createNewItemRequest'])
    ->middleware(['auth','verified'])
    ->name('customer.customizations.new-item-request');

Route::post('/customer/customization/request-new-item', [CustomizationController::class, 'storeNewItemRequest'])
    ->middleware(['auth','verified'])
    ->name('customer.customizations.new-item-request.store');

Route::get('/customer/customization/custom-set', [CustomizationController::class, 'createCustomSet'])
    ->middleware(['auth','verified'])
    ->name('customer.customizations.custom-set');

Route::post('/customer/customization/custom-set', [CustomizationController::class, 'storeCustomSet'])
    ->middleware(['auth','verified'])
    ->name('customer.customizations.custom-set.store');
