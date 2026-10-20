{{-- ========================================================================= --}}
{{-- PROPER POSTING ADVERTISING FORM MODAL (AUTO-CREATE OWNER ACCOUNT & POST)   --}}
{{-- ========================================================================= --}}
<div id="postAdvertiseModal" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-200 opacity-0" role="dialog" aria-modal="true">
    <div id="postAdvertiseModalCard" class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl w-full max-w-2xl sm:max-w-3xl p-5 sm:p-7 transform scale-95 transition-all duration-200 relative max-h-[92vh] overflow-y-auto">
        
        {{-- Close Button --}}
        <button type="button" onclick="closePostAdvertiseModal()" class="absolute top-4 right-4 sm:top-5 sm:right-5 text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition-colors cursor-pointer" aria-label="Close modal">
            <i class="ph-bold ph-x text-lg"></i>
        </button>

        {{-- Modal Header --}}
        <div class="flex items-center gap-3.5 mb-5 pb-4 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-orange-500 via-amber-500 to-yellow-400 text-white flex items-center justify-center text-2xl shadow-md shadow-orange-500/25 ring-4 ring-orange-50 shrink-0">
                <i class="ph-bold ph-megaphone"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Post Property Advertisement</h3>
                <p class="text-xs text-slate-500 mt-0.5">Publish an advertisement on behalf of a client. Their active Owner profile will be created automatically!</p>
            </div>
        </div>

        {{-- The Form --}}
        <form method="POST" action="{{ route('admin.properties.post-advertise') }}" enctype="multipart/form-data" id="properPostAdvertiseForm" onsubmit="handleProperAdSubmit(event)" class="space-y-5">
            @csrf
            <input type="hidden" name="behalf_mode" id="modal_ad_behalf_mode" value="new">

            {{-- ============================================================= --}}
            {{-- SECTION 1: CLIENT / OWNER ACCOUNT                             --}}
            {{-- ============================================================= --}}
            <div class="bg-gradient-to-br from-amber-50/60 via-orange-50/40 to-slate-50 p-4 rounded-2xl border border-amber-200/70">
                <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="ph-bold ph-user-circle text-orange-600 text-base"></i>
                        1. Client / Owner Account
                    </span>

                    {{-- Segmented Mode Toggle: New Client vs Existing Registered User --}}
                    <div class="inline-flex p-0.5 bg-white/90 rounded-xl border border-slate-200 shadow-2xs">
                        <button type="button" id="tab-ad-new" onclick="switchAdModalTab('new')"
                                class="px-3 py-1 text-xs font-bold rounded-lg transition-all text-slate-900 bg-orange-500 text-white shadow-2xs cursor-pointer">
                            ✨ New Client (Auto-Create)
                        </button>
                        <button type="button" id="tab-ad-existing" onclick="switchAdModalTab('existing')"
                                class="px-3 py-1 text-xs font-semibold rounded-lg transition-all text-slate-500 hover:text-slate-800 cursor-pointer">
                            👤 Existing User
                        </button>
                    </div>
                </div>

                {{-- New Client Fields --}}
                <div id="ad-section-new" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="ad_client_name" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Client / Owner Name <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="ph-bold ph-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                                <input type="text" name="behalf_name" id="ad_client_name" required placeholder="e.g. Ramesh Sharma"
                                       class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="ad_client_phone" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Mobile Phone Number <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="ph-bold ph-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                                <input type="tel" name="behalf_phone" id="ad_client_phone" required placeholder="e.g. 9876543210"
                                       class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="ad_client_email" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Email Address <span class="text-slate-400 font-normal lowercase">(Optional - auto-generated if left blank)</span>
                        </label>
                        <div class="relative">
                            <i class="ph-bold ph-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                            <input type="email" name="behalf_email" id="ad_client_email" placeholder="e.g. ramesh@example.com (Optional)"
                                   class="w-full pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all">
                        </div>
                    </div>

                    <div class="p-2.5 bg-white/80 border border-amber-200/80 rounded-xl text-[11px] text-amber-900 flex items-center gap-2">
                        <i class="ph-bold ph-sparkle text-amber-600 text-sm shrink-0"></i>
                        <span>An active <strong>Owner account</strong> will be created in the User Directory with phone & email automatically verified!</span>
                    </div>
                </div>

                {{-- Existing User Fields --}}
                <div id="ad-section-existing" class="space-y-3 hidden">
                    <div>
                        <label for="ad_existing_user" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Select Existing User Account <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="behalf_user_id" id="ad_existing_user"
                                    class="w-full pl-3 pr-8 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all cursor-pointer">
                                <option value="">-- Choose User from Directory --</option>
                                @php
                                    $dropdownUsers = \App\Models\User::orderBy('name')->select('id', 'name', 'phone', 'email', 'role')->get();
                                @endphp
                                @foreach($dropdownUsers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone ?: $u->email }}) — {{ ucfirst($u->role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">If the selected user is currently a tenant, their account will be upgraded to Owner upon posting.</p>
                    </div>
                </div>
            </div>

            {{-- ============================================================= --}}
            {{-- SECTION 2: PROPERTY ADVERTISEMENT DETAILS                     --}}
            {{-- ============================================================= --}}
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3.5">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="ph-bold ph-buildings text-indigo-600 text-base"></i>
                    2. Advertisement & Pricing
                </span>

                {{-- Title --}}
                <div>
                    <label for="ad_property_title" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Advertisement Title / Heading <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="ad_property_title" required
                           placeholder="e.g. Spacious 3 BHK Luxury Flat in Indiranagar, Bangalore"
                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 transition-all">
                </div>

                {{-- Purpose, Type, Price, Period --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <label for="ad_purpose" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Purpose <span class="text-rose-500">*</span>
                        </label>
                        <select name="purpose" id="ad_purpose" required class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                            <option value="rent" selected>For Rent</option>
                            <option value="buy">For Sale (Buy)</option>
                            <option value="sell">Sell</option>
                        </select>
                    </div>

                    <div>
                        <label for="ad_type" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Type <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" id="ad_type" required class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                            <option value="house" selected>Flat / Apartment</option>
                            <option value="house">House / Villa</option>
                            <option value="shop">Commercial Shop / Office</option>
                            <option value="pg-hostel">PG / Hostel</option>
                            <option value="plot">Plot / Land</option>
                        </select>
                    </div>

                    <div>
                        <label for="ad_price" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Price (₹) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="price" id="ad_price" required min="1" step="100" placeholder="e.g. 35000"
                               class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                    </div>

                    <div>
                        <label for="ad_price_period" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Price Period
                        </label>
                        <select name="price_period" id="ad_price_period" class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                            <option value="month" selected>Per Month</option>
                            <option value="year">Per Year / Total</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ============================================================= --}}
            {{-- SECTION 3: LOCATION & SPECIFICATIONS                          --}}
            {{-- ============================================================= --}}
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3.5">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="ph-bold ph-map-pin text-rose-600 text-base"></i>
                    3. Location & Specifications
                </span>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label for="ad_location" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            City <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="location" id="ad_location" list="ad_cities_list" required placeholder="e.g. Bangalore"
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                        <datalist id="ad_cities_list">
                            <option value="Bangalore">
                            <option value="Gurgaon">
                            <option value="Noida">
                            <option value="Delhi">
                            <option value="Mumbai">
                            <option value="Pune">
                            <option value="Hyderabad">
                        </datalist>
                    </div>

                    <div>
                        <label for="ad_locality" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Locality / Sector <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="locality" id="ad_locality" required placeholder="e.g. Indiranagar"
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                    </div>

                    <div>
                        <label for="ad_address" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Full Address (Optional)
                        </label>
                        <input type="text" name="address" id="ad_address" placeholder="e.g. 100 Feet Road, Indiranagar"
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                    </div>
                </div>

                {{-- BHK, Bathrooms, Sqft, Furnishing --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                    <div>
                        <label for="ad_bedrooms" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Bedrooms (BHK)
                        </label>
                        <select name="bedrooms" id="ad_bedrooms" class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                            <option value="1">1 BHK</option>
                            <option value="2" selected>2 BHK</option>
                            <option value="3">3 BHK</option>
                            <option value="4">4 BHK</option>
                            <option value="5">5+ BHK</option>
                        </select>
                    </div>

                    <div>
                        <label for="ad_bathrooms" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Bathrooms
                        </label>
                        <select name="bathrooms" id="ad_bathrooms" class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                            <option value="1">1 Bathroom</option>
                            <option value="2" selected>2 Bathrooms</option>
                            <option value="3">3 Bathrooms</option>
                            <option value="4">4 Bathrooms</option>
                        </select>
                    </div>

                    <div>
                        <label for="ad_area_sqft" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Area (Sq. Ft.)
                        </label>
                        <input type="number" name="area_sqft" id="ad_area_sqft" min="10" placeholder="e.g. 1200"
                               class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                    </div>

                    <div>
                        <label for="ad_furnishing" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Furnishing
                        </label>
                        <select name="furnishing" id="ad_furnishing" class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600">
                            <option value="semi-furnished" selected>Semi-Furnished</option>
                            <option value="fully-furnished">Fully Furnished</option>
                            <option value="unfurnished">Unfurnished</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ============================================================= --}}
            {{-- SECTION 4: PHOTOS & DESCRIPTION                               --}}
            {{-- ============================================================= --}}
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3.5">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="ph-bold ph-camera text-emerald-600 text-base"></i>
                    4. Photos & Description
                </span>

                {{-- Images Upload --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Upload Property Photos <span class="text-slate-400 font-normal lowercase">(Multiple allowed)</span>
                    </label>
                    <div class="border-2 border-dashed border-slate-300 hover:border-orange-500 rounded-2xl p-4 text-center bg-white cursor-pointer transition-all" onclick="document.getElementById('ad_images_input').click()">
                        <i class="ph-bold ph-cloud-arrow-up text-2xl text-orange-500 mb-1"></i>
                        <p class="text-xs font-bold text-slate-700">Click to browse or drop photos here</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">JPEG, PNG, WebP up to 12MB each. First photo will be the main cover!</p>
                        <input type="file" name="images[]" id="ad_images_input" multiple accept="image/*" class="sr-only" onchange="handleAdImagesPreview(this)">
                    </div>

                    {{-- Live Image Previews Container --}}
                    <div id="ad_images_preview_grid" class="grid grid-cols-4 sm:grid-cols-6 gap-2 mt-2.5 hidden"></div>
                </div>

                {{-- Description & Auto-Generate Button --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="ad_description" class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                            Description
                        </label>
                        <button type="button" onclick="autoGenerateAdDescription()" class="inline-flex items-center gap-1 text-[11px] font-bold text-orange-600 hover:text-orange-700 cursor-pointer">
                            <i class="ph-bold ph-magic-wand text-xs"></i>
                            <span>✨ Auto-Generate Description</span>
                        </button>
                    </div>
                    <textarea name="description" id="ad_description" rows="3" placeholder="Provide details about the property, society amenities, parking, and rental terms..."
                              class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-indigo-600 transition-all"></textarea>
                </div>
            </div>

            {{-- ============================================================= --}}
            {{-- SECTION 5: PUBLISHING TOGGLES                                 --}}
            {{-- ============================================================= --}}
            <div class="flex items-center justify-between p-3.5 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl flex-wrap gap-3">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="auto_approve" value="1" checked class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Publish Directly (Approved)</span>
                        <span class="text-[10px] text-slate-500">Visible on search & homepage immediately</span>
                    </div>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-orange-600 rounded border-slate-300 focus:ring-orange-500">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Mark as Featured</span>
                        <span class="text-[10px] text-slate-500">Highlight in prime banner listings</span>
                    </div>
                </label>
            </div>

            {{-- Live Results Container --}}
            <div id="properAdResultBox" class="hidden p-4 rounded-2xl border text-xs"></div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closePostAdvertiseModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition-colors cursor-pointer"
                        style="background-color: #ffffff !important; color: #334155 !important; border: 1px solid #cbd5e1 !important;">
                    Cancel
                </button>

                <button type="submit" id="properAdSubmitBtn"
                        class="inline-flex items-center gap-2 text-white font-extrabold text-xs shadow-md transition-all cursor-pointer hover:brightness-110 active:scale-95 px-5 py-2.5 rounded-xl"
                        style="background: linear-gradient(180deg, #ea580c 0%, #c2410c 100%) !important; color: #ffffff !important; border: 1px solid #9a3412 !important;">
                    <i class="ph-bold ph-rocket-launch text-sm" id="properAdSubmitIcon"></i>
                    <span id="properAdSubmitText">🚀 Post Advertisement & Create Account</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openPostAdvertiseModal() {
    const modal = document.getElementById('postAdvertiseModal');
    const card = document.getElementById('postAdvertiseModalCard');
    if (!modal || !card) return;
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    });
    const nameInput = document.getElementById('ad_client_name');
    if (nameInput) setTimeout(() => nameInput.focus(), 100);
}

function closePostAdvertiseModal() {
    const modal = document.getElementById('postAdvertiseModal');
    const card = document.getElementById('postAdvertiseModalCard');
    if (!modal || !card) return;
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function switchAdModalTab(mode) {
    const modeInput = document.getElementById('modal_ad_behalf_mode');
    const tabNew = document.getElementById('tab-ad-new');
    const tabExisting = document.getElementById('tab-ad-existing');
    const secNew = document.getElementById('ad-section-new');
    const secExisting = document.getElementById('ad-section-existing');
    const nameInput = document.getElementById('ad_client_name');
    const phoneInput = document.getElementById('ad_client_phone');
    const userInput = document.getElementById('ad_existing_user');

    if (mode === 'new') {
        if (modeInput) modeInput.value = 'new';
        if (tabNew) tabNew.className = 'px-3 py-1 text-xs font-bold rounded-lg transition-all bg-orange-500 text-white shadow-2xs cursor-pointer';
        if (tabExisting) tabExisting.className = 'px-3 py-1 text-xs font-semibold rounded-lg transition-all text-slate-500 hover:text-slate-800 cursor-pointer';
        if (secNew) secNew.classList.remove('hidden');
        if (secExisting) secExisting.classList.add('hidden');
        if (nameInput) nameInput.required = true;
        if (phoneInput) phoneInput.required = true;
        if (userInput) userInput.required = false;
    } else {
        if (modeInput) modeInput.value = 'existing';
        if (tabNew) tabNew.className = 'px-3 py-1 text-xs font-semibold rounded-lg transition-all text-slate-500 hover:text-slate-800 cursor-pointer';
        if (tabExisting) tabExisting.className = 'px-3 py-1 text-xs font-bold rounded-lg transition-all bg-orange-500 text-white shadow-2xs cursor-pointer';
        if (secNew) secNew.classList.add('hidden');
        if (secExisting) secExisting.classList.remove('hidden');
        if (nameInput) nameInput.required = false;
        if (phoneInput) phoneInput.required = false;
        if (userInput) userInput.required = true;
    }
}

function handleAdImagesPreview(input) {
    const grid = document.getElementById('ad_images_preview_grid');
    if (!grid) return;
    grid.innerHTML = '';
    if (!input.files || input.files.length === 0) {
        grid.classList.add('hidden');
        return;
    }

    grid.classList.remove('hidden');
    Array.from(input.files).forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative aspect-square rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs group';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                ${idx === 0 ? '<span class="absolute top-1 left-1 bg-amber-500 text-slate-950 font-extrabold text-[9px] px-1.5 py-0.5 rounded shadow-xs flex items-center gap-0.5"><i class="ph-fill ph-star text-[10px]"></i> Cover</span>' : ''}
                <span class="absolute bottom-1 right-1 bg-black/60 text-white text-[9px] px-1 rounded font-mono">#${idx + 1}</span>
            `;
            grid.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function autoGenerateAdDescription() {
    const title = document.getElementById('ad_property_title')?.value || '';
    const purpose = document.getElementById('ad_purpose')?.value || 'rent';
    const type = document.getElementById('ad_type')?.value || 'house';
    const price = document.getElementById('ad_price')?.value || '';
    const city = document.getElementById('ad_location')?.value || 'Bangalore';
    const locality = document.getElementById('ad_locality')?.value || 'Prime Location';
    const bedrooms = document.getElementById('ad_bedrooms')?.value || '2';
    const bathrooms = document.getElementById('ad_bathrooms')?.value || '2';
    const area = document.getElementById('ad_area_sqft')?.value || '1150';
    const furnishing = document.getElementById('ad_furnishing')?.value || 'semi-furnished';
    const clientName = document.getElementById('ad_client_name')?.value || 'Property Owner';
    const clientPhone = document.getElementById('ad_client_phone')?.value || '';

    const purposeText = purpose === 'rent' ? 'rent' : 'sale';
    const priceText = price ? `at ₹${Number(price).toLocaleString()}${purpose === 'rent' ? ' per month' : ''}` : '';

    const text = `Spacious and beautifully maintained ${bedrooms} BHK ${type} available for ${purposeText} in ${locality}, ${city} ${priceText}. 

Key Highlights:
• Super built-up carpet area of ${area} sq.ft. with ${bathrooms} attached bathrooms.
• Comes ${furnishing} with quality fittings, modular woodwork, and modern fixtures.
• Well-ventilated layout with ample natural sunlight and open views.
• Situated in a prime, peaceful neighborhood close to public transit, reputed schools, grocery markets, and commercial hubs.

Contact ${clientName}${clientPhone ? ' at ' + clientPhone : ''} for genuine inquiries, property visits, and immediate leasing.`;

    const descEl = document.getElementById('ad_description');
    if (descEl) {
        descEl.value = text.trim();
        descEl.focus();
    }
}

async function handleProperAdSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('properAdSubmitBtn');
    const icon = document.getElementById('properAdSubmitIcon');
    const text = document.getElementById('properAdSubmitText');
    const resultBox = document.getElementById('properAdResultBox');

    btn.disabled = true;
    icon.className = 'ph-bold ph-arrows-clockwise text-sm animate-spin';
    text.textContent = 'Posting Advertisement & Creating Account...';

    resultBox.className = 'p-3.5 rounded-2xl border border-orange-200 bg-orange-50/70 text-orange-950 flex items-center gap-2.5 text-xs font-semibold';
    resultBox.innerHTML = `
        <span class="w-3.5 h-3.5 rounded-full border-2 border-orange-600 border-t-transparent animate-spin shrink-0"></span>
        <span>Creating verified owner account and publishing listing with optimized photo gallery...</span>
    `;
    resultBox.classList.remove('hidden');

    try {
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
            || document.querySelector('input[name="_token"]')?.value;

        const res = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await res.json();

        if (data.success) {
            resultBox.className = 'p-4 rounded-2xl border border-emerald-300 bg-emerald-50 text-emerald-950 text-xs shadow-xs';
            resultBox.innerHTML = `
                <div class="flex items-start gap-2.5">
                    <i class="ph-fill ph-check-circle text-xl text-emerald-600 shrink-0 mt-0.5"></i>
                    <div class="min-w-0 flex-1 space-y-2">
                        <p class="font-extrabold text-sm text-emerald-900">${data.message}</p>
                        <div class="bg-white/90 p-2.5 rounded-xl border border-emerald-200/80 space-y-1">
                            <p class="font-bold text-slate-800">👤 Owner: <span class="text-emerald-700">${data.owner?.name}</span> (${data.owner?.phone || 'No phone'})</p>
                            <p class="text-slate-600 font-mono text-[11px]">Email: ${data.owner?.email} • Role: ${data.owner?.role}</p>
                            <p class="font-semibold text-slate-800 pt-1 border-t border-slate-100">🏡 Property: <strong class="text-slate-900">#${data.property?.id} ${data.property?.title}</strong></p>
                            <p class="text-emerald-700 font-bold">₹${Number(data.property?.price).toLocaleString()} • ${data.property?.images_count} Photos Attached</p>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <a href="${data.property?.view_url}" target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition-colors inline-flex items-center gap-1">
                                <i class="ph-bold ph-arrow-square-out"></i> View Live Property
                            </a>
                            <a href="${data.property?.edit_url}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-200 text-slate-800 font-bold text-xs hover:bg-slate-300 transition-colors inline-flex items-center gap-1">
                                <i class="ph-bold ph-pencil-simple"></i> Edit Property
                            </a>
                            <button type="button" onclick="closePostAdvertiseModal()" class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-700 font-bold text-xs hover:bg-slate-50 transition-colors ml-auto cursor-pointer">
                                Done
                            </button>
                        </div>
                    </div>
                </div>
            `;

            if (typeof showAdminToast === 'function') {
                showAdminToast(data.message, 'success');
            }

            if (typeof refreshPropertiesTableSilently === 'function') {
                refreshPropertiesTableSilently();
            }

            form.reset();
            const previewGrid = document.getElementById('ad_images_preview_grid');
            if (previewGrid) {
                previewGrid.innerHTML = '';
                previewGrid.classList.add('hidden');
            }
        } else {
            resultBox.className = 'p-3.5 rounded-2xl border border-rose-300 bg-rose-50 text-rose-900 text-xs';
            resultBox.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-warning-circle text-lg text-rose-600 shrink-0"></i>
                    <span>${data.message || 'Error posting advertisement. Please check form inputs.'}</span>
                </div>
            `;
            if (typeof showAdminToast === 'function') {
                showAdminToast(data.message || 'Error posting advertisement', 'error');
            }
        }
    } catch (err) {
        resultBox.className = 'p-3.5 rounded-2xl border border-rose-300 bg-rose-50 text-rose-900 text-xs';
        resultBox.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="ph-bold ph-warning-circle text-lg text-rose-600 shrink-0"></i>
                <span>Failed to communicate with server: ${err.message}</span>
            </div>
        `;
        if (typeof showAdminToast === 'function') {
            showAdminToast('Request failed. Check network or console.', 'error');
        }
    } finally {
        btn.disabled = false;
        icon.className = 'ph-bold ph-rocket-launch text-sm';
        text.textContent = '🚀 Post Advertisement & Create Account';
    }
}
</script>
