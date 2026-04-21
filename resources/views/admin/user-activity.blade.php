@extends('layouts.app')

@section('title', 'User Activity - ' . $user->name)

@section('content')
<div class="py-12 bg-stone-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <a href="{{ route('admin.subscriptions') }}" class="text-sm text-zinc-500 hover:text-[#2563EB] flex items-center gap-1 mb-4">
                <i class="ph ph-arrow-left"></i> Back to Subscriptions
            </a>
            
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-[#2563EB]/10 flex items-center justify-center text-[#2563EB] text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-serif font-light text-zinc-900">{{ $user->name }}</h1>
                    <div class="flex gap-4 mt-1">
                        <span class="text-sm text-zinc-500 flex items-center gap-1">
                            <i class="ph ph-envelope"></i> {{ $user->email }}
                        </span>
                        <span class="text-sm text-zinc-500 flex items-center gap-1">
                            <i class="ph ph-phone"></i> {{ $user->phone ?? '+91 7974164274' }}
                        </span>
                        <span class="text-xs px-2 py-0.5 bg-stone-200 text-stone-600 rounded-sm uppercase font-bold tracking-tight">
                            {{ $user->role }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Sidebar: Plan Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm">
                    <h2 class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-4">Current Subscription</h2>
                    @php $activePlan = $user->activePlan(); @endphp
                    @if($activePlan)
                        <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-sm mb-4">
                            <p class="font-bold text-zinc-900">{{ $activePlan->plan->name }}</p>
                            <p class="text-xs text-zinc-500 mt-1">Expires {{ $activePlan->expires_at->format('d M, Y') }}</p>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-500">Unlocks Used</span>
                                <span class="text-zinc-900 font-bold">{{ $activePlan->contacts_used }} / {{ $activePlan->plan->contact_limit }}</span>
                            </div>
                            <div class="w-full bg-stone-100 h-1 rounded-full overflow-hidden">
                                <div class="bg-[#2563EB] h-full" style="width: {{ ($activePlan->contacts_used / $activePlan->plan->contact_limit) * 100 }}%"></div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-zinc-400 italic">No active subscription</p>
                    @endif
                </div>

                <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm">
                    <h2 class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-4">User Statistics</h2>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="p-3 bg-stone-50 rounded-sm">
                            <p class="text-xl font-bold text-zinc-900">{{ $user->inquiries->count() }}</p>
                            <p class="text-[10px] text-zinc-500 uppercase">Inquiries</p>
                        </div>
                        <div class="p-3 bg-stone-50 rounded-sm">
                            <p class="text-xl font-bold text-zinc-900">{{ $contactViews->count() }}</p>
                            <p class="text-[10px] text-zinc-500 uppercase">Total Unlocks</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content: Activity Timeline -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Unlocked Properties -->
                <div class="bg-white border border-stone-200 rounded-sm overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-stone-100 flex justify-between items-center">
                        <h2 class="text-sm font-bold text-zinc-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph ph-lock-open text-blue-500"></i> Property Unlocks
                        </h2>
                    </div>
                    @if($contactViews->count() > 0)
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-stone-50 text-xs font-bold text-zinc-400 uppercase">
                                    <th class="text-left px-6 py-3">Property</th>
                                    <th class="text-left px-6 py-3">Unlocked At</th>
                                    <th class="text-right px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @foreach($contactViews as $view)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-zinc-900">{{ $view->property->title }}</p>
                                            <p class="text-[10px] text-zinc-500">{{ $view->property->location }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-zinc-500 text-xs">
                                            {{ $view->created_at->format('d M y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('properties.show', $view->property) }}" target="_blank" class="text-blue-500 hover:underline text-xs">View Listing</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-12 text-center">
                            <i class="ph ph-magnifying-glass text-4xl text-stone-200 mb-2"></i>
                            <p class="text-sm text-zinc-400">No properties unlocked yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Inquiries -->
                <div class="bg-white border border-stone-200 rounded-sm overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-stone-100 flex justify-between items-center">
                        <h2 class="text-sm font-bold text-zinc-900 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph ph-paper-plane-tilt text-emerald-500"></i> Recent Inquiries
                        </h2>
                    </div>
                    @if($user->inquiries->count() > 0)
                        <div class="divide-y divide-stone-100">
                            @foreach($user->inquiries->sortByDesc('created_at') as $inquiry)
                                <div class="px-6 py-4 hover:bg-stone-50/50">
                                    <div class="flex justify-between mb-1">
                                        <p class="font-medium text-zinc-900">{{ $inquiry->property->title }}</p>
                                        <span class="text-[10px] text-zinc-400">{{ $inquiry->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-zinc-600 italic">"{{ Str::limit($inquiry->message, 100) }}"</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="ph ph-chat-circle text-4xl text-stone-200 mb-2"></i>
                            <p class="text-sm text-zinc-400">No inquiries sent yet.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
