@extends('layouts.admin')

@section('title', ($blog ? 'Edit' : 'Create') . ' Blog Post - Admin CRM')

@section('content')
<section class="py-8 lg:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Top Navigation & Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('admin.blogs.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-zinc-500 hover:text-blue-600 mb-2 transition-colors" title="Back to All Articles">
                    <i class="ph-bold ph-arrow-left"></i> Back to Articles
                </a>
                <h1 class="text-2xl font-bold text-zinc-900 tracking-tight">
                    {{ $blog ? 'Edit Article: ' . Str::limit($blog->title, 45) : 'Create New Article' }}
                </h1>
                <p class="text-xs text-zinc-500 mt-0.5">
                    {{ $blog ? 'Make changes to your published article or draft.' : 'Draft a new guide, news piece, or real estate market insight.' }}
                </p>
            </div>

            @if($blog)
            <div class="flex items-center gap-2">
                <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-stone-200 hover:bg-stone-50 text-zinc-700 text-xs font-semibold rounded-sm transition-all shadow-sm" title="View Public Post">
                    <i class="ph ph-arrow-square-out text-sm text-blue-600"></i>
                    <span>Live Preview</span>
                </a>
            </div>
            @endif
        </div>

        {{-- Errors Alert --}}
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-sm text-xs text-red-700 space-y-1 shadow-sm">
            <div class="font-bold flex items-center gap-1.5 text-red-800">
                <i class="ph-bold ph-warning-circle text-base"></i> Please fix the following errors:
            </div>
            <ul class="list-disc pl-5 space-y-0.5 mt-1">
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
                <div class="bg-white border border-stone-200/80 rounded-sm p-5 sm:p-6 shadow-sm space-y-4">
                    {{-- Title --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="post-title" class="text-xs font-bold uppercase tracking-wider text-zinc-700">
                                Article Title <span class="text-red-500">*</span>
                            </label>
                            <span id="title-counter" class="text-[11px] text-zinc-400 font-medium">0 / 100 chars</span>
                        </div>
                        <input type="text" name="title" id="post-title" required maxlength="255"
                               value="{{ old('title', $blog->title ?? '') }}"
                               placeholder="e.g. 10 Essential Rental Agreement Clauses Every Tenant Must Check"
                               class="w-full px-4 py-3 border border-stone-200 rounded-sm text-sm font-semibold text-zinc-900 placeholder:text-zinc-400 focus:outline-none focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB]">
                    </div>

                    {{-- Slug / URL Preview --}}
                    <div>
                        <label for="post-slug" class="text-xs font-bold uppercase tracking-wider text-zinc-700 block mb-1.5">
                            Permanent URL Slug <span class="text-zinc-400 font-normal lowercase">(auto-generated from title)</span>
                        </label>
                        <div class="flex items-center rounded-sm border border-stone-200 bg-stone-50 overflow-hidden text-xs">
                            <span class="px-3 py-2 text-zinc-400 bg-stone-100/80 border-r border-stone-200 font-mono select-none">
                                {{ url('/blog') }}/
                            </span>
                            <input type="text" name="slug" id="post-slug"
                                   value="{{ old('slug', $blog->slug ?? '') }}"
                                   placeholder="article-url-slug"
                                   class="flex-1 px-3 py-2 text-xs font-mono text-zinc-800 bg-transparent focus:outline-none focus:bg-white">
                        </div>
                    </div>

                    {{-- Excerpt / Short Summary --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="post-excerpt" class="text-xs font-bold uppercase tracking-wider text-zinc-700">
                                Summary / Excerpt <span class="text-zinc-400 font-normal lowercase">(shown in listings & Google snippet)</span>
                            </label>
                            <span id="excerpt-counter" class="text-[11px] text-zinc-400 font-medium">0 / 250</span>
                        </div>
                        <textarea name="excerpt" id="post-excerpt" rows="3" maxlength="500"
                                  placeholder="Brief summary of the guide to catch reader attention in listings..."
                                  class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-xs text-zinc-800 placeholder:text-zinc-400 focus:outline-none focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB] resize-none">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Card: Rich Content Editor with Toolbar & Live Preview --}}
                <div class="bg-white border border-stone-200/80 rounded-sm shadow-sm overflow-hidden">
                    {{-- Editor Header & Tabs --}}
                    <div class="p-4 border-b border-stone-200 bg-stone-50/70 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-zinc-700">
                                Article Content <span class="text-red-500">*</span>
                            </span>
                            <span class="text-[10px] text-zinc-400 bg-stone-200/60 px-2 py-0.5 rounded-sm">HTML / Rich Formatted</span>
                        </div>

                        {{-- Tab Switcher --}}
                        <div class="flex items-center bg-white border border-stone-200 rounded-sm p-0.5">
                            <button type="button" id="tab-btn-editor" onclick="switchEditorTab('editor')"
                                    class="px-3 py-1 text-xs font-bold rounded-sm bg-[#2563EB] text-white transition-all">
                                <i class="ph-bold ph-code"></i> Editor
                            </button>
                            <button type="button" id="tab-btn-preview" onclick="switchEditorTab('preview')"
                                    class="px-3 py-1 text-xs font-semibold text-zinc-600 hover:text-zinc-900 transition-all">
                                <i class="ph-bold ph-eye"></i> Live Preview
                            </button>
                        </div>
                    </div>

                    {{-- Formatting Quick Toolbar --}}
                    <div id="editor-toolbar" class="p-2 border-b border-stone-200/80 bg-stone-100/60 flex flex-wrap items-center gap-1 text-xs">
                        <button type="button" onclick="insertTag('<h2>', '</h2>', 'Section Heading')" class="px-2.5 py-1 rounded bg-white hover:bg-stone-200 border border-stone-200 font-bold text-zinc-700 shadow-2xs" title="Heading 2">H2</button>
                        <button type="button" onclick="insertTag('<h3>', '</h3>', 'Subheading')" class="px-2.5 py-1 rounded bg-white hover:bg-stone-200 border border-stone-200 font-bold text-zinc-700 shadow-2xs" title="Heading 3">H3</button>
                        <span class="w-px h-4 bg-stone-300 mx-1"></span>
                        <button type="button" onclick="insertTag('<strong>', '</strong>', 'Bold text')" class="px-2.5 py-1 rounded bg-white hover:bg-stone-200 border border-stone-200 font-black text-zinc-800 shadow-2xs" title="Bold"><strong>B</strong></button>
                        <button type="button" onclick="insertTag('<em>', '</em>', 'Italic text')" class="px-2.5 py-1 rounded bg-white hover:bg-stone-200 border border-stone-200 italic text-zinc-700 shadow-2xs" title="Italic"><em>I</em></button>
                        <span class="w-px h-4 bg-stone-300 mx-1"></span>
                        <button type="button" onclick="insertList('ul')" class="px-2 py-1 rounded bg-white hover:bg-stone-200 border border-stone-200 text-zinc-700 shadow-2xs flex items-center gap-1" title="Bullet List">
                            <i class="ph-bold ph-list-bullets"></i> Bullet List
                        </button>
                        <button type="button" onclick="insertList('ol')" class="px-2 py-1 rounded bg-white hover:bg-stone-200 border border-stone-200 text-zinc-700 shadow-2xs flex items-center gap-1" title="Numbered List">
                            <i class="ph-bold ph-list-numbers"></i> Numbered List
                        </button>
                        <button type="button" onclick="insertTag('<blockquote>', '</blockquote>', 'Important key takeaway or quote...')" class="px-2 py-1 rounded bg-white hover:bg-stone-200 border border-stone-200 text-zinc-700 shadow-2xs flex items-center gap-1" title="Quote Box">
                            <i class="ph-bold ph-quotes"></i> Quote
                        </button>
                        <span class="w-px h-4 bg-stone-300 mx-1"></span>
                        <button type="button" onclick="insertLink()" class="px-2 py-1 rounded bg-white hover:bg-stone-200 border border-stone-200 text-blue-600 shadow-2xs flex items-center gap-1" title="Insert Link">
                            <i class="ph-bold ph-link"></i> Link
                        </button>
                        <button type="button" onclick="insertImage()" class="px-2 py-1 rounded bg-white hover:bg-stone-200 border border-stone-200 text-purple-600 shadow-2xs flex items-center gap-1" title="Insert Image Tag">
                            <i class="ph-bold ph-image"></i> Image
                        </button>
                    </div>

                    {{-- Editor Textarea Panel --}}
                    <div id="editor-panel" class="p-4 sm:p-5">
                        <textarea name="content" id="post-content" required rows="18"
                                  placeholder="Write your article in structured HTML or paragraphs..."
                                  class="w-full p-4 border border-stone-200 rounded-sm text-sm font-mono text-zinc-900 bg-stone-50/40 focus:bg-white focus:outline-none focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB] leading-relaxed">{{ old('content', $blog->content ?? '') }}</textarea>
                        <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-2">
                            <span id="word-counter">0 words</span>
                            <span>Standard HTML tags allowed (h2, h3, p, strong, em, ul, li, a, img, blockquote)</span>
                        </div>
                    </div>

                    {{-- Live Preview Panel --}}
                    <div id="preview-panel" class="hidden p-6 sm:p-10 bg-white min-h-[450px]">
                        <div class="max-w-3xl mx-auto">
                            <div class="mb-4">
                                <span class="px-2.5 py-1 text-[11px] font-bold uppercase bg-blue-100 text-blue-800 rounded-sm" id="preview-category-badge">Tenant Guide</span>
                            </div>
                            <h1 id="preview-title" class="text-2xl sm:text-3xl font-extrabold text-zinc-900 mb-4 font-['Playfair_Display',serif]">
                                Article Title Preview
                            </h1>
                            <div class="flex items-center gap-3 text-xs text-zinc-400 pb-4 mb-6 border-b border-stone-200">
                                <span id="preview-author-name">By {{ auth()->user()->name }}</span>
                                <span>•</span>
                                <span>{{ now()->format('M d, Y') }}</span>
                                <span>•</span>
                                <span id="preview-read-time">5 min read</span>
                            </div>
                            <div id="preview-body" class="prose prose-slate max-w-none text-sm text-zinc-700 leading-relaxed space-y-4">
                                {{-- Content preview injected via JS --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card: SEO Meta Information & Google Snippet Simulator --}}
                <div class="bg-white border border-stone-200/80 rounded-sm p-5 sm:p-6 shadow-sm space-y-5">
                    <div class="flex items-center gap-2 border-b border-stone-200 pb-3">
                        <i class="ph-bold ph-google-logo text-lg text-blue-600"></i>
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-800">Search Engine Optimization (SEO)</h3>
                            <p class="text-[11px] text-zinc-400">Optimize how this article ranks and looks on Google search results.</p>
                        </div>
                    </div>

                    {{-- Google SERP Snippet Preview Box --}}
                    <div class="p-4 bg-stone-50 border border-stone-200 rounded-sm space-y-1">
                        <div class="text-[11px] text-zinc-500 flex items-center gap-1 font-mono">
                            <span class="text-emerald-700">https://unlockrentals.com</span> › blog › <span id="serp-slug-preview">{{ $blog->slug ?? 'article-slug' }}</span>
                        </div>
                        <div id="serp-title-preview" class="text-base font-medium text-blue-700 hover:underline cursor-pointer line-clamp-1">
                            {{ $blog->meta_title ?? ($blog->title ?? 'Article Title - UnlockRentals Blog') }}
                        </div>
                        <div id="serp-desc-preview" class="text-xs text-zinc-600 line-clamp-2 leading-relaxed">
                            {{ $blog->meta_description ?? ($blog->excerpt ?? 'Meta description will be displayed here as it appears on Google search snippet results.') }}
                        </div>
                    </div>

                    <div class="space-y-4">
                        {{-- Meta Title --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="post-meta-title" class="text-xs font-semibold text-zinc-700">Custom Meta Title</label>
                                <span id="meta-title-counter" class="text-[11px] text-zinc-400">0 / 60 chars (Recommended)</span>
                            </div>
                            <input type="text" name="meta_title" id="post-meta-title" maxlength="255"
                                   value="{{ old('meta_title', $blog->meta_title ?? '') }}"
                                   placeholder="Custom SEO Title (defaults to post title if left empty)"
                                   class="w-full px-3.5 py-2 border border-stone-200 rounded-sm text-xs text-zinc-800 placeholder:text-zinc-400 focus:outline-none focus:border-[#2563EB]">
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="post-meta-description" class="text-xs font-semibold text-zinc-700">Custom Meta Description</label>
                                <span id="meta-desc-counter" class="text-[11px] text-zinc-400">0 / 160 chars (Recommended)</span>
                            </div>
                            <textarea name="meta_description" id="post-meta-description" rows="2" maxlength="500"
                                      placeholder="Compelling 150-160 character description for Google Search..."
                                      class="w-full px-3.5 py-2 border border-stone-200 rounded-sm text-xs text-zinc-800 placeholder:text-zinc-400 focus:outline-none focus:border-[#2563EB] resize-none">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                        </div>

                        {{-- Tags / Keywords --}}
                        <div>
                            <label for="post-tags" class="text-xs font-semibold text-zinc-700 block mb-1.5">
                                Tags / Keywords <span class="text-zinc-400 font-normal">(comma-separated)</span>
                            </label>
                            <input type="text" name="tags" id="post-tags"
                                   value="{{ old('tags', $blog && is_array($blog->tags) ? implode(', ', $blog->tags) : '') }}"
                                   placeholder="e.g. Renting Tips, Tenant Rights, Security Deposit, Real Estate"
                                   class="w-full px-3.5 py-2 border border-stone-200 rounded-sm text-xs text-zinc-800 placeholder:text-zinc-400 focus:outline-none focus:border-[#2563EB]">
                        </div>
                    </div>
                </div>

            </div>

            {{-- ──────────────── Right Column (4 cols): Settings Sidebar ──────────────── --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Card: Publishing Actions --}}
                <div class="bg-white border border-stone-200/80 rounded-sm p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-stone-200 pb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-zinc-800 flex items-center gap-1.5">
                            <i class="ph-bold ph-paper-plane-tilt text-blue-600"></i> Publishing State
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-sm {{ ($blog && $blog->is_published) || old('is_published', true) ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ ($blog && $blog->is_published) || old('is_published', true) ? 'Live' : 'Draft' }}
                        </span>
                    </div>

                    {{-- Publish Status Toggle --}}
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-zinc-700 block">Status</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-2.5 border rounded-sm cursor-pointer transition-all {{ old('is_published', $blog ? ($blog->is_published ? '1' : '0') : '1') == '1' ? 'border-emerald-500 bg-emerald-50/50 text-emerald-900 font-bold' : 'border-stone-200 hover:bg-stone-50 text-zinc-600' }}">
                                <input type="radio" name="is_published" value="1" {{ old('is_published', $blog ? ($blog->is_published ? '1' : '0') : '1') == '1' ? 'checked' : '' }} class="accent-emerald-600">
                                <span class="text-xs">Published</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 border rounded-sm cursor-pointer transition-all {{ old('is_published', $blog ? ($blog->is_published ? '1' : '0') : '1') == '0' ? 'border-amber-500 bg-amber-50/50 text-amber-900 font-bold' : 'border-stone-200 hover:bg-stone-50 text-zinc-600' }}">
                                <input type="radio" name="is_published" value="0" {{ old('is_published', $blog ? ($blog->is_published ? '1' : '0') : '1') == '0' ? 'checked' : '' }} class="accent-amber-600">
                                <span class="text-xs">Draft</span>
                            </label>
                        </div>
                    </div>

                    {{-- Featured Article Checkbox --}}
                    <div class="pt-2 border-t border-stone-100">
                        <label class="flex items-center gap-2.5 cursor-pointer p-2 rounded-sm hover:bg-amber-50/60 transition-colors">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $blog->is_featured ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 accent-amber-500 rounded-sm">
                            <div>
                                <span class="text-xs font-bold text-zinc-800 flex items-center gap-1">
                                    <i class="ph-fill ph-star text-amber-500 text-sm"></i> Featured Guide
                                </span>
                                <span class="text-[10px] text-zinc-400 block">Promote prominently in homepage hero banner</span>
                            </div>
                        </label>
                    </div>

                    {{-- Published Date & Time --}}
                    <div class="pt-2 border-t border-stone-100">
                        <label for="post-published-at" class="text-xs font-semibold text-zinc-700 block mb-1">
                            Publish Date & Time <span class="text-zinc-400 font-normal">(Optional)</span>
                        </label>
                        <input type="datetime-local" name="published_at" id="post-published-at"
                               value="{{ old('published_at', $blog && $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-stone-200 rounded-sm text-xs text-zinc-800 bg-stone-50/50 focus:outline-none focus:border-[#2563EB]">
                    </div>

                    {{-- Read Time Estimate --}}
                    <div>
                        <label for="post-read-time" class="text-xs font-semibold text-zinc-700 block mb-1">
                            Read Time <span class="text-zinc-400 font-normal">(e.g. 5 min read)</span>
                        </label>
                        <input type="text" name="read_time" id="post-read-time"
                               value="{{ old('read_time', $blog->read_time ?? '') }}"
                               placeholder="Auto-calculated if blank"
                               class="w-full px-3 py-2 border border-stone-200 rounded-sm text-xs text-zinc-800 placeholder:text-zinc-400 focus:outline-none focus:border-[#2563EB]">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="pt-4 border-t border-stone-200 space-y-2">
                        <button type="submit" class="w-full py-2.5 px-4 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs font-bold rounded-sm transition-all shadow-sm flex items-center justify-center gap-2">
                            <i class="ph-bold ph-check"></i>
                            <span>{{ $blog ? 'Save Changes' : 'Publish Article' }}</span>
                        </button>
                        <a href="{{ route('admin.blogs.index') }}" class="w-full py-2 px-4 bg-stone-100 hover:bg-stone-200 text-zinc-600 text-xs font-semibold rounded-sm transition-all text-center block" title="Cancel">
                            Cancel
                        </a>
                    </div>
                </div>

                {{-- Card: Category Selection --}}
                <div class="bg-white border border-stone-200/80 rounded-sm p-5 shadow-sm space-y-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-800 flex items-center gap-1.5 border-b border-stone-200 pb-3">
                        <i class="ph-bold ph-tag text-blue-600"></i> Category <span class="text-red-500">*</span>
                    </span>

                    <div>
                        <select name="category" id="category-select" required onchange="handleCategoryChange(this.value)"
                                class="w-full px-3 py-2.5 border border-stone-200 rounded-sm text-xs text-zinc-800 bg-stone-50/50 focus:bg-white focus:outline-none focus:border-[#2563EB]">
                            @php $selectedCategory = old('category', $blog->category ?? 'Tenant Guide'); @endphp
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $selectedCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                            <option value="__custom__">+ Add New Category...</option>
                        </select>
                    </div>

                    {{-- Custom category input hidden by default unless clicked --}}
                    <div id="custom-category-box" class="hidden">
                        <label class="text-[11px] font-semibold text-zinc-600 block mb-1">Type New Category Name</label>
                        <input type="text" name="custom_category" id="custom-category-input"
                               placeholder="e.g. Legal & Tax Tips"
                               class="w-full px-3 py-2 border border-blue-300 bg-blue-50/30 rounded-sm text-xs text-zinc-900 focus:outline-none focus:border-blue-600">
                    </div>
                </div>

                {{-- Card: Featured Cover Image --}}
                <div class="bg-white border border-stone-200/80 rounded-sm p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-stone-200 pb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-zinc-800 flex items-center gap-1.5">
                            <i class="ph-bold ph-image text-blue-600"></i> Cover Image
                        </span>
                        <span class="text-[10px] text-zinc-400">16:9 Recommended</span>
                    </div>

                    {{-- Live Image Preview --}}
                    <div class="relative rounded-sm overflow-hidden bg-stone-100 border border-stone-200 aspect-video flex items-center justify-center group">
                        <img id="cover-image-preview"
                             src="{{ $blog ? $blog->cover_image_url : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80' }}"
                             alt="Cover Preview"
                             class="w-full h-full object-cover">
                    </div>

                    {{-- Upload file --}}
                    <div>
                        <label class="text-xs font-semibold text-zinc-700 block mb-1">Upload Local Image</label>
                        <input type="file" name="image" id="cover-image-file" accept="image/*" onchange="previewImageFile(this)"
                               class="w-full text-xs text-zinc-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-sm file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-stone-200 rounded-sm p-1">
                    </div>

                    {{-- Or External URL --}}
                    <div>
                        <label class="text-xs font-semibold text-zinc-700 block mb-1">Or External Image URL</label>
                        <input type="url" name="image_url" id="cover-image-url" oninput="previewImageUrl(this.value)"
                               value="{{ old('image_url', $blog && str_starts_with($blog->image ?? '', 'http') ? $blog->image : '') }}"
                               placeholder="https://images.unsplash.com/..."
                               class="w-full px-3 py-2 border border-stone-200 rounded-sm text-xs text-zinc-800 placeholder:text-zinc-400 focus:outline-none focus:border-[#2563EB]">
                    </div>
                </div>

                {{-- Card: Author Details --}}
                <div class="bg-white border border-stone-200/80 rounded-sm p-5 shadow-sm space-y-3.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-800 flex items-center gap-1.5 border-b border-stone-200 pb-3">
                        <i class="ph-bold ph-user-circle text-blue-600"></i> Author Information
                    </span>

                    <div>
                        <label class="text-xs font-semibold text-zinc-700 block mb-1">Author Name</label>
                        <input type="text" name="author_name" id="author-name-input"
                               value="{{ old('author_name', $blog->author_name ?? auth()->user()->name) }}"
                               placeholder="e.g. Priya Sharma"
                               class="w-full px-3 py-2 border border-stone-200 rounded-sm text-xs text-zinc-800 focus:outline-none focus:border-[#2563EB]">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-zinc-700 block mb-1">Author Role / Title</label>
                        <input type="text" name="author_role"
                               value="{{ old('author_role', $blog->author_role ?? 'Real Estate Advisor') }}"
                               placeholder="e.g. Real Estate Strategist"
                               class="w-full px-3 py-2 border border-stone-200 rounded-sm text-xs text-zinc-800 focus:outline-none focus:border-[#2563EB]">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-zinc-700 block mb-1">Author Avatar Photo (Optional)</label>
                        <input type="file" name="author_avatar" accept="image/*"
                               class="w-full text-xs text-zinc-500 file:mr-3 file:py-1 file:px-2.5 file:rounded-sm file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-zinc-700 hover:file:bg-stone-200 border border-stone-200 rounded-sm p-1">
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

    // Image Previews
    function previewImageFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('cover-image-preview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewImageUrl(url) {
        if (url && url.startsWith('http')) {
            document.getElementById('cover-image-preview').src = url;
        }
    }
</script>
@endpush
@endsection
