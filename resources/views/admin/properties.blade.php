@extends('layouts.app')

@section('title', 'Manage Properties - Admin - UnlockRentals')

@section('content')

<section class="py-8 lg:py-12" id="admin-properties">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-medium text-zinc-900 mb-1">All Properties</h1>
                <p class="text-zinc-500">Manage and review property listings</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-[#2563EB] hover:text-[#2563EB] font-medium transition-colors flex items-center gap-1">
                <i class="ph ph-arrow-left"></i> Dashboard
            </a>
        </div>

        {{-- Status Filters --}}
        <div class="flex gap-2 mb-6 flex-wrap">
            <a href="{{ route('admin.properties') }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ !request('status') ? 'bg-[#2563EB]/10 text-[#2563EB] border border-[#2563EB]/50' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}">
                All
            </a>
            <a href="{{ route('admin.properties', ['status' => 'pending']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('status') === 'pending' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}">
                Pending
            </a>
            <a href="{{ route('admin.properties', ['status' => 'approved']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('status') === 'approved' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}">
                Approved
            </a>
            <a href="{{ route('admin.properties', ['status' => 'rejected']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('status') === 'rejected' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}">
                Rejected
            </a>
        </div>

        {{-- Properties Table --}}
        <div class="bg-stone-50 border border-stone-200/50 rounded-sm overflow-hidden">
            @if($properties->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Property</th>
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Owner</th>
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
                                        <img src="{{ $property->primaryImage->imageUrl() }}" class="w-10 h-10 rounded-sm object-cover border border-stone-200/50" alt="">
                                    @else
                                        <div class="w-10 h-10 rounded-sm bg-stone-50 flex items-center justify-center">
                                            <i class="ph ph-image text-zinc-500 text-sm"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('properties.show', $property) }}" class="text-sm font-medium text-zinc-900 hover:text-[#2563EB] transition-colors">{{ Str::limit($property->title, 30) }}</a>
                                        <p class="text-xs text-zinc-500">{{ $property->location }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-500">{{ $property->owner->name ?? 'N/A' }}</td>
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
                                <div class="flex items-center gap-1">
                                    @if($property->status !== 'approved')
                                    <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                                        @csrf
                                        <button type="submit" class="p-2 text-emerald-400 hover:bg-emerald-500/10 rounded-sm transition-all" title="Approve">
                                            <i class="ph ph-check-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($property->status !== 'rejected')
                                    <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                                        @csrf
                                        <button type="submit" class="p-2 text-red-400 hover:bg-red-500/10 rounded-sm transition-all" title="Reject">
                                            <i class="ph ph-x-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('properties.show', $property) }}" class="p-2 text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 rounded-sm transition-all" title="View">
                                        <i class="ph ph-eye"></i>
                                    </a>
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
                <i class="ph ph-buildings text-5xl text-gray-700 mb-4"></i>
                <h3 class="text-xl font-semibold text-zinc-500">No properties found</h3>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection
