<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otp_sessions', function (Blueprint $table): void {
            $table->id();
            $table->string('session_id', 64)->unique();
            $table->string('email')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('purpose', 32)->index();
            $table->string('otp_hash');
            $table->timestamp('expires_at')->index();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->unsignedTinyInteger('max_attempts')->default(5);
            $table->timestamp('used_at')->nullable();
            $table->timestamp('last_attempt_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['email', 'purpose', 'expires_at'], 'otp_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_sessions');
    }
};
