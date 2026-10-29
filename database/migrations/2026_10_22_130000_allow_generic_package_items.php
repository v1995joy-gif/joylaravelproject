<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_items', function (Blueprint $table) {
            $table->foreignId('rental_item_id')->nullable()->change();
            $table->string('item_name')->nullable()->after('rental_item_id');
        });
    }

    public function down(): void
    {
        if (DB::table('package_items')->whereNull('rental_item_id')->exists()) {
            throw new RuntimeException('Generic package items must be removed or converted before this migration can be rolled back.');
        }

        Schema::table('package_items', function (Blueprint $table) {
            $table->dropColumn('item_name');
            $table->foreignId('rental_item_id')->nullable(false)->change();
        });
    }
};
