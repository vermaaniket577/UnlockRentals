{{-- Universal Luxury Auth / Login Required Modal --}}
<div id="ur-auth-modal" class="fixed inset-0 z-[99999] flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-950/75 backdrop-blur-md transition-all duration-300 opacity-0 pointer-events-none pb-[env(safe-area-inset-bottom,0px)]" style="display: none;" role="dialog" aria-modal="true">
    
    {{-- Modal Card Container --}}
    <div class="relative w-full max-w-md max-h-[94dvh] sm:max-h-[90vh] flex flex-col bg-white dark:bg-slate-900 rounded-t-3xl sm:rounded-3xl border-t sm:border border-slate-200/90 dark:border-slate-800 shadow-2xl overflow-hidden transform scale-95 transition-all duration-300 sm:my-auto" id="ur-auth-modal-card">
        
        {{-- Close Button --}}
        <button type="button" onclick="window.closeAuthModal()" class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-colors" title="Close">
            <i class="ph-bold ph-x text-sm"></i>
        </button>

        {{-- Top Gradient Header (Compact & Mobile-friendly) --}}
        <div class="flex-shrink-0 pt-4 pb-3 px-5 text-center bg-gradient-to-b from-blue-500/[0.08] dark:from-blue-500/[0.15] to-transparent border-b border-slate-100 dark:border-slate-800">
            <div class="w-10 h-10 mx-auto rounded-xl bg-white flex items-center justify-center p-1 shadow-sm ring-1 ring-slate-900/10 dark:ring-white/20 mb-1.5">
                <img src="{{ asset('images/logo-icon.png') }}" alt="UnlockRentals" title="UnlockRentals" class="w-full h-full object-contain" onerror="this.src='{{ asset('images/icons/icon-192x192.png') }}'">
            </div>
            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white tracking-tight leading-snug" id="ur-auth-title">
                Unlock Full Property Access
            </h3>
            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-tight max-w-xs mx-auto" id="ur-auth-subtitle">
                Sign in to view direct owner contact & verified locations.
            </p>

            {{-- Tab Switcher --}}
            <div class="grid grid-cols-2 p-1 mt-2.5 bg-slate-100 dark:bg-slate-800/80 rounded-xl">
                <button type="button" id="tab-btn-login" onclick="window.switchAuthTab('login')" class="py-1 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs">
                    Sign In
                </button>
                <button type="button" id="tab-btn-register" onclick="window.switchAuthTab('register')" class="py-1 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white">
                    Create Account
                </button>
            </div>
        </div>

        {{-- Forms Body (Scrollable with overscroll protection) --}}
        <div class="overflow-y-auto flex-1 p-4 sm:p-5 overscroll-contain" id="ur-auth-modal-body">
            
            {{-- Flash Alert Box --}}
            <div id="ur-auth-alert" class="hidden mb-3 p-2.5 rounded-xl text-xs font-semibold flex items-center gap-2"></div>

            {{-- 1. Sign In Form --}}
            <form id="ur-modal-login-form" method="POST" action="{{ route('login') }}" class="space-y-3" onsubmit="handleModalAuthSubmit(event, 'login')">
                @csrf
                <input type="hidden" name="redirect" id="ur-login-redirect" value="">

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Email Address</label>
                    <div class="relative">
                        <i class="ph-bold ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="email" name="email" required
                               placeholder="you@example.com"
                               class="ur-auth-input w-full pl-9 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-base sm:text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Password</label>
                        <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-blue-600 hover:underline" title="Forgot Password?">Forgot?</a>
                    </div>
                    <div class="relative">
                        <i class="ph-bold ph-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="password" required
                               placeholder="••••••••"
                               class="ur-auth-input w-full pl-9 pr-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-base sm:text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Remember me</span>
                    </label>
                </div>

                <button type="submit" id="ur-modal-login-submit" class="w-full py-3 bg-[#2563EB] hover:bg-blue-700 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-sm shadow-blue-500/25 hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2 mt-1">
                    <span>Sign In & Continue</span>
                    <i class="ph-bold ph-arrow-right text-xs"></i>
                </button>
            </form>

            {{-- 2. Register Form --}}
            <form id="ur-modal-register-form" method="POST" action="{{ route('register') }}" class="space-y-2.5 hidden" onsubmit="handleModalAuthSubmit(event, 'register')">
                @csrf
                <input type="hidden" name="redirect" id="ur-register-redirect" value="">

                <div>
                    <label class="block text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-0.5">Full Name</label>
                    <input type="text" name="name" required
                           placeholder="John Doe"
                           class="ur-auth-input w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-base sm:text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-0.5">Email Address</label>
                    <input type="email" name="email" required
                           placeholder="you@example.com"
                           class="ur-auth-input w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-base sm:text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-0.5">Phone Number (Optional)</label>
                    <input type="tel" name="phone"
                           placeholder="+91 98765 43210"
                           class="ur-auth-input w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-base sm:text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-0.5">Password</label>
                        <input type="password" name="password" required minlength="8"
                               placeholder="••••••••"
                               class="ur-auth-input w-full px-3 py-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-base sm:text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-0.5">Confirm</label>
                        <input type="password" name="password_confirmation" required minlength="8"
                               placeholder="••••••••"
                               class="ur-auth-input w-full px-3 py-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-base sm:text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 transition-all">
                    </div>
                </div>

                <input type="hidden" name="role" value="tenant">

                <button type="submit" id="ur-modal-register-submit" class="w-full py-3 bg-[#2563EB] hover:bg-blue-700 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-sm shadow-blue-500/25 hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2 mt-1">
                    <span>Create Free Account</span>
                    <i class="ph-bold ph-check text-xs"></i>
                </button>
            </form>

        </div>
    </div>
</div>

<script>
window.authModalTargetUrl = '';

window.openAuthModal = function(tab = 'login', redirectUrl = '') {
    // Instantly hide any page loader
    if (window.URLoader && typeof window.URLoader.hide === 'function') {
        window.URLoader.hide();
    }

    const modal = document.getElementById('ur-auth-modal');
    const card = document.getElementById('ur-auth-modal-card');
    if (!modal || !card) return;

    window.authModalTargetUrl = redirectUrl || window.location.href;
    document.getElementById('ur-login-redirect').value = window.authModalTargetUrl;
    document.getElementById('ur-register-redirect').value = window.authModalTargetUrl;

    // Update social buttons with redirect target
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
    }, 300);
};

window.switchAuthTab = function(tab) {
    const loginForm = document.getElementById('ur-modal-login-form');
    const regForm = document.getElementById('ur-modal-register-form');
    const tabLogin = document.getElementById('tab-btn-login');
    const tabReg = document.getElementById('tab-btn-register');
    const alertBox = document.getElementById('ur-auth-alert');
    if (alertBox) alertBox.classList.add('hidden');

    if (tab === 'register') {
        loginForm.classList.add('hidden');
        regForm.classList.remove('hidden');
        tabReg.className = "py-1 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs";
        tabLogin.className = "py-1 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white";
    } else {
        regForm.classList.add('hidden');
        loginForm.classList.remove('hidden');
        tabLogin.className = "py-1 text-xs font-extrabold rounded-lg transition-all bg-white dark:bg-slate-900 text-blue-600 shadow-xs";
        tabReg.className = "py-1 text-xs font-bold text-slate-500 dark:text-slate-400 rounded-lg transition-all hover:text-slate-900 dark:hover:text-white";
    }
};

// Close on backdrop click or ESC & auto-scroll inputs into view on mobile keyboard opening
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

    // Auto-scroll focused input into view on mobile when virtual keyboard appears
    document.querySelectorAll('.ur-auth-input').forEach(input => {
        input.addEventListener('focus', () => {
            setTimeout(() => {
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        });
    });

    if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', () => {
            const activeEl = document.activeElement;
            if (activeEl && activeEl.classList.contains('ur-auth-input')) {
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
    alertBox.className = 'hidden mb-3 p-2.5 rounded-xl text-xs font-semibold flex items-center gap-2';
    
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
                alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-semibold flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200';
                alertBox.innerHTML = `<i class="ph-bold ph-check-circle text-emerald-600"></i> ${data.message || 'Success! Redirecting...'}`;
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
                alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-semibold flex items-center gap-2 bg-red-50 text-red-800 border border-red-200';
                alertBox.innerHTML = `<i class="ph-bold ph-warning-circle text-red-600"></i> ${errorMsg}`;
                alertBox.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (err) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-semibold flex items-center gap-2 bg-red-50 text-red-800 border border-red-200';
            alertBox.innerHTML = `<i class="ph-bold ph-warning-circle text-red-600"></i> Network or session error. Please try again.`;
            alertBox.classList.remove('hidden');
        }
    }

    doSubmit();
}
</script>
