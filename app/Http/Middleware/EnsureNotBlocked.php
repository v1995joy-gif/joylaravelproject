<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Force-logout for accounts blocked mid-session — the block check in
 * LoginRequest only stops NEW logins; this evicts live sessions too.
 */
class EnsureNotBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->blocked_at) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been blocked. Contact the shop for assistance.',
            ]);
        }

        return $next($request);
    }
}
