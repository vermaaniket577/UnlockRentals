@extends('layouts.admin')

@section('title', 'Push Notifications - Admin Hub')
@section('topbar_title', 'Push Notifications')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" id="push-notifications-page">

    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 flex-shrink-0">
                    <i class="ph-bold ph-bell-ringing text-xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Push Notifications</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-200/70 uppercase tracking-wider">Web &amp; Mobile</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Compose, preview, and broadcast instant push alerts to web browsers and mobile app users</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button" onclick="sendTestNotification()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all active:scale-95 cursor-pointer shadow-xs">
                <i class="ph-bold ph-broadcast text-blue-600 text-sm"></i>
                <span>Test on My Device</span>
            </button>
        </div>
    </div>

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="ph-bold ph-users-three"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Subscribers</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ number_format($subscribersCount) }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="ph-bold ph-globe"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Web Devices</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ number_format($webSubscribersCount) }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="ph-bold ph-device-mobile"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">App Devices</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ number_format($mobileSubscribersCount) }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="ph-bold ph-paper-plane-tilt"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Campaigns Sent</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ number_format($campaigns->total()) }}</p>
            </div>
        </div>
    </div>

    {{-- Main 2-Column Grid: Smooth Step Composer & Live Device Preview --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- Left: Structured Step-by-Step Composer (7 cols) --}}
        <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Compose Notification</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Craft rich push notifications with instant delivery to Web &amp; Mobile</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Instant Dispatch
                </span>
            </div>

            <form action="{{ route('admin.push-notifications.send') }}" method="POST" enctype="multipart/form-data" id="push-composer-form" class="space-y-6">
                @csrf

                {{-- STEP 1: Content & Copywriting --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-blue-600">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px]">1</span>
                        <span>Notification Content</span>
                    </div>

                    {{-- Title --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="push-title" class="text-xs font-bold text-slate-700">
                                Notification Title <span class="text-rose-500">*</span>
                            </label>
                            <span id="title-char-count" class="text-[11px] text-slate-400 font-mono font-semibold">0 / 60</span>
                        </div>
                        <input type="text"
                               name="title"
                               id="push-title"
                               required
                               maxlength="120"
                               placeholder="e.g. 🏠 New 2 BHK Flat Just Listed in Raipur!"
                               value="{{ old('title') }}"
                               oninput="updatePreview()"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-semibold">

                        {{-- Quick Presets --}}
                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-1">Quick Fill:</span>
                            <button type="button" onclick="applyTemplate('property')" class="text-[10px] font-semibold bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-600 px-2.5 py-1 rounded-lg transition-colors cursor-pointer">🏠 New Property</button>
                            <button type="button" onclick="applyTemplate('discount')" class="text-[10px] font-semibold bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-600 px-2.5 py-1 rounded-lg transition-colors cursor-pointer">⚡ 20% Off Pass</button>
                            <button type="button" onclick="applyTemplate('app')" class="text-[10px] font-semibold bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-600 px-2.5 py-1 rounded-lg transition-colors cursor-pointer">📲 Download App</button>
                            <button type="button" onclick="applyTemplate('brokerage')" class="text-[10px] font-semibold bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-600 px-2.5 py-1 rounded-lg transition-colors cursor-pointer">🔑 Zero Brokerage</button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="push-body" class="text-xs font-bold text-slate-700">
                                Message Body <span class="text-rose-500">*</span>
                            </label>
                            <span id="body-char-count" class="text-[11px] text-slate-400 font-mono font-semibold">0 / 150</span>
                        </div>
                        <textarea name="body"
                                  id="push-body"
                                  rows="3"
                                  required
                                  maxlength="500"
                                  placeholder="e.g. Fully furnished with modular kitchen, lift & 24x7 security. Direct owner contact with zero brokerage. Tap to explore!"
                                  oninput="updatePreview()"
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-medium resize-none leading-relaxed">{{ old('body') }}</textarea>
                    </div>

                    {{-- Action URL --}}
                    <div>
                        <label for="push-action-url" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Click Destination URL
                        </label>
                        <div class="relative">
                            <i class="ph-bold ph-link text-slate-400 absolute left-3.5 top-3.5 text-sm"></i>
                            <input type="text"
                                   name="action_url"
                                   id="push-action-url"
                                   value="{{ old('action_url', url('/properties')) }}"
                                   placeholder="https://unlockrentals.com/properties"
                                   class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-blue-600 font-medium">
                        </div>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="text-[10px] text-slate-400">Shortcuts:</span>
                            <button type="button" onclick="setActionUrl('{{ url('/properties') }}')" class="text-[10px] font-semibold text-blue-600 hover:underline">/properties</button>
                            <span class="text-slate-300">&bull;</span>
                            <button type="button" onclick="setActionUrl('{{ url('/plans') }}')" class="text-[10px] font-semibold text-blue-600 hover:underline">/plans</button>
                            <span class="text-slate-300">&bull;</span>
                            <button type="button" onclick="setActionUrl('{{ url('/app/download') }}')" class="text-[10px] font-semibold text-blue-600 hover:underline">/app/download</button>
                            <span class="text-slate-300">&bull;</span>
                            <button type="button" onclick="setActionUrl('{{ url('/dashboard') }}')" class="text-[10px] font-semibold text-blue-600 hover:underline">/dashboard</button>
                        </div>
                    </div>
                </div>

                {{-- STEP 2: Rich Media & Banner Image Upload --}}
                <div class="pt-5 border-t border-slate-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-blue-600">
                            <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px]">2</span>
                            <span>Banner Image (Optional)</span>
                        </div>

                        {{-- Mode Selector Tabs: Upload File vs Image URL --}}
                        <div class="flex items-center bg-slate-100 p-0.5 rounded-lg text-[11px] font-bold">
                            <button type="button" id="tab-upload-btn" onclick="setImageInputMode('upload')" class="px-2.5 py-1 rounded-md bg-white text-blue-700 shadow-xs transition-all">
                                <i class="ph-bold ph-upload-simple mr-1"></i> Upload File
                            </button>
                            <button type="button" id="tab-url-btn" onclick="setImageInputMode('url')" class="px-2.5 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all">
                                <i class="ph-bold ph-link-simple mr-1"></i> Image URL
                            </button>
                        </div>
                    </div>

                    {{-- Upload Option: Drag & Drop Dropzone --}}
                    <div id="image-upload-mode-container" class="space-y-3">
                        <input type="file"
                               name="image_file"
                               id="push-image-file"
                               accept="image/png,image/jpeg,image/jpg,image/webp,image/gif"
                               class="hidden"
                               onchange="handleImageFileSelect(this)">

                        <div id="dropzone-box"
                             onclick="document.getElementById('push-image-file').click()"
                             ondragover="handleDragOver(event)"
                             ondragleave="handleDragLeave(event)"
                             ondrop="handleDrop(event)"
                             class="border-2 border-dashed border-slate-200 hover:border-blue-500 bg-slate-50 hover:bg-blue-50/40 rounded-2xl p-5 text-center cursor-pointer transition-all duration-200 group">
                            
                            <div class="w-11 h-11 rounded-xl bg-blue-100/70 text-blue-600 group-hover:scale-110 flex items-center justify-center mx-auto mb-2 text-xl transition-transform">
                                <i class="ph-bold ph-cloud-arrow-up"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-800">
                                <span class="text-blue-600 hover:underline">Click to browse</span> or drag and drop banner image
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1">PNG, JPG, WebP up to 5MB (Recommended: 1200 &times; 600px)</p>
                        </div>

                        {{-- Active File Selected Card --}}
                        <div id="selected-file-card" class="hidden flex items-center justify-between p-3 bg-blue-50/70 border border-blue-200 rounded-xl">
                            <div class="flex items-center gap-3 min-w-0">
                                <img id="selected-file-thumb" src="" alt="Thumbnail" class="w-12 h-12 rounded-lg object-cover border border-blue-200 flex-shrink-0">
                                <div class="min-w-0">
                                    <p id="selected-file-name" class="text-xs font-bold text-slate-900 truncate">banner.jpg</p>
                                    <p id="selected-file-size" class="text-[10px] text-slate-500 font-medium">120 KB</p>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 mt-0.5">
                                        <i class="ph-bold ph-check-circle"></i> Ready to send
                                    </span>
                                </div>
                            </div>
                            <button type="button" onclick="removeImageFile()" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Remove image">
                                <i class="ph-bold ph-trash text-sm"></i>
                            </button>
                        </div>
                    </div>

                    {{-- URL Option Container --}}
                    <div id="image-url-mode-container" class="hidden">
                        <div class="relative">
                            <i class="ph-bold ph-image text-slate-400 absolute left-3.5 top-3 text-sm"></i>
                            <input type="url"
                                   name="image_url"
                                   id="push-image-url"
                                   value="{{ old('image_url') }}"
                                   placeholder="https://example.com/banner-image.jpg"
                                   oninput="updatePreview()"
                                   class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-blue-600">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Direct public URL of the banner image (HTTPS recommended).</p>
                    </div>
                </div>

                {{-- STEP 3: Platform Channel & Audience Targeting --}}
                <div class="pt-5 border-t border-slate-100 space-y-4">
                    <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-blue-600">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px]">3</span>
                        <span>Delivery Channels &amp; Audience</span>
                    </div>

                    {{-- 1. Delivery Platform / Channel Selector (Web, Mobile App, or Both) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">
                            Delivery Platform <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="channel" value="both" checked class="peer sr-only">
                                <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50 peer-checked:border-blue-600 peer-checked:bg-blue-50/60 peer-checked:text-blue-700 transition-all text-center">
                                    <div class="flex items-center justify-center gap-1.5 text-base mb-1">
                                        <i class="ph-bold ph-globe"></i>
                                        <span class="text-xs font-bold">+</span>
                                        <i class="ph-bold ph-device-mobile"></i>
                                    </div>
                                    <span class="text-xs font-extrabold block">Web &amp; Mobile</span>
                                    <span class="text-[10px] text-slate-500 block mt-0.5">All Devices (Recommended)</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="channel" value="web" class="peer sr-only">
                                <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50 peer-checked:border-blue-600 peer-checked:bg-blue-50/60 peer-checked:text-blue-700 transition-all text-center">
                                    <i class="ph-bold ph-globe text-base block mb-1"></i>
                                    <span class="text-xs font-extrabold block">Web Push Only</span>
                                    <span class="text-[10px] text-slate-500 block mt-0.5">Desktop &amp; Browsers</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="channel" value="app" class="peer sr-only">
                                <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50 peer-checked:border-blue-600 peer-checked:bg-blue-50/60 peer-checked:text-blue-700 transition-all text-center">
                                    <i class="ph-bold ph-device-mobile text-base block mb-1"></i>
                                    <span class="text-xs font-extrabold block">Mobile App Only</span>
                                    <span class="text-[10px] text-slate-500 block mt-0.5">Android APK &amp; iOS</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 2. Target Audience Selector --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">
                            Target Audience <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="target_type" value="all" checked onchange="toggleAudienceOptions()" class="peer sr-only">
                                <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50 peer-checked:border-blue-600 peer-checked:bg-blue-50/60 peer-checked:text-blue-700 transition-all text-center">
                                    <i class="ph-bold ph-broadcast text-base block mb-1"></i>
                                    <span class="text-xs font-extrabold block">All Subscribers</span>
                                    <span class="text-[10px] text-slate-500 block mt-0.5">Broadcast Alert</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="target_type" value="role" onchange="toggleAudienceOptions()" class="peer sr-only">
                                <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50 peer-checked:border-blue-600 peer-checked:bg-blue-50/60 peer-checked:text-blue-700 transition-all text-center">
                                    <i class="ph-bold ph-users-three text-base block mb-1"></i>
                                    <span class="text-xs font-extrabold block">By User Role</span>
                                    <span class="text-[10px] text-slate-500 block mt-0.5">Filter by Group</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="target_type" value="specific_user" onchange="toggleAudienceOptions()" class="peer sr-only">
                                <div class="p-3 rounded-2xl border border-slate-200 bg-slate-50/50 peer-checked:border-blue-600 peer-checked:bg-blue-50/60 peer-checked:text-blue-700 transition-all text-center">
                                    <i class="ph-bold ph-user text-base block mb-1"></i>
                                    <span class="text-xs font-extrabold block">Single User</span>
                                    <span class="text-[10px] text-slate-500 block mt-0.5">1-to-1 Direct</span>
                                </div>
                            </label>
                        </div>

                        {{-- Role Selection Dropdown --}}
                        <div id="role-select-box" class="mt-3 hidden bg-slate-50 p-3.5 rounded-2xl border border-slate-200">
                            <label for="target-role" class="block text-[11px] font-bold text-slate-600 mb-1.5">Select Role Group:</label>
                            <select name="target_value_role" id="target-role" onchange="syncTargetValue()" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-600">
                                <option value="tenant">Tenants &amp; Property Buyers</option>
                                <option value="owner">Property Owners &amp; Landlords</option>
                                <option value="admin">Administrators Only</option>
                            </select>
                        </div>

                        {{-- Single User Selection Dropdown --}}
                        <div id="user-select-box" class="mt-3 hidden bg-slate-50 p-3.5 rounded-2xl border border-slate-200">
                            <label for="target-user" class="block text-[11px] font-bold text-slate-600 mb-1.5">Select Recipient User:</label>
                            <select name="target_value_user" id="target-user" onchange="syncTargetValue()" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-600">
                                <option value="">-- Choose Registered User --</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->phone ?? $user->email }}) [{{ ucfirst($user->role ?? 'user') }}]
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="target_value" id="final-target-value" value="">
                    </div>
                </div>

                {{-- Action Dispatch Button --}}
                <div class="pt-3">
                    <button type="submit"
                            id="send-push-btn"
                            class="w-full py-4 px-6 rounded-2xl text-sm font-extrabold text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-700 hover:to-indigo-800 shadow-lg shadow-blue-600/30 active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2.5 cursor-pointer">
                        <i class="ph-bold ph-paper-plane-tilt text-lg"></i>
                        <span>Dispatch Push Notification Now</span>
                    </button>
                    <p class="text-[11px] text-center text-slate-400 mt-2 flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-shield-check text-emerald-500"></i>
                        <span>Immediate multi-platform delivery via Web Push &amp; Firebase Cloud Messaging</span>
                    </p>
                </div>
            </form>
        </div>

        {{-- Right: Live Interactive Device Preview & Delivery Diagnostics (5 cols) --}}
        <div class="lg:col-span-5 space-y-5 lg:sticky lg:top-20">

            {{-- Device Preview Card --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="ph-bold ph-eye text-blue-600 text-sm"></i>
                        <span>Live Device Simulator</span>
                    </h3>

                    {{-- Device Mode Selector --}}
                    <div class="flex items-center bg-slate-100 p-0.5 rounded-lg text-[10px] font-bold">
                        <button type="button" onclick="setPreviewDevice('android')" id="prev-android-btn" class="px-2 py-1 rounded-md bg-white text-slate-900 shadow-xs transition-all">Android</button>
                        <button type="button" onclick="setPreviewDevice('ios')" id="prev-ios-btn" class="px-2 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all">iOS</button>
                        <button type="button" onclick="setPreviewDevice('desktop')" id="prev-desktop-btn" class="px-2 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all">Desktop</button>
                    </div>
                </div>

                {{-- Phone / Mockup Container --}}
                <div id="device-mockup-frame" class="w-full bg-slate-900 p-4 rounded-3xl shadow-xl border border-slate-800 text-white transition-all duration-300">
                    
                    {{-- Status Bar (Clock & Icons) --}}
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mb-3 px-1">
                        <span id="preview-clock">12:30 PM</span>
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-wifi-high text-xs"></i>
                            <i class="ph-bold ph-battery-charging text-xs"></i>
                        </div>
                    </div>

                    {{-- Push Notification Floating Banner --}}
                    <div id="preview-banner-card" class="bg-slate-800/95 backdrop-blur-md rounded-2xl p-4 border border-slate-700/80 shadow-2xl space-y-2.5 transition-all">
                        
                        {{-- App Header Info --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-4 h-4 rounded-md object-cover">
                                <span class="text-[11px] font-bold text-slate-200">UnlockRentals</span>
                                <span class="text-[10px] text-slate-400">&bull; Just now</span>
                            </div>
                            <i class="ph-bold ph-bell-simple text-xs text-blue-400"></i>
                        </div>

                        {{-- Text Content --}}
                        <div>
                            <h4 id="preview-title" class="text-xs font-extrabold text-white line-clamp-1 leading-snug">
                                🏠 New 2 BHK Flat Just Listed in Raipur!
                            </h4>
                            <p id="preview-body" class="text-[11px] text-slate-300 line-clamp-3 leading-relaxed mt-1">
                                Fully furnished with modular kitchen, lift & 24x7 security. Direct owner contact with zero brokerage. Tap to explore!
                            </p>
                        </div>

                        {{-- Banner Image Preview (Auto-expanded when uploaded or URL provided) --}}
                        <div id="preview-image-container" class="hidden rounded-xl overflow-hidden mt-2 max-h-40 bg-slate-950 border border-slate-700/80 shadow-sm">
                            <img id="preview-image" src="" alt="Banner Preview" class="w-full h-auto object-cover">
                        </div>

                        {{-- Action Hint --}}
                        <div class="pt-1 flex items-center justify-between text-[10px] text-slate-400 border-t border-slate-700/40 mt-2">
                            <span class="flex items-center gap-1 text-blue-400">
                                <i class="ph-bold ph-arrow-square-out text-xs"></i> Tap to open
                            </span>
                            <span class="text-slate-500">Zero Brokerage</span>
                        </div>
                    </div>

                    <p class="text-[10px] text-center text-slate-500 mt-3.5 font-medium">Real-time simulation on device lockscreen</p>
                </div>
            </div>

            {{-- Clear, Readable Delivery Engines Card (Fixed contrast) --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-3.5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">
                            <i class="ph-bold ph-cpu"></i>
                        </div>
                        <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Delivery Engines</h4>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Connected
                    </span>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <i class="ph-bold ph-globe text-blue-600 text-base mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="font-bold text-slate-800">Web Push Service Worker</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">Delivers to Chrome, Edge, Firefox, and Safari on desktop and mobile browsers via standard VAPID.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <i class="ph-bold ph-device-mobile text-emerald-600 text-base mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="font-bold text-slate-800">Firebase Cloud Messaging (FCM)</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">Delivers to Android APK app installs and background notification topics instantaneously.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notification Campaigns History --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-extrabold text-slate-900">Campaign History</h3>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">{{ $campaigns->total() }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Previously dispatched push alerts, delivery channels, and recipient logs</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-5">Date &amp; Time</th>
                        <th class="py-3.5 px-5">Notification Details</th>
                        <th class="py-3.5 px-5">Channel</th>
                        <th class="py-3.5 px-5">Audience Target</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Dispatched By</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($campaigns as $camp)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="font-bold text-slate-800">{{ $camp->created_at ? \Carbon\Carbon::parse($camp->created_at)->format('M d, Y') : 'Recently' }}</span>
                            <span class="block text-[10px] text-slate-400 mt-0.5">{{ $camp->created_at ? \Carbon\Carbon::parse($camp->created_at)->format('h:i A') : '' }}</span>
                        </td>
                        <td class="py-4 px-5 max-w-sm">
                            <div class="flex items-start gap-3">
                                @if(!empty($camp->image_url))
                                <img src="{{ $camp->image_url }}" alt="Banner" class="w-10 h-10 rounded-lg object-cover border border-slate-200 flex-shrink-0 mt-0.5" onerror="this.style.display='none'">
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate" title="{{ $camp->title ?? '' }}">{{ $camp->title ?? 'Untitled' }}</p>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5" title="{{ $camp->body ?? '' }}">{{ $camp->body ?? '' }}</p>
                                    @if(!empty($camp->action_url))
                                    <a href="{{ $camp->action_url }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-blue-600 hover:underline mt-1 font-semibold">
                                        <i class="ph-bold ph-link text-[9px]"></i> View Destination
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-5 whitespace-nowrap">
                            @php
                                $channel = $camp->channel ?? 'both';
                            @endphp
                            @if($channel === 'both')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60">
                                    <i class="ph-bold ph-globe text-xs"></i> + <i class="ph-bold ph-device-mobile text-xs"></i> Web &amp; App
                                </span>
                            @elseif($channel === 'web')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                                    <i class="ph-bold ph-globe text-xs"></i> Web Only
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <i class="ph-bold ph-device-mobile text-xs"></i> Mobile App
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700">
                                <i class="ph-bold ph-users text-xs text-blue-600"></i> {{ $camp->audience_label ?? 'All Subscribers' }}
                            </span>
                        </td>
                        <td class="py-4 px-5 whitespace-nowrap">
                            @php
                                $campStatus = $camp->status ?? 'sent';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $campStatus === 'sent' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $campStatus === 'sent' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ ucfirst($campStatus) }}
                            </span>
                        </td>
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="font-semibold text-slate-700">{{ $camp->sender?->name ?? 'Administrator' }}</span>
                        </td>
                        <td class="py-4 px-5 text-right whitespace-nowrap">
                            <form action="{{ route('admin.push-notifications.destroy', ['pushNotification' => $camp->id ?? $camp]) }}" method="POST" onsubmit="return confirm('Delete this notification log?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Delete Log">
                                    <i class="ph-bold ph-trash text-sm"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-xl">
                                <i class="ph-bold ph-bell-slash"></i>
                            </div>
                            <p class="font-bold text-slate-700 text-sm">No notification campaigns sent yet</p>
                            <p class="text-xs text-slate-400 mt-1">Compose your first alert using the form above.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($campaigns->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $campaigns->links() }}
        </div>
        @endif
    </div>

</div>

<script>
    // Live Clock for Simulator
    function updateClock() {
        const now = new Date();
        const clock = document.getElementById('preview-clock');
        if (clock) {
            clock.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Image Input Mode Switcher (Upload vs URL)
    let currentImageMode = 'upload';

    function setImageInputMode(mode) {
        currentImageMode = mode;
        const uploadBtn = document.getElementById('tab-upload-btn');
        const urlBtn = document.getElementById('tab-url-btn');
        const uploadContainer = document.getElementById('image-upload-mode-container');
        const urlContainer = document.getElementById('image-url-mode-container');

        if (mode === 'upload') {
            uploadBtn.className = 'px-2.5 py-1 rounded-md bg-white text-blue-700 shadow-xs transition-all font-bold';
            urlBtn.className = 'px-2.5 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all font-bold';
            uploadContainer.classList.remove('hidden');
            urlContainer.classList.add('hidden');
        } else {
            urlBtn.className = 'px-2.5 py-1 rounded-md bg-white text-blue-700 shadow-xs transition-all font-bold';
            uploadBtn.className = 'px-2.5 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all font-bold';
            urlContainer.classList.remove('hidden');
            uploadContainer.classList.add('hidden');
        }
        updatePreview();
    }

    // Direct File Upload Handling & Drag-and-Drop
    function handleImageFileSelect(input) {
        if (input.files && input.files[0]) {
            processFile(input.files[0]);
        }
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropzone-box')?.classList.add('border-blue-600', 'bg-blue-50/60');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropzone-box')?.classList.remove('border-blue-600', 'bg-blue-50/60');
    }

    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('dropzone-box')?.classList.remove('border-blue-600', 'bg-blue-50/60');

        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            const file = e.dataTransfer.files[0];
            const fileInput = document.getElementById('push-image-file');
            if (fileInput) {
                fileInput.files = e.dataTransfer.files;
            }
            processFile(file);
        }
    }

    let localUploadedImageSrc = '';

    function processFile(file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file (PNG, JPG, WebP, GIF).');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Image size exceeds 5MB limit. Please choose a smaller image.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            localUploadedImageSrc = e.target.result;

            const dropzone = document.getElementById('dropzone-box');
            const fileCard = document.getElementById('selected-file-card');
            const thumb = document.getElementById('selected-file-thumb');
            const nameEl = document.getElementById('selected-file-name');
            const sizeEl = document.getElementById('selected-file-size');

            if (dropzone) dropzone.classList.add('hidden');
            if (fileCard) fileCard.classList.remove('hidden');
            if (thumb) thumb.src = localUploadedImageSrc;
            if (nameEl) nameEl.textContent = file.name;
            if (sizeEl) sizeEl.textContent = formatBytes(file.size);

            updatePreview();
        };
        reader.readAsDataURL(file);
    }

    function removeImageFile() {
        const fileInput = document.getElementById('push-image-file');
        if (fileInput) fileInput.value = '';

        localUploadedImageSrc = '';

        const dropzone = document.getElementById('dropzone-box');
        const fileCard = document.getElementById('selected-file-card');
        if (dropzone) dropzone.classList.remove('hidden');
        if (fileCard) fileCard.classList.add('hidden');

        updatePreview();
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    // Live Device Preview Synchronization
    function updatePreview() {
        const titleInput = document.getElementById('push-title');
        const bodyInput = document.getElementById('push-body');
        const urlInput = document.getElementById('push-image-url');

        const previewTitle = document.getElementById('preview-title');
        const previewBody = document.getElementById('preview-body');
        const previewImage = document.getElementById('preview-image');
        const previewImageContainer = document.getElementById('preview-image-container');

        const titleCount = document.getElementById('title-char-count');
        const bodyCount = document.getElementById('body-char-count');

        if (titleInput && previewTitle) {
            previewTitle.textContent = titleInput.value.trim() || '🏠 New 2 BHK Flat Just Listed in Raipur!';
            if (titleCount) titleCount.textContent = `${titleInput.value.length} / 60`;
        }

        if (bodyInput && previewBody) {
            previewBody.textContent = bodyInput.value.trim() || 'Fully furnished with modular kitchen, lift & 24x7 security. Direct owner contact with zero brokerage. Tap to explore!';
            if (bodyCount) bodyCount.textContent = `${bodyInput.value.length} / 150`;
        }

        // Active Banner Image selection (Uploaded File vs URL)
        let imageSrc = '';
        if (currentImageMode === 'upload' && localUploadedImageSrc) {
            imageSrc = localUploadedImageSrc;
        } else if (currentImageMode === 'url' && urlInput && urlInput.value.trim()) {
            imageSrc = urlInput.value.trim();
        }

        if (imageSrc && previewImage && previewImageContainer) {
            previewImage.src = imageSrc;
            previewImageContainer.classList.remove('hidden');
        } else if (previewImageContainer) {
            previewImageContainer.classList.add('hidden');
            if (previewImage) previewImage.src = '';
        }
    }

    // Device Mockup Switcher (Android, iOS, Desktop)
    function setPreviewDevice(device) {
        const frame = document.getElementById('device-mockup-frame');
        const card = document.getElementById('preview-banner-card');
        const btnAndroid = document.getElementById('prev-android-btn');
        const btnIos = document.getElementById('prev-ios-btn');
        const btnDesktop = document.getElementById('prev-desktop-btn');

        btnAndroid.className = 'px-2 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all font-bold';
        btnIos.className = 'px-2 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all font-bold';
        btnDesktop.className = 'px-2 py-1 rounded-md text-slate-600 hover:text-slate-900 transition-all font-bold';

        if (device === 'android') {
            btnAndroid.className = 'px-2 py-1 rounded-md bg-white text-slate-900 shadow-xs transition-all font-bold';
            if (frame) frame.style.borderRadius = '1.75rem';
            if (card) card.style.borderRadius = '1rem';
        } else if (device === 'ios') {
            btnIos.className = 'px-2 py-1 rounded-md bg-white text-slate-900 shadow-xs transition-all font-bold';
            if (frame) frame.style.borderRadius = '2.5rem';
            if (card) card.style.borderRadius = '1.25rem';
        } else if (device === 'desktop') {
            btnDesktop.className = 'px-2 py-1 rounded-md bg-white text-slate-900 shadow-xs transition-all font-bold';
            if (frame) frame.style.borderRadius = '0.75rem';
            if (card) card.style.borderRadius = '0.5rem';
        }
    }

    // Quick Templates
    const templates = {
        property: {
            title: '🏠 Fresh 2 & 3 BHK Flats Just Listed!',
            body: 'Direct owner contact with zero brokerage. Verified properties in prime locations ready for immediate possession.',
            url: '{{ url("/properties") }}'
        },
        discount: {
            title: '⚡ Limited Time: 20% Off Buyer & Rent Pass',
            body: 'Unlock unlimited verified owner direct contacts, WhatsApp connect, and priority scheduling at discounted prices today!',
            url: '{{ url("/plans") }}'
        },
        app: {
            title: '📲 UnlockRentals Mobile App is Here!',
            body: 'Find rental flats faster with instant GPS nearby search, instant notifications, and 1-tap owner chat. Download now!',
            url: '{{ url("/app/download") }}'
        },
        brokerage: {
            title: '🔑 Say Goodbye to Brokerage Fees!',
            body: 'Rent your dream flat directly from verified owners. No middlemen, no commission, 100% transparent agreements.',
            url: '{{ url("/properties") }}'
        }
    };

    function applyTemplate(key) {
        const t = templates[key];
        if (!t) return;

        const titleInput = document.getElementById('push-title');
        const bodyInput = document.getElementById('push-body');
        const urlInput = document.getElementById('push-action-url');

        if (titleInput) titleInput.value = t.title;
        if (bodyInput) bodyInput.value = t.body;
        if (urlInput) urlInput.value = t.url;

        updatePreview();
    }

    function setActionUrl(url) {
        const input = document.getElementById('push-action-url');
        if (input) input.value = url;
    }

    // Audience selection toggle
    function toggleAudienceOptions() {
        const targetType = document.querySelector('input[name="target_type"]:checked')?.value || 'all';
        const roleBox = document.getElementById('role-select-box');
        const userBox = document.getElementById('user-select-box');

        if (roleBox) roleBox.classList.toggle('hidden', targetType !== 'role');
        if (userBox) userBox.classList.toggle('hidden', targetType !== 'specific_user');

        syncTargetValue();
    }

    function syncTargetValue() {
        const targetType = document.querySelector('input[name="target_type"]:checked')?.value || 'all';
        const finalInput = document.getElementById('final-target-value');
        if (!finalInput) return;

        if (targetType === 'role') {
            finalInput.value = document.getElementById('target-role')?.value || 'tenant';
        } else if (targetType === 'specific_user') {
            finalInput.value = document.getElementById('target-user')?.value || '';
        } else {
            finalInput.value = '';
        }
    }

    // Submit state feedback
    document.getElementById('push-composer-form')?.addEventListener('submit', function() {
        const btn = document.getElementById('send-push-btn');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            btn.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin text-lg"></i><span>Delivering Notification Across Channels...</span>';
        }
    });

    // Test on Current Browser Device
    async function sendTestNotification() {
        const title = document.getElementById('push-title')?.value.trim() || '🔔 UnlockRentals Test Notification';
        const body = document.getElementById('push-body')?.value.trim() || 'This is how your custom push notification looks and sounds on subscriber devices!';
        const icon = '{{ asset("favicon.png") }}';
        const url = document.getElementById('push-action-url')?.value.trim() || window.location.href;

        let image = null;
        if (currentImageMode === 'upload' && localUploadedImageSrc) {
            image = localUploadedImageSrc;
        } else if (currentImageMode === 'url') {
            image = document.getElementById('push-image-url')?.value.trim() || null;
        }

        if (!("Notification" in window)) {
            alert("This browser does not support desktop notifications.");
            return;
        }

        if (Notification.permission === "granted") {
            triggerBrowserNotification(title, body, icon, url, image);
        } else if (Notification.permission !== "denied") {
            const permission = await Notification.requestPermission();
            if (permission === "granted") {
                triggerBrowserNotification(title, body, icon, url, image);
            }
        } else {
            alert("Notification permission is blocked in your browser settings. Please enable notifications for this site to test.");
        }
    }

    function triggerBrowserNotification(title, body, icon, url, image) {
        const options = {
            body: body,
            icon: icon,
            image: image,
            badge: icon,
            data: { url: url }
        };

        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            navigator.serviceWorker.controller.postMessage({
                type: 'SHOW_CUSTOM_NOTIFICATION',
                payload: { title, body, icon, url, image }
            });
        } else {
            new Notification(title, options);
        }
    }

    // Initialize on load
    updatePreview();
    toggleAudienceOptions();
</script>
@endsection
