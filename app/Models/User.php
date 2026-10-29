<?php

namespace App\Models;

use App\Models\CustomizationRequest;
use App\Services\OtpService;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // role/blocked_at are set via forceFill only — never mass-assignable.
    protected $fillable = [
    'name',
    'email',
    'password',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked_at' => 'datetime',
        ];
    }

/**
 * Send an OTP code instead of the default signed verification link.
 * Hooked into the Registered event via SendEmailVerificationNotification.
 */
public function sendEmailVerificationNotification(): void
{
    try {
        app(OtpService::class)->generate(
            $this->email,
            OtpSession::PURPOSE_EMAIL_VERIFICATION,
            $this,
        );
    } catch (\Throwable $e) {
        // Total transport failure (smtp AND log down) must not 500 registration —
        // the user lands on verify-email and can hit Resend.
        report($e);
        session()->flash(
            'error',
            'We could not send the verification code — please use Resend.'
        );
    }
}

public function customizationRequests(): HasMany
{
    return $this->hasMany(CustomizationRequest::class, 'customer_id');
}

public function reservations(): HasMany
{
    return $this->hasMany(Reservation::class, 'customer_id');
}
}