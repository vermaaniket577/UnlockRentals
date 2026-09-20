@extends('layouts.admin')

@section('title', 'Push Notifications - Admin Hub')
@section('topbar_title', 'Push Notifications')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" id="push-notifications-page">

    {{-- Header Banner --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20">
                    <i class="ph-bold ph-bell-ringing text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Push Notifications</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Compose, preview, and broadcast instant push alerts to web and mobile users</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="sendTestNotification()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all active:scale-95 cursor-pointer">
                <i class="ph-bold ph-broadcast text-blue-600"></i>
                <span>Test on My Device</span>
            </button>
        </div>
    </div>

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="ph-bold ph-device-mobile"></i>
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
                <i class="ph-bold ph-android-logo"></i>
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

    {{-- Main 2-Column Grid: Composer & Live Preview --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left: Compose Form (7 cols) --}}
        <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between pb-5 border-b border-slate-100 mb-6">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Compose Notification</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Craft your custom push alert with instant delivery</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60">Instant Dispatch</span>
            </div>

            <form action="{{ route('admin.push-notifications.send') }}" method="POST" id="push-composer-form" class="space-y-5">
                @csrf

                {{-- Notification Title --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="push-title" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Notification Title <span class="text-rose-500">*</span>
                        </label>
                        <span id="title-char-count" class="text-[11px] text-slate-400 font-mono">0 / 60</span>
                    </div>
                    <div class="relative">
                        <input type="text"
                               name="title"
                               id="push-title"
                               required
                               maxlength="120"
                               placeholder="e.g. 🏠 New 2 BHK Flat Just Listed in Raipur!"
                               value="{{ old('title') }}"
                               oninput="updatePreview()"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-semibold">
                    </div>
                </div>

                {{-- Notification Body --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="push-body" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Message Body <span class="text-rose-500">*</span>
                        </label>
                        <span id="body-char-count" class="text-[11px] text-slate-400 font-mono">0 / 150</span>
                    </div>
                    <textarea name="body"
                              id="push-body"
                              rows="3"
                              required
                              maxlength="500"
                              placeholder="e.g. Fully furnished with modular kitchen, lift & 24x7 security. Direct owner contact with zero brokerage. Tap to explore!"
                              oninput="updatePreview()"
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-medium resize-none">{{ old('body') }}</textarea>
                </div>

                {{-- Target Audience Selector --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Target Audience <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2.5">
                        <label class="cursor-pointer">
                            <input type="radio" name="target_type" value="all" checked onchange="toggleAudienceOptions()" class="peer sr-only">
                            <div class="p-3 rounded-xl border border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50/50 peer-checked:text-blue-700 transition-all text-center">
                                <i class="ph-bold ph-broadcast text-lg block mb-1"></i>
                                <span class="text-xs font-bold block">All Users</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="target_type" value="role" onchange="toggleAudienceOptions()" class="peer sr-only">
                            <div class="p-3 rounded-xl border border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50/50 peer-checked:text-blue-700 transition-all text-center">
                                <i class="ph-bold ph-users-three text-lg block mb-1"></i>
                                <span class="text-xs font-bold block">By User Role</span>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="target_type" value="specific_user" onchange="toggleAudienceOptions()" class="peer sr-only">
                            <div class="p-3 rounded-xl border border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50/50 peer-checked:text-blue-700 transition-all text-center">
                                <i class="ph-bold ph-user text-lg block mb-1"></i>
                                <span class="text-xs font-bold block">Single User</span>
                            </div>
                        </label>
                    </div>

                    {{-- Role Selection Sub-field --}}
                    <div id="role-select-box" class="mt-3 hidden">
                        <select name="target_value_role" id="target-role" onchange="syncTargetValue()" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-600">
                            <option value="tenant">All Tenants / Buyers</option>
                            <option value="owner">All Property Owners / Landlords</option>
                            <option value="admin">Administrators Only</option>
                        </select>
                    </div>

                    {{-- Single User Selection Sub-field --}}
                    <div id="user-select-box" class="mt-3 hidden">
                        <select name="target_value_user" id="target-user" onchange="syncTargetValue()" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-600">
                            <option value="">-- Choose User --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->phone ?? $user->email }}) [{{ ucfirst($user->role) }}]
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="target_value" id="final-target-value" value="">
                </div>

                {{-- Action URL & Banner Image Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="push-action-url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Click Action URL
                        </label>
                        <input type="text"
                               name="action_url"
                               id="push-action-url"
                               value="{{ old('action_url', url('/properties')) }}"
                               placeholder="https://unlockrentals.com/properties"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600">
                        <p class="text-[10px] text-slate-400 mt-1">Target link opened when user taps notification.</p>
                    </div>

                    <div>
                        <label for="push-image-url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Banner Image URL <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <input type="url"
                               name="image_url"
                               id="push-image-url"
                               value="{{ old('image_url') }}"
                               placeholder="https://.../banner.jpg"
                               oninput="updatePreview()"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600">
                        <p class="text-[10px] text-slate-400 mt-1">Large banner preview on supported devices.</p>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-3">
                    <button type="submit"
                            id="send-push-btn"
                            class="w-full py-3.5 px-6 rounded-xl text-sm font-extrabold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-600/25 active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="ph-bold ph-paper-plane-tilt text-base"></i>
                        <span>Dispatch Push Notification Now</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Right: Real-time Device Preview (5 cols) --}}
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="ph-bold ph-eye text-blue-600"></i> Real-time Device Preview
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400">Lockscreen / Banner</span>
                </div>

                {{-- Phone Mockup Frame --}}
                <div class="w-full bg-slate-900 p-4 rounded-2xl shadow-inner border border-slate-800 text-white">
                    <div class="flex items-center justify-between text-[11px] text-slate-400 mb-3 px-1">
                        <span id="preview-clock">12:30 PM</span>
                        <div class="flex items-center gap-1.5">
                            <i class="ph-bold ph-wifi-high text-xs"></i>
                            <i class="ph-bold ph-battery-full text-xs"></i>
                        </div>
                    </div>

                    {{-- Push Card Box inside Mockup --}}
                    <div class="bg-slate-800/90 backdrop-blur-md rounded-2xl p-3.5 border border-slate-700/60 shadow-xl space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('favicon.png') }}" alt="Logo" class="w-4 h-4 rounded-full object-cover">
                                <span class="text-[11px] font-bold text-slate-200">UnlockRentals</span>
                                <span class="text-[10px] text-slate-400">&bull; Just now</span>
                            </div>
                            <i class="ph-bold ph-bell-simple text-xs text-blue-400"></i>
                        </div>

                        <div>
                            <h4 id="preview-title" class="text-xs font-extrabold text-white line-clamp-1 leading-snug">
                                🏠 New 2 BHK Flat Just Listed in Raipur!
                            </h4>
                            <p id="preview-body" class="text-[11px] text-slate-300 line-clamp-3 leading-relaxed mt-0.5">
                                Fully furnished with modular kitchen, lift & 24x7 security. Direct owner contact with zero brokerage. Tap to explore!
                            </p>
                        </div>

                        {{-- Optional Banner Image Preview --}}
                        <div id="preview-image-container" class="hidden rounded-xl overflow-hidden mt-2 max-h-36 bg-slate-950 border border-slate-700">
                            <img id="preview-image" src="" alt="Banner Preview" class="w-full h-auto object-cover">
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-[10px] text-slate-500">Preview simulated as native notification banner</p>
                    </div>
                </div>
            </div>

            {{-- FCM & Web Push Health Card --}}
            <div class="bg-gradient-to-br from-slate-900 to-slate-950 p-5 rounded-3xl border border-slate-800 text-white shadow-xs">
                <div class="flex items-center gap-2.5 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm font-black">
                        <i class="ph-bold ph-check-circle"></i>
                    </div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Delivery Engines</h4>
                </div>
                <p class="text-[11px] text-slate-400 leading-relaxed">
                    Broadcasts automatically deliver across <strong>Web Push (Service Worker)</strong> and <strong>Firebase Cloud Messaging (FCM)</strong> topics. All active browsers and mobile app installations are updated instantaneously.
                </p>
            </div>
        </div>
    </div>

    {{-- Notification Campaigns History --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Campaign History</h3>
                <p class="text-xs text-slate-500 mt-0.5">Previously dispatched push alerts and delivery logs</p>
            </div>
            <span class="text-xs font-bold text-slate-400">{{ $campaigns->total() }} Total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-5">Date & Time</th>
                        <th class="py-3.5 px-5">Notification Details</th>
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
                            <span class="font-bold text-slate-800">{{ $camp->created_at->format('M d, Y') }}</span>
                            <span class="block text-[10px] text-slate-400 mt-0.5">{{ $camp->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="py-4 px-5 max-w-sm">
                            <p class="font-bold text-slate-900 truncate" title="{{ $camp->title }}">{{ $camp->title }}</p>
                            <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5" title="{{ $camp->body }}">{{ $camp->body }}</p>
                            @if($camp->action_url)
                            <a href="{{ $camp->action_url }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] text-blue-600 hover:underline mt-1 font-semibold">
                                <i class="ph-bold ph-link text-[9px]"></i> View Destination Link
                            </a>
                            @endif
                        </td>
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700">
                                <i class="ph-bold ph-users text-xs text-blue-600"></i> {{ $camp->audience_label }}
                            </span>
                        </td>
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $camp->status === 'sent' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $camp->status === 'sent' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ ucfirst($camp->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="font-semibold text-slate-700">{{ $camp->sender?->name ?? 'Administrator' }}</span>
                        </td>
                        <td class="py-4 px-5 text-right whitespace-nowrap">
                            <form action="{{ route('admin.push-notifications.destroy', $camp) }}" method="POST" onsubmit="return confirm('Delete this notification log?');" class="inline-block">
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
                        <td colspan="6" class="text-center py-12 text-slate-400">
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
    function updateClock() {
        const now = new Date();
        const clock = document.getElementById('preview-clock');
        if (clock) {
            clock.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    function updatePreview() {
        const titleInput = document.getElementById('push-title');
        const bodyInput = document.getElementById('push-body');
        const imageInput = document.getElementById('push-image-url');

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

        if (imageInput && previewImage && previewImageContainer) {
            const url = imageInput.value.trim();
            if (url) {
                previewImage.src = url;
                previewImageContainer.classList.remove('hidden');
            } else {
                previewImageContainer.classList.add('hidden');
                previewImage.src = '';
            }
        }
    }

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

    // Instant browser notification test
    async function sendTestNotification() {
        const title = document.getElementById('push-title')?.value.trim() || '🔔 UnlockRentals Test Notification';
        const body = document.getElementById('push-body')?.value.trim() || 'This is how your custom push notification looks and sounds on subscriber devices!';
        const icon = '{{ asset("favicon.png") }}';
        const url = document.getElementById('push-action-url')?.value.trim() || window.location.href;

        if (!("Notification" in window)) {
            alert("This browser does not support desktop notifications.");
            return;
        }

        if (Notification.permission === "granted") {
            triggerBrowserNotification(title, body, icon, url);
        } else if (Notification.permission !== "denied") {
            const permission = await Notification.requestPermission();
            if (permission === "granted") {
                triggerBrowserNotification(title, body, icon, url);
            }
        } else {
            alert("Notification permission is blocked in your browser settings. Please enable notifications for this site to test.");
        }
    }

    function triggerBrowserNotification(title, body, icon, url) {
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            navigator.serviceWorker.controller.postMessage({
                type: 'SHOW_CUSTOM_NOTIFICATION',
                payload: { title, body, icon, url }
            });
        } else {
            new Notification(title, { body, icon });
        }
    }
</script>
@endsection
