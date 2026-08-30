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
     * Redirect to social provider.
     */
    public function redirectToProvider($provider)
    {
        if (empty(config("services.$provider.client_id"))) {
            return redirect('/login')->with('error', ucfirst($provider) . ' login is not configured yet. Please contact admin.');
        }

        if (request()->filled('redirect')) {
            session(['url.intended' => request('redirect')]);
        } elseif (!session()->has('url.intended')) {
            $previous = url()->previous();
            if ($previous && 
                !str_contains($previous, '/login') && 
                !str_contains($previous, '/register') && 
                !str_contains($previous, '/auth/')) {
                session(['url.intended' => $previous]);
            }
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

        $targetUrl = session()->pull('url.intended');
        if ($user->isAdmin()) {
            $redirectPath = '/admin';
        } else {
            $redirectPath = '/';
            if ($targetUrl && !str_contains($targetUrl, '/login') && !str_contains($targetUrl, '/register') && !str_contains($targetUrl, '/auth/')) {
                $parsed = parse_url($targetUrl, PHP_URL_PATH);
                $query = parse_url($targetUrl, PHP_URL_QUERY);
                $redirectPath = ($parsed ?: '/') . ($query ? '?' . $query : '');
            }
        }

        // Use inline JS redirect instead of HTTP 302 to stay inside Android WebView
        // HTTP 302 with full domain URLs triggers Android intent filters and opens Chrome
        $userName = e($user->name);
        return response("<!DOCTYPE html><html><head><meta charset='utf-8'><title>Welcome</title></head><body><script>window.location.replace(" . json_encode($redirectPath) . ");</script><noscript><meta http-equiv='refresh' content='0;url=" . e($redirectPath) . "'></noscript><p>Redirecting...</p></body></html>", 200)
            ->header('Content-Type', 'text/html')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (request()->filled('redirect')) {
            session(['url.intended' => request('redirect')]);
        }

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

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $targetUrl = $request->input('redirect') ?: session('url.intended') ?: route('home');
            
            $request->session()->regenerate();
            session()->forget('url.intended');

            if (auth()->user()->isAdmin()) {
                $targetUrl = route('admin.dashboard');
            }

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
        if (request()->filled('redirect')) {
            session(['url.intended' => request('redirect')]);
        }

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
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'tenant',
            'phone' => $validated['phone'] ?? null,
        ]);

        Auth::login($user);

        $targetUrl = $request->input('redirect') ?: session('url.intended') ?: route('home');
        $request->session()->regenerate();
        session()->forget('url.intended');

        if ($user->isAdmin()) {
            $targetUrl = route('admin.dashboard');
        }

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
