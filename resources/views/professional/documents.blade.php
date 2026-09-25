@extends('layouts.app')

@section('title', 'KYC Documents | Professional Dashboard | UnlockRentals')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="flex items-center text-xs text-slate-500 mb-6 gap-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">KYC Documents</span>
        </nav>

        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Confidential Verification Documents</h1>
                <p class="text-xs text-slate-500 mt-0.5">Documents submitted here are private and only used by admins to award the verified badge</p>
            </div>
            <a href="{{ route('professional.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200">
                Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-base text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Upload Document Form --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm mb-8">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="ph-bold ph-shield-check text-blue-600"></i>
                Submit KYC Document for Verification
            </h3>

            <form action="{{ route('professional.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Document Type *</label>
                        <select name="document_type" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                            <option value="ID Proof">Aadhaar Card / Voter ID / Passport</option>
                            <option value="Address Proof">Electricity Bill / Ration Card</option>
                            <option value="Certificate">Trade Certificate / ITI / Diploma</option>
                            <option value="Business Proof">GST / Shop Act / MSME</option>
                            <option value="Other">Other Official Document</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Document Number (Optional)</label>
                        <input type="text" name="document_number" placeholder="e.g. XXXX-XXXX-1234" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Upload Document File (PDF, JPG, PNG) *</label>
                    <input type="file" name="document_file" required accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
                </div>

                <div class="p-3.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-[11px] text-blue-700 dark:text-blue-300 flex items-center gap-2">
                    <i class="ph-bold ph-lock-key text-base flex-shrink-0"></i>
                    <span>Privacy Guarantee: Your private documents are never displayed to customers or search engines. They are stored in restricted private server storage.</span>
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all">
                    Upload & Submit for Review
                </button>
            </form>
        </div>

        {{-- Submitted Documents List --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4">Submitted Documents ({{ $documents->count() }})</h3>

            @if($documents->count() > 0)
                <div class="space-y-3">
                    @foreach($documents as $doc)
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/40 border border-slate-200/60 dark:border-slate-700 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 flex items-center justify-center font-bold text-lg">
                                    <i class="ph-bold ph-file-text"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-900 dark:text-white block">{{ $doc->document_type }}</span>
                                    <span class="text-[11px] text-slate-400">
                                        Uploaded {{ $doc->created_at->format('M d, Y') }} {{ $doc->document_number ? '• ' . $doc->document_number : '' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                @if($doc->status === 'verified')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold flex items-center gap-1">
                                        <i class="ph-bold ph-check"></i> Verified
                                    </span>
                                @elseif($doc->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 text-[11px] font-bold">
                                        Rejected
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold">
                                        Under Review
                                    </span>
                                @endif

                                <a href="{{ route('professionals.documents.download', $doc->id) }}" class="p-2 rounded-lg bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-300 text-xs" title="Download Document">
                                    <i class="ph-bold ph-download-simple"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-400">
                    <i class="ph ph-file-dashed text-3xl mb-2 block"></i>
                    <p class="text-xs">No documents uploaded yet. Upload an ID or Certificate to get verified.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
