@extends('layouts.admin')

@section('title', 'User Directory & Management - Admin CRM')

@section('content')
<div class="space-y-6 pb-12">

    {{-- Breadcrumbs & Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Admin CRM</a>
                <i class="ph-bold ph-caret-right text-[10px] text-slate-400"></i>
                <span class="font-semibold text-slate-800">User Directory</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <span>User Directory</span>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-200 text-xs font-bold">
                    {{ number_format($stats['total'] ?? $users->total()) }} Accounts
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage tenants, property owners, phone verifications, and subscription memberships.</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.subscriptions.assign') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm shadow-blue-500/25 active:scale-95 transition-all">
                <i class="ph-bold ph-plus-circle text-sm"></i>
                <span>Assign Plan Manually</span>
            </a>
            <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold shadow-xs active:scale-95 transition-all" title="Reset Filters">
                <i class="ph-bold ph-arrow-clockwise text-sm"></i>
                <span>Refresh</span>
            </a>
        </div>
    </div>

    {{-- KPI Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Users --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Users</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total'] ?? 0) }}</h3>
                <span class="text-[11px] font-semibold text-slate-500 mt-0.5 block">All registered accounts</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-xs">
                <i class="ph-bold ph-users"></i>
            </div>
        </div>

        {{-- Tenants --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Tenants</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($stats['tenants'] ?? 0) }}</h3>
                <span class="text-[11px] font-semibold text-slate-500 mt-0.5 block">Rent seekers</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                <i class="ph-bold ph-user-circle"></i>
            </div>
        </div>

        {{-- Owners / Landlords --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Owners / Hosts</p>
                <h3 class="text-2xl font-black text-indigo-600 mt-1">{{ number_format($stats['owners'] ?? 0) }}</h3>
                <span class="text-[11px] font-semibold text-slate-500 mt-0.5 block">Property posters</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-xs">
                <i class="ph-bold ph-buildings"></i>
            </div>
        </div>

        {{-- Phone Verified --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Phone Verified</p>
                <h3 class="text-2xl font-black text-teal-600 mt-1">{{ number_format($stats['verified'] ?? 0) }}</h3>
                <span class="text-[11px] font-semibold text-slate-500 mt-0.5 block">Security verified</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl shadow-xs">
                <i class="ph-bold ph-shield-check"></i>
            </div>
        </div>
    </div>

    {{-- Filter & Search Toolbar --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-soft space-y-3">
        <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            
            {{-- Search Bar --}}
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="ph-bold ph-magnifying-glass text-base"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name, email, or phone number..."
                       class="w-full pl-10 pr-10 py-2.5 bg-slate-50 hover:bg-slate-100/70 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                @if(request('search'))
                    <a href="{{ route('admin.users', array_merge(request()->except('search'), ['search' => ''])) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="ph-bold ph-x-circle text-base"></i>
                    </a>
                @endif
            </div>

            {{-- Hidden preserve other query params --}}
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            {{-- Filter Pills & Verification Selector --}}
            <div class="flex items-center gap-2 flex-wrap">
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-blue-600 transition-all cursor-pointer">
                    <option value="">All Verifications</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>✓ Phone Verified Only</option>
                    <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>⚠ Unverified Only</option>
                </select>

                <button type="submit" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1.5">
                    <i class="ph-bold ph-funnel text-sm"></i>
                    <span>Filter</span>
                </button>

                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users') }}" class="px-3 py-2 bg-rose-50 text-rose-600 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-100 transition-all flex items-center gap-1">
                        <i class="ph-bold ph-x text-xs"></i>
                        <span>Clear</span>
                    </a>
                @endif
            </div>
        </form>

        {{-- Role Tabs --}}
        <div class="pt-2 border-t border-slate-100 flex items-center gap-2 overflow-x-auto custom-scrollbar pb-1">
            <a href="{{ route('admin.users', request()->except(['role', 'page'])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ !request('role') ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/25' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/80' }}">
                <span>All Users</span>
                <span class="px-1.5 py-0.2 rounded-md {{ !request('role') ? 'bg-white/20 text-white' : 'bg-white text-slate-600' }} text-[10px]">{{ $stats['total'] ?? 0 }}</span>
            </a>

            <a href="{{ route('admin.users', array_merge(request()->except('page'), ['role' => 'tenant'])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ request('role') === 'tenant' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/25' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/80' }}">
                <i class="ph-bold ph-user-circle text-xs"></i>
                <span>Tenants</span>
                <span class="px-1.5 py-0.2 rounded-md {{ request('role') === 'tenant' ? 'bg-white/20 text-white' : 'bg-white text-slate-600' }} text-[10px]">{{ $stats['tenants'] ?? 0 }}</span>
            </a>

            <a href="{{ route('admin.users', array_merge(request()->except('page'), ['role' => 'owner'])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ request('role') === 'owner' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/25' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/80' }}">
                <i class="ph-bold ph-buildings text-xs"></i>
                <span>Owners</span>
                <span class="px-1.5 py-0.2 rounded-md {{ request('role') === 'owner' ? 'bg-white/20 text-white' : 'bg-white text-slate-600' }} text-[10px]">{{ $stats['owners'] ?? 0 }}</span>
            </a>

            <a href="{{ route('admin.users', array_merge(request()->except('page'), ['role' => 'admin'])) }}"
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ request('role') === 'admin' ? 'bg-amber-600 text-white shadow-sm shadow-amber-600/25' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/80' }}">
                <i class="ph-bold ph-shield-check text-xs"></i>
                <span>Admins</span>
                <span class="px-1.5 py-0.2 rounded-md {{ request('role') === 'admin' ? 'bg-white/20 text-white' : 'bg-white text-slate-600' }} text-[10px]">{{ $stats['admins'] ?? 0 }}</span>
            </a>
        </div>
    </div>

    {{-- Main Users Data Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-soft overflow-hidden">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5">User Profile</th>
                            <th class="py-3.5 px-4">Contact & Phone</th>
                            <th class="py-3.5 px-4">Role</th>
                            <th class="py-3.5 px-4">Membership Plan</th>
                            <th class="py-3.5 px-4">Activity</th>
                            <th class="py-3.5 px-4">Joined On</th>
                            <th class="py-3.5 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($users as $user)
                            @php
                                $userActivePlan = $user->activePlan();
                                $planName = $userActivePlan?->plan?->name;
                                $isVerified = !empty($user->phone_verified_at);
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                
                                {{-- User Name & Email --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white font-black text-sm flex items-center justify-center shadow-md shadow-blue-500/20 flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.users.activity', $user) }}" class="font-bold text-slate-900 group-hover:text-blue-600 transition-colors block truncate hover:underline" title="{{ $user->name }}">
                                                {{ $user->name }}
                                            </a>
                                            <p class="text-[11px] text-slate-500 truncate flex items-center gap-1.5 mt-0.5">
                                                <span>{{ $user->email }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Contact & Verification --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <p class="font-bold text-slate-900 flex items-center gap-1.5">
                                            <i class="ph-bold ph-phone text-slate-400 text-xs"></i>
                                            <span>{{ $user->formatted_phone ?: ($user->phone ?: 'No phone provided') }}</span>
                                        </p>
                                        @if($isVerified)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">
                                                <i class="ph-bold ph-check-circle text-xs"></i>
                                                <span>Phone Verified</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-semibold">
                                                <i class="ph-bold ph-warning-circle text-xs"></i>
                                                <span>Unverified</span>
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Role Badge --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-gradient-to-r from-amber-500/15 to-orange-500/15 text-amber-800 border border-amber-300/60 font-bold text-xs">
                                            <i class="ph-bold ph-shield-check text-amber-600"></i>
                                            <span>Admin</span>
                                        </span>
                                    @elseif($user->role === 'owner')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-gradient-to-r from-indigo-500/15 to-blue-500/15 text-indigo-800 border border-indigo-300/60 font-bold text-xs">
                                            <i class="ph-bold ph-buildings text-indigo-600"></i>
                                            <span>Owner</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 border border-slate-200/80 font-bold text-xs">
                                            <i class="ph-bold ph-user-circle text-slate-500"></i>
                                            <span>Tenant</span>
                                        </span>
                                    @endif
                                </td>

                                {{-- Membership Plan --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($userActivePlan)
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black text-[11px] shadow-xs">
                                                <i class="ph-bold ph-crown text-amber-300"></i>
                                                <span>{{ $planName ?? 'Pro Plan' }}</span>
                                            </span>
                                            <p class="text-[10px] text-slate-500 font-semibold">
                                                {{ $userActivePlan->remaining_contacts }} contacts left
                                            </p>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 text-slate-500 text-[11px] font-semibold">
                                            <span>Free Tier</span>
                                        </span>
                                    @endif
                                </td>

                                {{-- Activity --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5 text-slate-700 font-semibold text-xs">
                                            <i class="ph-bold ph-house text-blue-600"></i>
                                            <span>{{ $user->properties_count }} Properties</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-slate-500 text-[11px]">
                                            <i class="ph-bold ph-chat-circle-dots text-slate-400"></i>
                                            <span>{{ $user->inquiries_count ?? 0 }} Inquiries</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Joined On --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <p class="font-bold text-slate-800">{{ $user->created_at->format('M d, Y') }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $user->created_at->diffForHumans() }}</p>
                                </td>

                                {{-- Action Buttons --}}
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.users.activity', $user) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 font-bold text-xs transition-all border border-slate-200/80 hover:border-blue-300 shadow-2xs"
                                           title="View User Details & Activity">
                                            <span>View Details</span>
                                            <i class="ph-bold ph-arrow-right text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-500">
                <div>
                    Showing <span class="font-bold text-slate-900">{{ $users->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-900">{{ $users->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-900">{{ $users->total() }}</span> users
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="ph-bold ph-user-circle-gear"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mb-1">No users found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4">
                    @if(request()->hasAny(['search', 'role', 'status']))
                        No users match your active search or filters. Try clearing your search parameters.
                    @else
                        No user accounts have been registered on the platform yet.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs shadow-sm hover:bg-blue-700 transition-all">
                        <i class="ph-bold ph-arrow-clockwise text-sm"></i>
                        <span>Reset All Filters</span>
                    </a>
                @endif
            </div>
        @endif
    </div>

</div>
@endsection
