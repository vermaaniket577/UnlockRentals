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

            {{-- Social Login Buttons --}}
            <div class="grid grid-cols-2 gap-3 mb-6">
                <a href="{{ route('social.redirect', 'google') }}"
                   class="flex items-center justify-center gap-2.5 px-4 py-2.5 bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 transition-all duration-200 active:scale-[0.99] shadow-xs"
                   title="Sign in with Google">
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span>Google</span>
                </a>

                <a href="{{ route('social.redirect', 'facebook') }}"
                   class="flex items-center justify-center gap-2.5 px-4 py-2.5 bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 transition-all duration-200 active:scale-[0.99] shadow-xs"
                   title="Sign in with Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Facebook</span>
                </a>
            </div>

            {{-- Divider --}}
            <div class="relative flex items-center justify-center my-6">
                <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
                <span class="bg-white dark:bg-slate-900 px-3 text-xs font-medium text-slate-400 dark:text-slate-500 uppercase tracking-wider absolute">or continue with email</span>
            </div>

            {{-- Form Body --}}
            <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-4">
                @csrf

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
</script>
@endpush
