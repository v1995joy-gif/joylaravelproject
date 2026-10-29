<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name',
        'event_type',
        'price',
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function packageItems(): HasMany
    {
        return $this->hasMany(PackageItem::class);
    }
}