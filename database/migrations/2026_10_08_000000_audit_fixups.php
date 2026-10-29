<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Indexes on columns hit by admin lists/reports and stock math.
        Schema::table('reservations', function (Blueprint $table) {
            $table->index('status');
            $table->index('event_date');
            $table->index('return_date');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->index('appointment_date');
        });

        Schema::table('customization_requests', function (Blueprint $table) {
            $table->index('status');
            // Correction audit trail — was validated but never persisted.
            $table->string('correction_reason', 500)->nullable()->after('status');
        });

        // Align the default with the casing the app's status map expects.
        DB::table('customization_requests')->where('status', 'pending')->update(['status' => 'Pending']);
        Schema::table('customization_requests', function (Blueprint $table) {
            $table->string('status')->default('Pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['event_date']);
            $table->dropIndex(['return_date']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['appointment_date']);
        });

        Schema::table('customization_requests', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('correction_reason');
            $table->string('status')->default('pending')->change();
        });
    }
};
