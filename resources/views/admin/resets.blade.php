@extends('layouts.admin')

@section('title', 'Manage Password Resets - Admin Panel')

@section('content')
<section class="py-8 lg:py-12" id="admin-resets">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-medium text-zinc-900 mb-1">Password Reset Requests</h1>
                <p class="text-zinc-500">Monitor active reset tokens or manually trigger resets for platform users</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#2563EB] hover:underline flex items-center gap-2">
                <i class="ph ph-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Resets List --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-stone-200/50 rounded-sm shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-stone-100 flex justify-between items-center">
                        <h2 class="text-sm font-semibold text-zinc-900">Active Reset Tokens</h2>
                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-xs font-semibold rounded-full">{{ $resets->total() }} Active</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-stone-50 border-b border-stone-100 text-xs uppercase tracking-wider font-bold text-zinc-500">
                                <tr>
                                    <th class="px-6 py-3">Email Address</th>
                                    <th class="px-6 py-3">Token Info</th>
                                    <th class="px-6 py-3">Requested At</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-sm">
                                @forelse($resets as $reset)
                                <tr class="hover:bg-stone-50/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-zinc-950">
                                        {{ $reset->email }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono text-zinc-400">
                                        {{ substr($reset->token, 0, 16) }}...
                                    </td>
                                    <td class="px-6 py-4 text-xs text-zinc-500">
                                        {{ \Carbon\Carbon::parse($reset->created_at)->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                                        {{-- Resend --}}
                                        <form method="POST" action="{{ route('admin.resets.send') }}">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ $reset->email }}">
                                            <button type="submit" class="text-xs bg-stone-50 hover:bg-indigo-50 border border-stone-200 hover:border-indigo-200 text-zinc-600 hover:text-indigo-600 px-2.5 py-1.5 rounded-sm font-medium transition-all" title="Resend Reset Email">
                                                Resend
                                            </button>
                                        </form>

                                        {{-- Invalidate --}}
                                        <form method="POST" action="{{ route('admin.resets.delete', ['email' => $reset->email]) }}" onsubmit="return confirm('Are you sure you want to invalidate this reset token?')">
                                            @csrf
                                            <button type="submit" class="text-xs bg-stone-50 hover:bg-red-50 border border-stone-200 hover:border-red-200 text-zinc-600 hover:text-red-600 px-2.5 py-1.5 rounded-sm font-medium transition-all" title="Invalidate Token">
                                                Invalidate
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-zinc-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="ph ph-key text-4xl text-zinc-300 mb-2"></i>
                                            <span>No active password reset requests currently pending.</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($resets->hasPages())
                    <div class="px-6 py-4 border-t border-stone-100 bg-stone-50">
                        {{ $resets->links() }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Trigger Form --}}
            <div>
                <div class="bg-white border border-stone-200/50 rounded-sm shadow-sm p-6 sticky top-6">
                    <h2 class="text-sm font-bold text-zinc-950 mb-4 flex items-center gap-2">
                        <i class="ph ph-paper-plane-tilt text-[#2563EB]"></i>
                        Trigger Reset Email
                    </h2>
                    
                    <p class="text-xs text-zinc-500 mb-6 leading-relaxed">
                        Manually trigger and dispatch a secure password reset link to any registered user email. The system will auto-generate the token and dispatch it via your configured SMTP mail settings.
                    </p>

                    <form method="POST" action="{{ route('admin.resets.send') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-wider mb-2">User Email Address</label>
                            <input type="email" name="email" required placeholder="name@example.com" class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB] focus:ring-2 focus:ring-[#2563EB]/10 transition-all">
                        </div>

                        <button type="submit" class="w-full py-3 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs font-semibold rounded-sm shadow-sm transition-all tracking-wider uppercase">
                            Send Secure Reset Link
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
