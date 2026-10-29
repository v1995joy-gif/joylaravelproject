<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\OtpSession;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpTest extends TestCase
{
    use RefreshDatabase;

    private function service(): OtpService
    {
        return app(OtpService::class);
    }

    private function lastCode(string $email): string
    {
        return Mail::sent(OtpMail::class, fn ($m) => $m->hasTo($email))->last()->code;
    }

    private function wrongCode(string $correct): string
    {
        return $correct === '999999' ? '888888' : '999999';
    }

    public function test_generate_creates_hashed_session_and_sends_mail(): void
    {
        Mail::fake();

        $session = $this->service()->generate('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION);

        Mail::assertSent(OtpMail::class, fn ($m) => $m->hasTo('a@b.com'));
        $this->assertNotEmpty($session->session_id);
        $this->assertNotEquals($this->lastCode('a@b.com'), $session->otp_hash);
        $this->assertTrue(Hash::check($this->lastCode('a@b.com'), $session->otp_hash));
    }

    public function test_new_request_invalidates_prior_unused_codes(): void
    {
        Mail::fake();

        $service = $this->service();
        $first = $service->generate('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION);
        $firstCode = $this->lastCode('a@b.com');

        $service->generate('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION);

        $this->assertNotNull($first->fresh()->used_at);
        $this->assertNull($service->verifyForEmail('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION, $firstCode));
    }

    public function test_wrong_code_increments_attempts(): void
    {
        Mail::fake();

        $service = $this->service();
        $session = $service->generate('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION);
        $wrong = $this->wrongCode($this->lastCode('a@b.com'));

        $this->assertNull($service->verifyForEmail('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION, $wrong));
        $this->assertSame(1, $session->fresh()->attempts);
        $this->assertNotNull($session->fresh()->last_attempt_at);
    }

    public function test_lockout_after_max_attempts(): void
    {
        Mail::fake();

        $service = $this->service();
        $session = $service->generate('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION);
        $correct = $this->lastCode('a@b.com');
        $wrong = $this->wrongCode($correct);

        for ($i = 0; $i < 5; $i++) {
            $service->verifyForEmail('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION, $wrong);
        }

        $this->assertNull($service->verifyForEmail('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION, $correct));
        $this->assertNull($session->fresh()->used_at);
    }

    public function test_expired_code_is_rejected(): void
    {
        Mail::fake();

        $service = $this->service();
        $session = $service->generate('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION);
        $code = $this->lastCode('a@b.com');

        $session->update(['expires_at' => now()->subMinute()]);

        $this->assertNull($service->verifyForEmail('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION, $code));
    }

    public function test_code_is_single_use(): void
    {
        Mail::fake();

        $service = $this->service();
        $session = $service->generate('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION);
        $code = $this->lastCode('a@b.com');

        $this->assertNotNull($service->verifyForEmail('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION, $code));
        $this->assertNull($service->verifyForEmail('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION, $code));
    }

    public function test_verify_by_session_id(): void
    {
        Mail::fake();

        $service = $this->service();
        $session = $service->generate('a@b.com', OtpSession::PURPOSE_PASSWORD_RESET);
        $code = $this->lastCode('a@b.com');

        $this->assertNotNull($service->verifyBySessionId($session->session_id, OtpSession::PURPOSE_PASSWORD_RESET, $code));
    }

    public function test_session_id_is_purpose_scoped(): void
    {
        Mail::fake();

        $service = $this->service();
        $session = $service->generate('a@b.com', OtpSession::PURPOSE_PASSWORD_RESET);
        $code = $this->lastCode('a@b.com');

        $this->assertNull($service->verifyBySessionId($session->session_id, OtpSession::PURPOSE_EMAIL_VERIFICATION, $code));
    }

    public function test_ttl_matches_purpose(): void
    {
        $this->assertSame(180, OtpService::ttlFor(OtpSession::PURPOSE_EMAIL_VERIFICATION));
        $this->assertSame(600, OtpService::ttlFor(OtpSession::PURPOSE_PASSWORD_RESET));
    }

    public function test_session_records_audit_fields(): void
    {
        Mail::fake();

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])
            ->withHeader('User-Agent', 'OtpTest/1.0');

        // generate() resolves request() inside the app context
        $session = $this->service()->generate('a@b.com', OtpSession::PURPOSE_EMAIL_VERIFICATION, null, '203.0.113.7', 'OtpTest/1.0');

        $this->assertSame('203.0.113.7', $session->ip_address);
        $this->assertSame('OtpTest/1.0', $session->user_agent);
    }
}
