@extends('layouts.app')

@section('title', 'Sign In - UnlockRentals')

@section('content')

<section class="min-h-screen flex items-center justify-center py-16 px-4" id="login-section"
         style="background: linear-gradient(135deg, #f0f4ff 0%, #e8edf8 50%, #f5f6fa 100%);">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl shadow-blue-900/5 border border-blue-100/50 overflow-hidden">

            {{-- Header Banner --}}
            <div class="relative px-8 pt-10 pb-8 text-center"
                 style="background: linear-gradient(135deg, #1e3a8a 0%, #2563EB 100%);">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-sky-400/15 rounded-full blur-2xl"></div>

                <div class="relative z-10">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm border border-white/20"
                         style="box-shadow: 0 8px 32px rgba(37,99,235,0.3);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="7.5" cy="15.5" r="5.5"></circle>
                            <path d="m21 2-9.6 9.6"></path>
                            <path d="m15.5 7.5 3 3L22 7l-3-3"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Welcome Back</h1>
                    <p class="text-sm text-blue-200 mt-1.5">Sign in to your UnlockRentals account</p>
                </div>
            </div>

            {{-- Form Body --}}
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('login') }}" id="login-form">
                    @csrf

                    <div class="space-y-5">
                        {{-- Email --}}
                        <div>
                            <label for="login-email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email Address</label>
                            <div class="relative">
                                <i class="ph ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                <input type="email" name="email" id="login-email" value="{{ old('email') }}" required autofocus
                                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 focus:bg-white transition-all"
                                       placeholder="you@example.com">
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="login-password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password</label>
                            <div class="relative">
                                <i class="ph ph-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                <input type="password" name="password" id="login-password" required
                                       class="w-full pl-10 pr-11 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 focus:bg-white transition-all"
                                       placeholder="••••••••">
                                <button type="button" onclick="togglePassword('login-password', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2563EB] transition-colors focus:outline-none" aria-label="Toggle password">
                                    <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    <svg class="eye-closed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Remember + Forgot --}}
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2.5 cursor-pointer" for="login-remember">
                                <input type="checkbox" name="remember" id="login-remember"
                                       class="w-4 h-4 rounded accent-[#2563EB] border-gray-300">
                                <span class="text-sm text-gray-500">Remember me</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-xs text-[#2563EB] font-semibold hover:underline">Forgot password?</a>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" id="login-submit"
                                class="w-full px-6 py-3.5 rounded-xl text-sm font-bold text-white tracking-wide transition-all duration-300 flex items-center justify-center gap-2 bg-[#2563EB] hover:bg-[#1D4ED8]"
                                style="box-shadow: 0 4px 20px rgba(37,99,235,0.3);"
                                onmouseover="this.style.boxShadow='0 8px 32px rgba(37,99,235,0.45)'; this.style.transform='translateY(-1px)'"
                                onmouseout="this.style.boxShadow='0 4px 20px rgba(37,99,235,0.3)'; this.style.transform='translateY(0)'">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                            Sign In
                        </button>

                        {{-- Social divider --}}
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-px bg-gray-200"></div>
                            <span class="text-xs text-gray-400">or continue with</span>
                            <div class="flex-1 h-px bg-gray-200"></div>
                        </div>

                        {{-- Social buttons --}}
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-500 hover:bg-gray-100 transition-all">
                                <svg width="16" height="16" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                                Google
                            </a>
                            <a href="{{ route('social.redirect', 'facebook') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-500 hover:bg-gray-100 transition-all">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sign up link --}}
        <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[#2563EB] hover:text-[#1D4ED8] font-semibold transition-colors">Create one</a>
        </p>

        {{-- Trust badges --}}
        <div class="flex items-center justify-center gap-6 mt-6">
            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                SSL Secured
            </div>
            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Verified Platform
            </div>
            <div class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Privacy First
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
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';
    } else {
        input.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';
    }
}
</script>
@endpush
