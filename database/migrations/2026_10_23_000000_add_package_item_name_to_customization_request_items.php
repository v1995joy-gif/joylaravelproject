<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customization_request_items', function (Blueprint $table) {
            $table->string('package_item_name')->nullable()->after('package_item_id');
        });
    }

    public function down(): void
    {
        Schema::table('customization_request_items', function (Blueprint $table) {
            $table->dropColumn('package_item_name');
        });
    }
};
