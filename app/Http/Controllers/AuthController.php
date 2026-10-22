<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Check if request originates from the UnlockRentals Mobile App.
     */
    public static function isMobileApp(Request $request): bool
    {
        $userAgent = $request->userAgent() ?? '';
        return str_contains($userAgent, 'UnlockRentals')
            || str_contains($userAgent, 'UnlockRentalsMobileApp')
            || str_contains($userAgent, 'UnlockRentalsApp')
            || str_contains($userAgent, 'UnlockRentalsIOSApp')
            || $request->query('app') === '1'
            || $request->header('X-UnlockRentals-App') === '1'
            || session('is_mobile_app') === true
            || $request->cookie('is_mobile_app') === '1';
    }

    /**
     * Check if request originates from a mobile app or mobile device.
     */
    public static function isMobileDevice(Request $request): bool
    {
        if (self::isMobileApp($request)) {
            return true;
        }
        $userAgent = $request->userAgent() ?? '';
        return (bool) preg_match('/(android|iphone|ipad|ipod|mobile)/i', $userAgent);
    }

    /**
     * Redirect to social provider.
     */
    public function redirectToProvider($provider)
    {
        if (empty(config("services.$provider.client_id"))) {
            return redirect('/login')->with('error', ucfirst($provider) . ' login is not configured yet. Please contact admin.');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle social provider callback.
     */
    public function handleProviderCallback($provider)
    {
        try {
            // First attempt standard state verification
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            // If session/CSRF state verification failed (common on HTTPS/production), fallback to stateless()
            try {
                $socialUser = Socialite::driver($provider)->stateless()->user();
            } catch (\Exception $statelessException) {
                \Illuminate\Support\Facades\Log::error("Social login failed for [$provider]: " . $e->getMessage() . " | Stateless error: " . $statelessException->getMessage());
                
                $errorMessage = 'Social login failed. ';
                if (str_contains($e->getMessage(), 'invalid_client') || str_contains($statelessException->getMessage(), 'invalid_client')) {
                    $errorMessage .= 'Invalid Client ID or Secret in server configuration. Please check your .env settings.';
                } elseif (str_contains($e->getMessage(), 'redirect_uri_mismatch') || str_contains($statelessException->getMessage(), 'redirect_uri_mismatch')) {
                    $errorMessage .= 'Redirect URI mismatch in Google Cloud Console. Expected: ' . url("/auth/$provider/callback");
                } else {
                    $errorMessage .= 'Please verify your ' . ucfirst($provider) . ' credentials or sign in with your email.';
                }
                
                return redirect('/login')->with('error', $errorMessage);
            }
        }

        $email = $socialUser->getEmail();
        if (empty($email)) {
            return redirect('/login')->with('error', 'Unable to retrieve email from your ' . ucfirst($provider) . ' account. Please log in with email and password.');
        }

        $name = $socialUser->getName() ?: ($socialUser->getNickname() ?: explode('@', $email)[0]);

        // Check if user already exists
        $user = User::where('email', $email)->first();

        if ($user) {
            if (empty($user->avatar) && $socialUser->getAvatar()) {
                $user->avatar = $socialUser->getAvatar();
                $user->save();
            }
            Auth::login($user, true);
        } else {
            // Create new user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'role' => 'tenant', // Default role
                'avatar' => $socialUser->getAvatar(),
            ]);
            Auth::login($user, true);
        }

        $request = request();
        $isMobile = self::isMobileDevice($request);
        $redirectPath = $user->isAdmin() ? '/admin' : '/';

        $request->session()->regenerate();
        session()->forget('url.intended');

        if ($isMobile) {
            session(['is_mobile_app' => true]);
            cookie()->queue(cookie()->forever('is_mobile_app', '1'));
        }

        // If explicitly requested from the native mobile app via app parameter
        if ($request->query('app') === '1' || session('is_mobile_app')) {
            $loginToken = Str::random(48);
            \Illuminate\Support\Facades\Cache::put('app_auth_token_' . $loginToken, [
                'user_id' => $user->id,
                'redirect' => $redirectPath,
            ], 300);

            return response()
                ->view('auth.social-callback', compact('user', 'loginToken', 'redirectPath'))
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        // Direct website redirect
        return redirect($redirectPath)->with('success', 'Welcome, ' . $user->name . '!');
    }

    /**
     * Authenticate mobile application via secure one-time auth token.
     */
    public function loginWithToken(Request $request)
    {
        $token = $request->query('token');
        if ($token && ($payload = \Illuminate\Support\Facades\Cache::pull('app_auth_token_' . $token))) {
            $userId = is_array($payload) ? ($payload['user_id'] ?? null) : $payload;

            if ($userId && ($user = User::find($userId))) {
                Auth::login($user, true);
                $request->session()->regenerate();
                session()->forget('url.intended');
                session(['is_mobile_app' => true]);
                cookie()->queue(cookie()->forever('is_mobile_app', '1'));
                $target = $user->isAdmin() ? route('admin.dashboard') : route('home');
                return redirect($target)->with('success', 'Welcome, ' . $user->name . '!');
            }
        }

        return redirect('/')->with('error', 'Session expired or invalid login token.');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        session()->forget('url.intended');

        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $isMobile = self::isMobileDevice($request);
        // On mobile apps or mobile devices, ALWAYS keep user logged in with remember token
        $remember = $request->boolean('remember') || $isMobile;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            session()->forget('url.intended');

            if ($isMobile) {
                session(['is_mobile_app' => true]);
                cookie()->queue(cookie()->forever('is_mobile_app', '1'));
            }

            $targetUrl = auth()->user()->isAdmin() ? route('admin.dashboard') : route('home');

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Welcome back, ' . auth()->user()->name . '!',
                    'redirect' => $targetUrl,
                ]);
            }

            return redirect($targetUrl)->with('success', 'Welcome back, ' . auth()->user()->name . '!');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
            ], 422);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        session()->forget('url.intended');

        return response()
            ->view('auth.register')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'nullable|in:tenant,owner',
            'phone' => 'required|string|max:20',
        ]);

        // Verify phone was OTP-verified in this session
        $normalizedPhone = preg_replace('/[^0-9]/', '', $validated['phone']);
        $normalizedPhone = strlen($normalizedPhone) >= 10 ? substr($normalizedPhone, -10) : $normalizedPhone;
        $sessionPhone = session('otp_verified_phone');

        $phoneVerifiedAt = null;
        if ($sessionPhone && $sessionPhone === $normalizedPhone) {
            $phoneVerifiedAt = now();
            session()->forget('otp_verified_phone');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'tenant',
            'phone' => $validated['phone'],
            'phone_verified_at' => $phoneVerifiedAt,
        ]);

        $isMobile = self::isMobileDevice($request);
        Auth::login($user, true);

        $request->session()->regenerate();
        session()->forget('url.intended');

        if ($isMobile) {
            session(['is_mobile_app' => true]);
            cookie()->queue(cookie()->forever('is_mobile_app', '1'));
        }

        $targetUrl = $user->isAdmin() ? route('admin.dashboard') : route('home');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Account created successfully!',
                'redirect' => $targetUrl,
            ]);
        }

        return redirect($targetUrl)->with('success', 'Account created successfully! Welcome to UnlockRentals.');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $redirect = '/';
        $referer = $request->headers->get('referer');
        if ($referer && !str_contains($referer, 'dashboard') && !str_contains($referer, 'admin') && !str_contains($referer, 'login') && !str_contains($referer, 'register')) {
            $parsed = parse_url($referer, PHP_URL_PATH);
            $query = parse_url($referer, PHP_URL_QUERY);
            $redirect = ($parsed ?: '/') . ($query ? '?' . $query : '');
        }

        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json' || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
                'redirect' => $redirect,
            ]);
        }

        return redirect($redirect)->with('success', 'You have been logged out.');
    }
}
