@extends('layouts.admin')

@section('title', 'Manage Blog Posts - Admin CRM')

@section('content')
<section class="py-8 lg:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 rounded-sm">Content Hub</span>
                    <span class="text-xs text-zinc-400">•</span>
                    <span class="text-xs text-zinc-500">Knowledge & Market Guides</span>
                </div>
                <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">Blog Post Management</h1>
                <p class="text-zinc-500 text-sm mt-0.5">Publish articles, market insights, and rental guides to drive organic SEO traffic.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('blog.index') }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white border border-stone-200 hover:bg-stone-50 text-zinc-700 text-xs font-semibold rounded-sm transition-all shadow-sm" title="View Public Blog">
                    <i class="ph ph-arrow-square-out text-sm text-blue-600"></i>
                    <span>Public Blog</span>
                </a>
                <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs font-bold rounded-sm transition-all shadow-sm shadow-blue-500/20" title="Write New Blog Post">
                    <i class="ph-bold ph-plus text-sm"></i>
                    <span>Write New Article</span>
                </a>
            </div>
        </div>

        {{-- KPI Summary Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-stone-200/80 rounded-sm p-4.5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Articles</span>
                    <div class="w-8 h-8 rounded-sm bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="ph-bold ph-newspaper text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-zinc-900">{{ number_format($stats['total']) }}</span>
                    <span class="text-xs text-zinc-400">articles</span>
                </div>
            </div>

            <div class="bg-white border border-stone-200/80 rounded-sm p-4.5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Published</span>
                    <div class="w-8 h-8 rounded-sm bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-emerald-700">{{ number_format($stats['published']) }}</span>
                    <span class="text-xs text-emerald-600/70 font-medium">live on site</span>
                </div>
            </div>

            <div class="bg-white border border-stone-200/80 rounded-sm p-4.5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Drafts</span>
                    <div class="w-8 h-8 rounded-sm bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="ph-bold ph-file-dashed text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-amber-700">{{ number_format($stats['draft']) }}</span>
                    <span class="text-xs text-amber-600/70 font-medium">unpublished</span>
                </div>
            </div>

            <div class="bg-white border border-stone-200/80 rounded-sm p-4.5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Total Read Views</span>
                    <div class="w-8 h-8 rounded-sm bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="ph-bold ph-eye text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-black text-purple-700">{{ number_format($stats['views']) }}</span>
                    <span class="text-xs text-purple-600/70 font-medium">impressions</span>
                </div>
            </div>
        </div>

        {{-- Filters & Search Toolbar --}}
        <div class="bg-white border border-stone-200/80 rounded-sm p-4 mb-6 shadow-sm">
            <form method="GET" action="{{ route('admin.blogs.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                <div class="flex-1 flex flex-col sm:flex-row items-center gap-3">
                    {{-- Search Input --}}
                    <div class="relative w-full sm:w-80">
                        <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400 text-base"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search title, author, content..."
                               class="w-full pl-9 pr-4 py-2 border border-stone-200 rounded-sm text-xs text-zinc-900 bg-stone-50/50 focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB]">
                    </div>

                    {{-- Category Filter --}}
                    <select name="category" onchange="this.form.submit()"
                            class="w-full sm:w-48 px-3 py-2 border border-stone-200 rounded-sm text-xs text-zinc-700 bg-stone-50/50 focus:bg-white focus:outline-none focus:border-[#2563EB]">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>

                    {{-- Status Filter --}}
                    <select name="status" onchange="this.form.submit()"
                            class="w-full sm:w-40 px-3 py-2 border border-stone-200 rounded-sm text-xs text-zinc-700 bg-stone-50/50 focus:bg-white focus:outline-none focus:border-[#2563EB]">
                        <option value="">All Statuses</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Live)</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                        <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>Featured Only</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 justify-end">
                    @if(request()->hasAny(['search', 'category', 'status']))
                        <a href="{{ route('admin.blogs.index') }}" class="px-3.5 py-2 text-xs font-semibold text-zinc-600 hover:text-zinc-900 bg-stone-100 hover:bg-stone-200 rounded-sm transition-all flex items-center gap-1.5" title="Reset Filters">
                            <i class="ph ph-x"></i> Reset
                        </a>
                    @endif
                    <button type="submit" class="px-4 py-2 bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-semibold rounded-sm transition-all">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Blog Posts Table --}}
        <div class="bg-white border border-stone-200/80 rounded-sm shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-stone-50/80 border-b border-stone-200 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 sm:px-6 w-5/12">Article Details</th>
                            <th class="py-3.5 px-4 text-left">Category</th>
                            <th class="py-3.5 px-4 text-left">Author</th>
                            <th class="py-3.5 px-4 text-center">Views</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-left">Date</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200/60">
                        @forelse($blogs as $blog)
                        <tr class="hover:bg-stone-50/70 transition-colors group">
                            
                            {{-- Article Thumbnail & Title --}}
                            <td class="py-4 px-4 sm:px-6">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-16 h-12 rounded-sm overflow-hidden bg-stone-100 border border-stone-200 flex-shrink-0 relative">
                                        <img src="{{ $blog->cover_image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @if($blog->is_featured)
                                            <span class="absolute top-0.5 right-0.5 w-4 h-4 rounded-full bg-amber-400 text-amber-950 flex items-center justify-center text-[9px] shadow-sm" title="Featured Post">
                                                <i class="ph-fill ph-star"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="font-bold text-xs sm:text-sm text-zinc-900 hover:text-blue-600 transition-colors line-clamp-1">
                                                {{ $blog->title }}
                                            </a>
                                            @if($blog->is_featured)
                                                <span class="px-1.5 py-0.2 bg-amber-100 text-amber-800 text-[9px] font-extrabold uppercase rounded-sm border border-amber-200/60">Featured</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] text-zinc-400">
                                            <span class="truncate max-w-[200px] sm:max-w-[280px]">/blog/{{ $blog->slug }}</span>
                                            <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-0.5" title="Preview Public Page">
                                                <i class="ph ph-arrow-square-out text-xs"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-sm text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60 inline-block">
                                    {{ $blog->category }}
                                </span>
                            </td>

                            {{-- Author --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $blog->author_avatar_url }}" alt="{{ $blog->author_display_name }}" class="w-6 h-6 rounded-full object-cover border border-stone-200">
                                    <div>
                                        <p class="text-xs font-semibold text-zinc-800">{{ $blog->author_display_name }}</p>
                                        <span class="text-[10px] text-zinc-400 block leading-none">{{ $blog->author_role_title }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Views & Read Time --}}
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-zinc-700">
                                    <i class="ph ph-eye text-zinc-400 text-sm"></i> {{ number_format($blog->views_count) }}
                                </span>
                                <span class="block text-[10px] text-zinc-400 mt-0.5">{{ $blog->estimated_read_time }}</span>
                            </td>

                            {{-- Status Toggle --}}
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.blogs.toggle-publish', $blog) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="group/btn inline-flex items-center gap-1.5 px-2.5 py-1 rounded-sm text-xs font-bold transition-all {{ $blog->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-stone-100 text-zinc-500 border border-stone-200 hover:bg-stone-200' }}" title="Click to toggle publish status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $blog->is_published ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                                        <span>{{ $blog->is_published ? 'Published' : 'Draft' }}</span>
                                    </button>
                                </form>
                            </td>

                            {{-- Date --}}
                            <td class="py-4 px-4 whitespace-nowrap text-xs text-zinc-600">
                                <div>{{ $blog->formatted_published_date }}</div>
                                <span class="text-[10px] text-zinc-400">{{ $blog->updated_at->diffForHumans() }}</span>
                            </td>

                            {{-- Action Buttons --}}
                            <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Quick Featured Toggle --}}
                                    <form method="POST" action="{{ route('admin.blogs.toggle-featured', $blog) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-sm {{ $blog->is_featured ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-stone-100 text-zinc-400 hover:text-amber-500 hover:bg-stone-200' }} transition-colors" title="{{ $blog->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                            <i class="ph-bold ph-star text-sm"></i>
                                        </button>
                                    </form>

                                    {{-- Live Preview --}}
                                    <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="p-1.5 bg-stone-100 text-zinc-600 hover:text-blue-600 hover:bg-blue-50 rounded-sm transition-all" title="View Public Page">
                                        <i class="ph-bold ph-arrow-square-out text-sm"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-sm transition-all font-semibold" title="Edit Article">
                                        <i class="ph-bold ph-pencil-simple text-sm"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Are you sure you want to delete this blog post? This action cannot be undone.')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-sm transition-all font-semibold" title="Delete Article">
                                            <i class="ph-bold ph-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-zinc-400">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-full bg-stone-100 text-zinc-400 flex items-center justify-center text-2xl mb-3">
                                        <i class="ph ph-newspaper"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-zinc-800 mb-1">No blog posts found</h3>
                                    <p class="text-xs text-zinc-500 mb-4">Get started by creating your first rich article or rental guide.</p>
                                    <a href="{{ route('admin.blogs.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-sm transition-all shadow-sm">
                                        + Write First Post
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($blogs->hasPages())
            <div class="px-6 py-4 border-t border-stone-200 bg-stone-50/50 flex items-center justify-between">
                {{ $blogs->links() }}
            </div>
            @endif
        </div>

    </div>
</section>
@endsection
