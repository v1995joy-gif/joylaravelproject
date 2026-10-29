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
        Schema::create('customization_request_new_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customization_request_id')
                ->constrained('customization_requests')
                ->cascadeOnDelete();

            $table->string('item_name');
            $table->unsignedInteger('quantity')->default(1);
            $table->text('description')->nullable();
            $table->string('reference_image_path')->nullable();
            $table->string('status')->default('Pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customization_request_new_items');
    }
};