@extends('layouts.app')

@section('title', 'Owner Dashboard - UnlockRentals')

@section('content')

<section class="py-8 lg:py-12" id="owner-dashboard">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-medium text-zinc-900 mb-1">Owner Dashboard</h1>
                <p class="text-zinc-500">Welcome back, {{ auth()->user()->name }}!</p>
            </div>
            <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-sm font-semibold rounded-sm shadow-sm transition-all" id="dash-add-property">
                <i class="ph ph-plus-circle"></i> List New Property
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6" id="dash-stat-properties">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#2563EB]/10 rounded-sm flex items-center justify-center">
                        <i class="ph ph-buildings text-2xl text-[#2563EB]"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-medium text-zinc-900">{{ $properties->total() }}</p>
                        <p class="text-sm text-zinc-500">My Properties</p>
                    </div>
                </div>
            </div>
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6" id="dash-stat-inquiries">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-600/10 rounded-sm flex items-center justify-center">
                        <i class="ph ph-chat-dots text-2xl text-indigo-500"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-medium text-zinc-900">{{ $totalInquiries }}</p>
                        <p class="text-sm text-zinc-500">Total Inquiries</p>
                    </div>
                </div>
            </div>
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6" id="dash-stat-unread">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-sm flex items-center justify-center">
                        <i class="ph ph-bell-ringing text-2xl text-amber-400"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-medium text-zinc-900">{{ $unreadInquiries }}</p>
                        <p class="text-sm text-zinc-500">Unread Inquiries</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Properties Table --}}
        <div class="bg-stone-50 border border-stone-200/50 rounded-sm overflow-hidden" id="dash-properties-table">
            <div class="px-6 py-5 border-b border-stone-200/50 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-zinc-900">My Properties</h2>
                <a href="{{ route('inquiries.index') }}" class="text-sm text-[#2563EB] hover:text-[#2563EB] font-medium transition-colors flex items-center gap-1">
                    View Inquiries <i class="ph ph-arrow-right"></i>
                </a>
            </div>

            @if($properties->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Property</th>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Type</th>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Price</th>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Status</th>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($properties as $property)
                        <tr class="hover:bg-stone-50/[0.02] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($property->primaryImage)
                                        <img src="{{ $property->primaryImage->imageUrl() }}" class="w-12 h-12 rounded-sm object-cover border border-stone-200/50" alt="">
                                    @else
                                        <div class="w-12 h-12 rounded-sm bg-stone-50 flex items-center justify-center">
                                            <i class="ph ph-image text-zinc-500"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-zinc-900">{{ Str::limit($property->title, 35) }}</p>
                                        <p class="text-xs text-zinc-500">{{ $property->location }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-{{ $property->type === 'house' ? 'violet' : 'fuchsia' }}-500/10 text-{{ $property->type === 'house' ? 'violet' : 'fuchsia' }}-400 text-xs font-medium rounded-sm">
                                    {{ ucfirst($property->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-900 font-medium">{{ $property->formatted_price }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = ['pending' => 'amber', 'approved' => 'emerald', 'rejected' => 'red'];
                                    $color = $statusColors[$property->status] ?? 'gray';
                                @endphp
                                <span class="px-2.5 py-1 bg-{{ $color }}-500/10 text-{{ $color }}-400 text-xs font-medium rounded-sm">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('properties.show', $property) }}" class="p-2 text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 rounded-sm transition-all" title="View">
                                        <i class="ph ph-eye"></i>
                                    </a>
                                    <a href="{{ route('properties.edit', $property) }}" class="p-2 text-zinc-500 hover:text-[#2563EB] hover:bg-[#2563EB]/10 rounded-sm transition-all" title="Edit">
                                        <i class="ph ph-pencil-simple"></i>
                                    </a>
                                    <form method="POST" action="{{ route('properties.destroy', $property) }}" onsubmit="return confirm('Delete this property?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-zinc-500 hover:text-red-400 hover:bg-red-500/5 rounded-sm transition-all" title="Delete">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-stone-200/50">
                {{ $properties->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <i class="ph ph-house text-5xl text-gray-700 mb-4"></i>
                <h3 class="text-xl font-semibold text-zinc-500 mb-2">No properties yet</h3>
                <p class="text-zinc-500 mb-6">Start by listing your first property.</p>
                <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#2563EB] text-white text-sm font-semibold rounded-sm transition-all">
                    <i class="ph ph-plus-circle"></i> List a Property
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection
