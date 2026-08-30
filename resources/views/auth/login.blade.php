@extends('layouts.app')

@section('title', 'Sign In - UnlockRentals')
@section('robots', 'noindex, nofollow')

@section('content')

<section class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 relative overflow-hidden bg-slate-50 dark:bg-slate-950" id="login-section">
    {{-- Ambient Background Glows --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-blue-100/60 via-indigo-50/30 to-transparent dark:from-blue-950/30 dark:via-transparent pointer-events-none blur-3xl -z-10"></div>
    <div class="absolute -top-24 right-1/4 w-96 h-96 bg-blue-400/10 dark:bg-blue-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute bottom-10 left-1/4 w-96 h-96 bg-indigo-400/10 dark:bg-indigo-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-[440px] relative z-10">

        {{-- Top Brand Icon / Badge --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 group transition-transform duration-200 hover:scale-[1.02]" title="UnlockRentals">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-600/25 ring-4 ring-blue-50 dark:ring-blue-900/30 transition-all duration-300 group-hover:shadow-blue-600/35">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5l2 2 3.5-3.5"></path>
                    </svg>
                </div>
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-4">Welcome back</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">Sign in to your UnlockRentals account</p>
        </div>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-[0_20px_50px_rgba(15,23,42,0.06)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.4)] p-7 sm:p-9 transition-all duration-300">

            {{-- Flash Messages --}}
            @if (session('status'))
                <div class="mb-5 p-3.5 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 rounded-xl flex items-center gap-2.5 text-xs font-semibold text-blue-700 dark:text-blue-300">
                    <i class="ph-bold ph-info text-base flex-shrink-0"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 p-3.5 bg-rose-50 dark:bg-rose-900/30 border border-rose-100 dark:border-rose-800 rounded-xl flex items-center gap-2.5 text-xs font-semibold text-rose-700 dark:text-rose-300">
                    <i class="ph-bold ph-warning-circle text-base flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @php
                $targetRedirect = request('redirect') ?: session('url.intended') ?: old('redirect');
            @endphp

            {{-- Form Body --}}
            <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-4">
                @csrf
                @if($targetRedirect)
                    <input type="hidden" name="redirect" value="{{ $targetRedirect }}">
                @endif

                {{-- Email Address --}}
                <div>
                    <label for="login-email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="ph-bold ph-envelope text-base"></i>
                        </div>
                        <input type="email"
                               name="email"
                               id="login-email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               placeholder="you@example.com"
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-slate-50/70 dark:bg-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-850 border @error('email') border-rose-300 dark:border-rose-700 focus:ring-rose-500/10 focus:border-rose-500 @else border-slate-200 dark:border-slate-700 focus:border-blue-600 focus:ring-blue-600/10 @enderror rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 transition-all duration-200 shadow-xs">
                    </div>
                    @error('email')
                        <p class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1.5 flex items-center gap-1">
                            <i class="ph-bold ph-warning text-xs"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="login-password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Password
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:underline transition-colors" title="Forgot Password?">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="ph-bold ph-lock-key text-base"></i>
                        </div>
                        <input type="password"
                               name="password"
                               id="login-password"
                               required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-11 py-2.5 sm:py-3 bg-slate-50/70 dark:bg-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-850 border @error('password') border-rose-300 dark:border-rose-700 focus:ring-rose-500/10 focus:border-rose-500 @else border-slate-200 dark:border-slate-700 focus:border-blue-600 focus:ring-blue-600/10 @enderror rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 transition-all duration-200 shadow-xs">
                        <button type="button"
                                onclick="togglePassword('login-password', this)"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors focus:outline-none"
                                aria-label="Toggle password visibility">
                            <i class="ph-bold ph-eye eye-open text-base"></i>
                            <i class="ph-bold ph-eye-slash eye-closed text-base hidden"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-600 dark:text-rose-400 text-xs font-semibold mt-1.5 flex items-center gap-1">
                            <i class="ph-bold ph-warning text-xs"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember Me Checkbox --}}
                <div class="flex items-center pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none group" for="login-remember">
                        <input type="checkbox"
                               name="remember"
                               id="login-remember"
                               class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/20 accent-blue-600 cursor-pointer">
                        <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition-colors">
                            Remember me on this device
                        </span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                            id="login-submit"
                            class="w-full py-3 sm:py-3.5 px-6 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] shadow-lg shadow-blue-600/25 hover:shadow-blue-600/35 transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Sign In</span>
                        <i class="ph-bold ph-arrow-right text-sm"></i>
                    </button>
                </div>
            </form>

            {{-- Bottom Register Link --}}
            <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 text-center">
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:underline transition-colors" title="Create an account">
                        Create one free
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

@endsection

@push('scripts')
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

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    if (!loginForm) return;

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('login-submit');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="ph-bold ph-spinner animate-spin text-sm"></i> Signing in...`;

        // Clear existing dynamic errors
        document.querySelectorAll('.ajax-error-msg').forEach(el => el.remove());

        async function submitAttempt(retryCount = 0) {
            try {
                const formData = new FormData(loginForm);
                const response = await fetch(loginForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 419) {
                    if (retryCount === 0) {
                        const tokenRes = await fetch('/csrf-token');
                        const tokenData = await tokenRes.json();
                        if (tokenData && tokenData.csrf_token) {
                            const csrfInput = loginForm.querySelector('input[name="_token"]');
                            if (csrfInput) csrfInput.value = tokenData.csrf_token;
                            const metaCsrf = document.querySelector('meta[name="csrf-token"]');
                            if (metaCsrf) metaCsrf.setAttribute('content', tokenData.csrf_token);
                            return submitAttempt(1);
                        }
                    }
                    throw new Error('Your session expired. Please refresh the page.');
                }

                const data = await response.json();

                if (response.ok && data.success) {
                    submitBtn.innerHTML = `<i class="ph-bold ph-check text-sm"></i> Redirecting...`;
                    submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    submitBtn.classList.add('bg-emerald-600');
                    window.location.href = data.redirect || '/';
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;

                    let errorMsg = data.message || 'The provided credentials do not match our records.';
                    if (data.errors) {
                        const firstKey = Object.keys(data.errors)[0];
                        errorMsg = data.errors[firstKey][0];
                    }

                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'ajax-error-msg mb-5 p-3.5 bg-rose-50 dark:bg-rose-900/30 border border-rose-100 dark:border-rose-800 rounded-xl flex items-center gap-2.5 text-xs font-semibold text-rose-700 dark:text-rose-300';
                    errorAlert.innerHTML = `<i class="ph-bold ph-warning-circle text-base flex-shrink-0"></i> <span>${errorMsg}</span>`;
                    loginForm.parentNode.insertBefore(errorAlert, loginForm);
                }
            } catch (err) {
                // Fallback to standard form submission
                loginForm.submit();
            }
        }

        submitAttempt();
    });
});
</script>
@endpush
