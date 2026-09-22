@extends('layouts.admin')

@section('title', 'Callback Requests - Admin Panel')

@section('content')
<section class="py-6 sm:py-8" id="admin-callbacks">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        {{-- Header & Stats --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-xl shadow-md shadow-blue-500/20">
                        <i class="ph-bold ph-phone-call"></i>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Callback Requests</h1>
                        <p class="text-xs sm:text-sm text-slate-500">Manage chatbot leads, follow up calls, and add discussion comments</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-slate-700 hover:text-blue-600 bg-slate-100 hover:bg-blue-50 rounded-xl border border-slate-200 hover:border-blue-200 transition-all shadow-xs" title="Back to Dashboard">
                    <i class="ph-bold ph-arrow-left text-sm"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        {{-- Filter & Search Bar --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            {{-- Status Filter Tabs --}}
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 custom-scrollbar text-xs">
                @php
                    $currentStatus = request('status', 'all');
                @endphp
                <a href="{{ route('admin.callbacks', ['status' => 'all', 'search' => request('search')]) }}" 
                   class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition-all {{ $currentStatus === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All Leads
                </a>
                <a href="{{ route('admin.callbacks', ['status' => 'new', 'search' => request('search')]) }}" 
                   class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'new' ? 'bg-amber-600 text-white shadow-sm' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    New
                </a>
                <a href="{{ route('admin.callbacks', ['status' => 'called', 'search' => request('search')]) }}" 
                   class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'called' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Called
                </a>
                <a href="{{ route('admin.callbacks', ['status' => 'interested', 'search' => request('search')]) }}" 
                   class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'interested' ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    Interested
                </a>
                <a href="{{ route('admin.callbacks', ['status' => 'no_answer', 'search' => request('search')]) }}" 
                   class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'no_answer' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-800 hover:bg-rose-100' }}">
                    <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                    No Answer
                </a>
                <a href="{{ route('admin.callbacks', ['status' => 'completed', 'search' => request('search')]) }}" 
                   class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ $currentStatus === 'completed' ? 'bg-purple-600 text-white shadow-sm' : 'bg-purple-50 text-purple-800 hover:bg-purple-100' }}">
                    <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                    Completed
                </a>
            </div>

            {{-- Search Bar --}}
            <form action="{{ route('admin.callbacks') }}" method="GET" class="flex items-center gap-2 max-w-sm w-full">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative flex-1">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search customer, phone, notes..." 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold transition-all shadow-xs flex-shrink-0">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.callbacks', ['status' => request('status')]) }}" class="p-2 text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100" title="Clear Search">
                        <i class="ph-bold ph-x text-sm"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Callbacks Table --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200/80 text-[11px] uppercase tracking-wider font-extrabold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5 whitespace-nowrap">Status</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Customer</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Phone Number</th>
                            <th class="px-5 py-3.5 min-w-[240px]">Comments / Discussion Notes</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Request Date</th>
                            <th class="px-5 py-3.5 text-right whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($callbacks as $callback)
                        @php
                            $badge = $callback->status_badge;
                            $hasNotes = !empty($callback->admin_notes);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors group" id="callback-row-{{ $callback->id }}">
                            {{-- Status Column with Quick Dropdown --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.callbacks.status', ['id' => $callback->id]) }}" method="POST" class="inline-block" onchange="this.submit()">
                                    @csrf
                                    <div class="relative inline-block">
                                        <select name="status" class="appearance-none pl-3 pr-7 py-1 rounded-full text-[11px] font-black uppercase tracking-wider cursor-pointer border shadow-2xs transition-all outline-none {{ $badge['bg'] }}">
                                            <option value="new" {{ $callback->status === 'new' ? 'selected' : '' }}>🟡 New</option>
                                            <option value="called" {{ $callback->status === 'called' ? 'selected' : '' }}>🟢 Called</option>
                                            <option value="interested" {{ $callback->status === 'interested' ? 'selected' : '' }}>🔵 Interested</option>
                                            <option value="no_answer" {{ $callback->status === 'no_answer' ? 'selected' : '' }}>🔴 No Answer</option>
                                            <option value="completed" {{ $callback->status === 'completed' ? 'selected' : '' }}>🟣 Completed</option>
                                            <option value="cancelled" {{ $callback->status === 'cancelled' ? 'selected' : '' }}>⚪ Cancelled</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] pointer-events-none opacity-70"></i>
                                    </div>
                                </form>
                            </td>

                            {{-- Customer Column --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-extrabold text-slate-900 flex items-center gap-1.5">
                                    <span>{{ $callback->name ?: 'Customer' }}</span>
                                    @if($callback->user)
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-600 border border-blue-200">Registered</span>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-500 border border-slate-200">Guest</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                                    <i class="ph ph-envelope text-slate-400"></i>
                                    <span>{{ $callback->email ?: ($callback->user?->email ?: 'No email provided') }}</span>
                                </div>
                                @if($callback->property)
                                <div class="mt-1">
                                    <a href="{{ route('property.detail', $callback->property->slug ?? $callback->property->id) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-600 hover:underline">
                                        <i class="ph-bold ph-buildings text-xs"></i>
                                        <span class="truncate max-w-[150px]">{{ $callback->property->title }}</span>
                                    </a>
                                </div>
                                @endif
                            </td>

                            {{-- Phone Column --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="tel:{{ $callback->phone }}" class="text-sm font-black text-slate-900 hover:text-blue-600 tracking-tight transition-colors flex items-center gap-1" title="Click to call">
                                        <i class="ph-bold ph-phone text-blue-600"></i>
                                        <span>{{ $callback->phone }}</span>
                                    </a>
                                </div>
                            </td>

                            {{-- Comments / Notes Column --}}
                            <td class="px-5 py-4">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        @if($hasNotes)
                                            <div class="p-2.5 rounded-xl bg-amber-50/60 border border-amber-200/60 text-slate-800 text-[11px] font-medium leading-relaxed group-hover:bg-amber-50 transition-colors">
                                                <div class="flex items-center gap-1 text-[10px] font-bold text-amber-700 mb-1">
                                                    <i class="ph-bold ph-chat-centered-text"></i>
                                                    <span>Admin Note:</span>
                                                    @if($callback->called_at)
                                                        <span class="text-[9px] text-slate-400 font-normal ml-auto">&bull; Handled {{ $callback->called_at->diffForHumans() }}</span>
                                                    @endif
                                                </div>
                                                <p class="whitespace-pre-line line-clamp-3">{{ $callback->admin_notes }}</p>
                                            </div>
                                        @else
                                            <button type="button" 
                                                    onclick="openCommentModal({{ $callback->id }}, '{{ addslashes($callback->name ?: 'Customer') }}', '{{ $callback->phone }}', '{{ addslashes($callback->email ?: '') }}', '{{ $callback->status }}', '', '{{ $callback->whatsapp_url }}')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50/80 hover:bg-blue-100/80 border border-blue-200/60 transition-all cursor-pointer">
                                                <i class="ph-bold ph-chat-circle-dots text-sm"></i>
                                                <span>+ Add Comment</span>
                                            </button>
                                        @endif
                                    </div>

                                    @if($hasNotes)
                                        <button type="button" 
                                                onclick="openCommentModal({{ $callback->id }}, '{{ addslashes($callback->name ?: 'Customer') }}', '{{ $callback->phone }}', '{{ addslashes($callback->email ?: '') }}', '{{ $callback->status }}', '{{ addslashes($callback->admin_notes) }}', '{{ $callback->whatsapp_url }}')"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors cursor-pointer flex-shrink-0" title="Edit Comment">
                                            <i class="ph-bold ph-pencil-simple text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>

                            {{-- Date Column --}}
                            <td class="px-5 py-4 whitespace-nowrap text-slate-500">
                                <div class="font-bold text-slate-700">{{ $callback->created_at->diffForHumans() }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $callback->created_at->format('M d, Y h:i A') }}</div>
                            </td>

                            {{-- Actions Column --}}
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- WhatsApp Button --}}
                                    <a href="{{ $callback->whatsapp_url }}" target="_blank" 
                                       class="p-2 text-emerald-600 hover:text-white bg-emerald-50 hover:bg-emerald-600 rounded-xl transition-all shadow-2xs" 
                                       title="Chat on WhatsApp">
                                        <i class="ph-bold ph-whatsapp-logo text-base"></i>
                                    </a>

                                    {{-- Direct Call Button --}}
                                    <a href="tel:{{ $callback->phone }}" 
                                       class="p-2 text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 rounded-xl transition-all shadow-2xs" 
                                       title="Call Directly">
                                        <i class="ph-bold ph-phone text-base"></i>
                                    </a>

                                    {{-- Add / Edit Comment Button --}}
                                    <button type="button" 
                                            onclick="openCommentModal({{ $callback->id }}, '{{ addslashes($callback->name ?: 'Customer') }}', '{{ $callback->phone }}', '{{ addslashes($callback->email ?: '') }}', '{{ $callback->status }}', '{{ addslashes($callback->admin_notes ?: '') }}', '{{ $callback->whatsapp_url }}')"
                                            class="p-2 text-slate-600 hover:text-white bg-slate-100 hover:bg-slate-900 rounded-xl transition-all shadow-2xs cursor-pointer" 
                                            title="{{ $hasNotes ? 'Edit Comment & Status' : 'Add Comment / Message' }}">
                                        <i class="ph-bold ph-note-pencil text-base"></i>
                                    </button>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.callbacks.destroy', ['id' => $callback->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this callback request?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" title="Delete Callback">
                                            <i class="ph-bold ph-trash text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl shadow-inner">
                                    <i class="ph-bold ph-phone-slash"></i>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm">No callback requests found</h3>
                                <p class="text-xs text-slate-400 mt-1">When website visitors request a callback through the AI chatbot or property forms, they will show up here.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($callbacks->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $callbacks->links() }}
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Interactive Add/Edit Comment & Status Modal --}}
<div id="callbackCommentModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-[99999] hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all flex flex-col">
        
        {{-- Modal Header --}}
        <div class="p-5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-xl">
                    <i class="ph-bold ph-note-pencil"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold" id="modalCustomerName">Callback Notes &amp; Message</h3>
                    <p class="text-xs text-blue-100" id="modalCustomerPhone">+91 - Customer Details</p>
                </div>
            </div>
            <button type="button" onclick="closeCommentModal()" class="p-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-colors cursor-pointer" title="Close">
                <i class="ph-bold ph-x text-base"></i>
            </button>
        </div>

        {{-- Modal Body Form --}}
        <form id="commentForm" method="POST" action="" class="p-6 space-y-5">
            @csrf
            
            {{-- Status Selector --}}
            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                    Update Lead Status
                </label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border border-slate-200 cursor-pointer text-xs font-bold transition-all hover:bg-slate-50 has-checked:border-amber-500 has-checked:bg-amber-50 has-checked:text-amber-800">
                        <input type="radio" name="status" value="new" id="status-new" class="sr-only">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span>New</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border border-slate-200 cursor-pointer text-xs font-bold transition-all hover:bg-slate-50 has-checked:border-emerald-500 has-checked:bg-emerald-50 has-checked:text-emerald-800">
                        <input type="radio" name="status" value="called" id="status-called" class="sr-only">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Called</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border border-slate-200 cursor-pointer text-xs font-bold transition-all hover:bg-slate-50 has-checked:border-blue-500 has-checked:bg-blue-50 has-checked:text-blue-800">
                        <input type="radio" name="status" value="interested" id="status-interested" class="sr-only">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        <span>Interested</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border border-slate-200 cursor-pointer text-xs font-bold transition-all hover:bg-slate-50 has-checked:border-rose-500 has-checked:bg-rose-50 has-checked:text-rose-800">
                        <input type="radio" name="status" value="no_answer" id="status-no_answer" class="sr-only">
                        <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                        <span>No Answer</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border border-slate-200 cursor-pointer text-xs font-bold transition-all hover:bg-slate-50 has-checked:border-purple-500 has-checked:bg-purple-50 has-checked:text-purple-800">
                        <input type="radio" name="status" value="completed" id="status-completed" class="sr-only">
                        <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                        <span>Completed</span>
                    </label>
                    <label class="flex items-center justify-center gap-1.5 p-2 rounded-xl border border-slate-200 cursor-pointer text-xs font-bold transition-all hover:bg-slate-50 has-checked:border-slate-500 has-checked:bg-slate-100 has-checked:text-slate-800">
                        <input type="radio" name="status" value="cancelled" id="status-cancelled" class="sr-only">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        <span>Cancelled</span>
                    </label>
                </div>
            </div>

            {{-- Quick Comment Templates --}}
            <div>
                <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 mb-1.5">
                    Quick Templates (Click to insert)
                </label>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" onclick="insertNoteTemplate('Called: customer interested in ready-to-move flats.')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[11px] font-bold text-slate-700 transition-colors cursor-pointer">
                        + Interested in flats
                    </button>
                    <button type="button" onclick="insertNoteTemplate('Called: no answer. Will call back today evening.')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[11px] font-bold text-slate-700 transition-colors cursor-pointer">
                        + No answer (retry later)
                    </button>
                    <button type="button" onclick="insertNoteTemplate('Scheduled property visit for this weekend.')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[11px] font-bold text-slate-700 transition-colors cursor-pointer">
                        + Scheduled visit
                    </button>
                    <button type="button" onclick="insertNoteTemplate('Sent WhatsApp brochure and property options.')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[11px] font-bold text-slate-700 transition-colors cursor-pointer">
                        + Sent WhatsApp brochure
                    </button>
                </div>
            </div>

            {{-- Comment / Message Textarea --}}
            <div>
                <label for="modalAdminNotes" class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-1.5">
                    Comments / Internal Notes
                </label>
                <textarea name="admin_notes" id="modalAdminNotes" rows="4" 
                          placeholder="Type conversation notes, customer requirements, preferred localities, budget, or follow-up details here..."
                          class="w-full p-3.5 text-xs text-slate-900 rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-3 focus:ring-blue-500/20 outline-none transition-all leading-relaxed custom-scrollbar font-sans"></textarea>
                <p class="text-[10px] text-slate-400 mt-1">These notes are visible to administrators to keep track of customer interactions.</p>
            </div>

            {{-- Modal Actions --}}
            <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100">
                <a href="#" id="modalWhatsappBtn" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition-all">
                    <i class="ph-bold ph-whatsapp-logo text-base"></i>
                    <span>Open WhatsApp</span>
                </a>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="closeCommentModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md shadow-blue-500/20 transition-all cursor-pointer">
                        <i class="ph-bold ph-check text-sm"></i>
                        <span>Save Comment</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCommentModal(id, name, phone, email, status, notes, whatsappUrl) {
        const modal = document.getElementById('callbackCommentModal');
        const form = document.getElementById('commentForm');
        const nameEl = document.getElementById('modalCustomerName');
        const phoneEl = document.getElementById('modalCustomerPhone');
        const notesEl = document.getElementById('modalAdminNotes');
        const waBtn = document.getElementById('modalWhatsappBtn');

        // Populate Form
        form.action = `/admin/callbacks/${id}/comment`;
        nameEl.textContent = name || 'Customer Notes';
        phoneEl.textContent = `${phone} ${email ? '• ' + email : ''}`;
        notesEl.value = notes || '';
        waBtn.href = whatsappUrl || `https://wa.me/91${phone.replace(/[^0-9]/g, '')}`;

        // Set Radio Status
        const radio = document.getElementById('status-' + status);
        if (radio) {
            radio.checked = true;
        } else {
            const defaultRadio = document.getElementById('status-new');
            if (defaultRadio) defaultRadio.checked = true;
        }

        // Show Modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => notesEl.focus(), 100);
    }

    function closeCommentModal() {
        const modal = document.getElementById('callbackCommentModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function insertNoteTemplate(text) {
        const notesEl = document.getElementById('modalAdminNotes');
        if (!notesEl) return;
        if (notesEl.value.trim().length > 0) {
            notesEl.value += '\n' + text;
        } else {
            notesEl.value = text;
        }
        notesEl.focus();
    }

    // Close on Escape or click outside
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeCommentModal();
    });

    document.getElementById('callbackCommentModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeCommentModal();
    });
</script>
@endpush
@endsection
