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
        Schema::table('customization_requests', function (Blueprint $table) {
            $table->foreignId('package_id')
                ->nullable()
                ->after('customer_id')
                ->constrained('packages')
                ->nullOnDelete();

            $table->string('request_type')
                ->default('Custom Set')
                ->after('package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customization_requests', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['package_id', 'request_type']);
        });
    }
};