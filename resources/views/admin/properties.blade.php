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
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-[#2563EB] hover:text-[#2563EB] font-medium transition-colors flex items-center gap-1" title="Dashboard">
                <i class="ph ph-arrow-left"></i> Dashboard
            </a>
        </div>

        {{-- Status Filters & Bypass Approval Toggle --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.properties') }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ !request('status') ? 'bg-[#2563EB]/10 text-[#2563EB] border border-[#2563EB]/50' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}" title="All">
                    All
                </a>
                <a href="{{ route('admin.properties', ['status' => 'pending']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('status') === 'pending' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}" title="Pending">
                    Pending
                </a>
                <a href="{{ route('admin.properties', ['status' => 'approved']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('status') === 'approved' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}" title="Approved">
                    Approved
                </a>
                <a href="{{ route('admin.properties', ['status' => 'rejected']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('status') === 'rejected' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}" title="Rejected">
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
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Booked</th>
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
                                        <a href="{{ route('properties.show', $property) }}" class="text-sm font-medium text-zinc-900 hover:text-[#2563EB] transition-colors" title="UnlockRentals">{{ Str::limit($property->title, 30) }}</a>
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
                                <span class="booked-status-badge text-xs font-bold px-2.5 py-1 rounded-md border {{ $property->is_booked ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                    {{ $property->is_booked ? 'Booked' : 'Available' }}
                                </span>
                            </td>
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
                                    {{-- Toggle Booked Switch (AJAX) in Actions --}}
                                    <label class="booked-toggle-label" title="{{ $property->is_booked ? 'Booked (Click to mark Available)' : 'Available (Click to mark Booked)' }}">
                                        <input type="checkbox" 
                                               class="booked-toggle-input" 
                                               data-property-id="{{ $property->id }}"
                                               data-toggle-url="{{ route('properties.toggle-booked', $property) }}"
                                               onchange="handleBookedToggle(this)"
                                               {{ $property->is_booked ? 'checked' : '' }}>
                                        <div class="booked-toggle-track">
                                            <div class="booked-toggle-knob"></div>
                                        </div>
                                    </label>

                                    {{-- Update / Edit Property Button in Actions --}}
                                    <a href="{{ route('properties.edit', $property) }}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md transition-all shadow-sm border border-blue-200/50" title="Update / Edit Property">
                                        <i class="ph-bold ph-pencil-simple text-sm"></i>
                                    </a>

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

@push('scripts')
<script>
async function handleBookedToggle(input) {
    const label = input.closest('.booked-toggle-label');
    const url = input.dataset.toggleUrl;
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
    const newState = input.checked;

    if (label) label.classList.add('is-loading');

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        });

        const data = await response.json();

        if (response.ok && data.success) {
            input.checked = Boolean(data.is_booked);
            if (label) {
                label.title = data.is_booked 
                    ? 'Booked (Click to mark Available)' 
                    : 'Available (Click to mark Booked)';
            }
            const row = input.closest('tr');
            const badge = row?.querySelector('.booked-status-badge');
            if (badge) {
                if (data.is_booked) {
                    badge.className = 'booked-status-badge text-xs font-bold px-2.5 py-1 rounded-md border bg-rose-50 text-rose-700 border-rose-200';
                    badge.textContent = 'Booked';
                } else {
                    badge.className = 'booked-status-badge text-xs font-bold px-2.5 py-1 rounded-md border bg-emerald-50 text-emerald-700 border-emerald-200';
                    badge.textContent = 'Available';
                }
            }
            showAdminToast(data.message || (data.is_booked ? 'Property marked as Booked' : 'Property marked as Available'), 'success');
        } else {
            throw new Error(data.message || 'Failed to update property status');
        }
    } catch (err) {
        console.error('Toggle error:', err);
        input.checked = !newState;
        showAdminToast(err.message || 'Failed to update status. Please try again.', 'error');
    } finally {
        if (label) label.classList.remove('is-loading');
    }
}

function showAdminToast(message, type = 'success') {
    let container = document.getElementById('admin-ajax-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'admin-ajax-toast-container';
        container.className = 'fixed bottom-6 right-6 z-[9999] flex flex-col gap-2 pointer-events-none';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `pointer-events-auto px-4 py-3 rounded-xl shadow-xl border text-sm font-bold flex items-center gap-2.5 transition-all duration-300 transform translate-y-3 opacity-0 ${
        type === 'success' 
            ? 'bg-slate-900 text-white border-slate-700 shadow-slate-900/30' 
            : 'bg-rose-600 text-white border-rose-500 shadow-rose-600/30'
    }`;
    toast.innerHTML = `
        <i class="ph-bold ${type === 'success' ? 'ph-check-circle text-emerald-400' : 'ph-warning-circle text-white'} text-lg"></i>
        <span>${message}</span>
    `;

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-3', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
    }, 2800);
}
</script>
@endpush

@endsection
