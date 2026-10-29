<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomizationRequestItem extends Model
{
    protected $fillable = [
        'customization_request_id',
        'package_item_id',
        'package_item_name',
        'rental_item_id',
        'quantity',
        'is_additional',
        'size',
        'color',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'is_additional' => 'boolean',
    ];

    public function customizationRequest(): BelongsTo
    {
        return $this->belongsTo(CustomizationRequest::class);
    }

    public function rentalItem(): BelongsTo
    {
        return $this->belongsTo(RentalItem::class);
    }

    public function packageItem(): BelongsTo
    {
        return $this->belongsTo(PackageItem::class);
    }
}