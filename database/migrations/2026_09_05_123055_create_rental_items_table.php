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
        Schema::create('rental_items', function (Blueprint $table) {
    $table->id();
    $table->string('item_code')->unique();
    $table->string('name');
    $table->text('description')->nullable();
    $table->foreignId('category_id')->constrained('item_categories')->cascadeOnDelete();
    $table->decimal('rental_price', 10, 2);
    $table->string('status')->default('Available');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_items');
    }
};
