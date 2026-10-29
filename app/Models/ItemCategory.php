<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemCategory extends Model
{
    protected $fillable = [
    'name',
    'description',
    'image',
];

    public function rentalItems(): HasMany
    {
        return $this->hasMany(RentalItem::class, 'category_id');
    }
}