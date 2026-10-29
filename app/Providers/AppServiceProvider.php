<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\CustomizationRequest;
use App\Models\Payment;
use App\Models\Reservation;
use App\Mail\Transports\PhpmailerTransport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Mail::extend('phpmailer', fn () => new PhpmailerTransport());

        View::composer('layouts.navigation', function ($view): void {
            $notifications = collect();
            $notificationCount = 0;
            $notificationHeading = 'Customer requests';
            $notificationSummary = '0 pending';
            $notificationEmptyMessage = 'No pending customer requests.';

            $user = Auth::user();
            $notificationRole = $user?->role ?? 'guest';
            $notificationStorageKey = 'gms-notifications-read:' . ($user?->id ?? 'guest') . ':' . $notificationRole;
            $notificationVersion = '';

            if ($user?->role === 'admin') {
                $reservations = Reservation::with(['customer', 'rentalItem'])
                    ->whereRaw('LOWER(status) = ?', ['pending']);
                $appointments = Appointment::with(['customer', 'reservation.rentalItem'])
                    ->whereRaw('LOWER(status) = ?', ['pending']);
                $customizations = CustomizationRequest::with(['customer', 'package'])
                    ->whereRaw('LOWER(status) = ?', ['pending']);
                $payments = Payment::with(['reservation.customer', 'reservation.rentalItem'])
                    ->whereRaw('LOWER(status) = ?', ['pending']);

                $notificationCount = $reservations->count()
                    + $appointments->count()
                    + $customizations->count()
                    + $payments->count();

                $notifications = $reservations->latest()->take(8)->get()->map(fn (Reservation $reservation) => [
                    'title' => 'Reservation request',
                    'detail' => ($reservation->customer->name ?? 'Customer') . ' · ' . ($reservation->rentalItem->name ?? 'Rental item'),
                    'url' => route('admin.reservations.index'),
                    'created_at' => $reservation->created_at,
                    'key' => 'reservation:' . $reservation->id . ':' . $reservation->status . ':' . ($reservation->updated_at?->timestamp ?? 0),
                ])
                    ->concat($appointments->latest()->take(8)->get()->map(fn (Appointment $appointment) => [
                        'title' => 'Appointment request',
                        'detail' => ($appointment->customer->name ?? 'Customer') . ' · ' . ($appointment->reservation->rentalItem->name ?? 'Reservation'),
                        'url' => route('admin.appointments.index'),
                        'created_at' => $appointment->created_at,
                        'key' => 'appointment:' . $appointment->id . ':' . $appointment->status . ':' . ($appointment->updated_at?->timestamp ?? 0),
                    ]))
                    ->concat($customizations->latest()->take(8)->get()->map(fn (CustomizationRequest $customization) => [
                        'title' => 'Customization request',
                        'detail' => ($customization->customer->name ?? 'Customer') . ' · ' . ($customization->request_type ?: 'New request'),
                        'url' => route('admin.customization-requests.show', $customization->id),
                        'created_at' => $customization->created_at,
                        'key' => 'customization:' . $customization->id . ':' . $customization->status . ':' . ($customization->updated_at?->timestamp ?? 0),
                    ]))
                    ->concat($payments->latest()->take(8)->get()->map(fn (Payment $payment) => [
                        'title' => 'Payment submitted',
                        'detail' => ($payment->reservation->customer->name ?? 'Customer') . ' · ' . ($payment->reservation->rentalItem->name ?? 'Reservation'),
                        'url' => route('admin.payments.index'),
                        'created_at' => $payment->created_at,
                        'key' => 'payment:' . $payment->id . ':' . $payment->status . ':' . ($payment->updated_at?->timestamp ?? 0),
                    ]))
                    ->sortByDesc('created_at')
                    ->take(8)
                    ->values();
            } elseif ($user?->role === 'customer') {
                $reservations = Reservation::with('rentalItem')
                    ->where('customer_id', $user->id);
                $appointments = Appointment::with('reservation.rentalItem')
                    ->where('customer_id', $user->id);
                $customizations = CustomizationRequest::query()
                    ->where('customer_id', $user->id);
                $payments = Payment::with('reservation.rentalItem')
                    ->whereHas('reservation', fn ($query) => $query->where('customer_id', $user->id));

                $notificationCount = $reservations->count()
                    + $appointments->count()
                    + $customizations->count()
                    + $payments->count();
                $notificationHeading = 'Your activity';
                $notificationSummary = $notificationCount . ' activities';
                $notificationEmptyMessage = 'Your requests and updates will appear here.';

                $notifications = $reservations->latest('updated_at')->take(8)->get()->map(fn (Reservation $reservation) => [
                    'title' => 'Reservation update',
                    'detail' => ($reservation->rentalItem->name ?? 'Rental item') . ' · ' . match (strtolower($reservation->status)) {
                        'pending' => 'Request sent; awaiting admin review.',
                        'confirmed' => 'Admin approved your reservation.',
                        'cancelled' => 'Your reservation was declined or cancelled.',
                        'returned' => 'Your rental has been marked as returned.',
                        default => 'Current status: ' . ucfirst($reservation->status) . '.',
                    },
                    'url' => route('customer.reservations.index'),
                    'created_at' => $reservation->updated_at ?? $reservation->created_at,
                    'key' => 'reservation:' . $reservation->id . ':' . $reservation->status . ':' . ($reservation->updated_at?->timestamp ?? 0),
                ])
                    ->concat($appointments->latest('updated_at')->take(8)->get()->map(fn (Appointment $appointment) => [
                        'title' => 'Appointment update',
                        'detail' => ($appointment->reservation->rentalItem->name ?? 'Appointment') . ' · ' . match (strtolower($appointment->status)) {
                            'pending' => 'Request sent; awaiting admin review.',
                            'confirmed' => 'Admin confirmed your appointment.',
                            'completed' => 'Your appointment is complete.',
                            'cancelled' => 'Your appointment was declined or cancelled.',
                            'declined' => 'Your appointment request was declined.',
                            default => 'Current status: ' . ucfirst($appointment->status) . '.',
                        },
                        'url' => route('customer.appointments.index'),
                        'created_at' => $appointment->updated_at ?? $appointment->created_at,
                        'key' => 'appointment:' . $appointment->id . ':' . $appointment->status . ':' . ($appointment->updated_at?->timestamp ?? 0),
                    ]))
                    ->concat($customizations->latest('updated_at')->take(8)->get()->map(fn (CustomizationRequest $customization) => [
                        'title' => 'Customization update',
                        'detail' => match (strtolower($customization->status)) {
                            'pending' => 'Request sent; awaiting admin review.',
                            'confirmed' => 'Admin approved your customization request.',
                            'completed' => 'Your customization request is complete.',
                            'cancelled' => 'Admin declined or cancelled your customization request.',
                            default => 'Current status: ' . ucfirst($customization->status) . '.',
                        },
                        'url' => route('customer.customizations.show', $customization->id),
                        'created_at' => $customization->updated_at ?? $customization->created_at,
                        'key' => 'customization:' . $customization->id . ':' . $customization->status . ':' . ($customization->updated_at?->timestamp ?? 0),
                    ]))
                    ->concat($payments->latest('updated_at')->take(8)->get()->map(fn (Payment $payment) => [
                        'title' => 'Payment update',
                        'detail' => ($payment->reservation->rentalItem->name ?? 'Payment') . ' · ' . match (strtolower($payment->status)) {
                            'pending' => 'Payment submitted; awaiting admin review.',
                            'confirmed' => 'Admin confirmed your payment.',
                            'rejected' => 'Admin declined your payment.',
                            default => 'Current status: ' . ucfirst($payment->status) . '.',
                        },
                        'url' => route('customer.payments.index'),
                        'created_at' => $payment->updated_at ?? $payment->created_at,
                        'key' => 'payment:' . $payment->id . ':' . $payment->status . ':' . ($payment->updated_at?->timestamp ?? 0),
                    ]))
                    ->sortByDesc('created_at')
                    ->take(8)
                    ->values();
            }

            $notificationVersion = hash(
                'sha256',
                $notificationCount . '|' . $notifications->pluck('key')->implode('|')
            );

            $view->with(compact(
                'notifications',
                'notificationCount',
                'notificationHeading',
                'notificationSummary',
                'notificationEmptyMessage',
                'notificationStorageKey',
                'notificationVersion'
            ));
        });
    }
}
