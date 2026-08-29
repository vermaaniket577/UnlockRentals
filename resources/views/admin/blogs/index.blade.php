@extends('layouts.admin')

@section('title', 'Manage Blog Posts - UnlockRentals Admin')

@section('content')
<section class="py-6 sm:py-8 lg:py-10 bg-slate-50/50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wide bg-blue-50 text-blue-700 border border-blue-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                        Content Hub
                    </span>
                    <span class="text-xs text-slate-300">•</span>
                    <span class="text-xs font-medium text-slate-500">SEO & Market Guides</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Blog Post Management</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">Publish articles, market insights, and rental guides to grow organic traffic.</p>
            </div>
            
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('blog.index') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 hover:text-blue-600 text-xs font-bold rounded-xl border border-slate-200 shadow-xs hover:border-blue-300 transition-all duration-200" title="View Public Blog">
                    <i class="ph-bold ph-arrow-square-out text-sm text-blue-600"></i>
                    <span>Public Blog</span>
                </a>
                <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-extrabold tracking-wide uppercase rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-200 transform active:scale-98" title="Write New Article">
                    <i class="ph-bold ph-plus-circle text-base"></i>
                    <span>Write New Article</span>
                </a>
            </div>
        </div>

        {{-- KPI Summary Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Total Articles --}}
            <div class="relative overflow-hidden bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Articles</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-newspaper text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total']) }}</span>
                    <span class="text-xs font-medium text-slate-400">total created</span>
                </div>
            </div>

            {{-- Published --}}
            <div class="relative overflow-hidden bg-white p-5 rounded-2xl border border-emerald-100 shadow-xs hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Published</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-check-circle text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-emerald-700 tracking-tight">{{ number_format($stats['published']) }}</span>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600/80 bg-emerald-50 px-2 py-0.5 rounded-md">Live on site</span>
                </div>
            </div>

            {{-- Drafts --}}
            <div class="relative overflow-hidden bg-white p-5 rounded-2xl border border-amber-100 shadow-xs hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Drafts</span>
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-file-dashed text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-amber-700 tracking-tight">{{ number_format($stats['draft']) }}</span>
                    <span class="text-xs font-medium text-amber-600/80">unpublished</span>
                </div>
            </div>

            {{-- Total Views --}}
            <div class="relative overflow-hidden bg-white p-5 rounded-2xl border border-purple-100 shadow-xs hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Total Read Views</span>
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-eye text-lg"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-purple-700 tracking-tight">{{ number_format($stats['views']) }}</span>
                    <span class="text-xs font-medium text-purple-600/80">reader impressions</span>
                </div>
            </div>

        </div>

        {{-- Filter & Search Toolbar --}}
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <form method="GET" action="{{ route('admin.blogs.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                <div class="flex-1 flex flex-col sm:flex-row items-center gap-3">
                    
                    {{-- Search Input --}}
                    <div class="relative w-full sm:w-80">
                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search title, author, content..."
                               class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>

                    {{-- Category Filter --}}
                    <div class="relative w-full sm:w-52">
                        <select name="category" onchange="this.form.submit()"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                            <option value="all">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>

                    {{-- Status Filter --}}
                    <div class="relative w-full sm:w-44">
                        <select name="status" onchange="this.form.submit()"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                            <option value="">All Statuses</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Only</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                            <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>Featured Only</option>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>

                </div>

                <div class="flex items-center gap-2 justify-end">
                    @if(request()->hasAny(['search', 'category', 'status']))
                        <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all flex items-center gap-1.5" title="Reset Filters">
                            <i class="ph-bold ph-x text-xs"></i> Reset
                        </a>
                    @endif
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-all shadow-xs flex items-center gap-1.5">
                        <i class="ph-bold ph-funnel text-xs"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Blog Posts Table Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-5 sm:px-6 w-5/12">Article Details</th>
                            <th class="py-4 px-4 text-left">Category</th>
                            <th class="py-4 px-4 text-left">Author</th>
                            <th class="py-4 px-4 text-center">Views</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-4 text-left">Date</th>
                            <th class="py-4 px-5 sm:px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($blogs as $blog)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            
                            {{-- Article Thumbnail & Title --}}
                            <td class="py-4 px-5 sm:px-6">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-16 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 relative">
                                        <img src="{{ $blog->cover_image_url }}" alt="{{ $blog->title }}"
                                             onerror="this.onerror=null;this.src='{{ $blog->getDefaultCoverImage() }}';"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @if($blog->is_featured)
                                            <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-amber-400 text-amber-950 flex items-center justify-center text-[9px] shadow-sm font-bold" title="Featured Post">
                                                <i class="ph-fill ph-star"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5 flex-wrap mb-1">
                                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="font-extrabold text-xs sm:text-sm text-slate-900 hover:text-blue-600 transition-colors line-clamp-1">
                                                {{ $blog->title }}
                                            </a>
                                            @if($blog->is_featured)
                                                <span class="px-2 py-0.5 bg-amber-50 text-amber-800 text-[10px] font-extrabold uppercase rounded-md border border-amber-200/80">Featured</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-400">
                                            <span class="truncate max-w-[200px] sm:max-w-[280px]">/blog/{{ $blog->slug }}</span>
                                            <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-0.5" title="Preview Public Page">
                                                <i class="ph-bold ph-arrow-square-out text-xs"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/60 inline-block">
                                    {{ $blog->category }}
                                </span>
                            </td>

                            {{-- Author --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ $blog->author_avatar_url }}" alt="{{ $blog->author_display_name }}"
                                         onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($blog->author_display_name) }}&background=2563EB&color=fff&rounded=true&bold=true';"
                                         class="w-7 h-7 rounded-full object-cover border border-slate-200">
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">{{ $blog->author_display_name }}</p>
                                        <span class="text-[10px] font-medium text-slate-400 block leading-none">{{ $blog->author_role_title }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Views & Read Time --}}
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700">
                                    <i class="ph-bold ph-eye text-slate-400 text-xs"></i> {{ number_format($blog->views_count) }}
                                </span>
                                <span class="block text-[10px] text-slate-400 mt-0.5 font-medium">{{ $blog->estimated_read_time }}</span>
                            </td>

                            {{-- Status Toggle --}}
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.blogs.toggle-publish', $blog) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="group/btn inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all {{ $blog->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' }}" title="Click to toggle publish status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $blog->is_published ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $blog->is_published ? 'Published' : 'Draft' }}</span>
                                    </button>
                                </form>
                            </td>

                            {{-- Date --}}
                            <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-600">
                                <div class="font-semibold">{{ $blog->formatted_published_date }}</div>
                                <span class="text-[10px] text-slate-400">{{ $blog->updated_at->diffForHumans() }}</span>
                            </td>

                            {{-- Action Buttons --}}
                            <td class="py-4 px-5 sm:px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Quick Featured Toggle --}}
                                    <form method="POST" action="{{ route('admin.blogs.toggle-featured', $blog) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center {{ $blog->is_featured ? 'bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100' : 'bg-slate-100 text-slate-400 hover:text-amber-600 hover:bg-amber-50' }} transition-colors" title="{{ $blog->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                            <i class="ph-bold ph-star text-sm"></i>
                                        </button>
                                    </form>

                                    {{-- Live Preview --}}
                                    <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:text-blue-600 hover:bg-blue-50 flex items-center justify-center transition-all" title="View Public Page">
                                        <i class="ph-bold ph-arrow-square-out text-sm"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all font-semibold" title="Edit Article">
                                        <i class="ph-bold ph-pencil-simple text-sm"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Are you sure you want to delete this blog post? This action cannot be undone.')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-all font-semibold" title="Delete Article">
                                            <i class="ph-bold ph-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-20 text-center text-slate-400">
                                <div class="max-w-md mx-auto flex flex-col items-center p-6">
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-50 to-indigo-50 border border-blue-100 text-blue-600 flex items-center justify-center text-3xl mb-4 shadow-xs">
                                        <i class="ph-bold ph-newspaper"></i>
                                    </div>
                                    <h3 class="text-lg font-extrabold text-slate-800 mb-1.5">No blog posts found</h3>
                                    <p class="text-xs sm:text-sm text-slate-500 max-w-xs mx-auto mb-5 leading-relaxed">Publish rental guides, market updates, and tenant tips to boost your SEO traffic.</p>
                                    <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/25 transition-all">
                                        <i class="ph-bold ph-plus text-sm"></i>
                                        <span>Write First Post</span>
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
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                {{ $blogs->links() }}
            </div>
            @endif
        </div>

    </div>
</section>
@endsection

