<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_items', function (Blueprint $table) {
            $table->json('size_quantities')->nullable()->after('quantity');
            $table->json('colors')->nullable()->after('size_quantities');
        });
    }

    public function down(): void
    {
        Schema::table('rental_items', function (Blueprint $table) {
            $table->dropColumn(['size_quantities', 'colors']);
        });
    }
};