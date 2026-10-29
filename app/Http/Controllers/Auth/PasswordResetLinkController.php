<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpSession;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Email a 6-digit reset code (replaces the token-link flow).
     * Same redirect whether the account exists or not — no enumeration.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower($request->string('email')->toString());
        $user = User::where('email', $email)->first();

        if ($user) {
            try {
                $session = app(OtpService::class)->generate(
                    $email,
                    OtpSession::PURPOSE_PASSWORD_RESET,
                    $user,
                );
                $request->session()->put('password_reset_session_id', $session->session_id);
                $request->session()->put('password_reset_email', $email);
            } catch (\Throwable $e) {
                // All transports down — no code exists, so say so instead of
                // flashing a success that strands the user on the code screen.
                report($e);

                return back()->withInput()->with('error',
                    __('The mail service is unreachable right now — please try again shortly.'));
            }
        }
        // Unknown emails keep any prior session binding so a typo'd resubmit
        // doesn't kill the code that was already mailed.

        return redirect()
            ->route('password.reset')
            ->with('status', __('If that email is registered, a reset code has been sent.'));
    }
}
