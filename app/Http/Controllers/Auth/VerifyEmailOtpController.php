<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpSession;
use App\Services\OtpService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VerifyEmailOtpController extends Controller
{
    /**
     * Confirm the 6-digit email verification code (OTP equivalent of the
     * signed verify-email/{id}/{hash} link).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'digits:6'],
        ]);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $session = app(OtpService::class)->verifyForEmail(
            $user->email,
            OtpSession::PURPOSE_EMAIL_VERIFICATION,
            $request->string('otp_code')->toString(),
        );

        if (! $session) {
            throw ValidationException::withMessages([
                'otp_code' => __('The code is invalid or has expired.'),
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
