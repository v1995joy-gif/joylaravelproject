<?php

namespace Tests\Feature\Auth;

use App\Mail\OtpMail;
use App\Models\OtpSession;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified_with_valid_otp(): void
    {
        $user = User::factory()->unverified()->create();

        Mail::fake();
        Event::fake();

        $user->sendEmailVerificationNotification();
        $code = Mail::sent(OtpMail::class)->first()->code;

        $response = $this->actingAs($user)->post('/verify-email/otp', [
            'otp_code' => $code,
        ]);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
    }

    public function test_email_is_not_verified_with_invalid_otp(): void
    {
        $user = User::factory()->unverified()->create();

        Mail::fake();
        $user->sendEmailVerificationNotification();
        $code = Mail::sent(OtpMail::class)->first()->code;
        $wrong = $code === '000000' ? '111111' : '000000';

        $this->actingAs($user)->post('/verify-email/otp', [
            'otp_code' => $wrong,
        ]);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_verification_is_blocked_until_code_entered(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get('/dashboard')
            ->assertRedirect(route('verification.notice'));
    }

    public function test_unverified_user_cannot_access_app_routes(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get('/customer/rental-items')
            ->assertRedirect(route('verification.notice'));

        $this->actingAs($user)->get('/admin/reservations')
            ->assertRedirect(route('verification.notice'));
    }

    public function test_resend_sends_new_otp(): void
    {
        $user = User::factory()->unverified()->create();

        Mail::fake();

        $this->actingAs($user)->post('/email/verification-notification')
            ->assertSessionHas('status', 'verification-link-sent');

        Mail::assertSent(OtpMail::class);
        $this->assertDatabaseHas('otp_sessions', [
            'email' => $user->email,
            'purpose' => OtpSession::PURPOSE_EMAIL_VERIFICATION,
        ]);
    }
}
