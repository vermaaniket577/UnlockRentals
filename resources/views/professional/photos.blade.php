@extends('layouts.app')

@section('title', 'Work Photos | Professional Dashboard | UnlockRentals')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="flex items-center text-xs text-slate-500 mb-6 gap-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">Work Photos</span>
        </nav>

        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Work Photos Gallery</h1>
                <p class="text-xs text-slate-500 mt-0.5">Showcase your completed repairs, installations, and projects</p>
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

        {{-- Upload New Photo --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm mb-8">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="ph-bold ph-upload-simple text-blue-600"></i>
                Upload New Work Photo
            </h3>

            <form action="{{ route('professional.photos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Select Photo *</label>
                        <input type="file" name="image" required accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Caption / Description</label>
                        <input type="text" name="caption" placeholder="e.g. Modern kitchen plumbing installation" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all">
                    Upload Photo
                </button>
            </form>
        </div>

        {{-- Photo Grid --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4">Current Work Photos ({{ $photos->count() }})</h3>

            @if($photos->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($photos as $photo)
                        <div class="relative group rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-700 aspect-square border border-slate-200 dark:border-slate-600">
                            <img src="{{ $photo->image_url }}" alt="{{ $photo->caption }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-3 text-white">
                                <span class="text-[11px] font-medium line-clamp-2">{{ $photo->caption }}</span>
                                <form action="{{ route('professional.photos.destroy', $photo->id) }}" method="POST" onsubmit="return confirm('Delete this photo?')" class="self-end">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700 text-xs">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-400">
                    <i class="ph ph-images text-3xl mb-2 block"></i>
                    <p class="text-xs">No photos uploaded yet. Adding work photos increases customer inquiries by up to 3x.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
