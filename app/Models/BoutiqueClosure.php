<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoutiqueClosure extends Model
{
    protected $fillable = [
        'closure_date',
        'reason',
    ];

    protected $casts = [
        'closure_date' => 'date',
    ];
}
