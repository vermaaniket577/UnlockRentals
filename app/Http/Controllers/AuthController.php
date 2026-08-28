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

        // Capture intended URL before redirecting to social provider (if not already set by middleware)
        if (!session()->has('url.intended')) {
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

        if (session()->has('url.intended')) {
            $intended = session()->pull('url.intended');
            return redirect($intended)->with('success', 'Welcome, ' . $user->name . '!');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home')->with('success', 'Welcome, ' . $user->name . '!');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
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
            $request->session()->regenerate();

            $targetUrl = $request->input('redirect') ?: session()->pull('url.intended', route('home'));
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
        return view('auth.register');
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
            'role' => 'required|in:tenant,owner',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        Auth::login($user);

        $targetUrl = $request->input('redirect') ?: session()->pull('url.intended', route('home'));
        if ($user->isAdmin()) {
            $targetUrl = route('admin.dashboard');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Welcome to UnlockRentals! Your account has been created.',
                'redirect' => $targetUrl,
            ]);
        }

        return redirect($targetUrl)
            ->with('success', 'Welcome to UnlockRentals! Your account has been created.');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
