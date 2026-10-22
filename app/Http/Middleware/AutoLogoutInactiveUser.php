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
     * In mobile apps or once logged in, do NOT automatically log out users.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // NEVER auto-logout mobile app users, mobile devices, or remembered users
            if ($this->shouldSkipAutoLogout($request)) {
                session(['last_user_activity_time' => time()]);
                return $next($request);
            }

            // Only enforce inactivity logout if explicitly enabled in configuration
            if (!config('session.auto_logout_inactivity', false)) {
                session(['last_user_activity_time' => time()]);
                return $next($request);
            }

            $lastActivity = session('last_user_activity_time');
            $timeout = (int) config('session.inactivity_timeout_seconds', 15 * 60);

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

    /**
     * Determine if auto-logout should be completely skipped.
     */
    protected function shouldSkipAutoLogout(Request $request): bool
    {
        $userAgent = $request->userAgent() ?? '';

        // Mobile apps (native Android or iOS WebView / custom User Agent)
        if (str_contains($userAgent, 'UnlockRentals') ||
            str_contains($userAgent, 'UnlockRentalsMobileApp') ||
            str_contains($userAgent, 'UnlockRentalsApp') ||
            str_contains($userAgent, 'UnlockRentalsIOSApp')) {
            return true;
        }

        // Mobile app indicators via query, header, session, or persistent cookie
        if ($request->query('app') === '1' ||
            $request->header('X-UnlockRentals-App') === '1' ||
            session('is_mobile_app') === true ||
            $request->cookie('is_mobile_app') === '1') {
            return true;
        }

        // Mobile devices / phones / tablets
        if (preg_match('/(android|iphone|ipad|ipod|mobile)/i', $userAgent)) {
            return true;
        }

        // Users who have persistent auth recaller cookie (Remember Me)
        if ($request->hasCookie(Auth::getRecallerName())) {
            return true;
        }

        // Regular users once logged in should never be logged out due to inactivity
        if (Auth::user() && !Auth::user()->isAdmin()) {
            return true;
        }

        return false;
    }
}
