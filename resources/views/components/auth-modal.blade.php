{{-- Universal Luxury Auth / Login Required Modal --}}
<div id="ur-auth-modal" class="fixed inset-0 z-[99999] flex items-center justify-center p-3 sm:p-4 bg-slate-950/75 backdrop-blur-sm sm:backdrop-blur-md transition-all duration-300 opacity-0 pointer-events-none pb-[env(safe-area-inset-bottom,0px)]" style="display: none;" role="dialog" aria-modal="true">
    
    {{-- Modal Card Container --}}
    <div class="relative w-full max-w-[430px] max-h-[92dvh] sm:max-h-[90vh] flex flex-col bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 shadow-2xl overflow-hidden transform scale-95 transition-all duration-300 my-auto" id="ur-auth-modal-card">
        
        {{-- Close Button --}}
        <button type="button" onclick="window.closeAuthModal()" class="absolute top-3.5 right-3.5 z-20 w-8 h-8 rounded-full bg-slate-100/90 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-all hover:scale-105 active:scale-95 cursor-pointer" title="Close" aria-label="Close modal">
            <i class="ph-bold ph-x text-sm"></i>
        </button>

        {{-- Top Header --}}
        <div class="flex-shrink-0 pt-5 pb-3.5 px-6 text-center bg-gradient-to-b from-blue-50/70 dark:from-blue-950/30 to-transparent border-b border-slate-100 dark:border-slate-800/80">
            <div class="w-11 h-11 mx-auto rounded-2xl bg-white dark:bg-slate-800 flex items-center justify-center p-2 shadow-xs ring-1 ring-slate-900/10 dark:ring-white/10 mb-2">
                <img src="{{ asset('images/logo-icon.png') }}" alt="UnlockRentals" title="UnlockRentals" class="w-full h-full object-contain" onerror="this.src='{{ asset('images/icons/icon-192x192.png') }}'">
            </div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight leading-snug" id="ur-auth-title">
                Unlock Full Access
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-normal max-w-xs mx-auto" id="ur-auth-subtitle">
                Sign in to view direct owner contact & verified listings.
            </p>

            {{-- Primary Tab Switcher --}}
            <div class="grid grid-cols-2 p-1 mt-3 bg-slate-100 dark:bg-slate-800 rounded-xl" role="tablist">
                <button type="button" id="tab-btn-login" onclick="window.switchAuthTab('login')" class="py-2 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs cursor-pointer">
                    Sign In
                </button>
                <button type="button" id="tab-btn-register" onclick="window.switchAuthTab('register')" class="py-2 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white cursor-pointer">
                    Create Account
                </button>
            </div>
        </div>

        {{-- Forms Body --}}
        <div class="overflow-y-auto flex-1 p-4 sm:p-6 overscroll-contain" id="ur-auth-modal-body">
            
            {{-- Flash Alert Box --}}
            <div id="ur-auth-alert" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2"></div>

            {{-- ================================================================
                 TAB 1: SIGN IN (With Mobile OTP & Email Options)
                 ================================================================ --}}
            <div id="ur-modal-login-container">
                
                {{-- Sub-method selector: Mobile No. vs Email --}}
                <div class="grid grid-cols-2 p-1 mb-3.5 bg-slate-100/80 dark:bg-slate-800/80 rounded-xl">
                    <button type="button" id="modal-login-type-phone" onclick="window.switchLoginMethod('phone')"
                            class="py-1.5 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-emerald-600 dark:text-emerald-400 shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="ph-bold ph-phone text-sm"></i>
                        <span>Mobile No.</span>
                    </button>
                    <button type="button" id="modal-login-type-email" onclick="window.switchLoginMethod('email')"
                            class="py-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="ph-bold ph-envelope text-sm"></i>
                        <span>Email & Password</span>
                    </button>
                </div>

                {{-- A. Mobile Phone OTP Login Form --}}
                <div id="modal-login-panel-phone" class="space-y-3.5">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Mobile Number</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs select-none pointer-events-none">+91</span>
                                <input type="tel" id="modal-login-phone-input" maxlength="10" placeholder="98765 43210"
                                       class="w-full pl-12 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                                       onkeydown="if(event.key==='Enter'){event.preventDefault();window.modalSendLoginOtp();}">
                            </div>
                            <button type="button" id="modal-login-send-otp-btn" onclick="window.modalSendLoginOtp()"
                                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-1.5 shrink-0 whitespace-nowrap cursor-pointer">
                                <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                                <span>Send OTP</span>
                            </button>
                        </div>
                        <p id="modal-login-phone-hint" class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">We will send a 4-digit verification code to this phone.</p>
                    </div>

                    {{-- 4-Digit OTP Input Section --}}
                    <div id="modal-login-otp-section" class="hidden space-y-3 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <label class="block text-center text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Enter 4-Digit Code</label>
                        <div id="modal-login-otp-digits" class="flex justify-center gap-2.5 sm:gap-3">
                            <input type="text" inputmode="numeric" maxlength="1" class="modal-otp-digit w-12 h-13 sm:w-13 sm:h-14 text-center text-2xl font-black bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/15 focus:outline-none transition-all text-slate-900 dark:text-white" autofocus>
                            <input type="text" inputmode="numeric" maxlength="1" class="modal-otp-digit w-12 h-13 sm:w-13 sm:h-14 text-center text-2xl font-black bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/15 focus:outline-none transition-all text-slate-900 dark:text-white">
                            <input type="text" inputmode="numeric" maxlength="1" class="modal-otp-digit w-12 h-13 sm:w-13 sm:h-14 text-center text-2xl font-black bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/15 focus:outline-none transition-all text-slate-900 dark:text-white">
                            <input type="text" inputmode="numeric" maxlength="1" class="modal-otp-digit w-12 h-13 sm:w-13 sm:h-14 text-center text-2xl font-black bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/15 focus:outline-none transition-all text-slate-900 dark:text-white">
                        </div>
                        <div class="flex items-center justify-between text-xs px-1">
                            <span id="modal-login-otp-timer" class="text-slate-400 font-mono">Resend in 30s</span>
                            <button type="button" id="modal-login-resend-btn" onclick="window.modalSendLoginOtp()" class="hidden text-emerald-600 dark:text-emerald-400 font-bold hover:underline cursor-pointer">Resend OTP</button>
                        </div>
                        <button type="button" id="modal-login-verify-btn" onclick="window.modalVerifyLoginOtp()"
                                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                            <span>Verify & Sign In</span>
                            <i class="ph-bold ph-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>

                {{-- B. Email & Password Login Form --}}
                <form id="ur-modal-login-form" method="POST" action="{{ route('login') }}" class="space-y-3.5 hidden" onsubmit="handleModalAuthSubmit(event, 'login')">
                    @csrf
                    <input type="hidden" name="redirect" id="ur-login-redirect" value="">

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Email Address</label>
                        <div class="relative">
                            <i class="ph-bold ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                            <input type="email" name="email" required
                                   placeholder="you@example.com"
                                   class="ur-auth-input w-full pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Password</label>
                            <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 hover:underline" title="Forgot Password?">Forgot?</a>
                        </div>
                        <div class="relative">
                            <i class="ph-bold ph-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                            <input type="password" name="password" id="ur-login-password-input" required
                                   placeholder="••••••••"
                                   class="ur-auth-input w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                            <button type="button" onclick="window.toggleModalPassword('ur-login-password-input', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors cursor-pointer" title="Toggle password visibility">
                                <i class="ph-bold ph-eye text-base"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-0.5">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Remember me</span>
                        </label>
                    </div>

                    <button type="submit" id="ur-modal-login-submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 transition-all active:scale-[0.99] flex items-center justify-center gap-2 mt-2 cursor-pointer">
                        <span>Sign In with Email</span>
                        <i class="ph-bold ph-arrow-right text-xs"></i>
                    </button>
                </form>

                {{-- Social Login Buttons (Website Only) --}}
                @php
                    $isMobileApp = str_contains(request()->header('User-Agent', ''), 'UnlockRentals') || request()->has('app');
                @endphp
                @if(!$isMobileApp)
                <div class="website-only-social mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <div class="grid grid-cols-2 gap-2.5">
                        <a href="{{ route('social.redirect', ['provider' => 'google']) }}" id="ur-modal-google-btn"
                           class="flex items-center justify-center gap-2 px-3 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-750 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 transition-all duration-150 active:scale-[0.98] shadow-xs"
                           title="Continue with Google">
                            <svg width="17" height="17" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <span>Google</span>
                        </a>

                        <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" id="ur-modal-facebook-btn"
                           class="flex items-center justify-center gap-2 px-3 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-750 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 transition-all duration-150 active:scale-[0.98] shadow-xs"
                           title="Continue with Facebook">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="#1877F2">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span>Facebook</span>
                        </a>
                    </div>
                </div>
                @endif

            </div>

            {{-- ================================================================
                 TAB 2: CREATE ACCOUNT (With Mobile OTP & Email Options)
                 ================================================================ --}}
            <div id="ur-modal-register-container" class="hidden">
                
                {{-- Sub-method selector: Create with Mobile vs Standard --}}
                <div class="grid grid-cols-2 p-1 mb-3.5 bg-slate-100/80 dark:bg-slate-800/80 rounded-xl">
                    <button type="button" id="modal-reg-type-phone" onclick="window.switchRegisterMethod('phone')"
                            class="py-1.5 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-emerald-600 dark:text-emerald-400 shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="ph-bold ph-phone text-sm"></i>
                        <span>Create with Mobile</span>
                    </button>
                    <button type="button" id="modal-reg-type-email" onclick="window.switchRegisterMethod('email')"
                            class="py-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="ph-bold ph-envelope text-sm"></i>
                        <span>Standard Form</span>
                    </button>
                </div>

                {{-- A. Create Account with Mobile OTP Form --}}
                <div id="modal-reg-panel-phone" class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="ph-bold ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                            <input type="text" id="modal-reg-phone-name" placeholder="John Doe"
                                   class="ur-auth-input w-full pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Mobile Number <span class="text-rose-500">*</span></label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs select-none pointer-events-none">+91</span>
                                <input type="tel" id="modal-reg-phone-input" maxlength="10" placeholder="98765 43210"
                                       class="w-full pl-12 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                                       onkeydown="if(event.key==='Enter'){event.preventDefault();window.modalSendRegisterOtp();}">
                            </div>
                            <button type="button" id="modal-reg-send-otp-btn" onclick="window.modalSendRegisterOtp()"
                                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-1.5 shrink-0 whitespace-nowrap cursor-pointer">
                                <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                                <span>Send OTP</span>
                            </button>
                        </div>
                    </div>

                    {{-- OTP Input Section for Registration --}}
                    <div id="modal-reg-otp-section" class="hidden space-y-2.5 pt-1 border-t border-slate-100 dark:border-slate-800">
                        <label class="block text-center text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Enter 4-Digit Code</label>
                        <div id="modal-reg-otp-digits" class="flex justify-center gap-2.5 sm:gap-3">
                            <input type="text" inputmode="numeric" maxlength="1" class="modal-otp-digit-reg w-12 h-13 sm:w-13 sm:h-14 text-center text-2xl font-black bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/15 focus:outline-none transition-all text-slate-900 dark:text-white" autofocus>
                            <input type="text" inputmode="numeric" maxlength="1" class="modal-otp-digit-reg w-12 h-13 sm:w-13 sm:h-14 text-center text-2xl font-black bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/15 focus:outline-none transition-all text-slate-900 dark:text-white">
                            <input type="text" inputmode="numeric" maxlength="1" class="modal-otp-digit-reg w-12 h-13 sm:w-13 sm:h-14 text-center text-2xl font-black bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/15 focus:outline-none transition-all text-slate-900 dark:text-white">
                            <input type="text" inputmode="numeric" maxlength="1" class="modal-otp-digit-reg w-12 h-13 sm:w-13 sm:h-14 text-center text-2xl font-black bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/15 focus:outline-none transition-all text-slate-900 dark:text-white">
                        </div>
                        <div class="flex items-center justify-between text-xs px-1">
                            <span id="modal-reg-otp-timer" class="text-slate-400 font-mono">Resend in 30s</span>
                            <button type="button" id="modal-reg-resend-btn" onclick="window.modalSendRegisterOtp()" class="hidden text-emerald-600 dark:text-emerald-400 font-bold hover:underline cursor-pointer">Resend OTP</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Email Address <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="ph-bold ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                            <input type="email" id="modal-reg-phone-email" placeholder="you@example.com"
                                   class="ur-auth-input w-full pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Password <span class="text-rose-500">*</span></label>
                            <input type="password" id="modal-reg-phone-password" minlength="8" placeholder="Min 8 chars"
                                   class="ur-auth-input-no-icon w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Confirm <span class="text-rose-500">*</span></label>
                            <input type="password" id="modal-reg-phone-confirm" minlength="8" placeholder="Repeat"
                                   class="ur-auth-input-no-icon w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                    </div>

                    <button type="button" id="modal-reg-phone-submit" onclick="window.modalSubmitRegisterWithMobile()"
                            class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-md shadow-emerald-500/20 hover:shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2 mt-2 cursor-pointer">
                        <span>Verify & Create Account</span>
                        <i class="ph-bold ph-check text-xs"></i>
                    </button>
                </div>

                {{-- B. Standard Email Registration Form --}}
                <form id="ur-modal-register-form" method="POST" action="{{ route('register') }}" class="space-y-3 hidden" onsubmit="handleModalAuthSubmit(event, 'register')">
                    @csrf
                    <input type="hidden" name="redirect" id="ur-register-redirect" value="">

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Full Name <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="ph-bold ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                            <input type="text" name="name" required
                                   placeholder="John Doe"
                                   class="ur-auth-input w-full pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Email Address <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="ph-bold ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                            <input type="email" name="email" required
                                   placeholder="you@example.com"
                                   class="ur-auth-input w-full pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Phone Number <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <i class="ph-bold ph-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                            <input type="tel" name="phone" required
                                   placeholder="+91 94254 55499"
                                   class="ur-auth-input w-full pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" required minlength="8"
                                   placeholder="••••••••"
                                   class="ur-auth-input-no-icon w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Confirm <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" required minlength="8"
                                   placeholder="••••••••"
                                   class="ur-auth-input-no-icon w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                    </div>

                    <input type="hidden" name="role" value="tenant">

                    <button type="submit" id="ur-modal-register-submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 transition-all active:scale-[0.99] flex items-center justify-center gap-2 mt-2 cursor-pointer">
                        <span>Create Free Account</span>
                        <i class="ph-bold ph-check text-xs"></i>
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>

{{-- Scoped Styles for Bulletproof Resilience Against Global Reset Conflicts --}}
<style>
#ur-auth-modal * {
    box-sizing: border-box;
}
#ur-auth-modal .ur-auth-input {
    padding-left: 2.6rem !important;
    padding-right: 1rem !important;
    padding-top: 0.65rem !important;
    padding-bottom: 0.65rem !important;
    height: 2.75rem !important;
    line-height: 1.4 !important;
}
#ur-auth-modal .ur-auth-input-phone {
    padding-left: 2.9rem !important;
    padding-right: 0.75rem !important;
    padding-top: 0.65rem !important;
    padding-bottom: 0.65rem !important;
    height: 2.75rem !important;
    line-height: 1.4 !important;
}
#ur-auth-modal .ur-auth-input-no-icon {
    padding-left: 0.875rem !important;
    padding-right: 0.875rem !important;
    padding-top: 0.65rem !important;
    padding-bottom: 0.65rem !important;
    height: 2.75rem !important;
    line-height: 1.4 !important;
}
#ur-auth-modal input::placeholder {
    color: #94a3b8;
    opacity: 1;
}
</style>

<script>
window.authModalTargetUrl = '';
let loginCountdownTimer = null;
let regCountdownTimer = null;

/* ── Modal Open & Close ───────────────────────────────── */
window.openAuthModal = function(tab = 'login', redirectUrl = '') {
    if (window.URLoader && typeof window.URLoader.hide === 'function') {
        window.URLoader.hide();
    }

    const modal = document.getElementById('ur-auth-modal');
    const card = document.getElementById('ur-auth-modal-card');
    if (!modal || !card) return;

    window.authModalTargetUrl = redirectUrl || window.location.href;
    const loginRedirect = document.getElementById('ur-login-redirect');
    const regRedirect = document.getElementById('ur-register-redirect');
    if (loginRedirect) loginRedirect.value = window.authModalTargetUrl;
    if (regRedirect) regRedirect.value = window.authModalTargetUrl;

    const googleBtn = document.getElementById('ur-modal-google-btn');
    const fbBtn = document.getElementById('ur-modal-facebook-btn');
    if (googleBtn) {
        googleBtn.href = "{{ route('social.redirect', ['provider' => 'google']) }}?redirect=" + encodeURIComponent(window.authModalTargetUrl);
    }
    if (fbBtn) {
        fbBtn.href = "{{ route('social.redirect', ['provider' => 'facebook']) }}?redirect=" + encodeURIComponent(window.authModalTargetUrl);
    }

    window.switchAuthTab(tab);

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    });
};

window.closeAuthModal = function() {
    const modal = document.getElementById('ur-auth-modal');
    const card = document.getElementById('ur-auth-modal-card');
    if (!modal || !card) return;

    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0', 'pointer-events-none');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }, 250);
};

/* ── Tab Switching: Sign In vs Create Account ─────────── */
window.switchAuthTab = function(tab) {
    const loginContainer = document.getElementById('ur-modal-login-container');
    const regContainer = document.getElementById('ur-modal-register-container');
    const tabLogin = document.getElementById('tab-btn-login');
    const tabReg = document.getElementById('tab-btn-register');
    const alertBox = document.getElementById('ur-auth-alert');
    if (alertBox) alertBox.classList.add('hidden');

    if (tab === 'register') {
        if (loginContainer) loginContainer.classList.add('hidden');
        if (regContainer) regContainer.classList.remove('hidden');
        if (tabReg) {
            tabReg.className = "py-2 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs cursor-pointer";
        }
        if (tabLogin) {
            tabLogin.className = "py-2 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white cursor-pointer";
        }
    } else {
        if (regContainer) regContainer.classList.add('hidden');
        if (loginContainer) loginContainer.classList.remove('hidden');
        if (tabLogin) {
            tabLogin.className = "py-2 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs cursor-pointer";
        }
        if (tabReg) {
            tabReg.className = "py-2 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white cursor-pointer";
        }
    }
};

/* ── Sub-Method Switching (Login): Mobile No. vs Email ── */
window.switchLoginMethod = function(method) {
    const panelPhone = document.getElementById('modal-login-panel-phone');
    const formEmail = document.getElementById('ur-modal-login-form');
    const btnPhone = document.getElementById('modal-login-type-phone');
    const btnEmail = document.getElementById('modal-login-type-email');
    const alertBox = document.getElementById('ur-auth-alert');
    if (alertBox) alertBox.classList.add('hidden');

    if (method === 'email') {
        if (panelPhone) panelPhone.classList.add('hidden');
        if (formEmail) formEmail.classList.remove('hidden');
        if (btnEmail) btnEmail.className = "py-1.5 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs flex items-center justify-center gap-1.5 cursor-pointer";
        if (btnPhone) btnPhone.className = "py-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1.5 cursor-pointer";
    } else {
        if (formEmail) formEmail.classList.add('hidden');
        if (panelPhone) panelPhone.classList.remove('hidden');
        if (btnPhone) btnPhone.className = "py-1.5 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-emerald-600 dark:text-emerald-400 shadow-xs flex items-center justify-center gap-1.5 cursor-pointer";
        if (btnEmail) btnEmail.className = "py-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1.5 cursor-pointer";
    }
};

/* ── Sub-Method Switching (Register): Mobile No. vs Standard */
window.switchRegisterMethod = function(method) {
    const panelPhone = document.getElementById('modal-reg-panel-phone');
    const formEmail = document.getElementById('ur-modal-register-form');
    const btnPhone = document.getElementById('modal-reg-type-phone');
    const btnEmail = document.getElementById('modal-reg-type-email');
    const alertBox = document.getElementById('ur-auth-alert');
    if (alertBox) alertBox.classList.add('hidden');

    if (method === 'email') {
        if (panelPhone) panelPhone.classList.add('hidden');
        if (formEmail) formEmail.classList.remove('hidden');
        if (btnEmail) btnEmail.className = "py-1.5 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs flex items-center justify-center gap-1.5 cursor-pointer";
        if (btnPhone) btnPhone.className = "py-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1.5 cursor-pointer";
    } else {
        if (formEmail) formEmail.classList.add('hidden');
        if (panelPhone) panelPhone.classList.remove('hidden');
        if (btnPhone) btnPhone.className = "py-1.5 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-emerald-600 dark:text-emerald-400 shadow-xs flex items-center justify-center gap-1.5 cursor-pointer";
        if (btnEmail) btnEmail.className = "py-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1.5 cursor-pointer";
    }
};

/* ── Helper: Get CSRF Token ──────────────────────────── */
function getModalCsrf() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '{{ csrf_token() }}';
}

function showModalAlert(msg, type = 'error') {
    const alertBox = document.getElementById('ur-auth-alert');
    if (!alertBox) return;
    alertBox.classList.remove('hidden');
    if (type === 'success') {
        alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800';
        alertBox.innerHTML = `<i class="ph-bold ph-check-circle text-emerald-600 text-sm flex-shrink-0"></i> <span>${msg}</span>`;
    } else {
        alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2 bg-rose-50 text-rose-800 border border-rose-200 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800';
        alertBox.innerHTML = `<i class="ph-bold ph-warning-circle text-rose-600 text-sm flex-shrink-0"></i> <span>${msg}</span>`;
    }
}

/* ── 1. SEND OTP FOR LOGIN ────────────────────────────── */
window.modalSendLoginOtp = async function() {
    const phoneInput = document.getElementById('modal-login-phone-input');
    const sendBtn = document.getElementById('modal-login-send-otp-btn');
    const otpSection = document.getElementById('modal-login-otp-section');
    const resendBtn = document.getElementById('modal-login-resend-btn');
    const timerEl = document.getElementById('modal-login-otp-timer');
    const phone = phoneInput ? phoneInput.value.trim() : '';

    if (!phone || phone.length !== 10) {
        showModalAlert('Please enter a valid 10-digit mobile number.');
        if (phoneInput) phoneInput.focus();
        return;
    }

    sendBtn.disabled = true;
    sendBtn.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-sm"></i> Sending...`;

    try {
        const res = await fetch('{{ route("otp.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getModalCsrf(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ phone: phone, purpose: 'login' })
        });

        const data = await res.json();

        if (res.ok && data.success) {
            showModalAlert(data.message || 'OTP sent successfully to your mobile number!', 'success');
            if (otpSection) otpSection.classList.remove('hidden');
            sendBtn.innerHTML = `<i class="ph-bold ph-check text-sm"></i> Sent`;
            
            // Focus first OTP input
            const firstDigit = document.querySelector('#modal-login-otp-digits .modal-otp-digit');
            if (firstDigit) setTimeout(() => firstDigit.focus(), 150);

            // Start countdown
            startTimer(timerEl, resendBtn, 'loginCountdownTimer');
        } else {
            showModalAlert(data.message || 'Unable to send OTP. Please check your number.');
            sendBtn.disabled = false;
            sendBtn.innerHTML = `<i class="ph-bold ph-paper-plane-tilt text-sm"></i> Send OTP`;
        }
    } catch (e) {
        showModalAlert('Connection error. Please try again.');
        sendBtn.disabled = false;
        sendBtn.innerHTML = `<i class="ph-bold ph-paper-plane-tilt text-sm"></i> Send OTP`;
    }
};

/* ── 2. VERIFY & SIGN IN VIA OTP ──────────────────────── */
window.modalVerifyLoginOtp = async function() {
    const phoneInput = document.getElementById('modal-login-phone-input');
    const verifyBtn = document.getElementById('modal-login-verify-btn');
    const digits = document.querySelectorAll('#modal-login-otp-digits .modal-otp-digit');
    const phone = phoneInput ? phoneInput.value.trim() : '';
    let otp = '';
    digits.forEach(d => otp += d.value.trim());

    if (!otp || otp.length !== 4) {
        showModalAlert('Please enter the complete 4-digit OTP.');
        return;
    }

    verifyBtn.disabled = true;
    verifyBtn.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-sm"></i> Verifying...`;

    try {
        const res = await fetch('{{ route("otp.login") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getModalCsrf(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ phone: phone, otp: otp })
        });

        const data = await res.json();

        if (res.ok && data.success) {
            showModalAlert(data.message || 'Signed in successfully! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || window.authModalTargetUrl || '/';
            }, 600);
        } else {
            showModalAlert(data.message || 'Invalid OTP code. Please try again.');
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = `<span>Verify & Sign In</span> <i class="ph-bold ph-arrow-right text-xs"></i>`;
        }
    } catch (e) {
        showModalAlert('Verification failed. Please try again.');
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = `<span>Verify & Sign In</span> <i class="ph-bold ph-arrow-right text-xs"></i>`;
    }
};

/* ── 3. SEND OTP FOR REGISTRATION ─────────────────────── */
window.modalSendRegisterOtp = async function() {
    const phoneInput = document.getElementById('modal-reg-phone-input');
    const sendBtn = document.getElementById('modal-reg-send-otp-btn');
    const otpSection = document.getElementById('modal-reg-otp-section');
    const resendBtn = document.getElementById('modal-reg-resend-btn');
    const timerEl = document.getElementById('modal-reg-otp-timer');
    const phone = phoneInput ? phoneInput.value.trim() : '';

    if (!phone || phone.length !== 10) {
        showModalAlert('Please enter a valid 10-digit mobile number.');
        if (phoneInput) phoneInput.focus();
        return;
    }

    sendBtn.disabled = true;
    sendBtn.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-sm"></i> Sending...`;

    try {
        const res = await fetch('{{ route("otp.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getModalCsrf(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ phone: phone, purpose: 'register' })
        });

        const data = await res.json();

        if (res.ok && data.success) {
            showModalAlert(data.message || 'Verification code sent to your mobile!', 'success');
            if (otpSection) otpSection.classList.remove('hidden');
            sendBtn.innerHTML = `<i class="ph-bold ph-check text-sm"></i> Sent`;

            const firstDigit = document.querySelector('#modal-reg-otp-digits .modal-otp-digit-reg');
            if (firstDigit) setTimeout(() => firstDigit.focus(), 150);

            startTimer(timerEl, resendBtn, 'regCountdownTimer');
        } else {
            showModalAlert(data.message || 'Unable to send OTP. Number may already be registered.');
            sendBtn.disabled = false;
            sendBtn.innerHTML = `<i class="ph-bold ph-paper-plane-tilt text-sm"></i> Send OTP`;
        }
    } catch (e) {
        showModalAlert('Connection error. Please try again.');
        sendBtn.disabled = false;
        sendBtn.innerHTML = `<i class="ph-bold ph-paper-plane-tilt text-sm"></i> Send OTP`;
    }
};

/* ── 4. VERIFY OTP & CREATE ACCOUNT WITH MOBILE ───────── */
window.modalSubmitRegisterWithMobile = async function() {
    const nameInput = document.getElementById('modal-reg-phone-name');
    const phoneInput = document.getElementById('modal-reg-phone-input');
    const emailInput = document.getElementById('modal-reg-phone-email');
    const pwdInput = document.getElementById('modal-reg-phone-password');
    const confirmInput = document.getElementById('modal-reg-phone-confirm');
    const submitBtn = document.getElementById('modal-reg-phone-submit');
    const digits = document.querySelectorAll('#modal-reg-otp-digits .modal-otp-digit-reg');

    const name = nameInput ? nameInput.value.trim() : '';
    const phone = phoneInput ? phoneInput.value.trim() : '';
    const email = emailInput ? emailInput.value.trim() : '';
    const password = pwdInput ? pwdInput.value : '';
    const password_confirmation = confirmInput ? confirmInput.value : '';

    if (!name) {
        showModalAlert('Please enter your full name.');
        if (nameInput) nameInput.focus();
        return;
    }
    if (!phone || phone.length !== 10) {
        showModalAlert('Please enter a valid 10-digit mobile number.');
        if (phoneInput) phoneInput.focus();
        return;
    }
    let otp = '';
    digits.forEach(d => otp += d.value.trim());
    if (!otp || otp.length !== 4) {
        showModalAlert('Please enter the 4-digit code sent to your mobile number.');
        return;
    }
    if (!email || !email.includes('@')) {
        showModalAlert('Please enter a valid email address.');
        if (emailInput) emailInput.focus();
        return;
    }
    if (!password || password.length < 8) {
        showModalAlert('Password must be at least 8 characters.');
        if (pwdInput) pwdInput.focus();
        return;
    }
    if (password !== password_confirmation) {
        showModalAlert('Password confirmation does not match.');
        if (confirmInput) confirmInput.focus();
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-sm"></i> Creating account...`;

    try {
        // Step 1: Verify the registration OTP
        const verifyRes = await fetch('{{ route("otp.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getModalCsrf(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ phone: phone, otp: otp, purpose: 'register' })
        });

        const verifyData = await verifyRes.json();
        if (!verifyRes.ok || !verifyData.success) {
            showModalAlert(verifyData.message || 'Invalid OTP code. Please enter the correct code.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<span>Verify & Create Account</span> <i class="ph-bold ph-check text-xs"></i>`;
            return;
        }

        // Step 2: Register account
        const regRes = await fetch('{{ route("register") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getModalCsrf(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                name: name,
                phone: phone,
                email: email,
                password: password,
                password_confirmation: password_confirmation,
                role: 'tenant'
            })
        });

        const regData = await regRes.json();

        if (regRes.ok && regData.success) {
            showModalAlert('Account created successfully! Welcome to UnlockRentals.', 'success');
            setTimeout(() => {
                window.location.href = regData.redirect || window.authModalTargetUrl || '/';
            }, 600);
        } else {
            let errorMsg = regData.message || 'Registration failed. Please check your details.';
            if (regData.errors) {
                const firstKey = Object.keys(regData.errors)[0];
                errorMsg = regData.errors[firstKey][0];
            }
            showModalAlert(errorMsg);
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<span>Verify & Create Account</span> <i class="ph-bold ph-check text-xs"></i>`;
        }
    } catch (e) {
        showModalAlert('Network error during registration. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<span>Verify & Create Account</span> <i class="ph-bold ph-check text-xs"></i>`;
    }
};

/* ── Timer Helper ─────────────────────────────────────── */
function startTimer(timerEl, resendBtn, timerVarName) {
    let timeLeft = 30;
    if (timerEl) {
        timerEl.classList.remove('hidden');
        timerEl.textContent = `Resend in ${timeLeft}s`;
    }
    if (resendBtn) resendBtn.classList.add('hidden');

    if (window[timerVarName]) clearInterval(window[timerVarName]);

    window[timerVarName] = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(window[timerVarName]);
            if (timerEl) timerEl.classList.add('hidden');
            if (resendBtn) resendBtn.classList.remove('hidden');
        } else {
            if (timerEl) timerEl.textContent = `Resend in ${timeLeft}s`;
        }
    }, 1000);
}

/* ── Toggle Password Visibility ──────────────────────── */
window.toggleModalPassword = function(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
            icon.classList.remove('ph-eye');
            icon.classList.add('ph-eye-slash');
        }
    } else {
        input.type = 'password';
        if (icon) {
            icon.classList.remove('ph-eye-slash');
            icon.classList.add('ph-eye');
        }
    }
};

/* ── Setup OTP Digits Auto-Advance ────────────────────── */
function setupOtpDigitInputs(containerSelector) {
    const container = document.querySelector(containerSelector);
    if (!container) return;
    const inputs = container.querySelectorAll('input');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const val = input.value.replace(/[^0-9]/g, '');
            input.value = val ? val[0] : '';
            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            if (!text) return;
            for (let i = 0; i < inputs.length; i++) {
                if (i < text.length) {
                    inputs[i].value = text[i];
                }
            }
            const targetIdx = Math.min(text.length, inputs.length - 1);
            inputs[targetIdx].focus();
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('ur-auth-modal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) window.closeAuthModal();
        });
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') window.closeAuthModal();
    });

    setupOtpDigitInputs('#modal-login-otp-digits');
    setupOtpDigitInputs('#modal-reg-otp-digits');
});

/* ── Fallback Standard Email Form Handler ─────────────── */
async function handleModalAuthSubmit(event, type) {
    event.preventDefault();
    const form = event.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-sm"></i> Please wait...`;

    try {
        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showModalAlert(data.message || 'Success! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || window.authModalTargetUrl || '/';
            }, 600);
        } else {
            let errorMsg = data.message || 'Authentication failed. Please check your credentials.';
            if (data.errors) {
                const firstKey = Object.keys(data.errors)[0];
                errorMsg = data.errors[firstKey][0];
            }
            showModalAlert(errorMsg);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    } catch (err) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        showModalAlert('Network or session error. Please try again.');
    }
}
</script>
