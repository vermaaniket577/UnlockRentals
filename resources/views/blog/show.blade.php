@extends('layouts.app')

@section('title', ($post->meta_title ?: $post->title) . ' - UnlockRentals')
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('og_image', $post->cover_image_url)

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 pt-24 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Admin Draft Banner --}}
        @if(!$post->is_published)
        <div class="mb-6 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-900 dark:text-amber-300 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <i class="ph-bold ph-eye-slash text-xl text-amber-600"></i>
                <div>
                    <span class="font-bold text-sm">Draft Preview Mode</span>
                    <p class="text-xs text-amber-700 dark:text-amber-400">This post is currently unpublished. Only admins can see this preview.</p>
                </div>
            </div>
            <a href="{{ route('admin.blogs.edit', $post) }}" class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg shadow-sm">
                Edit in Admin Panel
            </a>
        </div>
        @endif

        {{-- Breadcrumb & Category --}}
        <div class="flex items-center justify-between gap-4 mb-6">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline" title="Back to All Articles">
                <i class="ph-bold ph-arrow-left"></i> Back to Knowledge Hub
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('blog.index', ['category' => $post->category]) }}" class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 hover:bg-blue-200 transition">
                    {{ $post->category }}
                </a>
                @if($post->is_featured)
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 flex items-center gap-1">
                    <i class="ph-fill ph-star"></i> Featured
                </span>
                @endif
            </div>
        </div>

        {{-- Article Title --}}
        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white leading-tight tracking-tight mb-6 font-['Playfair_Display',serif]">
            {{ $post->title }}
        </h1>

        {{-- Article Meta & Author Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-4 py-4 border-y border-slate-200 dark:border-slate-800 mb-8">
            <div class="flex items-center gap-3">
                <img src="{{ $post->author_avatar_url }}" alt="{{ $post->author_display_name }}"
                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($post->author_display_name) }}&background=2563EB&color=fff&rounded=true&bold=true';"
                     class="w-12 h-12 rounded-full object-cover border-2 border-blue-600 shadow-sm">
                <div>
                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $post->author_display_name }}</p>
                    <p class="text-xs text-slate-500">{{ $post->author_role_title }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                <span class="flex items-center gap-1.5"><i class="ph ph-calendar-blank"></i> {{ $post->formatted_published_date }}</span>
                <span>•</span>
                <span class="flex items-center gap-1.5"><i class="ph ph-clock"></i> {{ $post->estimated_read_time }}</span>
                <span>•</span>
                <span class="flex items-center gap-1.5"><i class="ph ph-eye"></i> {{ number_format($post->views_count) }} views</span>
            </div>
        </div>

        {{-- Featured Hero Image --}}
        <div class="rounded-3xl overflow-hidden mb-10 shadow-xl border border-slate-200/80 dark:border-slate-800 max-h-[480px] bg-slate-100 dark:bg-slate-800 aspect-[16/9] sm:aspect-[21/9]">
            <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}"
                 onerror="this.onerror=null;this.src='{{ $post->getDefaultCoverImage() }}';"
                 class="w-full h-full object-cover">
        </div>

        {{-- Article Content --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-12 shadow-sm border border-slate-200/80 dark:border-slate-800 mb-12">
            
            @if($post->excerpt)
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border-l-4 border-blue-600 mb-8 text-base text-slate-700 dark:text-slate-200 italic font-medium leading-relaxed">
                {{ $post->excerpt }}
            </div>
            @endif

            <div class="prose prose-slate dark:prose-invert max-w-none prose-headings:font-bold prose-headings:text-slate-900 dark:prose-headings:text-white prose-p:text-slate-600 dark:prose-p:text-slate-300 prose-p:leading-relaxed prose-li:text-slate-600 dark:prose-li:text-slate-300 prose-img:rounded-2xl">
                {!! $post->content !!}
            </div>

            {{-- Tags Section --}}
            @if(!empty($post->tags) && is_array($post->tags) && count($post->tags) > 0)
            <div class="mt-10 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 flex-wrap">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">Tags:</span>
                @foreach($post->tags as $tag)
                    <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        #{{ $tag }}
                    </span>
                @endforeach
            </div>
            @endif

            {{-- Share Article Box --}}
            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between flex-wrap gap-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Share this article</span>
                <div class="flex items-center gap-2">
                    <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-600 transition" title="Copy Link">
                        <i class="ph-bold ph-link text-base"></i>
                    </button>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-600 transition" title="Share on X (Twitter)">
                        <i class="ph-bold ph-x-logo text-base"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-green-50 hover:text-green-600 transition" title="Share on WhatsApp">
                        <i class="ph-bold ph-whatsapp-logo text-base"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 hover:text-blue-700 transition" title="Share on LinkedIn">
                        <i class="ph-bold ph-linkedin-logo text-base"></i>
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
                <a href="{{ route('blog.show', $rPost->slug) }}" class="group bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-lg transition flex flex-col" title="{{ $rPost->title }}">
                    <div class="h-36 overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <img src="{{ $rPost->cover_image_url }}" alt="{{ $rPost->title }}"
                             onerror="this.onerror=null;this.src='{{ $rPost->getDefaultCoverImage() }}';"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-1">{{ $rPost->category }}</span>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition-colors line-clamp-2">{{ $rPost->title }}</h4>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2 block">{{ $rPost->formatted_published_date }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
