<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomizationRequestNewItem extends Model
{
    protected $fillable = [
        'customization_request_id',
        'item_name',
        'quantity',
        'description',
        'reference_image_path',
        'status',
        'quoted_price',
        'expense_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quoted_price' => 'decimal:2',
        'expense_price' => 'decimal:2',
    ];

    public function customizationRequest(): BelongsTo
    {
        return $this->belongsTo(CustomizationRequest::class);
    }
}