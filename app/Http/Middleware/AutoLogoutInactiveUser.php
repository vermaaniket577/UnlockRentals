<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLogoutInactiveUser
{
    /**
     * Handle an incoming request.
     * Automatically log out users if inactive for more than 15 minutes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = session('last_user_activity_time');
            $timeout = 15 * 60; // 15 minutes in seconds

            if ($lastActivity && (time() - $lastActivity > $timeout)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'You were logged out due to inactivity.',
                        'redirect' => route('login', ['inactivity' => 1]),
                    ], 401);
                }

                return redirect()->route('login', ['inactivity' => 1]);
            }

            session(['last_user_activity_time' => time()]);
        }

        return $next($request);
    }
}
