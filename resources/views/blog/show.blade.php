@extends('layouts.app')

@section('title', $post['title'] . ' - UnlockRentals Blog')
@section('meta_description', $post['excerpt'])
@section('og_image', $post['image'])

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 pt-24 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb & Back --}}
        <div class="flex items-center justify-between gap-4 mb-8">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                <i class="ph-bold ph-arrow-left"></i> Back to All Articles
            </a>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                {{ $post['category'] }}
            </span>
        </div>

        {{-- Article Title & Meta --}}
        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight tracking-tight mb-6 font-['Playfair_Display',serif]">
            {{ $post['title'] }}
        </h1>

        <div class="flex flex-wrap items-center justify-between gap-4 py-4 border-y border-slate-200 dark:border-slate-800 mb-8">
            <div class="flex items-center gap-3">
                <img src="{{ $post['author_avatar'] }}" alt="{{ $post['author'] }}" class="w-12 h-12 rounded-full object-cover border-2 border-blue-600">
                <div>
                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $post['author'] }}</p>
                    <p class="text-xs text-slate-500">{{ $post['author_role'] }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                <span class="flex items-center gap-1.5"><i class="ph ph-calendar-blank"></i> {{ $post['published_at'] }}</span>
                <span>•</span>
                <span class="flex items-center gap-1.5"><i class="ph ph-clock"></i> {{ $post['read_time'] }}</span>
            </div>
        </div>

        {{-- Featured Image --}}
        <div class="rounded-3xl overflow-hidden mb-10 shadow-xl border border-slate-200/80 dark:border-slate-800 max-h-[480px]">
            <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="w-full h-full object-cover">
        </div>

        {{-- Article Content --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-12 shadow-sm border border-slate-200/80 dark:border-slate-800 mb-12">
            <div class="prose prose-slate dark:prose-invert max-w-none prose-headings:font-bold prose-headings:text-slate-900 dark:prose-headings:text-white prose-p:text-slate-600 dark:prose-p:text-slate-300 prose-p:leading-relaxed prose-li:text-slate-600 dark:prose-li:text-slate-300">
                {!! $post['content'] !!}
            </div>

            {{-- Share --}}
            <div class="mt-12 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between flex-wrap gap-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Share this article</span>
                <div class="flex items-center gap-2">
                    <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-600 transition" title="Copy Link">
                        <i class="ph-bold ph-link"></i>
                    </button>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post['title']) }}" target="_blank" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-600 transition" title="Share on Twitter">
                        <i class="ph-bold ph-x-logo"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post['title'] . ' ' . url()->current()) }}" target="_blank" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-green-50 hover:text-green-600 transition" title="Share on WhatsApp">
                        <i class="ph-bold ph-whatsapp-logo"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Related Articles --}}
        @if($relatedPosts->count() > 0)
        <div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Related Articles & Guides</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $rPost)
                <a href="{{ route('blog.show', $rPost['slug']) }}" class="group bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-lg transition">
                    <div class="h-36 overflow-hidden">
                        <img src="{{ $rPost['image'] }}" alt="{{ $rPost['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-4">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-1">{{ $rPost['category'] }}</span>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition-colors line-clamp-2">{{ $rPost['title'] }}</h4>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
