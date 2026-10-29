<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customization_requests', function (Blueprint $table) {
            $table->decimal('package_total', 12, 2)->nullable()->after('customization_details');
        });

        Schema::table('customization_request_items', function (Blueprint $table) {
            $table->foreignId('package_item_id')
                ->nullable()
                ->after('customization_request_id')
                ->constrained('package_items')
                ->nullOnDelete();
            $table->boolean('is_additional')->default(false)->after('quantity');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('customization_request_id')
                ->nullable()
                ->after('customer_id')
                ->constrained('customization_requests')
                ->nullOnDelete();
            $table->decimal('amount_due', 12, 2)->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customization_request_id');
            $table->dropColumn('amount_due');
        });

        Schema::table('customization_request_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('package_item_id');
            $table->dropColumn('is_additional');
        });

        Schema::table('customization_requests', function (Blueprint $table) {
            $table->dropColumn('package_total');
        });
    }
};
