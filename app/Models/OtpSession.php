<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtpSession extends Model
{
    use Prunable;

    public const PURPOSE_EMAIL_VERIFICATION = 'email_verification';
    public const PURPOSE_PASSWORD_RESET = 'password_reset';

    protected $fillable = [
        'session_id',
        'email',
        'user_id',
        'purpose',
        'otp_hash',
        'expires_at',
        'attempts',
        'max_attempts',
        'used_at',
        'last_attempt_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
            'last_attempt_at' => 'datetime',
            'attempts' => 'integer',
            'max_attempts' => 'integer',
        ];
    }

    /**
     * Purge rows expired for over 24h (mirrors nexus purge event).
     * Schedule via `php artisan model:prune`.
     */
    public function prunable()
    {
        return static::where('expires_at', '<', now()->subHours(24));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function hasAttemptsRemaining(): bool
    {
        return $this->attempts < $this->max_attempts;
    }
}
