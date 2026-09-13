{{-- Universal Luxury Auth / Login Required Modal --}}
<div id="ur-auth-modal" class="fixed inset-0 z-[99999] flex items-center justify-center p-3 sm:p-4 bg-slate-950/75 backdrop-blur-sm sm:backdrop-blur-md transition-all duration-300 opacity-0 pointer-events-none pb-[env(safe-area-inset-bottom,0px)]" style="display: none;" role="dialog" aria-modal="true">
    
    {{-- Modal Card Container --}}
    <div class="relative w-full max-w-[430px] max-h-[92dvh] sm:max-h-[90vh] flex flex-col bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 shadow-2xl overflow-hidden transform scale-95 transition-all duration-300 my-auto" id="ur-auth-modal-card">
        
        {{-- Close Button --}}
        <button type="button" onclick="window.closeAuthModal()" class="absolute top-3.5 right-3.5 z-20 w-8 h-8 rounded-full bg-slate-100/90 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-all hover:scale-105 active:scale-95" title="Close" aria-label="Close modal">
            <i class="ph-bold ph-x text-sm"></i>
        </button>

        {{-- Top Header --}}
        <div class="flex-shrink-0 pt-5 pb-3.5 px-6 text-center bg-gradient-to-b from-blue-50/70 dark:from-blue-950/30 to-transparent border-b border-slate-100 dark:border-slate-800/80">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-white dark:bg-slate-800 flex items-center justify-center p-2 shadow-xs ring-1 ring-slate-900/10 dark:ring-white/10 mb-2.5">
                <img src="{{ asset('images/logo-icon.png') }}" alt="UnlockRentals" title="UnlockRentals" class="w-full h-full object-contain" onerror="this.src='{{ asset('images/icons/icon-192x192.png') }}'">
            </div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight leading-snug" id="ur-auth-title">
                Unlock Full Property Access
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-normal max-w-xs mx-auto" id="ur-auth-subtitle">
                Sign in to view direct owner contact & verified locations.
            </p>

            {{-- Tab Switcher --}}
            <div class="grid grid-cols-2 p-1 mt-3.5 bg-slate-100 dark:bg-slate-800 rounded-xl" role="tablist">
                <button type="button" id="tab-btn-login" onclick="window.switchAuthTab('login')" class="py-2 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs">
                    Sign In
                </button>
                <button type="button" id="tab-btn-register" onclick="window.switchAuthTab('register')" class="py-2 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white">
                    Create Account
                </button>
            </div>
        </div>

        {{-- Forms Body --}}
        <div class="overflow-y-auto flex-1 p-5 sm:p-6 overscroll-contain" id="ur-auth-modal-body">
            
            {{-- Flash Alert Box --}}
            <div id="ur-auth-alert" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2"></div>

            {{-- Social Login Buttons (Website Only) --}}
            @php
                $isMobileApp = str_contains(request()->header('User-Agent', ''), 'UnlockRentals') || request()->has('app');
            @endphp
            @if(!$isMobileApp)
            <div class="website-only-social mb-4">
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

                {{-- Clean Divider --}}
                <div class="relative flex items-center justify-center my-4">
                    <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
                    <span class="bg-white dark:bg-slate-900 px-3 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider absolute">or continue with email</span>
                </div>
            </div>
            @endif

            {{-- 1. Sign In Form --}}
            <form id="ur-modal-login-form" method="POST" action="{{ route('login') }}" class="space-y-3.5" onsubmit="handleModalAuthSubmit(event, 'login')">
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
                        <button type="button" onclick="window.toggleModalPassword('ur-login-password-input', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Toggle password visibility">
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

                <button type="submit" id="ur-modal-login-submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 transition-all active:scale-[0.99] flex items-center justify-center gap-2 mt-2">
                    <span>Sign In & Continue</span>
                    <i class="ph-bold ph-arrow-right text-xs"></i>
                </button>
            </form>

            {{-- 2. Register Form --}}
            <form id="ur-modal-register-form" method="POST" action="{{ route('register') }}" class="space-y-3 hidden" onsubmit="handleModalAuthSubmit(event, 'register')">
                @csrf
                <input type="hidden" name="redirect" id="ur-register-redirect" value="">

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Full Name</label>
                    <div class="relative">
                        <i class="ph-bold ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                        <input type="text" name="name" required
                               placeholder="John Doe"
                               class="ur-auth-input w-full pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Email Address</label>
                    <div class="relative">
                        <i class="ph-bold ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                        <input type="email" name="email" required
                               placeholder="you@example.com"
                               class="ur-auth-input w-full pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Phone Number (Optional)</label>
                    <div class="relative">
                        <i class="ph-bold ph-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                        <input type="tel" name="phone"
                               placeholder="+91 94254 55499"
                               class="ur-auth-input w-full pl-10 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" name="password" required minlength="8"
                               placeholder="••••••••"
                               class="ur-auth-input-no-icon w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Confirm</label>
                        <input type="password" name="password_confirmation" required minlength="8"
                               placeholder="••••••••"
                               class="ur-auth-input-no-icon w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>

                <input type="hidden" name="role" value="tenant">

                <button type="submit" id="ur-modal-register-submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 transition-all active:scale-[0.99] flex items-center justify-center gap-2 mt-2">
                    <span>Create Free Account</span>
                    <i class="ph-bold ph-check text-xs"></i>
                </button>
            </form>

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
    padding-right: 2.2rem !important;
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

window.switchAuthTab = function(tab) {
    const loginForm = document.getElementById('ur-modal-login-form');
    const regForm = document.getElementById('ur-modal-register-form');
    const tabLogin = document.getElementById('tab-btn-login');
    const tabReg = document.getElementById('tab-btn-register');
    const alertBox = document.getElementById('ur-auth-alert');
    if (alertBox) alertBox.classList.add('hidden');

    if (tab === 'register') {
        if (loginForm) loginForm.classList.add('hidden');
        if (regForm) regForm.classList.remove('hidden');
        if (tabReg) {
            tabReg.className = "py-2 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs";
        }
        if (tabLogin) {
            tabLogin.className = "py-2 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white";
        }
    } else {
        if (regForm) regForm.classList.add('hidden');
        if (loginForm) loginForm.classList.remove('hidden');
        if (tabLogin) {
            tabLogin.className = "py-2 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs";
        }
        if (tabReg) {
            tabReg.className = "py-2 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white";
        }
    }
};

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

    document.querySelectorAll('#ur-auth-modal .ur-auth-input, #ur-auth-modal .ur-auth-input-no-icon').forEach(input => {
        input.addEventListener('focus', () => {
            setTimeout(() => {
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        });
    });

    if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', () => {
            const activeEl = document.activeElement;
            if (activeEl && (activeEl.classList.contains('ur-auth-input') || activeEl.classList.contains('ur-auth-input-no-icon'))) {
                activeEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }
});

async function handleModalAuthSubmit(event, type) {
    event.preventDefault();
    const form = event.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const alertBox = document.getElementById('ur-auth-alert');
    const originalText = submitBtn.innerHTML;

    alertBox.classList.add('hidden');
    alertBox.className = 'hidden mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2';
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-sm"></i> Please wait...`;

    async function doSubmit(retry = 0) {
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

            if (response.status === 419 && retry === 0) {
                const tokenRes = await fetch('/csrf-token');
                const tokenData = await tokenRes.json();
                if (tokenData && tokenData.csrf_token) {
                    const tokenInputs = document.querySelectorAll('input[name="_token"]');
                    tokenInputs.forEach(i => i.value = tokenData.csrf_token);
                    const metaCsrf = document.querySelector('meta[name="csrf-token"]');
                    if (metaCsrf) metaCsrf.setAttribute('content', tokenData.csrf_token);
                    return doSubmit(1);
                }
            }

            const data = await response.json();

            if (response.ok && data.success) {
                alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200';
                alertBox.innerHTML = `<i class="ph-bold ph-check-circle text-emerald-600 text-sm"></i> ${data.message || 'Success! Redirecting...'}`;
                alertBox.classList.remove('hidden');

                setTimeout(() => {
                    window.location.href = data.redirect || window.authModalTargetUrl || '/';
                }, 500);
            } else {
                let errorMsg = data.message || 'Authentication failed. Please check your credentials.';
                if (data.errors) {
                    const firstKey = Object.keys(data.errors)[0];
                    errorMsg = data.errors[firstKey][0];
                }
                alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2 bg-red-50 text-red-800 border border-red-200';
                alertBox.innerHTML = `<i class="ph-bold ph-warning-circle text-red-600 text-sm"></i> ${errorMsg}`;
                alertBox.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (err) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2 bg-red-50 text-red-800 border border-red-200';
            alertBox.innerHTML = `<i class="ph-bold ph-warning-circle text-red-600 text-sm"></i> Network or session error. Please try again.`;
            alertBox.classList.remove('hidden');
        }
    }

    doSubmit();
}
</script>
