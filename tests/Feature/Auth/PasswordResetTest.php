<?php

namespace Tests\Feature\Auth;

use App\Mail\OtpMail;
use App\Models\OtpSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_code_can_be_requested(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email])
            ->assertRedirect(route('password.reset'));

        Mail::assertSent(OtpMail::class, fn ($mail) => $mail->hasTo($user->email));
        $this->assertDatabaseHas('otp_sessions', [
            'email' => $user->email,
            'purpose' => OtpSession::PURPOSE_PASSWORD_RESET,
        ]);
    }

    public function test_reset_request_for_unknown_email_behaves_identically(): void
    {
        Mail::fake();

        $this->post('/forgot-password', ['email' => 'ghost@example.com'])
            ->assertRedirect(route('password.reset'));

        Mail::assertNotSent(OtpMail::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        $this->get('/reset-password')->assertStatus(200);
    }

    public function test_password_can_be_reset_with_valid_otp(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);
        $code = Mail::sent(OtpMail::class)->first()->code;

        $response = $this->post('/reset-password', [
            'email' => $user->email,
            'otp_code' => $code,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('new-secure-password', $user->fresh()->password));
    }

    public function test_prior_code_survives_typo_resubmit(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);
        $code = Mail::sent(OtpMail::class)->first()->code;

        // Mistyped second request — unknown email must not clear the binding.
        $this->post('/forgot-password', ['email' => 'typo@example.com']);

        $response = $this->post('/reset-password', [
            'email' => $user->email,
            'otp_code' => $code,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('new-secure-password', $user->fresh()->password));
    }

    public function test_reset_form_redirects_without_session_binding(): void
    {
        $this->get('/reset-password')->assertRedirect(route('password.request'));
    }

    public function test_password_is_not_reset_with_invalid_otp(): void
    {
        Mail::fake();

        $user = User::factory()->create(['password' => Hash::make('original-pass')]);

        $this->post('/forgot-password', ['email' => $user->email]);
        $code = Mail::sent(OtpMail::class)->first()->code;
        $wrong = $code === '000000' ? '111111' : '000000';

        $response = $this->post('/reset-password', [
            'email' => $user->email,
            'otp_code' => $wrong,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response->assertSessionHasErrors('otp_code');
        $this->assertTrue(Hash::check('original-pass', $user->fresh()->password));
    }
}
