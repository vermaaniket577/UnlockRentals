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
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('billing.history') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-stone-200/60 hover:bg-stone-200 text-zinc-700 text-sm font-semibold rounded-sm transition-all" id="dash-billing-history">
                    <i class="ph ph-receipt"></i> Billing History
                </a>
                <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-sm font-semibold rounded-sm shadow-sm transition-all" id="dash-add-property">
                    <i class="ph ph-plus-circle"></i> List New Property
                </a>
            </div>
        </div>

        {{-- Exclusive Offers --}}
        @if(isset($privateOffers) && $privateOffers->count() > 0)
        <div class="mb-8 p-6 bg-indigo-50 border border-indigo-200 rounded-sm relative overflow-hidden shadow-sm">
            <div class="absolute top-0 right-0 bg-indigo-600 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-bl-sm">Exclusive Offer</div>
            <h2 class="text-xl font-bold text-indigo-900 mb-2">You Have a Custom Plan Offer!</h2>
            <p class="text-sm text-indigo-700 mb-4">An admin has assigned a special, private plan just for you.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($privateOffers as $offer)
                    <div class="bg-white p-4 rounded-sm border border-indigo-100 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            @if($offer->plan->image_path)
                                <img src="{{ asset('storage/' . $offer->plan->image_path) }}" class="w-12 h-12 object-cover rounded-sm border border-stone-200">
                            @else
                                <div class="w-12 h-12 bg-indigo-100 rounded-sm flex items-center justify-center text-indigo-500">
                                    <i class="ph-bold ph-gift text-xl"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-zinc-900">{{ $offer->plan->name }}</p>
                                <p class="text-xs text-zinc-500 mt-0.5">
                                    @if($offer->discounted_price)
                                        <span class="line-through text-zinc-400 mr-1">{{ $offer->plan->formatted_price }}</span>
                                    @endif
                                    <span class="font-bold text-indigo-600">{{ $offer->formatted_effective_price }}</span> • {{ $offer->plan->duration_days }} Days • {{ $offer->plan->contact_limit }} Unlocks
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('plans.checkout', $offer->plan) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-sm transition-colors whitespace-nowrap ml-4">
                            Buy Now
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

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
                            <th class="text-left text-xs font-medium text-zinc-500 uppercase tracking-wider px-6 py-3">Booked</th>
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
                                <form method="POST" action="{{ route('properties.toggle-booked', $property) }}" class="inline-block align-middle">
                                    @csrf
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_booked" class="sr-only peer" onchange="this.form.submit()" {{ $property->is_booked ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2563EB]"></div>
                                        <span class="ml-2 text-xs font-semibold {{ $property->is_booked ? 'text-red-500 font-bold' : 'text-zinc-400' }}">
                                            {{ $property->is_booked ? 'Booked' : 'Available' }}
                                        </span>
                                    </label>
                                </form>
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
