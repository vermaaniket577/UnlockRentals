@extends('layouts.app')

@section('title', 'Callback Requests - Admin Panel')

@section('content')
<section class="py-12" id="admin-callbacks">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-medium text-zinc-900">Callback Requests</h1>
                <p class="text-zinc-500">Manage leads generated through the chatbot</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#2563EB] hover:underline flex items-center gap-2">
                <i class="ph ph-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="bg-white border border-stone-200/50 rounded-lg overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead class="bg-stone-50 border-b border-stone-200/50 text-xs uppercase tracking-wider font-bold text-zinc-500">
                    <tr>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200/50">
                    @forelse($callbacks as $callback)
                    <tr>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-[10px] uppercase font-bold 
                                {{ $callback->status == 'new' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $callback->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-zinc-900">{{ $callback->name ?? 'N/A' }}</div>
                            <div class="text-xs text-zinc-500">{{ $callback->user->email ?? 'Guest' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="tel:{{ $callback->phone }}" class="text-[#2563EB] hover:underline font-medium">
                                {{ $callback->phone }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-zinc-500">
                            {{ $callback->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="p-2 text-zinc-400 hover:text-zinc-900 transition-colors">
                                <i class="ph ph-dots-three-vertical-bold"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-zinc-500">
                            No callback requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($callbacks->hasPages())
                <div class="px-6 py-4 border-t border-stone-200/50">
                    {{ $callbacks->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
