@extends('layouts.app')

@section('title', 'Customer Feedback - Admin')

@section('content')
<div class="py-12 bg-stone-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-serif font-light text-zinc-900 mb-2">Customer Feedback</h1>
                <p class="text-zinc-500 text-sm">Review user ratings and suggestions to improve the platform.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.feedback') }}" class="px-4 py-2 bg-white border border-stone-200 text-zinc-600 text-xs font-bold uppercase tracking-widest rounded-sm hover:bg-stone-50 transition-all">All</a>
                <a href="{{ route('admin.feedback', ['status' => 'new']) }}" class="px-4 py-2 bg-white border border-stone-200 text-zinc-600 text-xs font-bold uppercase tracking-widest rounded-sm hover:bg-stone-50 transition-all">New</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-stone-200 rounded-sm overflow-hidden shadow-sm shadow-stone-100/50">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 border-bottom border-stone-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em]">User</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em]">Rating</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em]">Comment</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em]">Date</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em]">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($feedbacks as $feedback)
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center text-xs font-bold text-zinc-400 uppercase">
                                    {{ substr($feedback->user->name ?? 'G', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-zinc-900">{{ $feedback->user->name ?? 'Guest User' }}</div>
                                    <div class="text-[10px] text-zinc-500">{{ $feedback->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ph-fill ph-star text-sm {{ $i <= $feedback->rating ? 'text-amber-400' : 'text-zinc-200' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-zinc-600 max-w-md line-clamp-2" title="{{ $feedback->comment }}">
                                {{ $feedback->comment ?: 'No comment provided.' }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-xs text-zinc-500">
                            {{ $feedback->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full {{ $feedback->status == 'new' ? 'bg-amber-50 text-amber-600' : 'bg-green-50 text-green-600' }}">
                                {{ $feedback->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-zinc-400 text-sm italic">
                            No feedback received yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>
@endsection
