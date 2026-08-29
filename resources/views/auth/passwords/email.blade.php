@extends('layouts.app')

@section('title', 'Reset Password - UnlockRentals')
@section('robots', 'noindex, nofollow')

@section('content')

<section class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 relative overflow-hidden bg-slate-50 dark:bg-slate-950" id="reset-password-section">
    {{-- Ambient Background Glows --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-blue-100/60 via-indigo-50/30 to-transparent dark:from-blue-950/30 dark:via-transparent pointer-events-none blur-3xl -z-10"></div>
    <div class="absolute -top-24 right-1/4 w-96 h-96 bg-blue-400/10 dark:bg-blue-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute bottom-10 left-1/4 w-96 h-96 bg-indigo-400/10 dark:bg-indigo-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-[440px] relative z-10">

        {{-- Top Brand Icon / Badge --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 group transition-transform duration-200 hover:scale-[1.02]" title="UnlockRentals">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-600/25 ring-4 ring-blue-50 dark:ring-blue-900/30 transition-all duration-300 group-hover:shadow-blue-600/35">
                    <i class="ph-bold ph-key text-xl"></i>
                </div>
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-4">Forgot password?</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">No worries, we'll send you reset instructions</p>
        </div>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-[0_20px_50px_rgba(15,23,42,0.06)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.4)] p-7 sm:p-9 transition-all duration-300">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-5 p-3.5 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-800 rounded-xl flex items-center gap-2.5 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                    <i class="ph-bold ph-check-circle text-base flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 p-3.5 bg-rose-50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-800 rounded-xl flex items-center gap-2.5 text-xs font-semibold text-rose-700 dark:text-rose-300">
                    <i class="ph-bold ph-warning-circle text-base flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Form Body --}}
            <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form" class="space-y-4">
                @csrf

                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="ph-bold ph-envelope text-base"></i>
                        </div>
                        <input type="email"
                               name="email"
                               id="email"
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

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 sm:py-3.5 px-6 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] shadow-lg shadow-blue-600/25 hover:shadow-blue-600/35 transition-all duration-200 flex items-center justify-center gap-2">
                        <span>Send Reset Instructions</span>
                        <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                    </button>
                </div>
            </form>

            {{-- Back to Sign In Link --}}
            <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors group" title="Back to Sign In">
                    <i class="ph-bold ph-arrow-left transition-transform group-hover:-translate-x-1"></i>
                    <span>Back to Sign In</span>
                </a>
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
                <span>Secure Authentication</span>
            </div>
        </div>
    </div>
</section>

@endsection
