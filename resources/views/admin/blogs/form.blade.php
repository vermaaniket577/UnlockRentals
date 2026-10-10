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

                {{-- ⚡ Fast-Post Accelerator Toolbar --}}
                <div class="bg-gradient-to-r from-blue-50/90 via-indigo-50/90 to-purple-50/90 border border-blue-200/90 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold shadow-md shadow-blue-500/25 flex-shrink-0">
                                <i class="ph-bold ph-lightning text-lg"></i>
                            </span>
                            <div>
                                <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                    <span>Fast-Post Accelerator</span>
                                    <span class="text-[10px] font-extrabold px-1.5 py-0.2 bg-blue-100 text-blue-700 rounded-md normal-case">Superfast</span>
                                </h4>
                                <p class="text-[11px] text-slate-500 font-medium">1-Click helpers to format content, draft structure, and auto-generate SEO</p>
                            </div>
                        </div>

                        {{-- Fast Notification Toast --}}
                        <div id="fast-toast" class="hidden text-xs font-bold px-3 py-1.5 bg-emerald-600 text-white rounded-xl shadow-xs animate-pulse"></div>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap pt-1">
                        {{-- 1-Click Starter Template --}}
                        <div class="relative flex-1 sm:flex-none min-w-[200px]">
                            <select id="quick-template-select" onchange="applyStarterTemplate(this.value)"
                                    class="w-full text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300/90 rounded-xl px-3.5 py-2.5 pr-8 shadow-2xs transition-all appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-600/20">
                                <option value="">⚡ Load Starter Outline...</option>
                                <option value="tenant_guide">Tenant Renting Guide Outline</option>
                                <option value="locality_guide">Gurugram Sector / Locality Review</option>
                                <option value="agreement_guide">Rental Agreement & Legal Terms</option>
                                <option value="owner_tips">Owner Rental Strategy (0 Brokerage)</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>

                        {{-- 1-Click Auto-Fill SEO & Excerpt --}}
                        <button type="button" onclick="autoGenerateSeoAndExcerpt()"
                                class="px-3.5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-98 text-white text-xs font-extrabold rounded-xl shadow-xs hover:shadow transition-all flex items-center gap-1.5 cursor-pointer">
                            <i class="ph-bold ph-magic-wand text-sm"></i>
                            <span>Auto-Generate SEO & Summary</span>
                        </button>

                        {{-- 1-Click Format HTML / Clean Paste --}}
                        <button type="button" onclick="cleanAndFormatPastedContent()"
                                class="px-3.5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300/90 text-xs font-bold rounded-xl shadow-2xs transition-all flex items-center gap-1.5 cursor-pointer"
                                title="Converts raw copied text or ChatGPT response into neat paragraphs and headings">
                            <i class="ph-bold ph-text-align-left text-sm text-purple-600"></i>
                            <span>Auto-Format HTML</span>
                        </button>
                    </div>
                </div>

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

                    @php
                        $sliderIds = json_decode(\App\Models\Setting::get('home_blog_slider_ids', '[]'), true) ?: [];
                        $inSliderCurrent = ($blog && (!empty($blog->show_in_slider) || in_array($blog->id, $sliderIds)));
                    @endphp
                    {{-- Homepage Slider Checkbox --}}
                    <div class="pt-3 border-t border-slate-100">
                        <label class="flex items-start gap-3 cursor-pointer p-2.5 rounded-xl hover:bg-teal-50/50 transition-colors">
                            <input type="checkbox" name="show_in_slider" value="1" {{ old('show_in_slider', $inSliderCurrent) ? 'checked' : '' }}
                                   class="w-4 h-4 mt-0.5 accent-teal-600 rounded">
                            <div>
                                <span class="text-xs font-extrabold text-slate-800 flex items-center gap-1">
                                    <i class="ph-bold ph-slideshow text-teal-600 text-sm"></i> Show in Homepage Slider (RTL)
                                </span>
                                <span class="text-[11px] text-slate-400 block mt-0.5">Include this article in the animated homepage right-to-left slider</span>
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
                        <button type="submit" id="btn-submit-post"
                                class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 !text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/25 transition-all flex items-center justify-center gap-2 transform active:scale-98 cursor-pointer"
                                style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important;">
                            <i class="ph-bold ph-check text-base !text-white" style="color: #ffffff !important;"></i>
                            <span class="!text-white" id="btn-submit-text" style="color: #ffffff !important;">{{ $blog ? 'Save Changes' : 'Publish Article' }}</span>
                        </button>

                        <button type="button" onclick="saveAsDraftNow()"
                                class="w-full py-2.5 px-4 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="ph-bold ph-file-dashed text-sm text-amber-700"></i>
                            <span>Save as Draft</span>
                        </button>

                        <div class="flex items-center justify-between text-[11px] text-slate-400 px-1 pt-1 font-semibold">
                            <span class="inline-flex items-center gap-1">
                                <kbd class="px-1.5 py-0.5 bg-slate-100 rounded text-[10px] font-mono border border-slate-200">Ctrl</kbd> + <kbd class="px-1.5 py-0.5 bg-slate-100 rounded text-[10px] font-mono border border-slate-200">S</kbd> to quick save
                            </span>
                            <a href="{{ route('admin.blogs.index') }}" class="text-slate-500 hover:text-slate-800 transition-colors" title="Cancel">
                                Cancel
                            </a>
                        </div>
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

                    {{-- ⚡ Instant Stock Real Estate Covers (0-Upload Needed) --}}
                    <div class="pt-2.5 border-t border-slate-100 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1">
                                <i class="ph-bold ph-lightning text-amber-500"></i>
                                <span>1-Click Stock Covers (No Upload)</span>
                            </label>
                            <span class="text-[10px] text-slate-400 font-medium">Instant</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" onclick="selectInstantCover('https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80', 'Tower Condominium')"
                                    class="group/c relative rounded-xl overflow-hidden aspect-video border-2 border-slate-200 hover:border-blue-600 focus:border-blue-600 transition-all cursor-pointer">
                                <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=300&q=60" alt="Tower" class="w-full h-full object-cover group-hover/c:scale-105 transition-transform">
                                <span class="absolute inset-x-0 bottom-0 bg-slate-900/80 text-[10px] font-bold text-white text-center py-0.5">High-Rise</span>
                            </button>
                            <button type="button" onclick="selectInstantCover('https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80', 'Modern Living Room')"
                                    class="group/c relative rounded-xl overflow-hidden aspect-video border-2 border-slate-200 hover:border-blue-600 focus:border-blue-600 transition-all cursor-pointer">
                                <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=300&q=60" alt="Living Room" class="w-full h-full object-cover group-hover/c:scale-105 transition-transform">
                                <span class="absolute inset-x-0 bottom-0 bg-slate-900/80 text-[10px] font-bold text-white text-center py-0.5">Living Room</span>
                            </button>
                            <button type="button" onclick="selectInstantCover('https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&w=1200&q=80', 'Apartment Keys')"
                                    class="group/c relative rounded-xl overflow-hidden aspect-video border-2 border-slate-200 hover:border-blue-600 focus:border-blue-600 transition-all cursor-pointer">
                                <img src="https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&w=300&q=60" alt="Keys" class="w-full h-full object-cover group-hover/c:scale-105 transition-transform">
                                <span class="absolute inset-x-0 bottom-0 bg-slate-900/80 text-[10px] font-bold text-white text-center py-0.5">Key Handover</span>
                            </button>
                            <button type="button" onclick="selectInstantCover('https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1200&q=80', 'Rental Agreement Desk')"
                                    class="group/c relative rounded-xl overflow-hidden aspect-video border-2 border-slate-200 hover:border-blue-600 focus:border-blue-600 transition-all cursor-pointer">
                                <img src="https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=300&q=60" alt="Legal" class="w-full h-full object-cover group-hover/c:scale-105 transition-transform">
                                <span class="absolute inset-x-0 bottom-0 bg-slate-900/80 text-[10px] font-bold text-white text-center py-0.5">Agreement</span>
                            </button>
                            <button type="button" onclick="selectInstantCover('https://images.unsplash.com/photo-1556912172-45b7abe8b7e1?auto=format&fit=crop&w=1200&q=80', 'Modular Kitchen')"
                                    class="group/c relative rounded-xl overflow-hidden aspect-video border-2 border-slate-200 hover:border-blue-600 focus:border-blue-600 transition-all cursor-pointer">
                                <img src="https://images.unsplash.com/photo-1556912172-45b7abe8b7e1?auto=format&fit=crop&w=300&q=60" alt="Kitchen" class="w-full h-full object-cover group-hover/c:scale-105 transition-transform">
                                <span class="absolute inset-x-0 bottom-0 bg-slate-900/80 text-[10px] font-bold text-white text-center py-0.5">Kitchen</span>
                            </button>
                            <button type="button" onclick="selectInstantCover('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80', 'Studio / Co-Living')"
                                    class="group/c relative rounded-xl overflow-hidden aspect-video border-2 border-slate-200 hover:border-blue-600 focus:border-blue-600 transition-all cursor-pointer">
                                <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=300&q=60" alt="Studio" class="w-full h-full object-cover group-hover/c:scale-105 transition-transform">
                                <span class="absolute inset-x-0 bottom-0 bg-slate-900/80 text-[10px] font-bold text-white text-center py-0.5">Studio / Flat</span>
                            </button>
                        </div>
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

    // Fast Toast Notification
    function showFastNotification(msg) {
        const toast = document.getElementById('fast-toast');
        if (!toast) return;
        toast.textContent = msg;
        toast.classList.remove('hidden');
        clearTimeout(window._fastToastTimer);
        window._fastToastTimer = setTimeout(() => {
            toast.classList.add('hidden');
        }, 3200);
    }

    // 1-Click Auto-Generate SEO & Excerpt from Title + Content
    window.autoGenerateSeoAndExcerpt = function() {
        const title = (titleInput.value || '').trim();
        const contentHtml = contentInput.value || '';

        if (!title && !contentHtml) {
            alert('Please enter an Article Title or Content first.');
            return;
        }

        // Clean text from HTML content
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = contentHtml;
        const cleanText = (tempDiv.textContent || tempDiv.innerText || '').replace(/\s+/g, ' ').trim();

        // 1. Generate Excerpt (first 1-2 clean sentences, max 200 chars)
        let excerpt = '';
        if (cleanText) {
            const sentenceMatch = cleanText.match(/^.*?[.!?](?:\s|$)/);
            if (sentenceMatch && sentenceMatch[0].length >= 35 && sentenceMatch[0].length <= 220) {
                excerpt = sentenceMatch[0].trim();
            } else {
                excerpt = cleanText.length > 200 ? cleanText.substring(0, 197).trim() + '...' : cleanText;
            }
        } else {
            excerpt = `Comprehensive rental guide on ${title} with essential advice, market trends, and verification tips.`;
        }
        excerptInput.value = excerpt;

        // 2. SEO Meta Title (max 60 chars)
        if (!metaTitleInput.value.trim() && title) {
            const metaT = title.length > 40 ? title : `${title} | UnlockRentals Guide`;
            metaTitleInput.value = metaT.substring(0, 60);
        }

        // 3. SEO Meta Description (max 155 chars)
        if (!metaDescInput.value.trim()) {
            let metaD = excerpt.length <= 155 ? excerpt : excerpt.substring(0, 152) + '...';
            metaDescInput.value = metaD;
        }

        // 4. Smart Auto-Tags
        const tagsInput = document.getElementById('post-tags');
        if (tagsInput && !tagsInput.value.trim()) {
            const keywords = ['Renting Tips', 'Gurugram', 'Zero Brokerage'];
            const fullText = (title + ' ' + cleanText).toLowerCase();

            if (fullText.includes('agreement') || fullText.includes('clause') || fullText.includes('legal')) keywords.push('Rental Agreement', 'Legal Checks');
            if (fullText.includes('deposit') || fullText.includes('security')) keywords.push('Security Deposit');
            if (fullText.includes('owner') || fullText.includes('landlord')) keywords.push('Owner Insights');
            if (fullText.includes('sector') || fullText.includes('cyber city') || fullText.includes('golf course')) keywords.push('Gurugram Sectors');
            if (fullText.includes('commercial') || fullText.includes('office')) keywords.push('Commercial Hub');
            if (fullText.includes('bhk') || fullText.includes('flat') || fullText.includes('apartment')) keywords.push('Apartment Living');

            tagsInput.value = [...new Set(keywords)].slice(0, 5).join(', ');
        }

        updateCounters();
        showFastNotification('✨ Summary, Meta Title, Description & Tags auto-generated!');
    };

    // 1-Click Clean & Format Pasted Content (e.g. from ChatGPT, Google Docs, Word)
    window.cleanAndFormatPastedContent = function() {
        let raw = (contentInput.value || '').trim();
        if (!raw) {
            alert('Please paste some text into the editor first.');
            return;
        }

        const lines = raw.split(/\r?\n/);
        const formattedBlocks = [];
        let inList = null;
        let listBuffer = [];

        function flushList() {
            if (inList && listBuffer.length > 0) {
                formattedBlocks.push(`<${inList}>\n  ` + listBuffer.map(li => `<li>${li}</li>`).join('\n  ') + `\n</${inList}>`);
                inList = null;
                listBuffer = [];
            }
        }

        for (let i = 0; i < lines.length; i++) {
            let line = lines[i].trim();
            if (!line) {
                flushList();
                continue;
            }

            if (/^###?\s+(.+)/.test(line)) {
                flushList();
                const m = line.match(/^###?\s+(.+)/);
                formattedBlocks.push(`<h3>${m[1].trim()}</h3>`);
            } else if (/^##\s+(.+)/.test(line) || (/^[0-9]+\.\s+([A-Z].{3,60})$/.test(line) && line.length < 65)) {
                flushList();
                const headingText = line.replace(/^(##\s+|[0-9]+\.\s+)/, '').trim();
                formattedBlocks.push(`<h2>${headingText}</h2>`);
            } else if (/^[-*•]\s+(.+)/.test(line)) {
                if (inList !== 'ul') {
                    flushList();
                    inList = 'ul';
                }
                listBuffer.push(line.replace(/^[-*•]\s+/, '').trim());
            } else if (/^[0-9]+[.)]\s+(.+)/.test(line)) {
                if (inList !== 'ol') {
                    flushList();
                    inList = 'ol';
                }
                listBuffer.push(line.replace(/^[0-9]+[.)]\s+/, '').trim());
            } else {
                flushList();
                if (!line.startsWith('<')) {
                    formattedBlocks.push(`<p>${line}</p>`);
                } else {
                    formattedBlocks.push(line);
                }
            }
        }
        flushList();

        contentInput.value = formattedBlocks.join('\n\n');
        updateCounters();
        showFastNotification('🪄 Pasted content formatted into clean HTML paragraphs & headings!');
    };

    // 1-Click Starter Outline Templates
    window.applyStarterTemplate = function(templateKey) {
        if (!templateKey) return;

        if (contentInput.value.trim() && !confirm('Insert starter template? Your existing editor text will be replaced.')) {
            document.getElementById('quick-template-select').value = '';
            return;
        }

        const templates = {
            tenant_guide: {
                title: '10 Essential Rental Agreement Clauses Every Tenant Must Check Before Signing',
                category: 'Tenant Guide',
                content: `<h2>1. Introduction & Market Overview</h2>
<p>Finding the right rental home in Gurugram can feel overwhelming with numerous sectors, high-rises, and varying maintenance charges. Whether you are moving close to Cyber City, Golf Course Extension, or Sohna Road, having a clear checklist protects your security deposit and peace of mind.</p>

<h2>2. Essential Verification Checklist</h2>
<ul>
  <li><strong>Verify Property Ownership:</strong> Ask for title deed copy or recent electricity/property tax bill matching the owner's name.</li>
  <li><strong>Check Maintenance & Electricity Billing:</strong> Confirm whether society maintenance is included in the rent and verify prepaid electricity meter rates.</li>
  <li><strong>Inspect Plumbing, Fixtures & Water Pressure:</strong> Run taps, check geysers, AC points, and check for any seepage before handing over token money.</li>
</ul>

<blockquote><strong>Pro Tip:</strong> Always record a 2-minute video walkthrough of the furnished/semi-furnished apartment before taking possession to avoid security deposit deduction disputes at checkout.</blockquote>

<h2>3. Key Agreement Clauses Every Tenant Must Have</h2>
<ol>
  <li><strong>Lock-in Period & Notice Period:</strong> Standard is 1 month notice after a 6-month lock-in. Ensure penalty clauses are symmetric.</li>
  <li><strong>Security Deposit Refund Timeline:</strong> Specify that the security deposit must be refunded via bank transfer within 7 days of handover.</li>
  <li><strong>Major vs. Minor Repairs:</strong> Structural repairs (pipes, seepage, wiring) should be strictly the owner's responsibility.</li>
</ol>

<h2>4. Final Takeaway</h2>
<p>With UnlockRentals, you connect directly with verified property owners with 0 brokerage, verified inventory, and complete legal transparency.</p>`
            },
            locality_guide: {
                title: 'Gurugram Sector Rental Guide: Best Neighborhoods, Pricing & Metro Access',
                category: 'Market Trends',
                content: `<h2>1. Why Locality Choice Dictates Rental Quality</h2>
<p>In Gurugram, your daily commute, water supply, and society amenities vary substantially across micro-markets. Here is a breakdown of top sectors for working professionals, families, and expatriates.</p>

<h2>2. Top Sectors Compared</h2>
<ul>
  <li><strong>Golf Course Road & Extension:</strong> Premium gated condominiums, excellent Rapid Metro access, luxury amenities (DLF Phase 5, Sector 54, Sector 56).</li>
  <li><strong>Sohna Road (Sector 47 - 50):</strong> Ideal for families with top schools, hospitals, and central retail hubs nearby.</li>
  <li><strong>Dwarka Expressway (Sector 102 - 109):</strong> High-end modern gated complexes with larger floor plans at 25-35% lower rental cost compared to central Gurugram.</li>
</ul>

<h2>3. Typical Rental Price Bands (2026 Updated)</h2>
<ul>
  <li><strong>1 BHK / Studio:</strong> ₹18,000 - ₹28,000 / month</li>
  <li><strong>2 BHK Apartment:</strong> ₹28,000 - ₹45,000 / month</li>
  <li><strong>3 BHK Luxury Condo:</strong> ₹48,000 - ₹85,000+ / month</li>
</ul>

<blockquote><strong>Note:</strong> Gated society maintenance typically adds ₹3,000 - ₹6,500/month depending on clubhouse amenities and power backup capacity.</blockquote>

<h2>4. Final Advice</h2>
<p>Visit the sector during peak traffic hours (8:30 AM or 7:00 PM) to gauge authentic commute times before finalizing your rental lease.</p>`
            },
            agreement_guide: {
                title: 'Rental Agreement Essentials: Stamp Duty, Registration & Safe Clauses',
                category: 'Legal & Finance',
                content: `<h2>1. Why an 11-Month Lease Agreement is Standard</h2>
<p>Under Indian property law, rental agreements for 11 months avoid mandatory sub-registrar registration charges while remaining legally binding on stamp paper when properly executed and notarized.</p>

<h2>2. Must-Have Clauses to Protect Both Parties</h2>
<ul>
  <li><strong>Exact Rent & Due Date:</strong> State the exact monthly rental amount, grace period, and payment mode (UPI / NEFT).</li>
  <li><strong>Security Deposit Handling:</strong> Exact deposit amount, terms of deduction, and guaranteed refund timeline upon vacating.</li>
  <li><strong>Notice Period Terms:</strong> Clear 30-day written notice requirement from either party.</li>
  <li><strong>Permitted Use of Premises:</strong> Explicitly stating residential usage to prevent unauthorized subletting or commercial use.</li>
</ul>

<blockquote><strong>Legal Warning:</strong> Never pay a token advance without a signed written token receipt containing property details and agreed basic terms.</blockquote>

<h2>3. Inventory Annexure Checklist</h2>
<p>Always attach an Annexure listing all electrical appliances, fans, AC remotes, geysers, and furniture along with their working condition signed by both owner and tenant.</p>`
            },
            owner_tips: {
                title: 'Owner Rental Strategy: How to Find Verified Tenants & Maximize Rental Yield',
                category: 'Owner Insights',
                content: `<h2>1. Preparing Your Property to Rent Faster</h2>
<p>Vacant properties cost owners between ₹30,000 to ₹70,000 each month in lost rental income. A few small enhancements can help your home get rented within 7 days.</p>

<h2>2. 4 Quick Steps to Attract Premium Tenants</h2>
<ul>
  <li><strong>Fresh Paint & Deep Cleaning:</strong> A freshly painted home rents 3x faster and commands 8-12% higher rental interest.</li>
  <li><strong>Professional Photos with Good Lighting:</strong> High quality, uncluttered photos generate 5x more clicks and direct inquiries.</li>
  <li><strong>Zero Brokerage Direct Listing:</strong> High-intent corporate tenants prefer zero-brokerage listings where they deal directly with the landlord.</li>
</ul>

<h2>3. Tenant Police Verification & KYC</h2>
<p>Always complete tenant verification with the local police station (now conveniently available online via Haryana Police portal) along with PAN, Aadhaar, and corporate employment ID proof.</p>`
            }
        };

        const selected = templates[templateKey];
        if (!selected) return;

        if (!titleInput.value.trim()) {
            titleInput.value = selected.title;
        }
        contentInput.value = selected.content;

        const catSelect = document.getElementById('category-select');
        if (catSelect) {
            catSelect.value = selected.category;
        }

        window.autoGenerateSeoAndExcerpt();
        updateCounters();
        document.getElementById('quick-template-select').value = '';
        showFastNotification('⚡ Template loaded! Ready to review and publish.');
    };

    // 1-Click Instant Stock Cover Selection (0 Upload Required)
    window.selectInstantCover = function(url, label) {
        const preview = document.getElementById('cover-image-preview');
        const urlInput = document.getElementById('cover-image-url');
        const fileInput = document.getElementById('cover-image-file');
        const base64Input = document.getElementById('image-base64-input');
        const statusBox = document.getElementById('file-chosen-status');

        preview.src = url;
        urlInput.value = url;
        if (fileInput) fileInput.value = '';
        if (base64Input) base64Input.value = '';
        if (statusBox) statusBox.classList.add('hidden');

        showFastNotification(`🖼️ Cover image set: ${label}`);
    };

    // Direct 1-Click Save as Draft
    window.saveAsDraftNow = function() {
        const draftRadio = document.querySelector('input[name="is_published"][value="0"]');
        if (draftRadio) {
            draftRadio.checked = true;
        }
        document.getElementById('blog-form').requestSubmit();
    };

    // Fast Form Submission: Strips heavy binary files if base64 is already compressed!
    const blogForm = document.getElementById('blog-form');
    if (blogForm) {
        blogForm.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('btn-submit-post');
            const submitText = document.getElementById('btn-submit-text');

            if (submitBtn && submitText) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-85', 'cursor-not-allowed');
                submitText.textContent = 'Saving Article...';
                const icon = submitBtn.querySelector('i');
                if (icon) {
                    icon.className = 'ph-bold ph-spinner animate-spin text-base !text-white';
                }
            }

            // SPEED OPTIMIZATION:
            // If client-side compressed base64 is present, clear the raw multi-megabyte file input
            // so the browser does NOT send a 15MB+ multipart payload over the network!
            // This cuts payload size by 99% and accelerates form submission from 25s to <0.2s!
            const base64Input = document.getElementById('image-base64-input');
            const fileInput = document.getElementById('cover-image-file');
            if (base64Input && base64Input.value && fileInput) {
                fileInput.value = '';
            }

            const avatarBase64 = document.getElementById('author-avatar-base64-input');
            const avatarFile = document.querySelector('input[name="author_avatar"]');
            if (avatarBase64 && avatarBase64.value && avatarFile) {
                avatarFile.value = '';
            }
        });
    }

    // Ctrl+S / Cmd+S to Quick Save
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
            e.preventDefault();
            const form = document.getElementById('blog-form');
            if (form) {
                form.requestSubmit();
            }
        }
    });
</script>
@endpush
@endsection
