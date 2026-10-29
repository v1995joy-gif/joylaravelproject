<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customization_request_new_items', function (Blueprint $table) {
            $table->decimal('quoted_price', 12, 2)->nullable()->after('status');
            $table->decimal('expense_price', 12, 2)->nullable()->after('quoted_price');
        });
    }

    public function down(): void
    {
        Schema::table('customization_request_new_items', function (Blueprint $table) {
            $table->dropColumn(['quoted_price', 'expense_price']);
        });
    }
};
