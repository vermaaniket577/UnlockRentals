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

            @php
                $targetRedirect = request('redirect') ?: session('url.intended') ?: old('redirect');
            @endphp

            {{-- Form Body --}}
            <form method="POST" action="{{ route('register') }}" id="register-form" class="space-y-4">
                @csrf
                @if($targetRedirect)
                    <input type="hidden" name="redirect" value="{{ $targetRedirect }}">
                @endif

                <input type="hidden" name="role" value="tenant">

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
                            Phone Number
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph-bold ph-phone text-base"></i>
                            </div>
                            <input type="tel"
                                   name="phone"
                                   id="register-phone"
                                   value="{{ old('phone') }}"
                                   placeholder="+91 98765 43210"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50/70 dark:bg-slate-800/60 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-850 border border-slate-200 dark:border-slate-700 focus:border-blue-600 focus:ring-blue-600/10 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 transition-all duration-200 shadow-xs">
                        </div>
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
                        <span>Create Free Account</span>
                        <i class="ph-bold ph-arrow-right text-sm"></i>
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
