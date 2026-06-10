@extends('layouts.admin')

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

        {{-- Status Filters & Bypass Approval Toggle --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex gap-2 flex-wrap">
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
            
            {{-- Direct Auto-Approve Toggle --}}
            <div class="flex items-center gap-3 bg-white px-4 py-2.5 border border-stone-200/50 rounded-sm shadow-sm">
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-zinc-800 uppercase tracking-wider">Bypass Approval</span>
                    <span class="text-[10px] text-zinc-400">Post directly to website</span>
                </div>
                <form id="bypass-toggle-form" action="{{ route('admin.properties.toggle-bypass') }}" method="POST" class="m-0 p-0 flex items-center">
                    @csrf
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="bypass-approval-checkbox" name="bypass_property_approval" value="1" {{ \App\Models\Setting::get('bypass_property_approval', '0') == '1' ? 'checked' : '' }} class="sr-only peer" onchange="document.getElementById('bypass-toggle-form').submit()">
                        <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2563EB]"></div>
                    </label>
                </form>
            </div>
        </div>

        {{-- Properties Table --}}
        <div class="bg-white border border-stone-200 rounded-xl shadow-sm overflow-hidden">
            @if($properties->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-stone-50 border-b border-stone-200">
                        <tr>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Property</th>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Owner</th>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Type</th>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Price</th>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Status</th>
                            <th class="text-center text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200/80">
                        @foreach($properties as $property)
                        <tr class="hover:bg-stone-50/50 transition-colors">
                            <td class="px-6 py-5">
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
                            <td class="px-6 py-5 text-sm font-medium text-zinc-600">{{ $property->owner->name ?? 'N/A' }}</td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1.5 bg-[#2563EB]/10 text-[#2563EB] border border-[#2563EB]/20 text-xs font-bold uppercase tracking-wider rounded-md">
                                    {{ ucfirst($property->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm text-zinc-900 font-bold tracking-tight">{{ $property->formatted_price }}</td>
                            <td class="px-6 py-5">
                                @php
                                    $statusColors = ['pending' => 'amber', 'approved' => 'emerald', 'rejected' => 'red'];
                                    $color = $statusColors[$property->status] ?? 'gray';
                                @endphp
                                <span class="px-3 py-1.5 bg-{{ $color }}-100 text-{{ $color }}-700 border border-{{ $color }}-200 text-xs font-bold uppercase tracking-wider rounded-md">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    @if($property->status !== 'approved')
                                    <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded-md transition-all shadow-sm border border-emerald-200/50" title="Approve">
                                            <i class="ph-bold ph-check text-sm"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($property->status !== 'rejected')
                                    <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-md transition-all shadow-sm border border-red-200/50" title="Reject">
                                            <i class="ph-bold ph-x text-sm"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('properties.show', $property) }}" class="w-8 h-8 flex items-center justify-center bg-zinc-50 text-zinc-500 hover:bg-zinc-800 hover:text-white rounded-md transition-all shadow-sm border border-zinc-200" title="View Property">
                                        <i class="ph-bold ph-eye text-sm"></i>
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
