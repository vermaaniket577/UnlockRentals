@extends('layouts.admin')

@section('title', 'Moderate Reviews | UnlockRentals CRM')

@section('content')
<div class="p-6 space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i class="ph-bold ph-star text-amber-500"></i>
                Review Moderation CRM
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Approve, reject, or flag customer ratings & reviews before or after publishing</p>
        </div>

        <a href="{{ route('admin.professionals.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <i class="ph-fill ph-check-circle text-base text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter by Status --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-bold">
        @foreach(['all' => 'All Reviews', 'pending' => 'Pending Review', 'approved' => 'Approved / Live', 'flagged' => 'Flagged', 'rejected' => 'Rejected'] as $st => $label)
            <a href="{{ route('admin.professionals.reviews', $st !== 'all' ? ['status' => $st] : []) }}" class="px-3 py-1.5 rounded-xl transition-all whitespace-nowrap {{ (request('status') === $st || (!request('status') && $st === 'all')) ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Reviews Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
        @if($reviews->count() > 0)
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($reviews as $rev)
                    <div class="p-5 flex flex-col sm:flex-row items-start justify-between gap-4">
                        <div class="space-y-2 flex-1">
                            <div class="flex items-center gap-3">
                                <div class="flex text-amber-500 text-xs">
                                    @for($i=1; $i<=$rev->rating; $i++) <i class="ph-fill ph-star"></i> @endfor
                                </div>
                                <span class="font-bold text-xs text-slate-900 dark:text-white">{{ $rev->title }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $rev->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($rev->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $rev->status }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">{{ $rev->review }}</p>

                            <div class="flex flex-wrap items-center gap-4 text-[11px] text-slate-400">
                                <span>Customer: <strong class="text-slate-700 dark:text-slate-300">{{ $rev->user?->name ?? 'User' }}</strong></span>
                                <span>Professional: <a href="{{ route('admin.professionals.show', $rev->professional_id) }}" class="text-blue-600 font-bold hover:underline">{{ $rev->professional->business_name }}</a></span>
                                <span>{{ $rev->created_at->format('M d, Y • h:i A') }}</span>
                            </div>
                        </div>

                        {{-- Moderation Form --}}
                        <form action="{{ route('admin.professionals.reviews.moderate', $rev->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <select name="status" class="text-xs py-1 px-2 rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                                <option value="approved" {{ $rev->status === 'approved' ? 'selected' : '' }}>Approve</option>
                                <option value="rejected" {{ $rev->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                                <option value="flagged" {{ $rev->status === 'flagged' ? 'selected' : '' }}>Flag</option>
                            </select>
                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-blue-600 text-white font-bold text-xs whitespace-nowrap">
                                Update
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $reviews->links() }}
            </div>
        @else
            <div class="text-center py-12 text-slate-400">
                <p class="text-xs">No customer reviews in this filter.</p>
            </div>
        @endif
    </div>

</div>
@endsection
