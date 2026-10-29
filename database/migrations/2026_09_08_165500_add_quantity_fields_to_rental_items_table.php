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
        Schema::table('rental_items', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->after('status');
            $table->integer('available_quantity')->default(1)->after('quantity');
            $table->integer('damaged_quantity')->default(0)->after('available_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental_items', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'available_quantity', 'damaged_quantity']);
        });
    }
};
