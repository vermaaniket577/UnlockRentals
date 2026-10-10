@extends('layouts.admin')

@section('title', 'Manage Blog Posts - UnlockRentals Admin')

@section('content')
<section class="py-4 sm:py-5 bg-slate-50/50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3.5">

        {{-- Page Header (Compact) --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white px-5 py-3 rounded-xl border border-slate-200/80 shadow-xs">
            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide bg-blue-50 text-blue-700 border border-blue-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                        Content Hub
                    </span>
                    <span class="text-xs text-slate-300">•</span>
                    <span class="text-[11px] font-medium text-slate-500">SEO & Market Guides</span>
                </div>
                <h1 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Blog Post Management</h1>
            </div>
            
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.settings') }}#section-blog-slider" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-700 text-xs font-bold rounded-lg border border-teal-200 shadow-xs transition-all duration-200" title="Configure Homepage Slider">
                    <i class="ph-bold ph-slideshow text-xs text-teal-600"></i>
                    <span>Slider Settings</span>
                </a>
                <a href="{{ route('blog.index') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 hover:text-blue-600 text-xs font-bold rounded-lg border border-slate-200 shadow-xs hover:border-blue-300 transition-all duration-200" title="View Public Blog">
                    <i class="ph-bold ph-arrow-square-out text-xs text-blue-600"></i>
                    <span>Public Blog</span>
                </a>
                <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 !text-white text-xs font-extrabold uppercase rounded-lg shadow-sm hover:shadow transition-all duration-200" style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important;" title="Write New Article">
                    <i class="ph-bold ph-plus-circle text-sm !text-white" style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important;"></i>
                    <span class="!text-white" style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important;">Write Article</span>
                </a>
            </div>
        </div>

        {{-- KPI Summary Stats (Compact) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            
            {{-- Total Articles --}}
            <div class="bg-white px-4 py-2.5 rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Total Articles</span>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span class="text-xl sm:text-2xl font-black text-slate-900">{{ number_format($stats['total']) }}</span>
                        <span class="text-[10px] text-slate-400 font-medium">created</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-newspaper text-base"></i>
                </div>
            </div>

            {{-- Published --}}
            <div class="bg-white px-4 py-2.5 rounded-xl border border-emerald-100 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider block">Published</span>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span class="text-xl sm:text-2xl font-black text-emerald-700">{{ number_format($stats['published']) }}</span>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Live</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-check-circle text-base"></i>
                </div>
            </div>

            {{-- Drafts --}}
            <div class="bg-white px-4 py-2.5 rounded-xl border border-amber-100 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider block">Drafts</span>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span class="text-xl sm:text-2xl font-black text-amber-700">{{ number_format($stats['draft']) }}</span>
                        <span class="text-[10px] text-amber-600/80 font-medium">pending</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-file-dashed text-base"></i>
                </div>
            </div>

            {{-- Total Views --}}
            <div class="bg-white px-4 py-2.5 rounded-xl border border-purple-100 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-purple-600 uppercase tracking-wider block">Total Views</span>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span class="text-xl sm:text-2xl font-black text-purple-700">{{ number_format($stats['views']) }}</span>
                        <span class="text-[10px] text-purple-600/80 font-medium">reads</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-eye text-base"></i>
                </div>
            </div>

        </div>

        {{-- Filter & Search Toolbar (Compact) --}}
        <div class="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200/80 shadow-xs">
            <form method="GET" action="{{ route('admin.blogs.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-2.5">
                <div class="flex-1 flex flex-wrap items-center gap-2">
                    
                    {{-- Search Input --}}
                    <div class="relative w-full sm:w-64">
                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search title, author, content..."
                               class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>

                    {{-- Category Filter --}}
                    <div class="relative w-full sm:w-44">
                        <select name="category" onchange="this.form.submit()"
                                class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                            <option value="all">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                    </div>

                    {{-- Status Filter --}}
                    <div class="relative w-full sm:w-36">
                        <select name="status" onchange="this.form.submit()"
                                class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                            <option value="">All Statuses</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Only</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                            <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>Featured Only</option>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                    </div>

                    {{-- Sort Filter --}}
                    <div class="relative w-full sm:w-48">
                        <i class="ph-bold ph-arrows-down-up absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[11px] pointer-events-none"></i>
                        <select name="sort" onchange="this.form.submit()"
                                class="w-full pl-7 pr-7 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                            <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Date: Newest First</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Date: Oldest First</option>
                            <option value="views_desc" {{ request('sort') === 'views_desc' ? 'selected' : '' }}>Views: High to Low</option>
                            <option value="views_asc" {{ request('sort') === 'views_asc' ? 'selected' : '' }}>Views: Low to High</option>
                            <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Title: A &rarr; Z</option>
                            <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Title: Z &rarr; A</option>
                            <option value="updated_desc" {{ request('sort') === 'updated_desc' ? 'selected' : '' }}>Recently Updated</option>
                            <option value="read_time_desc" {{ request('sort') === 'read_time_desc' ? 'selected' : '' }}>Read Time: Longest</option>
                            <option value="read_time_asc" {{ request('sort') === 'read_time_asc' ? 'selected' : '' }}>Read Time: Shortest</option>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                    </div>

                </div>

                <div class="flex items-center gap-2 justify-end shrink-0">
                    @if(request()->filled('search') || (request()->filled('category') && request('category') !== 'all') || request()->filled('status') || (request()->filled('sort') && request('sort') !== 'latest'))
                        <a href="{{ route('admin.blogs.index') }}" class="px-3 py-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition-all flex items-center gap-1" title="Reset Filters & Sorting">
                            <i class="ph-bold ph-x text-[11px]"></i> Reset
                        </a>
                    @endif
                    <button type="submit" class="px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-lg transition-all shadow-xs flex items-center gap-1">
                        <i class="ph-bold ph-funnel text-[11px]"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Blog Posts Table Card (Compact) --}}
        @php
            $currentSort = request('sort', 'latest');
            $buildSortUrl = function($targetSort) {
                return route('admin.blogs.index', array_merge(request()->except(['page', 'sort']), ['sort' => $targetSort]));
            };
            $nextTitleSort = ($currentSort === 'title_asc') ? 'title_desc' : 'title_asc';
            $nextViewsSort = ($currentSort === 'views_desc') ? 'views_asc' : 'views_desc';
            $nextDateSort  = ($currentSort === 'latest') ? 'oldest' : 'latest';
        @endphp
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">
                            <th class="py-2.5 px-4 w-5/12">
                                <a href="{{ $buildSortUrl($nextTitleSort) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition-colors group/sort" title="Sort by Title">
                                    <span>Article Details</span>
                                    @if($currentSort === 'title_asc')
                                        <i class="ph-bold ph-arrow-up text-blue-600 text-[10px]"></i>
                                    @elseif($currentSort === 'title_desc')
                                        <i class="ph-bold ph-arrow-down text-blue-600 text-[10px]"></i>
                                    @else
                                        <i class="ph-bold ph-arrows-down-up text-slate-400 group-hover/sort:text-blue-600 text-[10px] opacity-70"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="py-2.5 px-3 text-left">Category</th>
                            <th class="py-2.5 px-3 text-left">Author</th>
                            <th class="py-2.5 px-3 text-center">
                                <a href="{{ $buildSortUrl($nextViewsSort) }}" class="inline-flex items-center justify-center gap-1 hover:text-blue-600 transition-colors group/sort mx-auto" title="Sort by Views">
                                    <span>Views</span>
                                    @if($currentSort === 'views_desc')
                                        <i class="ph-bold ph-arrow-down text-blue-600 text-[10px]"></i>
                                    @elseif($currentSort === 'views_asc')
                                        <i class="ph-bold ph-arrow-up text-blue-600 text-[10px]"></i>
                                    @else
                                        <i class="ph-bold ph-arrows-down-up text-slate-400 group-hover/sort:text-blue-600 text-[10px] opacity-70"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                            <th class="py-2.5 px-3 text-left">
                                <a href="{{ $buildSortUrl($nextDateSort) }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition-colors group/sort" title="Sort by Date">
                                    <span>Date</span>
                                    @if($currentSort === 'latest')
                                        <i class="ph-bold ph-arrow-down text-blue-600 text-[10px]"></i>
                                    @elseif($currentSort === 'oldest')
                                        <i class="ph-bold ph-arrow-up text-blue-600 text-[10px]"></i>
                                    @else
                                        <i class="ph-bold ph-arrows-down-up text-slate-400 group-hover/sort:text-blue-600 text-[10px] opacity-70"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="py-2.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($blogs as $blog)
                        <tr class="hover:bg-blue-50/20 transition-colors group">
                            
                            {{-- Article Thumbnail & Title --}}
                            <td class="py-2 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-12 h-9 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 relative">
                                        <img src="{{ $blog->cover_image_url }}" alt="{{ $blog->title }}"
                                             onerror="this.onerror=null;this.src='{{ $blog->getDefaultCoverImage() }}';"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @if($blog->is_featured)
                                            <span class="absolute top-0.5 right-0.5 w-3.5 h-3.5 rounded-full bg-amber-400 text-amber-950 flex items-center justify-center text-[8px] font-bold" title="Featured Post">
                                                <i class="ph-fill ph-star"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1 flex-wrap">
                                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="font-extrabold text-xs text-slate-900 hover:text-blue-600 transition-colors line-clamp-1">
                                                {{ $blog->title }}
                                            </a>
                                            @if($blog->is_featured)
                                                <span class="px-1.5 py-0.2 bg-amber-50 text-amber-800 text-[9px] font-extrabold uppercase rounded border border-amber-200/80">Featured</span>
                                            @endif
                                            @php
                                                $sliderIds = json_decode(\App\Models\Setting::get('home_blog_slider_ids', '[]'), true) ?: [];
                                                $isInSlider = (!empty($blog->show_in_slider) && $blog->show_in_slider) || in_array($blog->id, $sliderIds);
                                            @endphp
                                            @if($isInSlider)
                                                <span class="px-1.5 py-0.2 bg-teal-50 text-teal-800 text-[9px] font-extrabold uppercase rounded border border-teal-200/80 inline-flex items-center gap-0.5">
                                                    <i class="ph-bold ph-slideshow text-[9px]"></i> Slider
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5 text-[10px] text-slate-400">
                                            <span class="truncate max-w-[200px] sm:max-w-[280px]">/blog/{{ $blog->slug }}</span>
                                            <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-0.5" title="Preview Public Page">
                                                <i class="ph-bold ph-arrow-square-out text-[10px]"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="py-2 px-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60 inline-block">
                                    {{ $blog->category }}
                                </span>
                            </td>

                            {{-- Author --}}
                            <td class="py-2 px-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $blog->author_avatar_url }}" alt="{{ $blog->author_display_name }}"
                                         onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($blog->author_display_name) }}&background=2563EB&color=fff&rounded=true&bold=true';"
                                         class="w-6 h-6 rounded-full object-cover border border-slate-200">
                                    <div>
                                        <p class="text-xs font-bold text-slate-800 leading-tight">{{ $blog->author_display_name }}</p>
                                        <span class="text-[9px] font-medium text-slate-400 block leading-tight">{{ $blog->author_role_title }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Views & Read Time --}}
                            <td class="py-2 px-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700">
                                    <i class="ph-bold ph-eye text-slate-400 text-[11px]"></i> {{ number_format($blog->views_count) }}
                                </span>
                                <span class="block text-[9px] text-slate-400 leading-tight font-medium">{{ $blog->estimated_read_time }}</span>
                            </td>

                            {{-- Status Toggle --}}
                            <td class="py-2 px-3 text-center whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.blogs.toggle-publish', $blog) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="group/btn inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold transition-all {{ $blog->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' }}" title="Click to toggle publish status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $blog->is_published ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $blog->is_published ? 'Published' : 'Draft' }}</span>
                                    </button>
                                </form>
                            </td>

                            {{-- Date --}}
                            <td class="py-2 px-3 whitespace-nowrap text-xs text-slate-600">
                                <div class="font-semibold text-xs leading-tight">{{ $blog->formatted_published_date }}</div>
                                <span class="text-[9px] text-slate-400 leading-tight block">{{ $blog->updated_at->diffForHumans() }}</span>
                            </td>

                            {{-- Action Buttons --}}
                            <td class="py-2 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    {{-- Quick Homepage Slider Toggle --}}
                                    <form method="POST" action="{{ route('admin.blogs.toggle-slider', $blog) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="w-7 h-7 rounded-lg flex items-center justify-center {{ $isInSlider ? 'bg-teal-50 text-teal-600 border border-teal-200 hover:bg-teal-100' : 'bg-slate-100 text-slate-400 hover:text-teal-600 hover:bg-teal-50' }} transition-colors" title="{{ $isInSlider ? 'Remove from Homepage Slider' : 'Add to Homepage Slider (RTL)' }}">
                                            <i class="ph-bold ph-slideshow text-xs"></i>
                                        </button>
                                    </form>

                                    {{-- Quick Featured Toggle --}}
                                    <form method="POST" action="{{ route('admin.blogs.toggle-featured', $blog) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="w-7 h-7 rounded-lg flex items-center justify-center {{ $blog->is_featured ? 'bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100' : 'bg-slate-100 text-slate-400 hover:text-amber-600 hover:bg-amber-50' }} transition-colors" title="{{ $blog->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                            <i class="ph-bold ph-star text-xs"></i>
                                        </button>
                                    </form>

                                    {{-- Live Preview --}}
                                    <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 hover:text-blue-600 hover:bg-blue-50 flex items-center justify-center transition-all" title="View Public Page">
                                        <i class="ph-bold ph-arrow-square-out text-xs"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all font-semibold" title="Edit Article">
                                        <i class="ph-bold ph-pencil-simple text-xs"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Are you sure you want to delete this blog post? This action cannot be undone.')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-all font-semibold" title="Delete Article">
                                            <i class="ph-bold ph-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="max-w-md mx-auto flex flex-col items-center p-4">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-blue-50 to-indigo-50 border border-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-3 shadow-xs">
                                        <i class="ph-bold ph-newspaper"></i>
                                    </div>
                                    <h3 class="text-base font-extrabold text-slate-800 mb-1">No blog posts found</h3>
                                    <p class="text-xs text-slate-500 max-w-xs mx-auto mb-4 leading-relaxed">Publish rental guides, market updates, and tenant tips to boost your SEO traffic.</p>
                                    <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 !text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm transition-all" style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important;">
                                        <i class="ph-bold ph-plus text-xs !text-white" style="color: #ffffff !important;"></i>
                                        <span class="!text-white" style="color: #ffffff !important;">Write First Post</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer (Compact) --}}
            @if($blogs->hasPages())
            <div class="px-4 py-2.5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                {{ $blogs->links() }}
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
