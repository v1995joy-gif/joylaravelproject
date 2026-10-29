<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageItem extends Model
{
    protected $fillable = [
        'package_id',
        'rental_item_id',
        'item_name',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function rentalItem(): BelongsTo
    {
        return $this->belongsTo(RentalItem::class);
    }
}