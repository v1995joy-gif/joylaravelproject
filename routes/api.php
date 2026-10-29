<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'message' => 'Event Apparel Rental System API is working!',
        'status' => 'success'
    ]);
});