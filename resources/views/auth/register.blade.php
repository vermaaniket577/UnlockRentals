@extends('layouts.app')

@section('title', 'Create Account - UnlockRentals')
@section('robots', 'noindex, nofollow')

@section('content')

<section class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 relative overflow-hidden bg-slate-50 dark:bg-slate-950" id="register-section">
    {{-- Ambient Background Glows --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-blue-100/60 via-indigo-50/30 to-transparent dark:from-blue-950/30 dark:via-transparent pointer-events-none blur-3xl -z-10"></div>
    <div class="absolute -top-24 right-1/4 w-96 h-96 bg-blue-400/10 dark:bg-blue-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute bottom-10 left-1/4 w-96 h-96 bg-indigo-400/10 dark:bg-indigo-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-lg relative z-10">

        {{-- Top Brand Icon / Badge --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 group transition-transform duration-200 hover:scale-[1.02]" title="UnlockRentals">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-600/25 ring-4 ring-blue-50 dark:ring-blue-900/30 transition-all duration-300 group-hover:shadow-blue-600/35">
                    <i class="ph-bold ph-user-plus text-xl"></i>
                </div>
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-4">Create your account</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">Join thousands finding verified homes & commercial rentals</p>
        </div>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-[0_20px_50px_rgba(15,23,42,0.06)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.4)] p-7 sm:p-9 transition-all duration-300">

            {{-- Client Error Banner Container --}}
            <div id="register-error-banner" class="hidden mb-5 p-3.5 bg-rose-50 dark:bg-rose-900/30 border border-rose-100 dark:border-rose-800 rounded-xl flex items-center gap-2.5 text-xs font-semibold text-rose-700 dark:text-rose-300">
                <i class="ph-bold ph-warning-circle text-base flex-shrink-0"></i>
                <span id="register-error-text"></span>
            </div>

            {{-- Form Body --}}
            <form method="POST" action="{{ route('register') }}" id="register-form" class="space-y-4">
                @csrf

                <input type="hidden" name="role" value="tenant">
                <input type="hidden" name="phone_verified" id="reg-phone-verified-input" value="0">

                {{-- Two columns: Name + Phone --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="register-name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Full Name <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph-bold ph-user text-base"></i>
                            </div>
                            <input type="text"
                                   name="name"
                                   id="register-name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   placeholder="John Doe"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 dark:bg-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-850 border @error('name') border-rose-300 dark:border-rose-700 focus:ring-rose-500/10 focus:border-rose-500 @else border-slate-200 dark:border-slate-700 focus:border-blue-600 focus:ring-blue-600/10 @enderror rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 transition-all duration-200 shadow-xs">
                        </div>
                        @error('name')
                            <p class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1 flex items-center gap-1">
                                <i class="ph-bold ph-warning text-xs"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="register-phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Phone Number <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph-bold ph-phone text-base"></i>
                            </div>
                            <input type="tel"
                                   name="phone"
                                   id="register-phone"
                                   value="{{ old('phone') }}"
                                   required
                                   placeholder="+91 98765 43210"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 dark:bg-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-850 border @error('phone') border-rose-300 dark:border-rose-700 focus:ring-rose-500/10 focus:border-rose-500 @else border-slate-200 dark:border-slate-700 focus:border-blue-600 focus:ring-blue-600/10 @enderror rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 transition-all duration-200 shadow-xs">
                        </div>
                        @error('phone')
                            <p class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1 flex items-center gap-1">
                                <i class="ph-bold ph-warning text-xs"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Email Address --}}
                <div>
                    <label for="register-email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="ph-bold ph-envelope text-base"></i>
                        </div>
                        <input type="email"
                               name="email"
                               id="register-email"
                               value="{{ old('email') }}"
                               required
                               placeholder="you@example.com"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 dark:bg-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-850 border @error('email') border-rose-300 dark:border-rose-700 focus:ring-rose-500/10 focus:border-rose-500 @else border-slate-200 dark:border-slate-700 focus:border-blue-600 focus:ring-blue-600/10 @enderror rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 transition-all duration-200 shadow-xs">
                    </div>
                    @error('email')
                        <p class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1.5 flex items-center gap-1">
                            <i class="ph-bold ph-warning text-xs"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password + Confirm Password side by side --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="register-password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph-bold ph-lock-key text-base"></i>
                            </div>
                            <input type="password"
                                   name="password"
                                   id="register-password"
                                   required
                                   placeholder="Min 8 chars"
                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50/70 dark:bg-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-850 border @error('password') border-rose-300 dark:border-rose-700 focus:ring-rose-500/10 focus:border-rose-500 @else border-slate-200 dark:border-slate-700 focus:border-blue-600 focus:ring-blue-600/10 @enderror rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 transition-all duration-200 shadow-xs">
                            <button type="button"
                                    onclick="togglePassword('register-password', this)"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors focus:outline-none"
                                    aria-label="Toggle password visibility">
                                <i class="ph-bold ph-eye eye-open text-base"></i>
                                <i class="ph-bold ph-eye-slash eye-closed text-base hidden"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1 flex items-center gap-1">
                                <i class="ph-bold ph-warning text-xs"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="register-password-confirm" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Confirm Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph-bold ph-shield-check text-base"></i>
                            </div>
                            <input type="password"
                                   name="password_confirmation"
                                   id="register-password-confirm"
                                   required
                                   placeholder="Repeat password"
                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50/70 dark:bg-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-850 border border-slate-200 dark:border-slate-700 focus:border-blue-600 focus:ring-blue-600/10 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 transition-all duration-200 shadow-xs">
                            <button type="button"
                                    onclick="togglePassword('register-password-confirm', this)"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors focus:outline-none"
                                    aria-label="Toggle confirm password visibility">
                                <i class="ph-bold ph-eye eye-open text-base"></i>
                                <i class="ph-bold ph-eye-slash eye-closed text-base hidden"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Terms Checkbox --}}
                <div class="pt-1">
                    <label class="flex items-start gap-2.5 cursor-pointer select-none group">
                        <input type="checkbox"
                               id="register-terms"
                               required
                               class="w-4 h-4 mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500/20 accent-blue-600 cursor-pointer">
                        <span class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-semibold hover:underline" title="Terms of Service">Terms of Service</a> and <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-semibold hover:underline" title="Privacy Policy">Privacy Policy</a>
                        </span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                            id="register-submit"
                            class="w-full py-3 sm:py-3.5 px-6 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] shadow-lg shadow-blue-600/25 hover:shadow-blue-600/35 transition-all duration-200 flex items-center justify-center gap-2">
                        <span id="register-btn-text">Create Free Account</span>
                        <i class="ph-bold ph-arrow-right text-sm" id="register-btn-icon"></i>
                    </button>
                </div>
            </form>

            {{-- Bottom Sign In Link --}}
            <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 text-center">
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:underline transition-colors" title="Sign In">
                        Sign in
                    </a>
                </p>
            </div>
        </div>

        {{-- Trust & Security Badges --}}
        <div class="flex items-center justify-center gap-6 mt-8">
            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-400 dark:text-slate-500">
                <i class="ph-bold ph-shield-check text-sm text-emerald-500"></i>
                <span>256-Bit SSL</span>
            </div>
            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-400 dark:text-slate-500">
                <i class="ph-bold ph-seal-check text-sm text-blue-500"></i>
                <span>Verified Direct Owners</span>
            </div>
            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-400 dark:text-slate-500">
                <i class="ph-bold ph-lock-key text-sm text-amber-500"></i>
                <span>Zero Brokerage</span>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- STANDARD OTP VERIFICATION MODAL POPUP --}}
{{-- ========================================================================= --}}
<div id="otp-modal-backdrop" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0">
    <div id="otp-modal-card" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl w-full max-w-md p-7 sm:p-8 transform scale-95 transition-all duration-300 relative">
        
        {{-- Close Button --}}
        <button type="button" id="btn-close-otp-modal" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <i class="ph-bold ph-x text-lg"></i>
        </button>

        {{-- Header Icon --}}
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl mx-auto mb-3.5 shadow-lg shadow-emerald-500/10 ring-4 ring-emerald-50 dark:ring-emerald-900/20">
                <i class="ph-bold ph-whatsapp-logo"></i>
            </div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Verify Mobile Number</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                We sent a 4-digit verification code to<br>
                <span id="modal-phone-display" class="font-extrabold text-slate-900 dark:text-white font-mono text-sm">+91 XXXXX XXXXX</span>
            </p>
        </div>

        {{-- Error / Status message inside modal --}}
        <div id="modal-otp-status" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2"></div>

        {{-- 4-Digit OTP Boxes (Clean Standard Size) --}}
        <div class="mb-5">
            <label class="block text-center text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2.5">Enter 4-Digit Code</label>
            <div id="modal-otp-digits" class="flex justify-center gap-2.5 sm:gap-3.5">
                <input type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" class="otp-digit w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-extrabold bg-slate-50 dark:bg-slate-800/90 border-2 border-slate-200 dark:border-slate-700 rounded-2xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/15 focus:outline-none transition-all text-slate-900 dark:text-white shadow-xs" autofocus>
                <input type="text" inputmode="numeric" maxlength="1" class="otp-digit w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-extrabold bg-slate-50 dark:bg-slate-800/90 border-2 border-slate-200 dark:border-slate-700 rounded-2xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/15 focus:outline-none transition-all text-slate-900 dark:text-white shadow-xs" autocomplete="off">
                <input type="text" inputmode="numeric" maxlength="1" class="otp-digit w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-extrabold bg-slate-50 dark:bg-slate-800/90 border-2 border-slate-200 dark:border-slate-700 rounded-2xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/15 focus:outline-none transition-all text-slate-900 dark:text-white shadow-xs" autocomplete="off">
                <input type="text" inputmode="numeric" maxlength="1" class="otp-digit w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl sm:text-3xl font-extrabold bg-slate-50 dark:bg-slate-800/90 border-2 border-slate-200 dark:border-slate-700 rounded-2xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/15 focus:outline-none transition-all text-slate-900 dark:text-white shadow-xs" autocomplete="off">
            </div>
        </div>

        {{-- Verify & Submit Button --}}
        <div class="space-y-3">
            <button type="button" id="btn-verify-and-register"
                    class="w-full py-3.5 px-6 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] shadow-lg shadow-emerald-600/25 transition-all duration-200 flex items-center justify-center gap-2">
                <i class="ph-bold ph-check-circle text-base"></i>
                <span id="btn-verify-text">Verify & Create Account</span>
            </button>

            {{-- Resend & Change Number Row --}}
            <div class="flex items-center justify-between text-xs pt-1 px-1">
                <div>
                    <span id="modal-otp-countdown" class="font-mono text-slate-500 dark:text-slate-400">Resend in 60s</span>
                    <button type="button" id="btn-resend-otp" class="hidden text-emerald-600 dark:text-emerald-400 font-bold hover:underline flex items-center gap-1">
                        <i class="ph-bold ph-arrow-clockwise"></i> Resend OTP
                    </button>
                </div>
                <button type="button" id="btn-change-number" class="text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 font-semibold hover:underline">
                    Edit phone number
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/otp-verification.js') }}?v={{ filemtime(public_path('js/otp-verification.js')) }}"></script>
<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const eyeOpen = btn.querySelector('.eye-open');
    const eyeClosed = btn.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const registerForm   = document.getElementById('register-form');
    const submitBtn      = document.getElementById('register-submit');
    const btnText        = document.getElementById('register-btn-text');
    const btnIcon        = document.getElementById('register-btn-icon');
    const errorBanner    = document.getElementById('register-error-banner');
    const errorText      = document.getElementById('register-error-text');

    const modalBackdrop  = document.getElementById('otp-modal-backdrop');
    const modalCard      = document.getElementById('otp-modal-card');
    const btnCloseModal  = document.getElementById('btn-close-otp-modal');
    const btnChangeNum   = document.getElementById('btn-change-number');
    const phoneDisplay   = document.getElementById('modal-phone-display');
    const modalStatus    = document.getElementById('modal-otp-status');
    const digitsContainer= document.getElementById('modal-otp-digits');
    const btnVerifyModal = document.getElementById('btn-verify-and-register');
    const btnVerifyText  = document.getElementById('btn-verify-text');
    const btnResend      = document.getElementById('btn-resend-otp');
    const countdownEl    = document.getElementById('modal-otp-countdown');
    const verifiedInput  = document.getElementById('reg-phone-verified-input');

    let countdownTimer = null;
    let isPhoneVerified = false;

    // Helper: Show Form Level Error and scroll to top to let user update details
    function showFormError(msg, targetInputId = null) {
        // Reset submit button state immediately
        isRequestingOtp = false;
        submitBtn.disabled = false;
        btnText.textContent = 'Create Free Account';
        btnIcon.className = 'ph-bold ph-arrow-right text-sm';

        if (!msg) {
            errorBanner.classList.add('hidden');
            return;
        }
        errorText.textContent = msg;
        errorBanner.classList.remove('hidden');

        // Scroll to error banner / top smoothly so user can see and update details
        errorBanner.scrollIntoView({ behavior: 'smooth', block: 'center' });

        if (targetInputId) {
            const targetEl = document.getElementById(targetInputId);
            if (targetEl) {
                targetEl.focus();
                targetEl.classList.add('border-rose-500', 'ring-4', 'ring-rose-500/20');
                setTimeout(() => {
                    targetEl.classList.remove('border-rose-500', 'ring-4', 'ring-rose-500/20');
                }, 3000);
            }
        }
    }

    // Helper: Show Modal Status Error/Success
    function showModalStatus(msg, type = 'error') {
        if (!msg) {
            modalStatus.classList.add('hidden');
            return;
        }
        modalStatus.textContent = msg;
        modalStatus.className = 'mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2 ' +
            (type === 'error' ? 'bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800');
        modalStatus.classList.remove('hidden');
    }

    // Modal Open / Close Animation
    function openOtpModal(phone) {
        phoneDisplay.textContent = phone;
        showModalStatus('', '');
        modalBackdrop.classList.remove('hidden');
        setTimeout(() => {
            modalBackdrop.classList.remove('opacity-0');
            modalCard.classList.remove('scale-95');
            modalCard.classList.add('scale-100');
        }, 10);

        // Clear and focus first digit
        const digitInputs = digitsContainer.querySelectorAll('.otp-digit');
        digitInputs.forEach(d => {
            d.value = '';
            d.disabled = false;
        });
        if (btnVerifyModal) btnVerifyModal.disabled = false;
        if (btnCloseModal) btnCloseModal.style.display = 'flex';
        if (digitInputs[0]) digitInputs[0].focus();
    }

    function closeOtpModal() {
        modalBackdrop.classList.add('opacity-0');
        modalCard.classList.remove('scale-100');
        modalCard.classList.add('scale-95');
        setTimeout(() => {
            modalBackdrop.classList.add('hidden');
        }, 300);

        // Re-enable and reset submit button so user can edit details anytime
        isRequestingOtp = false;
        isVerifying = false;
        submitBtn.disabled = false;
        btnText.textContent = 'Create Free Account';
        btnIcon.className = 'ph-bold ph-arrow-right text-sm';
    }

    if (btnCloseModal) btnCloseModal.addEventListener('click', closeOtpModal);
    if (btnChangeNum) btnChangeNum.addEventListener('click', () => {
        closeOtpModal();
        const pInput = document.getElementById('register-phone');
        if (pInput) {
            pInput.focus();
            pInput.select();
        }
    });

    // Clear error banner on any form input
    document.querySelectorAll('#register-form input').forEach(input => {
        input.addEventListener('input', () => {
            if (!errorBanner.classList.contains('hidden')) {
                errorBanner.classList.add('hidden');
            }
        });
    });

    // Auto-advance digit boxes inside modal
    const digitInputs = digitsContainer.querySelectorAll('.otp-digit');
    const btnVerifyIcon = btnVerifyModal.querySelector('i');
    let isVerifying = false;
    let isRequestingOtp = false;

    digitInputs.forEach((input, idx) => {
        input.addEventListener('input', function () {
            const raw = this.value.replace(/[^0-9]/g, '');
            if (raw.length > 1) {
                // Mobile OS native keyboard autofill (one-time-code)
                const chars = raw.slice(0, digitInputs.length).split('');
                chars.forEach((c, i) => {
                    if (digitInputs[i]) digitInputs[i].value = c;
                });
                if (digitInputs[digitInputs.length - 1]) digitInputs[digitInputs.length - 1].focus();
                const fullOtp = getEnteredOtp();
                if (fullOtp.length === digitInputs.length) {
                    verifyAndCompleteRegistration();
                }
                return;
            }

            this.value = raw.slice(0, 1);
            if (this.value && idx < digitInputs.length - 1) {
                digitInputs[idx + 1].focus();
            }
            // Auto trigger verify when all digits filled
            const fullOtp = getEnteredOtp();
            if (fullOtp.length === digitInputs.length) {
                verifyAndCompleteRegistration();
            }
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && idx > 0) {
                digitInputs[idx - 1].focus();
                digitInputs[idx - 1].value = '';
            }
        });

        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const maxLen = digitInputs.length || 4;
            const pasted = (e.clipboardData.getData('text') || '').replace(/[^0-9]/g, '').slice(0, maxLen);
            pasted.split('').forEach((char, i) => {
                if (digitInputs[i]) digitInputs[i].value = char;
            });
            if (pasted.length === maxLen) {
                verifyAndCompleteRegistration();
            } else if (digitInputs[pasted.length]) {
                digitInputs[pasted.length].focus();
            }
        });
    });

    function getEnteredOtp() {
        return Array.from(digitInputs).map(d => d.value).join('');
    }

    // Countdown Timer logic
    function startCountdown(seconds = 60) {
        if (countdownTimer) clearInterval(countdownTimer);
        let remaining = seconds;
        countdownEl.classList.remove('hidden');
        btnResend.classList.add('hidden');
        countdownEl.textContent = `Resend in ${remaining}s`;

        countdownTimer = setInterval(() => {
            remaining--;
            countdownEl.textContent = `Resend in ${remaining}s`;
            if (remaining <= 0) {
                clearInterval(countdownTimer);
                countdownTimer = null;
                countdownEl.classList.add('hidden');
                btnResend.classList.remove('hidden');
            }
        }, 1000);
    }

    // Send OTP Request
    async function requestOtp(phone) {
        if (isRequestingOtp) return; // Prevent duplicate clicks
        isRequestingOtp = true;
        submitBtn.disabled = true;
        btnText.textContent = 'Sending OTP...';
        btnIcon.className = 'ph-bold ph-spinner animate-spin text-sm';
        showFormError('');

        const emailVal = document.getElementById('register-email')?.value?.trim() || '';

        try {
            const res = await fetch('/otp/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone: phone, email: emailVal, purpose: 'register' })
            });

            const data = await res.json();
            isRequestingOtp = false;
            submitBtn.disabled = false;
            btnText.textContent = 'Create Free Account';
            btnIcon.className = 'ph-bold ph-arrow-right text-sm';

            if (res.ok && data.success) {
                openOtpModal(phone);
                startCountdown(data.resend_after || 60);
                setTimeout(() => {
                    if (data.notification && window.OtpVerification) {
                        window.OtpVerification.triggerPushNotification(data.notification);
                    }
                }, 100);
            } else {
                closeOtpModal();
                let targetId = 'register-phone';
                const msgLower = (data.message || '').toLowerCase();
                if (msgLower.includes('email')) {
                    targetId = 'register-email';
                }
                showFormError(data.message || 'Could not send verification OTP. Please check your information.', targetId);
            }
        } catch (err) {
            isRequestingOtp = false;
            submitBtn.disabled = false;
            btnText.textContent = 'Create Free Account';
            btnIcon.className = 'ph-bold ph-arrow-right text-sm';
            closeOtpModal();
            showFormError('Server or network issue. Please check your details and try again.', 'register-phone');
        }
    }

    // Resend OTP button inside Modal
    if (btnResend) {
        btnResend.addEventListener('click', async function() {
            if (isRequestingOtp) return;
            const phone = document.getElementById('register-phone').value.trim();
            isRequestingOtp = true;
            btnResend.disabled = true;
            btnResend.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Sending...';
            showModalStatus('', '');

            try {
                const res = await fetch('/otp/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ phone: phone, purpose: 'register' })
                });

                const data = await res.json();
                isRequestingOtp = false;
                btnResend.disabled = false;
                btnResend.innerHTML = '<i class="ph-bold ph-arrow-clockwise"></i> Resend OTP';

                if (res.ok && data.success) {
                    showModalStatus(data.message || 'New OTP sent to your device!', 'success');
                    startCountdown(data.resend_after || 60);
                    setTimeout(() => {
                        if (data.notification && window.OtpVerification) {
                            window.OtpVerification.triggerPushNotification(data.notification);
                        }
                    }, 100);
                } else {
                    showModalStatus(data.message || 'Could not resend OTP.', 'error');
                }
            } catch (err) {
                isRequestingOtp = false;
                btnResend.disabled = false;
                btnResend.innerHTML = '<i class="ph-bold ph-arrow-clockwise"></i> Resend OTP';
                showModalStatus('Network error. Please try again.', 'error');
            }
        });
    }

    // Verify OTP & Submit Registration
    async function verifyAndCompleteRegistration() {
        if (isVerifying) return; // Prevent double click while processing
        const phone = document.getElementById('register-phone').value.trim();
        const otp = getEnteredOtp();
        const expectedLen = digitInputs.length || 4;

        if (otp.length !== expectedLen) {
            showModalStatus(`Please enter the complete ${expectedLen}-digit OTP code.`, 'error');
            return;
        }

        isVerifying = true;
        btnVerifyModal.disabled = true;
        btnVerifyText.textContent = 'Verifying OTP...';
        if (btnVerifyIcon) btnVerifyIcon.className = 'ph-bold ph-spinner animate-spin text-base';
        showModalStatus('', '');

        try {
            const res = await fetch('/otp/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone: phone, otp: otp, purpose: 'register' })
            });

            const data = await res.json();

            if (res.ok && data.verified) {
                isPhoneVerified = true;
                verifiedInput.value = '1';
                showModalStatus('✓ Phone verified! Creating your account...', 'success');
                btnVerifyText.textContent = 'Creating Account...';
                if (btnVerifyIcon) btnVerifyIcon.className = 'ph-bold ph-spinner animate-spin text-base';
                
                // Keep button disabled to avoid clicking again
                btnVerifyModal.disabled = true;
                digitInputs.forEach(d => d.disabled = true);
                if (btnCloseModal) btnCloseModal.style.display = 'none';

                // Submit the form smoothly
                setTimeout(() => {
                    registerForm.submit();
                }, 500);
            } else {
                isVerifying = false;
                btnVerifyModal.disabled = false;
                btnVerifyText.textContent = 'Verify & Create Account';
                if (btnVerifyIcon) btnVerifyIcon.className = 'ph-bold ph-check-circle text-base';
                showModalStatus(data.message || 'Invalid OTP. Please try again.', 'error');

                // Shake animation
                digitInputs.forEach(d => {
                    d.classList.add('border-rose-400', 'animate-shake');
                    setTimeout(() => d.classList.remove('animate-shake', 'border-rose-400'), 500);
                });
            }
        } catch (err) {
            isVerifying = false;
            btnVerifyModal.disabled = false;
            btnVerifyText.textContent = 'Verify & Create Account';
            if (btnVerifyIcon) btnVerifyIcon.className = 'ph-bold ph-check-circle text-base';
            showModalStatus('Network error while verifying OTP.', 'error');
        }
    }

    if (btnVerifyModal) {
        btnVerifyModal.addEventListener('click', verifyAndCompleteRegistration);
    }

    // Intercept standard form submit
    registerForm.addEventListener('submit', function(e) {
        if (isPhoneVerified) {
            submitBtn.disabled = true;
            btnText.textContent = 'Creating Account...';
            btnIcon.className = 'ph-bold ph-spinner animate-spin text-sm';
            return; // Allow standard submission
        }

        e.preventDefault();
        showFormError('');

        // Client-side validations
        const name     = document.getElementById('register-name').value.trim();
        const phone    = document.getElementById('register-phone').value.trim();
        const email    = document.getElementById('register-email').value.trim();
        const password = document.getElementById('register-password').value;
        const confirm  = document.getElementById('register-password-confirm').value;
        const terms    = document.getElementById('register-terms').checked;

        if (!name) {
            showFormError('Please enter your full name.', 'register-name');
            return;
        }

        const cleanDigits = phone.replace(/[^0-9]/g, '');
        if (cleanDigits.length < 10) {
            showFormError('Please enter a valid 10-digit mobile number.', 'register-phone');
            return;
        }

        if (!email || !email.includes('@')) {
            showFormError('Please enter a valid email address.', 'register-email');
            return;
        }

        if (password.length < 8) {
            showFormError('Password must be at least 8 characters long.', 'register-password');
            return;
        }

        if (password !== confirm) {
            showFormError('Passwords do not match. Please check and try again.', 'register-password-confirm');
            return;
        }

        if (!terms) {
            showFormError('Please agree to the Terms of Service & Privacy Policy.', 'register-terms');
            return;
        }

        // Everything valid -> send OTP and open modal popup
        requestOtp(phone);
    });
});
</script>
<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-4px); }
    40%, 80% { transform: translateX(4px); }
}
.animate-shake { animation: shake 0.4s ease-in-out; }
</style>
@endpush

