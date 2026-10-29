<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customization_request_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customization_request_id')
                ->constrained('customization_requests')
                ->cascadeOnDelete();

            $table->foreignId('rental_item_id')
                ->constrained('rental_items')
                ->cascadeOnDelete();

            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();

            $table->unique(
    ['customization_request_id', 'rental_item_id'],
    'custom_request_item_unique'
);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customization_request_items');
    }
};