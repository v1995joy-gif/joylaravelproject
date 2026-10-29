<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\OtpSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Ports the nexus_broker_tms OTP rules (api/v1.0.0/utils/otp_helper.php):
 * 6-digit random_int code, hashed at rest, prior codes invalidated on
 * reissue, single-use, attempt-capped, TTL per purpose.
 */
class OtpService
{
    private const TTL = [
        OtpSession::PURPOSE_EMAIL_VERIFICATION => 180,
        OtpSession::PURPOSE_PASSWORD_RESET => 600,
    ];

    private const MAX_ATTEMPTS = 5;
    private const CODE_LENGTH = 6;

    public function generate(string $email, string $purpose, ?User $user = null, ?string $ip = null, ?string $userAgent = null): OtpSession
    {
        $email = strtolower(trim($email));

        $ttl = self::ttlFor($purpose);
        $code = $this->randomDigits(self::CODE_LENGTH);

        $session = OtpSession::create([
            'session_id' => bin2hex(random_bytes(16)),
            'email' => $email,
            'user_id' => $user?->id,
            'purpose' => $purpose,
            'otp_hash' => Hash::make($code),
            'expires_at' => now()->addSeconds($ttl),
            'max_attempts' => self::MAX_ATTEMPTS,
            'ip_address' => $ip ?? request()?->ip(),
            'user_agent' => $userAgent ?? request()?->userAgent(),
        ]);

        try {
            Mail::to($email)->send(new OtpMail($code, $purpose, $ttl));
        } catch (\Throwable $e) {
            $session->delete();
            throw $e;
        }

        // Prior codes are retired only after the replacement is on its way —
        // invalidating before send would lock the user out if every
        // transport fails (the failover chain may still have delivered nothing).
        OtpSession::where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->where('id', '!=', $session->id)
            ->update(['used_at' => now()]);

        return $session;
    }

    /**
     * Verify by the session token handed out at request time (password reset).
     * Returns the consumed session on success, null otherwise.
     */
    public function verifyBySessionId(string $sessionId, string $purpose, string $code): ?OtpSession
    {
        $session = OtpSession::where('session_id', $sessionId)
            ->where('purpose', $purpose)
            ->first();

        return $this->check($session, $code) ? $session : null;
    }

    /**
     * Verify the latest active code for an email (email verification).
     */
    public function verifyForEmail(string $email, string $purpose, string $code): ?OtpSession
    {
        // Check every live code, newest first — concurrent generate() calls can
        // leave two valid rows, and whichever the user typed should work.
        $sessions = OtpSession::where('email', strtolower(trim($email)))
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->latest('id')
            ->limit(3)
            ->get();

        foreach ($sessions as $session) {
            if ($this->check($session, $code)) {
                return $session;
            }
        }

        return null;
    }

    private function check(?OtpSession $session, string $code): bool
    {
        if (! $session) {
            return false;
        }

        // Atomically claim one attempt — a guarded UPDATE so concurrent
        // guesses can't all slip through the read-modify-write gap.
        $claimed = OtpSession::where('id', $session->id)
            ->whereNull('used_at')
            ->where('expires_at', '>=', now()->subSeconds(5))
            ->where('attempts', '<', $session->max_attempts)
            ->update([
                'attempts' => DB::raw('attempts + 1'),
                'last_attempt_at' => now(),
            ]);

        if ($claimed === 0) {
            return false;
        }

        if (! Hash::check($code, $session->otp_hash)) {
            return false;
        }

        // Only one request may consume the code.
        return OtpSession::where('id', $session->id)
            ->whereNull('used_at')
            ->update(['used_at' => now()]) === 1;
    }

    private function randomDigits(int $length): string
    {
        $digits = '';
        for ($i = 0; $i < $length; $i++) {
            $digits .= (string) random_int(0, 9);
        }

        return $digits;
    }

    public static function ttlFor(string $purpose): int
    {
        return self::TTL[$purpose] ?? 300;
    }
}
