@extends('layouts.app')

@section('title', 'Manage Users - Admin - UnlockRentals')

@section('content')

<section class="py-8 lg:py-12" id="admin-users">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-medium text-zinc-900 mb-1">All Users</h1>
                <p class="text-zinc-500">Manage platform users</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-[#2563EB] hover:text-[#2563EB] font-medium transition-colors flex items-center gap-1">
                <i class="ph ph-arrow-left"></i> Dashboard
            </a>
        </div>

        {{-- Role Filters --}}
        <div class="flex gap-2 mb-6 flex-wrap">
            <a href="{{ route('admin.users') }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ !request('role') ? 'bg-[#2563EB]/10 text-[#2563EB] border border-[#2563EB]/50' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}">
                All
            </a>
            <a href="{{ route('admin.users', ['role' => 'owner']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('role') === 'owner' ? 'bg-indigo-600/20 text-indigo-500 border border-indigo-600/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}">
                Owners
            </a>
            <a href="{{ route('admin.users', ['role' => 'tenant']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('role') === 'tenant' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}">
                Tenants
            </a>
            <a href="{{ route('admin.users', ['role' => 'admin']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('role') === 'admin' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}">
                Admins
            </a>
        </div>

        {{-- Users Table --}}
        <div class="bg-stone-50 border border-stone-200/50 rounded-sm overflow-hidden">
            @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">User</th>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Role</th>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Properties</th>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($users as $user)
                        <tr class="hover:bg-stone-50/[0.02] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#2563EB] rounded-full flex items-center justify-center text-zinc-900 text-sm font-medium">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-zinc-900">{{ $user->name }}</p>
                                        <p class="text-xs text-zinc-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleColors = ['admin' => 'amber', 'owner' => 'fuchsia', 'tenant' => 'emerald'];
                                    $roleColor = $roleColors[$user->role] ?? 'gray';
                                @endphp
                                <span class="px-2.5 py-1 bg-{{ $roleColor }}-500/10 text-{{ $roleColor }}-400 text-xs font-medium rounded-sm">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-500">{{ $user->properties_count }}</td>
                            <td class="px-6 py-4 text-sm text-zinc-500">{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-stone-200/50">
                {{ $users->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <i class="ph ph-users text-5xl text-gray-700 mb-4"></i>
                <h3 class="text-xl font-semibold text-zinc-500">No users found</h3>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection
