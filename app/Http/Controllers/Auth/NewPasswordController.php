<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpSession;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the OTP + new password form.
     */
    public function create(Request $request): RedirectResponse|View
    {
        if (! $request->session()->has('password_reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', [
            'email' => $request->session()->get('password_reset_email'),
        ]);
    }

    /**
     * Verify the reset code and update the password.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'digits:6'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = strtolower($request->string('email')->toString());
        $sessionId = $request->session()->get('password_reset_session_id');

        // Bind the session to the typed email BEFORE consuming the code —
        // a mistyped email must not burn a valid single-use OTP.
        $pending = is_string($sessionId)
            ? OtpSession::where('session_id', $sessionId)
                ->where('purpose', OtpSession::PURPOSE_PASSWORD_RESET)
                ->first()
            : null;

        if (! $pending || ! hash_equals($pending->email, $email)) {
            throw ValidationException::withMessages([
                'otp_code' => __('The code is invalid or has expired.'),
            ]);
        }

        $otp = app(OtpService::class)->verifyBySessionId(
            $sessionId,
            OtpSession::PURPOSE_PASSWORD_RESET,
            $request->string('otp_code')->toString(),
        );

        if (! $otp) {
            throw ValidationException::withMessages([
                'otp_code' => __('The code is invalid or has expired.'),
            ]);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => __('We can\'t find a user with that email address.'),
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // A password reset invalidates every other live session.
        if (config('session.driver') === 'database') {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        event(new PasswordReset($user));

        $request->session()->forget(['password_reset_session_id', 'password_reset_email']);

        return redirect()->route('login')->with('status', __('Your password has been reset.'));
    }
}
