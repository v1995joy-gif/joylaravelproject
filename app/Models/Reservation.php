<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    protected $fillable = [
    'customer_id',
    'customization_request_id',
    'rental_item_id',
    'quantity',
    'amount_due',
    'reservation_date',
    'event_date',
    'reservation_notification_sent_at',
    'return_date',
    'return_notification_sent_at',
    'returned_at',
    'status',
    'notes',
];

protected $casts = [
    'reservation_date' => 'date',
    'event_date' => 'date',
    'reservation_notification_sent_at' => 'datetime',
    'return_date' => 'date',
    'return_notification_sent_at' => 'datetime',
    'returned_at' => 'datetime',
    'quantity' => 'integer',
    'amount_due' => 'decimal:2',
];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function rentalItem(): BelongsTo
    {
        return $this->belongsTo(RentalItem::class, 'rental_item_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'reservation_id');
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(Appointment::class);
    }

    public function customizationRequest(): BelongsTo
    {
        return $this->belongsTo(CustomizationRequest::class);
    }

    public function totalDueAmount(): float
    {
        if ($this->amount_due !== null) {
            return (float) $this->amount_due;
        }

        return (float) ($this->rentalItem?->rental_price ?? 0) * (int) $this->quantity;
    }
}