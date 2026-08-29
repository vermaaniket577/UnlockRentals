@extends('layouts.admin')

@section('title', ($blog ? 'Edit' : 'Create') . ' Blog Post - UnlockRentals Admin')

@section('content')
<section class="py-6 sm:py-8 lg:py-10 bg-slate-50/50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Top Navigation & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 mb-2 transition-colors" title="Back to All Articles">
                    <i class="ph-bold ph-arrow-left"></i>
                    <span>Back to Blog Articles</span>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $blog ? 'Edit Article: ' . Str::limit($blog->title, 45) : 'Create New Article' }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    {{ $blog ? 'Make changes to your published guide, market insight, or draft.' : 'Draft a comprehensive guide, tenant advice, or market trends article.' }}
                </p>
            </div>

            @if($blog)
            <div class="flex items-center gap-2">
                <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 hover:text-blue-600 text-xs font-bold rounded-xl border border-slate-200 shadow-xs transition-all" title="View Public Post">
                    <i class="ph-bold ph-arrow-square-out text-sm text-blue-600"></i>
                    <span>Live Preview</span>
                </a>
            </div>
            @endif
        </div>

        {{-- Errors Alert --}}
        @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-700 space-y-1 shadow-xs">
            <div class="font-bold flex items-center gap-2 text-rose-800 text-sm">
                <i class="ph-bold ph-warning-circle text-base"></i> Please fix the following errors:
            </div>
            <ul class="list-disc pl-5 space-y-0.5 mt-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Main Form --}}
        <form method="POST" action="{{ $blog ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}"
              enctype="multipart/form-data" id="blog-form" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            @csrf
            @if($blog) @method('PUT') @endif

            {{-- ──────────────── Left Column (8 cols): Main Content Area ──────────────── --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- Card: Title, Slug & Excerpt --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
                    {{-- Title --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="post-title" class="text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Article Title <span class="text-rose-500">*</span>
                            </label>
                            <span id="title-counter" class="text-[11px] text-slate-400 font-semibold">0 / 100 chars</span>
                        </div>
                        <input type="text" name="title" id="post-title" required maxlength="255"
                               value="{{ old('title', $blog->title ?? '') }}"
                               placeholder="e.g. 10 Essential Rental Agreement Clauses Every Tenant Must Check"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>

                    {{-- Slug / URL Preview --}}
                    <div>
                        <label for="post-slug" class="text-xs font-extrabold uppercase tracking-wider text-slate-700 block mb-2">
                            Permanent URL Slug <span class="text-slate-400 font-medium normal-case">(auto-generated from title)</span>
                        </label>
                        <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50 overflow-hidden text-xs focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-600/10 transition-all">
                            <span class="px-3.5 py-2.5 text-slate-400 bg-slate-100/80 border-r border-slate-200 font-mono select-none">
                                {{ url('/blog') }}/
                            </span>
                            <input type="text" name="slug" id="post-slug"
                                   value="{{ old('slug', $blog->slug ?? '') }}"
                                   placeholder="article-url-slug"
                                   class="flex-1 px-3 py-2.5 text-xs font-mono font-semibold text-slate-800 bg-transparent focus:outline-none focus:bg-white">
                        </div>
                    </div>

                    {{-- Excerpt / Short Summary --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="post-excerpt" class="text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Summary / Excerpt <span class="text-slate-400 font-medium normal-case">(shown in listings & Google snippet)</span>
                            </label>
                            <span id="excerpt-counter" class="text-[11px] text-slate-400 font-semibold">0 / 250</span>
                        </div>
                        <textarea name="excerpt" id="post-excerpt" rows="3" maxlength="500"
                                  placeholder="Brief summary of the guide to catch reader attention in listings and search snippets..."
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all resize-none">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Card: Rich Content Editor with Toolbar & Live Preview --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    {{-- Editor Header & Tabs --}}
                    <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/60 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700">
                                Article Content <span class="text-rose-500">*</span>
                            </span>
                            <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200/60 px-2 py-0.5 rounded-md">HTML / Rich Formatted</span>
                        </div>

                        {{-- Tab Switcher --}}
                        <div class="flex items-center bg-slate-200/60 p-1 rounded-xl">
                            <button type="button" id="tab-btn-editor" onclick="switchEditorTab('editor')"
                                    class="px-3.5 py-1.5 text-xs font-extrabold rounded-lg bg-blue-600 text-white shadow-xs transition-all flex items-center gap-1.5">
                                <i class="ph-bold ph-code"></i> <span>Editor</span>
                            </button>
                            <button type="button" id="tab-btn-preview" onclick="switchEditorTab('preview')"
                                    class="px-3.5 py-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 rounded-lg transition-all flex items-center gap-1.5">
                                <i class="ph-bold ph-eye"></i> <span>Live Preview</span>
                            </button>
                        </div>
                    </div>

                    {{-- Formatting Quick Toolbar --}}
                    <div id="editor-toolbar" class="p-2.5 border-b border-slate-100 bg-slate-50/40 flex flex-wrap items-center gap-1.5 text-xs">
                        <button type="button" onclick="insertTag('<h2>', '</h2>', 'Section Heading')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-slate-100 border border-slate-200 font-extrabold text-slate-700 shadow-2xs transition-all" title="Heading 2">H2</button>
                        <button type="button" onclick="insertTag('<h3>', '</h3>', 'Subheading')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-slate-100 border border-slate-200 font-extrabold text-slate-700 shadow-2xs transition-all" title="Heading 3">H3</button>
                        <span class="w-px h-5 bg-slate-200 mx-1"></span>
                        <button type="button" onclick="insertTag('<strong>', '</strong>', 'Bold text')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-slate-100 border border-slate-200 font-black text-slate-800 shadow-2xs transition-all" title="Bold"><strong>B</strong></button>
                        <button type="button" onclick="insertTag('<em>', '</em>', 'Italic text')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-slate-100 border border-slate-200 italic text-slate-700 shadow-2xs transition-all" title="Italic"><em>I</em></button>
                        <span class="w-px h-5 bg-slate-200 mx-1"></span>
                        <button type="button" onclick="insertList('ul')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 shadow-2xs flex items-center gap-1 font-semibold transition-all" title="Bullet List">
                            <i class="ph-bold ph-list-bullets"></i> <span>Bullets</span>
                        </button>
                        <button type="button" onclick="insertList('ol')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 shadow-2xs flex items-center gap-1 font-semibold transition-all" title="Numbered List">
                            <i class="ph-bold ph-list-numbers"></i> <span>Numbered</span>
                        </button>
                        <button type="button" onclick="insertTag('<blockquote>', '</blockquote>', 'Important key takeaway or quote...')" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 shadow-2xs flex items-center gap-1 font-semibold transition-all" title="Quote Box">
                            <i class="ph-bold ph-quotes"></i> <span>Quote</span>
                        </button>
                        <span class="w-px h-5 bg-slate-200 mx-1"></span>
                        <button type="button" onclick="insertLink()" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-blue-50 border border-slate-200 text-blue-600 shadow-2xs flex items-center gap-1 font-semibold transition-all" title="Insert Link">
                            <i class="ph-bold ph-link"></i> <span>Link</span>
                        </button>
                        <button type="button" onclick="insertImage()" class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-purple-50 border border-slate-200 text-purple-600 shadow-2xs flex items-center gap-1 font-semibold transition-all" title="Insert Image Tag">
                            <i class="ph-bold ph-image"></i> <span>Image</span>
                        </button>
                    </div>

                    {{-- Editor Textarea Panel --}}
                    <div id="editor-panel" class="p-5">
                        <textarea name="content" id="post-content" required rows="18"
                                  placeholder="Write your article in structured HTML or paragraphs..."
                                  class="w-full p-4 border border-slate-200 rounded-xl text-sm font-mono text-slate-900 bg-slate-50/40 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 leading-relaxed transition-all">{{ old('content', $blog->content ?? '') }}</textarea>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 mt-2 font-medium">
                            <span id="word-counter">0 words</span>
                            <span>Supported HTML: h2, h3, p, strong, em, ul, ol, li, a, img, blockquote</span>
                        </div>
                    </div>

                    {{-- Live Preview Panel --}}
                    <div id="preview-panel" class="hidden p-6 sm:p-10 bg-white min-h-[450px]">
                        <div class="max-w-3xl mx-auto">
                            <div class="mb-4">
                                <span class="px-3 py-1 text-xs font-extrabold uppercase bg-blue-100 text-blue-800 rounded-lg" id="preview-category-badge">Tenant Guide</span>
                            </div>
                            <h1 id="preview-title" class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-4 tracking-tight">
                                Article Title Preview
                            </h1>
                            <div class="flex items-center gap-3 text-xs text-slate-400 pb-4 mb-6 border-b border-slate-100 font-medium">
                                <span id="preview-author-name">By {{ auth()->user()->name }}</span>
                                <span>•</span>
                                <span>{{ now()->format('M d, Y') }}</span>
                                <span>•</span>
                                <span id="preview-read-time">5 min read</span>
                            </div>
                            <div id="preview-body" class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed space-y-4">
                                {{-- Content preview injected via JS --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card: SEO Meta Information & Google Snippet Simulator --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="ph-bold ph-google-logo text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Search Engine Optimization (SEO)</h3>
                            <p class="text-[11px] text-slate-400">Optimize how this article ranks and previews on Google search results.</p>
                        </div>
                    </div>

                    {{-- Google SERP Snippet Preview Box --}}
                    <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-xl space-y-1.5 shadow-2xs">
                        <div class="text-[11px] text-slate-500 flex items-center gap-1.5 font-mono">
                            <span class="w-3.5 h-3.5 rounded-full bg-slate-200 flex items-center justify-center text-[9px] font-bold text-slate-600">G</span>
                            <span class="text-emerald-700 font-semibold">https://unlockrentals.com</span> › blog › <span id="serp-slug-preview">{{ $blog->slug ?? 'article-slug' }}</span>
                        </div>
                        <div id="serp-title-preview" class="text-base font-semibold text-blue-700 hover:underline cursor-pointer line-clamp-1">
                            {{ $blog->meta_title ?? ($blog->title ?? 'Article Title - UnlockRentals Blog') }}
                        </div>
                        <div id="serp-desc-preview" class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                            {{ $blog->meta_description ?? ($blog->excerpt ?? 'Meta description will be displayed here as it appears on Google search snippet results.') }}
                        </div>
                    </div>

                    <div class="space-y-4">
                        {{-- Meta Title --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="post-meta-title" class="text-xs font-bold text-slate-700">Custom Meta Title</label>
                                <span id="meta-title-counter" class="text-[11px] text-slate-400 font-semibold">0 / 60 chars</span>
                            </div>
                            <input type="text" name="meta_title" id="post-meta-title" maxlength="255"
                                   value="{{ old('meta_title', $blog->meta_title ?? '') }}"
                                   placeholder="Custom SEO Title (defaults to post title if left empty)"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="post-meta-description" class="text-xs font-bold text-slate-700">Custom Meta Description</label>
                                <span id="meta-desc-counter" class="text-[11px] text-slate-400 font-semibold">0 / 160 chars</span>
                            </div>
                            <textarea name="meta_description" id="post-meta-description" rows="2" maxlength="500"
                                      placeholder="Compelling 150-160 character description for Google Search snippet..."
                                      class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all resize-none">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                        </div>

                        {{-- Tags / Keywords --}}
                        <div>
                            <label for="post-tags" class="text-xs font-bold text-slate-700 block mb-1.5">
                                Tags / Keywords <span class="text-slate-400 font-normal">(comma-separated)</span>
                            </label>
                            <input type="text" name="tags" id="post-tags"
                                   value="{{ old('tags', $blog && is_array($blog->tags) ? implode(', ', $blog->tags) : '') }}"
                                   placeholder="e.g. Renting Tips, Tenant Rights, Security Deposit, Real Estate"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                    </div>
                </div>

            </div>

            {{-- ──────────────── Right Column (4 cols): Settings Sidebar ──────────────── --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Card: Publishing Actions --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <i class="ph-bold ph-paper-plane-tilt text-blue-600 text-base"></i> Publishing State
                        </span>
                        <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full {{ ($blog && $blog->is_published) || old('is_published', true) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/80' : 'bg-amber-50 text-amber-700 border border-amber-200/80' }}">
                            {{ ($blog && $blog->is_published) || old('is_published', true) ? 'Live' : 'Draft' }}
                        </span>
                    </div>

                    {{-- Publish Status Toggle --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 block">Status</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center justify-center gap-2 p-2.5 border rounded-xl cursor-pointer transition-all {{ old('is_published', $blog ? ($blog->is_published ? '1' : '0') : '1') == '1' ? 'border-emerald-500 bg-emerald-50 text-emerald-900 font-extrabold shadow-2xs' : 'border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold' }}">
                                <input type="radio" name="is_published" value="1" {{ old('is_published', $blog ? ($blog->is_published ? '1' : '0') : '1') == '1' ? 'checked' : '' }} class="accent-emerald-600">
                                <span class="text-xs">Published</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 p-2.5 border rounded-xl cursor-pointer transition-all {{ old('is_published', $blog ? ($blog->is_published ? '1' : '0') : '1') == '0' ? 'border-amber-500 bg-amber-50 text-amber-900 font-extrabold shadow-2xs' : 'border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold' }}">
                                <input type="radio" name="is_published" value="0" {{ old('is_published', $blog ? ($blog->is_published ? '1' : '0') : '0') == '0' ? 'checked' : '' }} class="accent-amber-600">
                                <span class="text-xs">Draft</span>
                            </label>
                        </div>
                    </div>

                    {{-- Featured Article Checkbox --}}
                    <div class="pt-3 border-t border-slate-100">
                        <label class="flex items-start gap-3 cursor-pointer p-2.5 rounded-xl hover:bg-amber-50/50 transition-colors">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $blog->is_featured ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 mt-0.5 accent-amber-500 rounded">
                            <div>
                                <span class="text-xs font-extrabold text-slate-800 flex items-center gap-1">
                                    <i class="ph-fill ph-star text-amber-500 text-sm"></i> Featured Guide
                                </span>
                                <span class="text-[11px] text-slate-400 block mt-0.5">Promote prominently on the homepage banner</span>
                            </div>
                        </label>
                    </div>

                    {{-- Published Date & Time --}}
                    <div class="pt-3 border-t border-slate-100">
                        <label for="post-published-at" class="text-xs font-bold text-slate-700 block mb-1.5">
                            Publish Date & Time <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <input type="datetime-local" name="published_at" id="post-published-at"
                               value="{{ old('published_at', $blog && $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>

                    {{-- Read Time Estimate --}}
                    <div>
                        <label for="post-read-time" class="text-xs font-bold text-slate-700 block mb-1.5">
                            Read Time <span class="text-slate-400 font-normal">(e.g. 5 min read)</span>
                        </label>
                        <input type="text" name="read_time" id="post-read-time"
                               value="{{ old('read_time', $blog->read_time ?? '') }}"
                               placeholder="Auto-calculated if blank"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="pt-4 border-t border-slate-100 space-y-2.5">
                        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/25 transition-all flex items-center justify-center gap-2 transform active:scale-98">
                            <i class="ph-bold ph-check text-sm"></i>
                            <span>{{ $blog ? 'Save Changes' : 'Publish Article' }}</span>
                        </button>
                        <a href="{{ route('admin.blogs.index') }}" class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-all text-center block" title="Cancel">
                            Cancel
                        </a>
                    </div>
                </div>

                {{-- Card: Category Selection --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-3.5">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="ph-bold ph-tag text-blue-600 text-base"></i> Category <span class="text-rose-500">*</span>
                    </span>

                    <div class="relative">
                        <select name="category" id="category-select" required onchange="handleCategoryChange(this.value)"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                            @php $selectedCategory = old('category', $blog->category ?? 'Tenant Guide'); @endphp
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $selectedCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                            <option value="__custom__">+ Add New Category...</option>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>

                    {{-- Custom category input hidden by default unless clicked --}}
                    <div id="custom-category-box" class="hidden pt-1">
                        <label class="text-[11px] font-bold text-slate-600 block mb-1">Type New Category Name</label>
                        <input type="text" name="custom_category" id="custom-category-input"
                               placeholder="e.g. Legal & Tax Tips"
                               class="w-full px-3.5 py-2.5 bg-blue-50/40 border border-blue-300 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10">
                    </div>
                </div>

                {{-- Card: Featured Cover Image --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                            <i class="ph-bold ph-image text-blue-600 text-base"></i> Cover Image
                        </span>
                        <span class="text-[10px] font-bold text-slate-400">16:9 ratio • Auto-Optimized</span>
                    </div>

                    @error('image')
                        <p class="text-xs font-bold text-rose-600 bg-rose-50 p-2.5 rounded-xl border border-rose-200 flex items-center gap-1.5">
                            <i class="ph-bold ph-warning-circle text-sm"></i> {{ $message }}
                        </p>
                    @enderror
                    @error('image_url')
                        <p class="text-xs font-bold text-rose-600 bg-rose-50 p-2.5 rounded-xl border border-rose-200 flex items-center gap-1.5">
                            <i class="ph-bold ph-warning-circle text-sm"></i> {{ $message }}
                        </p>
                    @enderror

                    {{-- Hidden Base64 Container for Guaranteed Upload across any server limits --}}
                    <input type="hidden" name="image_base64" id="image-base64-input">

                    {{-- Live Image Preview & Dropzone --}}
                    <div onclick="document.getElementById('cover-image-file').click()"
                         class="relative rounded-xl overflow-hidden bg-slate-100 border-2 border-dashed border-slate-300 hover:border-blue-500 aspect-video flex items-center justify-center group shadow-2xs cursor-pointer transition-all">
                        <img id="cover-image-preview"
                             src="{{ $blog ? $blog->cover_image_url : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80' }}"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80';"
                             alt="Cover Preview"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white p-4 text-center backdrop-blur-xs">
                            <i class="ph-bold ph-upload-simple text-2xl mb-1"></i>
                            <span class="text-xs font-bold">Click to choose image file</span>
                        </div>
                    </div>

                    {{-- Selected File Notification badge --}}
                    <div id="file-chosen-status" class="hidden text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 flex items-center justify-between gap-1.5">
                        <div class="flex items-center gap-1.5 truncate">
                            <i class="ph-bold ph-check-circle text-sm text-emerald-600"></i>
                            <span id="file-chosen-name" class="truncate">File chosen</span>
                        </div>
                        @if($blog)
                        <button type="button" onclick="instantUploadCoverImage()" id="btn-save-image-now"
                                class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold rounded-md transition-all flex items-center gap-1 flex-shrink-0 shadow-2xs">
                            <i class="ph-bold ph-floppy-disk"></i>
                            <span>Save Image Now</span>
                        </button>
                        @endif
                    </div>

                    {{-- Live upload status toast --}}
                    <div id="image-upload-toast" class="hidden text-xs font-bold px-3.5 py-2 rounded-xl border transition-all"></div>

                    {{-- Upload file --}}
                    <div>
                        <label for="cover-image-file" class="text-xs font-bold text-slate-700 block mb-1.5">Upload Local Image</label>
                        <input type="file" name="image" id="cover-image-file" accept="image/*" onchange="previewImageFile(this)"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-xl p-1 bg-slate-50 cursor-pointer">
                    </div>

                    {{-- Or External URL --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="cover-image-url" class="text-xs font-bold text-slate-700">Or External Image URL</label>
                            @if($blog)
                            <button type="button" onclick="saveExternalImageUrl()" class="text-[11px] font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                Apply & Save URL
                            </button>
                            @endif
                        </div>
                        <input type="text" name="image_url" id="cover-image-url" oninput="previewImageUrl(this.value)"
                               value="{{ old('image_url', $blog && (str_starts_with($blog->image ?? '', 'http') || str_starts_with($blog->image ?? '', '//')) ? $blog->image : '') }}"
                               placeholder="https://images.unsplash.com/..."
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>

                {{-- Card: Author Details --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="ph-bold ph-user-circle text-blue-600 text-base"></i> Author Information
                    </span>

                    @error('author_avatar')
                        <p class="text-xs font-bold text-rose-600 bg-rose-50 p-2.5 rounded-xl border border-rose-200 flex items-center gap-1.5">
                            <i class="ph-bold ph-warning-circle text-sm"></i> {{ $message }}
                        </p>
                    @enderror

                    {{-- Hidden Base64 Container for Avatar --}}
                    <input type="hidden" name="author_avatar_base64" id="author-avatar-base64-input">

                    <div>
                        <label for="author-name-input" class="text-xs font-bold text-slate-700 block mb-1.5">Author Name</label>
                        <input type="text" name="author_name" id="author-name-input"
                               value="{{ old('author_name', $blog->author_name ?? auth()->user()->name) }}"
                               placeholder="e.g. Priya Sharma"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>

                    <div>
                        <label for="author-role-input" class="text-xs font-bold text-slate-700 block mb-1.5">Author Role / Title</label>
                        <input type="text" name="author_role" id="author-role-input"
                               value="{{ old('author_role', $blog->author_role ?? 'Real Estate Advisor') }}"
                               placeholder="e.g. Real Estate Strategist"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1.5">Author Avatar Photo (Optional)</label>
                        <div class="flex items-center gap-3">
                            <img id="author-avatar-preview"
                                 src="{{ $blog ? $blog->author_avatar_url : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'Admin') . '&background=2563EB&color=fff&rounded=true&bold=true' }}"
                                 alt="Avatar"
                                 class="w-10 h-10 rounded-full object-cover border border-slate-200 flex-shrink-0">
                            <input type="file" name="author_avatar" accept="image/*" onchange="previewAvatarFile(this)"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-200 rounded-xl p-1 bg-slate-50 cursor-pointer">
                        </div>
                    </div>
                </div>

            </div>

        </form>

    </div>
</section>

@push('scripts')
<script>
    // Live Character and Word Counting
    const titleInput = document.getElementById('post-title');
    const slugInput = document.getElementById('post-slug');
    const excerptInput = document.getElementById('post-excerpt');
    const contentInput = document.getElementById('post-content');
    const metaTitleInput = document.getElementById('post-meta-title');
    const metaDescInput = document.getElementById('post-meta-description');

    const titleCounter = document.getElementById('title-counter');
    const excerptCounter = document.getElementById('excerpt-counter');
    const wordCounter = document.getElementById('word-counter');
    const metaTitleCounter = document.getElementById('meta-title-counter');
    const metaDescCounter = document.getElementById('meta-desc-counter');

    const serpTitlePreview = document.getElementById('serp-title-preview');
    const serpDescPreview = document.getElementById('serp-desc-preview');
    const serpSlugPreview = document.getElementById('serp-slug-preview');

    let manualSlugEdit = {{ $blog ? 'true' : 'false' }};

    function updateCounters() {
        // Title
        const titleLen = titleInput.value.length;
        titleCounter.textContent = `${titleLen} / 100 chars`;

        // Slug auto generation
        if (!manualSlugEdit && titleInput.value) {
            const slugVal = titleInput.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slugVal;
            serpSlugPreview.textContent = slugVal || 'article-slug';
        }

        // Excerpt
        excerptCounter.textContent = `${excerptInput.value.length} / 250`;

        // Word count
        const text = contentInput.value.replace(/<[^>]*>/g, ' ').trim();
        const words = text ? text.split(/\s+/).length : 0;
        wordCounter.textContent = `${words} words (~${Math.max(1, Math.ceil(words / 200))} min read)`;

        // SEO Previews
        const metaT = metaTitleInput.value.trim() || titleInput.value.trim() || 'Article Title - UnlockRentals Blog';
        serpTitlePreview.textContent = metaT;
        metaTitleCounter.textContent = `${metaTitleInput.value.length} / 60 chars`;

        const metaD = metaDescInput.value.trim() || excerptInput.value.trim() || 'Comprehensive rental advice and guides on UnlockRentals.';
        serpDescPreview.textContent = metaD;
        metaDescCounter.textContent = `${metaDescInput.value.length} / 160 chars`;
    }

    titleInput.addEventListener('input', updateCounters);
    slugInput.addEventListener('input', () => {
        manualSlugEdit = true;
        serpSlugPreview.textContent = slugInput.value || 'article-slug';
    });
    excerptInput.addEventListener('input', updateCounters);
    contentInput.addEventListener('input', updateCounters);
    metaTitleInput.addEventListener('input', updateCounters);
    metaDescInput.addEventListener('input', updateCounters);

    // Initial counter call
    updateCounters();

    // Editor Tab Switcher (Editor vs Live Preview)
    function switchEditorTab(tab) {
        const editorPanel = document.getElementById('editor-panel');
        const previewPanel = document.getElementById('preview-panel');
        const toolbar = document.getElementById('editor-toolbar');
        const tabBtnEditor = document.getElementById('tab-btn-editor');
        const tabBtnPreview = document.getElementById('tab-btn-preview');

        if (tab === 'preview') {
            editorPanel.classList.add('hidden');
            toolbar.classList.add('hidden');
            previewPanel.classList.remove('hidden');

            tabBtnPreview.className = "px-3 py-1 text-xs font-bold rounded-sm bg-[#2563EB] text-white transition-all";
            tabBtnEditor.className = "px-3 py-1 text-xs font-semibold text-zinc-600 hover:text-zinc-900 transition-all";

            // Populate live preview content
            document.getElementById('preview-title').textContent = titleInput.value || 'Untitled Article';
            document.getElementById('preview-author-name').textContent = 'By ' + (document.getElementById('author-name-input').value || 'Editorial Team');
            document.getElementById('preview-category-badge').textContent = document.getElementById('category-select').value || 'Tenant Guide';
            
            const words = contentInput.value.replace(/<[^>]*>/g, ' ').trim().split(/\s+/).length;
            document.getElementById('preview-read-time').textContent = Math.max(1, Math.ceil(words / 200)) + ' min read';

            document.getElementById('preview-body').innerHTML = contentInput.value || '<p class="text-zinc-400 italic">No content written yet.</p>';
        } else {
            previewPanel.classList.add('hidden');
            editorPanel.classList.remove('hidden');
            toolbar.classList.remove('hidden');

            tabBtnEditor.className = "px-3 py-1 text-xs font-bold rounded-sm bg-[#2563EB] text-white transition-all";
            tabBtnPreview.className = "px-3 py-1 text-xs font-semibold text-zinc-600 hover:text-zinc-900 transition-all";
        }
    }

    // Quick Insert Toolbar Helpers
    function insertTag(openTag, closeTag, placeholder) {
        const textarea = contentInput;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selected = textarea.value.substring(start, end) || placeholder;
        const replacement = `${openTag}${selected}${closeTag}`;

        textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + openTag.length, start + openTag.length + selected.length);
        updateCounters();
    }

    function insertList(type) {
        const listItems = "  <li>First key point...</li>\n  <li>Second key point...</li>\n  <li>Third key point...</li>";
        const tag = type === 'ol' ? `<ol>\n${listItems}\n</ol>` : `<ul>\n${listItems}\n</ul>`;
        insertTag(tag, '', '');
    }

    function insertLink() {
        const url = prompt('Enter the link URL (e.g. https://... or /properties):', 'https://');
        if (url) {
            insertTag(`<a href="${url}" target="_blank" rel="noopener">`, '</a>', 'Click here to read more');
        }
    }

    function insertImage() {
        const url = prompt('Enter image URL:', 'https://images.unsplash.com/...');
        if (url) {
            const alt = prompt('Enter image description / caption:', 'Property visual illustration');
            const imgTag = `\n<figure class="my-6">\n  <img src="${url}" alt="${alt || ''}" class="w-full rounded-2xl shadow-md">\n  <figcaption class="text-xs text-center text-zinc-400 mt-2">${alt || ''}</figcaption>\n</figure>\n`;
            insertTag(imgTag, '', '');
        }
    }

    // Category Selector
    function handleCategoryChange(val) {
        const customBox = document.getElementById('custom-category-box');
        const customInput = document.getElementById('custom-category-input');
        if (val === '__custom__') {
            customBox.classList.remove('hidden');
            customInput.focus();
        } else {
            customBox.classList.add('hidden');
            customInput.value = '';
        }
    }

    // Helper: Client-Side Canvas Image Compressor
    function compressImageFile(file, maxWidth, maxHeight, quality, callback) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                let width = img.width;
                let height = img.height;

                if (width > maxWidth) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                }
                if (height > maxHeight) {
                    width = Math.round((width * maxHeight) / height);
                    height = maxHeight;
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                // Export as JPEG
                const dataUrl = canvas.toDataURL('image/jpeg', quality);
                callback(dataUrl);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Image Previews & Handling
    function previewImageFile(input) {
        const preview = document.getElementById('cover-image-preview');
        const statusBox = document.getElementById('file-chosen-status');
        const nameSpan = document.getElementById('file-chosen-name');
        const urlInput = document.getElementById('cover-image-url');
        const base64Input = document.getElementById('image-base64-input');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Instantly compress and generate base64 payload
            compressImageFile(file, 1600, 1200, 0.88, function(compressedData) {
                preview.src = compressedData;
                if (base64Input) {
                    base64Input.value = compressedData;
                }
                if (statusBox && nameSpan) {
                    nameSpan.textContent = `Ready: ${file.name} (Optimized for Web)`;
                    statusBox.classList.remove('hidden');
                }
                if (urlInput) {
                    urlInput.value = '';
                }
            });
        }
    }

    function previewImageUrl(url) {
        const preview = document.getElementById('cover-image-preview');
        const fileInput = document.getElementById('cover-image-file');
        const base64Input = document.getElementById('image-base64-input');
        const statusBox = document.getElementById('file-chosen-status');

        if (url && (url.startsWith('http') || url.startsWith('//'))) {
            preview.src = url;
            if (fileInput) fileInput.value = '';
            if (base64Input) base64Input.value = '';
            if (statusBox) statusBox.classList.add('hidden');
        }
    }

    function previewAvatarFile(input) {
        const preview = document.getElementById('author-avatar-preview');
        const base64Input = document.getElementById('author-avatar-base64-input');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            compressImageFile(file, 400, 400, 0.85, function(compressedData) {
                if (preview) preview.src = compressedData;
                if (base64Input) base64Input.value = compressedData;
            });
        }
    }

    // Direct Instant Image Upload (AJAX)
    function instantUploadCoverImage() {
        @if($blog)
        const btn = document.getElementById('btn-save-image-now');
        const toast = document.getElementById('image-upload-toast');
        const base64Input = document.getElementById('image-base64-input');
        const fileInput = document.getElementById('cover-image-file');

        if (!base64Input.value && (!fileInput.files || !fileInput.files[0])) {
            alert('Please select an image file first.');
            return;
        }

        const originalBtnText = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Saving...';
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        if (base64Input.value) {
            formData.append('image_base64', base64Input.value);
        } else if (fileInput.files[0]) {
            formData.append('image', fileInput.files[0]);
        }

        fetch('{{ route("admin.blogs.upload-image", $blog) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
            }
            if (data.success) {
                if (toast) {
                    toast.className = 'text-xs font-bold px-3.5 py-2 rounded-xl border bg-emerald-50 text-emerald-800 border-emerald-300 block';
                    toast.innerHTML = '<i class="ph-bold ph-check-circle text-emerald-600 mr-1"></i> ' + data.message;
                }
                if (data.image_url) {
                    document.getElementById('cover-image-preview').src = data.image_url + '?t=' + Date.now();
                }
            } else {
                if (toast) {
                    toast.className = 'text-xs font-bold px-3.5 py-2 rounded-xl border bg-rose-50 text-rose-800 border-rose-300 block';
                    toast.innerHTML = '<i class="ph-bold ph-warning-circle text-rose-600 mr-1"></i> ' + (data.message || 'Failed to upload image.');
                }
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
            }
            if (toast) {
                toast.className = 'text-xs font-bold px-3.5 py-2 rounded-xl border bg-rose-50 text-rose-800 border-rose-300 block';
                toast.innerHTML = '<i class="ph-bold ph-warning-circle text-rose-600 mr-1"></i> Network error while saving image.';
            }
        });
        @endif
    }

    function saveExternalImageUrl() {
        @if($blog)
        const urlInput = document.getElementById('cover-image-url');
        const toast = document.getElementById('image-upload-toast');
        const val = urlInput ? urlInput.value.trim() : '';

        if (!val) {
            alert('Please enter an image URL first.');
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('image_url', val);

        fetch('{{ route("admin.blogs.upload-image", $blog) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (toast) {
                    toast.className = 'text-xs font-bold px-3.5 py-2 rounded-xl border bg-emerald-50 text-emerald-800 border-emerald-300 block';
                    toast.innerHTML = '<i class="ph-bold ph-check-circle text-emerald-600 mr-1"></i> ' + data.message;
                }
                if (data.image_url) {
                    document.getElementById('cover-image-preview').src = data.image_url;
                }
            } else {
                if (toast) {
                    toast.className = 'text-xs font-bold px-3.5 py-2 rounded-xl border bg-rose-50 text-rose-800 border-rose-300 block';
                    toast.innerHTML = '<i class="ph-bold ph-warning-circle text-rose-600 mr-1"></i> ' + (data.message || 'Failed to save URL.');
                }
            }
        });
        @endif
    }
</script>
@endpush
@endsection
