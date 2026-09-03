<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Send OTP to a phone number.
     * POST /otp/send
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone'   => 'required|string|min:10|max:15',
            'purpose' => 'required|in:register,login,update',
        ]);

        $phone   = $request->input('phone');
        $purpose = $request->input('purpose');

        // For login purpose: ensure user with this phone exists and is not admin
        if ($purpose === 'login') {
            $user = User::byPhone($phone)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No account found with this phone number. Please register first.',
                ], 422);
            }

            if ($user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin accounts cannot log in with mobile number. Please sign in using your Email and Password.',
                ], 422);
            }
        }

        // For register purpose: ensure phone and email are not already in use
        if ($purpose === 'register') {
            $existingUser = User::byPhone($phone)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'This phone number is already in use. Please enter a different number or login.',
                ], 422);
            }

            if ($request->filled('email')) {
                $emailTaken = User::where('email', trim($request->input('email')))->exists();
                if ($emailTaken) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This email address is already registered. Please enter a different email or login.',
                    ], 422);
                }
            }
        }

        try {
            $result = $this->otpService->sendOtp($phone, $purpose);
            return response()->json($result, $result['success'] ? 200 : 429);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to send OTP at the moment. Please verify your details and try again.',
            ], 500);
        }
    }

    /**
     * Verify an OTP.
     * POST /otp/verify
     */
    public function verify(Request $request)
    {
        $otpLength = (int) \App\Models\Setting::get('otp_length', config('otp.otp_length', 4));
        $request->validate([
            'phone'   => 'required|string|min:10|max:15',
            'otp'     => 'required|string|size:' . $otpLength,
            'purpose' => 'required|in:register,login,update',
        ]);

        $result = $this->otpService->verifyOtp(
            $request->input('phone'),
            $request->input('otp'),
            $request->input('purpose')
        );

        // Store verification in session for registration flow
        if ($result['verified'] && $request->input('purpose') === 'register') {
            session(['otp_verified_phone' => $this->normalizePhone($request->input('phone'))]);
        }

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Login via phone + OTP (alternative to email/password).
     * POST /otp/login
     */
    public function loginWithOtp(Request $request)
    {
        $otpLength = (int) \App\Models\Setting::get('otp_length', config('otp.otp_length', 4));
        $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'otp'   => 'required|string|size:' . $otpLength,
        ]);

        $phone = $request->input('phone');

        // Verify OTP first
        $verifyResult = $this->otpService->verifyOtp($phone, $request->input('otp'), 'login');

        if (!$verifyResult['verified']) {
            return response()->json($verifyResult, 422);
        }

        // Find user by phone
        $user = User::byPhone($phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with this phone number.',
            ], 422);
        }

        if ($user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Admin accounts cannot log in with mobile number. Please sign in using your Email and Password.',
            ], 422);
        }

        // Mark phone as verified if not already
        if (!$user->isPhoneVerified()) {
            $user->update(['phone_verified_at' => now()]);
        }

        // Login the user
        Auth::login($user, true);
        $request->session()->regenerate();
        session()->forget('url.intended');

        $redirect = route('home');

        return response()->json([
            'success'  => true,
            'message'  => 'Welcome back, ' . $user->name . '!',
            'redirect' => $redirect,
        ]);
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        return strlen($digits) >= 10 ? substr($digits, -10) : $digits;
    }
}
