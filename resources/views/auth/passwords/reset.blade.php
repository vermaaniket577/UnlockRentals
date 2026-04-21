@extends('layouts.app')

@section('title', 'Change Password - UnlockRentals')

@section('content')

<section class="min-h-screen flex items-center justify-center py-16 px-4" id="reset-form-section"
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
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Setup New Password</h1>
                    <p class="text-sm text-blue-200 mt-1.5">Please choose a strong password</p>
                </div>
            </div>

            {{-- Form Body --}}
            <div class="px-8 py-8">
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-xl">
                        <p class="text-sm text-red-600 font-medium italic">
                            <i class="ph-bold ph-warning-circle mr-1"></i> {{ session('error') }}
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" id="reset-password-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="space-y-5">
                        {{-- Email (Read-only or Pre-filled) --}}
                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Current Email</label>
                            <div class="relative">
                                <i class="ph ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}" required
                                       class="w-full pl-10 pr-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm text-gray-500 cursor-not-allowed"
                                       readonly>
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">New Password</label>
                            <div class="relative">
                                <i class="ph ph-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                <input type="password" name="password" id="password" required
                                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 focus:bg-white transition-all"
                                       placeholder="••••••••">
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                            <div class="relative">
                                <i class="ph ph-lock-key absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/20 focus:bg-white transition-all"
                                       placeholder="••••••••">
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                                class="w-full px-6 py-3.5 mt-2 rounded-xl text-sm font-bold text-white tracking-wide transition-all duration-300 flex items-center justify-center gap-2 bg-[#2563EB] hover:bg-[#1D4ED8]"
                                style="box-shadow: 0 4px 20px rgba(37,99,235,0.3);"
                                onmouseover="this.style.boxShadow='0 8px 32px rgba(37,99,235,0.45)'; this.style.transform='translateY(-1px)'"
                                onmouseout="this.style.boxShadow='0 4px 20px rgba(37,99,235,0.3)'; this.style.transform='translateY(0)'">
                            Update & Sign In
                            <i class="ph ph-check-circle"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Support Info --}}
        <div class="mt-8 text-center text-xs text-gray-400">
            Need help? Contact our <a href="#" class="text-[#2563EB] font-semibold hover:underline">Support Team</a>
        </div>
    </div>
</section>

@endsection
