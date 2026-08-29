@extends('layouts.app')

@section('title', 'Blog & Real Estate Guides - UnlockRentals')
@section('meta_description', 'Read the latest rental guides, tenant rights, commercial leasing trends, and property owner tips on UnlockRentals.')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 pt-24 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mb-3 border border-blue-200/50 dark:border-blue-800/50">
                <i class="ph-bold ph-newspaper"></i> UnlockRentals Knowledge Hub
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-slate-900 dark:text-white mb-4 font-['Playfair_Display',serif]">
                Insights, Guides & Market Trends
            </h1>
            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300">
                Expert tips for tenants, property owners, and commercial space seekers across India.
            </p>
        </div>

        {{-- Search & Filter Bar --}}
        <div class="mb-12 bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-slate-200/80 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('blog.index') }}" method="GET" class="w-full md:w-auto flex-1 flex items-center gap-2">
                <div class="relative flex-1">
                    <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles, guides, topics..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition">
                    Search
                </button>
            </form>

            {{-- Categories --}}
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 scrollbar-none">
                <a href="{{ route('blog.index', array_filter(['search' => request('search')])) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ !request('category') || request('category') == 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}" title="All">
                    All
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog.index', array_filter(['category' => $cat, 'search' => request('search')])) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition {{ request('category') == $cat ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}" title="Category {{ $cat }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Featured Article (Only if on page 1 without active filter/search) --}}
        @if($featuredPost && !request('search') && (!request('category') || request('category') == 'all'))
        <div class="mb-14">
            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="group block relative bg-white dark:bg-slate-900 rounded-3xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-lg hover:shadow-2xl transition-all duration-300" title="{{ $featuredPost->title }}">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                    <div class="lg:col-span-7 h-72 sm:h-96 lg:h-auto relative overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <img src="{{ $featuredPost->cover_image_url }}" alt="{{ $featuredPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-blue-600 text-white shadow-md">
                                Featured Guide
                            </span>
                        </div>
                    </div>
                    <div class="lg:col-span-5 p-6 sm:p-10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mb-3">
                                <span class="font-bold text-blue-600 dark:text-blue-400">{{ $featuredPost->category }}</span>
                                <span>•</span>
                                <span>{{ $featuredPost->formatted_published_date }}</span>
                                <span>•</span>
                                <span>{{ $featuredPost->estimated_read_time }}</span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-4 line-clamp-2">
                                {{ $featuredPost->title }}
                            </h2>
                            <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed line-clamp-3 mb-6">
                                {{ $featuredPost->excerpt }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-3">
                                <img src="{{ $featuredPost->author_avatar_url }}" alt="{{ $featuredPost->author_display_name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700">
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $featuredPost->author_display_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $featuredPost->author_role_title }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 text-sm font-bold text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform">
                                Read More <i class="ph-bold ph-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Articles Grid --}}
        @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            <article class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                <a href="{{ route('blog.show', $post->slug) }}" class="block relative h-48 sm:h-52 overflow-hidden bg-slate-100 dark:bg-slate-800" title="{{ $post->title }}">
                    <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-3 left-3 px-3 py-1 rounded-lg text-[11px] font-bold bg-slate-900/80 backdrop-blur-md text-white">
                        {{ $post->category }}
                    </span>
                    @if($post->is_featured)
                    <span class="absolute top-3 right-3 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-400 text-amber-950 shadow-sm flex items-center gap-1">
                        <i class="ph-fill ph-star"></i> Featured
                    </span>
                    @endif
                </a>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-2.5">
                            <span>{{ $post->formatted_published_date }}</span>
                            <span>•</span>
                            <span>{{ $post->estimated_read_time }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-2.5 line-clamp-2">
                            <a href="{{ route('blog.show', $post->slug) }}" title="{{ $post->title }}">
                                {{ $post->title }}
                            </a>
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed line-clamp-3 mb-4">
                            {{ $post->excerpt }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <img src="{{ $post->author_avatar_url }}" alt="{{ $post->author_display_name }}" class="w-8 h-8 rounded-full object-cover">
                            <div>
                                <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $post->author_display_name }}</p>
                                <p class="text-[10px] text-slate-500">{{ $post->author_role_title }}</p>
                            </div>
                        </div>
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1" title="Read Article">
                            Read <i class="ph-bold ph-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $posts->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-16 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8">
            <i class="ph ph-newspaper text-5xl text-slate-400 mb-3 inline-block"></i>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No articles found</h3>
            <p class="text-sm text-slate-500 mb-4">Try adjusting your search query or selecting a different category.</p>
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition" title="Clear Filters">
                Clear Filters
            </a>
        </div>
        @endif

        {{-- Newsletter / Rental Alert CTA --}}
        <div class="mt-20 relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-8 sm:p-12 shadow-xl">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-md mb-4">
                    <i class="ph-bold ph-bell-simple"></i> Stay Ahead in Real Estate
                </span>
                <h3 class="text-2xl sm:text-4xl font-extrabold mb-3">Get exclusive property insights & local rental reports</h3>
                <p class="text-blue-100 text-sm sm:text-base mb-6">Receive curated tips directly to your inbox every week. Zero spam, unsubscribe anytime.</p>
                <form onsubmit="event.preventDefault(); alert('Thank you for subscribing to UnlockRentals Blog!');" class="flex flex-col sm:flex-row gap-3">
                    <input type="email" placeholder="Enter your email address" required class="px-5 py-3 rounded-xl text-slate-900 text-sm flex-1 focus:outline-none focus:ring-2 focus:ring-white">
                    <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-black text-white text-sm font-bold rounded-xl transition shadow-lg">
                        Subscribe Now
                    </button>
                </form>
            </div>
            <div class="absolute -right-10 -bottom-10 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        </div>

    </div>
</div>
@endsection
