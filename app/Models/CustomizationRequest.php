<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomizationRequest extends Model
{
    protected $fillable = [
        'customer_id',
        'package_id',
        'request_type',
        'selected_item',
        'customization_details',
        'package_total',
        'status',
        'correction_reason',
    ];

    protected $casts = [
        'package_total' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CustomizationRequestItem::class);
    }

    public function newItems(): HasMany
{
    return $this->hasMany(CustomizationRequestNewItem::class);
}

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}