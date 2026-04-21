@extends('layouts.app')

@section('title', 'Create Account - UnlockRentals')

@section('content')

<section class="min-h-screen flex items-center justify-center py-16 px-4" id="register-section"
         style="background: linear-gradient(135deg, #f0f4ff 0%, #e8edf8 50%, #f5f6fa 100%);">
    <div class="w-full max-w-lg">

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
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <line x1="19" y1="8" x2="19" y2="14"></line>
                            <line x1="22" y1="11" x2="16" y2="11"></line>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Create Your Account</h1>
                    <p class="text-sm text-blue-200 mt-1.5">Join India's premium rental marketplace</p>
                </div>
            </div>

            {{-- Form Body --}}
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('register') }}" id="register-form">
                    @csrf

                    <div class="space-y-5">

                        {{-- Role Selection --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">I want to</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="role" value="tenant" {{ old('role', 'tenant') === 'tenant' ? 'checked' : '' }}
                                           class="sr-only peer" id="register-role-tenant">
                                    <div class="relative p-5 bg-gray-50 border-2 border-gray-200 rounded-xl text-center
                                                peer-checked:border-[#2563EB] peer-checked:bg-blue-50/60
                                                hover:border-gray-300 hover:bg-gray-50/80
                                                transition-all duration-200">
                                        <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-white border border-gray-200 flex items-center justify-center transition-all group-hover:shadow-sm">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-500"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-800">Find a Rental</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Browse as Tenant</p>
                                    </div>
                                    <div class="absolute top-2.5 right-2.5 w-5 h-5 rounded-full bg-[#2563EB] items-center justify-center hidden peer-checked:flex">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </label>

                                <label class="relative group cursor-pointer">
                                    <input type="radio" name="role" value="owner" {{ old('role') === 'owner' ? 'checked' : '' }}
                                           class="sr-only peer" id="register-role-owner">
                                    <div class="relative p-5 bg-gray-50 border-2 border-gray-200 rounded-xl text-center
                                                peer-checked:border-[#2563EB] peer-checked:bg-blue-50/60
                                                hover:border-gray-300 hover:bg-gray-50/80
                                                transition-all duration-200">
                                        <div class="w-11 h-11 mx-auto mb-3 rounded-xl bg-white border border-gray-200 flex items-center justify-center transition-all group-hover:shadow-sm">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-500"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-800">List Property</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Earn as Owner</p>
                                    </div>
                                    <div class="absolute top-2.5 right-2.5 w-5 h-5 rounded-full bg-[#2563EB] items-center justify-center hidden peer-checked:flex">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </label>
                            </div>
                            @error('role') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- Divider --}}
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-px bg-gray-200"></div>
                            <span class="text-xs text-gray-400 font-medium">Your Details</span>
                            <div class="flex-1 h-px bg-gray-200"></div>
                        </div>

                        {{-- Two columns: Name + Phone --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="register-name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Full Name *</label>
                                <div class="relative">
                                    <i class="ph ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="text" name="name" id="register-name" value="{{ old('name') }}" required autofocus
                                           class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 focus:bg-white transition-all"
                                           placeholder="John Doe">
                                </div>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="register-phone" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Phone</label>
                                <div class="relative">
                                    <i class="ph ph-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="tel" name="phone" id="register-phone" value="{{ old('phone') }}"
                                           class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 focus:bg-white transition-all"
                                           placeholder="+91 98765 43210">
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="register-email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email Address *</label>
                            <div class="relative">
                                <i class="ph ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                <input type="email" name="email" id="register-email" value="{{ old('email') }}" required
                                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 focus:bg-white transition-all"
                                       placeholder="you@example.com">
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Password + Confirm side by side --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="register-password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password *</label>
                                <div class="relative">
                                    <i class="ph ph-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="password" name="password" id="register-password" required
                                           class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 focus:bg-white transition-all"
                                           placeholder="Min 8 chars">
                                    <button type="button" onclick="togglePassword('register-password', this)"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2563EB] transition-colors focus:outline-none" aria-label="Toggle password">
                                        <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        <svg class="eye-closed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                    </button>
                                </div>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="register-password-confirm" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Confirm *</label>
                                <div class="relative">
                                    <i class="ph ph-lock-key absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                    <input type="password" name="password_confirmation" id="register-password-confirm" required
                                           class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 focus:bg-white transition-all"
                                           placeholder="Repeat password">
                                    <button type="button" onclick="togglePassword('register-password-confirm', this)"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2563EB] transition-colors focus:outline-none" aria-label="Toggle password">
                                        <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        <svg class="eye-closed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Terms --}}
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" required
                                   class="w-4 h-4 mt-0.5 rounded accent-[#2563EB] border-gray-300">
                            <span class="text-xs text-gray-500 leading-relaxed">
                                I agree to the <a href="#" class="text-[#2563EB] font-semibold hover:underline">Terms of Service</a>
                                and <a href="#" class="text-[#2563EB] font-semibold hover:underline">Privacy Policy</a>
                            </span>
                        </label>

                        {{-- Submit --}}
                        <button type="submit" id="register-submit"
                                class="w-full px-6 py-3.5 rounded-xl text-sm font-bold text-white tracking-wide transition-all duration-300 flex items-center justify-center gap-2 bg-[#2563EB] hover:bg-[#1D4ED8]"
                                style="box-shadow: 0 4px 20px rgba(37,99,235,0.3);"
                                onmouseover="this.style.boxShadow='0 8px 32px rgba(37,99,235,0.45)'; this.style.transform='translateY(-1px)'"
                                onmouseout="this.style.boxShadow='0 4px 20px rgba(37,99,235,0.3)'; this.style.transform='translateY(0)'">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sign in link --}}
        <p class="text-center text-sm text-gray-500 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[#2563EB] hover:text-[#1D4ED8] font-semibold transition-colors">Sign In</a>
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
