{{-- ========================================================================= --}}
{{-- UNIFIED LEAD CAPTURE MODALS (Similar Properties, Contact Owner, WhatsApp Alerts, Schedule Visit) --}}
{{-- ========================================================================= --}}

{{-- 1. Get Similar Properties Modal --}}
<div id="modal-similar-properties" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/70 backdrop-blur-md p-4">
    <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white relative">
            <button type="button" onclick="window.closeLeadModal('modal-similar-properties')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center cursor-pointer">
                <i class="ph-bold ph-x"></i>
            </button>
            <h3 class="text-xl font-black">Get Similar Properties</h3>
            <p class="text-xs text-blue-100 mt-1">Get handpicked zero-brokerage listings in the same locality and budget.</p>
        </div>
        <form onsubmit="window.handleLeadSubmit(event, 'modal-similar-properties', 'similar_properties_form')" class="p-6 space-y-3.5">
            <input type="text" name="website_hp" style="display:none !important;" tabindex="-1">
            <input type="hidden" name="lead_source" value="similar_properties_form">
            <input type="hidden" name="property_id" id="sim-prop-id" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Your Name *</label>
                    <input type="text" name="name" required value="{{ auth()->user()?->name }}" placeholder="Full Name"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Mobile Number *</label>
                    <input type="tel" name="mobile" required value="{{ auth()->user()?->phone }}" placeholder="10-digit number"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Preferred Locality</label>
                    <input type="text" name="preferred_locality" id="sim-locality" placeholder="e.g. DLF Phase 5"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Max Budget (₹/mo)</label>
                    <input type="number" name="budget_max" id="sim-budget" placeholder="e.g. 30000"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="pt-2 space-y-2 text-xs">
                <label class="flex items-start gap-2 text-slate-600 dark:text-slate-400 cursor-pointer">
                    <input type="checkbox" name="whatsapp_opt_in" value="1" checked class="mt-0.5 rounded text-blue-600">
                    <span><i class="ph-bold ph-whatsapp-logo text-emerald-500"></i> Send verified matching listings on WhatsApp</span>
                </label>
                <label class="flex items-start gap-2 text-slate-600 dark:text-slate-400 cursor-pointer">
                    <input type="checkbox" name="consent" value="1" required class="mt-0.5 rounded text-blue-600">
                    <span>I agree to be contacted by UnlockRentals regarding property options.</span>
                </label>
            </div>

            <div class="lead-modal-error hidden p-3 bg-red-50 text-xs font-bold text-red-600 rounded-xl"></div>

            <button type="submit" class="lead-submit-btn w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:brightness-105 text-white font-extrabold text-sm rounded-xl transition-all shadow-md cursor-pointer flex items-center justify-center gap-2">
                <i class="ph-bold ph-paper-plane-tilt"></i> Get Similar Properties
            </button>
        </form>
    </div>
</div>

{{-- 2. WhatsApp Property Alerts Modal --}}
<div id="modal-whatsapp-alerts" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/70 backdrop-blur-md p-4">
    <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-white relative">
            <button type="button" onclick="window.closeLeadModal('modal-whatsapp-alerts')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center cursor-pointer">
                <i class="ph-bold ph-x"></i>
            </button>
            <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-white/20 text-[10px] font-extrabold uppercase mb-1">
                <i class="ph-bold ph-bell-simple-ringing"></i> Instant Alerts
            </div>
            <h3 class="text-xl font-black">Get Property Alerts on WhatsApp</h3>
            <p class="text-xs text-emerald-100 mt-1">Receive immediate WhatsApp notifications the moment a matching verified home is posted.</p>
        </div>
        <form onsubmit="window.handleLeadSubmit(event, 'modal-whatsapp-alerts', 'whatsapp_alerts_form')" class="p-6 space-y-3.5">
            <input type="text" name="website_hp" style="display:none !important;" tabindex="-1">
            <input type="hidden" name="lead_source" value="whatsapp_alerts_form">
            <input type="hidden" name="whatsapp_opt_in" value="1">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Your Name *</label>
                    <input type="text" name="name" required value="{{ auth()->user()?->name }}" placeholder="Full Name"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">WhatsApp Mobile *</label>
                    <input type="tel" name="mobile" required value="{{ auth()->user()?->phone }}" placeholder="WhatsApp number"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">City</label>
                    <input type="text" name="preferred_city" value="Gurgaon" placeholder="City"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">BHK</label>
                    <select name="bedrooms" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                        <option value="1">1 BHK</option>
                        <option value="2" selected>2 BHK</option>
                        <option value="3">3 BHK</option>
                        <option value="4+">4+ BHK</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Max Budget</label>
                    <input type="number" name="budget_max" placeholder="₹/mo"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="pt-2 text-xs">
                <label class="flex items-start gap-2 text-slate-600 dark:text-slate-400 cursor-pointer">
                    <input type="checkbox" name="consent" value="1" required checked class="mt-0.5 rounded text-emerald-600">
                    <span>I agree to receive personalized WhatsApp property alerts from UnlockRentals (Reply STOP to cancel anytime).</span>
                </label>
            </div>

            <div class="lead-modal-error hidden p-3 bg-red-50 text-xs font-bold text-red-600 rounded-xl"></div>

            <button type="submit" class="lead-submit-btn w-full py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:brightness-105 text-white font-extrabold text-sm rounded-xl transition-all shadow-md cursor-pointer flex items-center justify-center gap-2">
                <i class="ph-bold ph-whatsapp-logo text-lg"></i> Activate WhatsApp Alerts
            </button>
        </form>
    </div>
</div>

{{-- 3. Schedule Property Visit Modal --}}
<div id="modal-schedule-visit" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/70 backdrop-blur-md p-4">
    <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 text-white relative">
            <button type="button" onclick="window.closeLeadModal('modal-schedule-visit')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center cursor-pointer">
                <i class="ph-bold ph-x"></i>
            </button>
            <h3 class="text-xl font-black">Schedule Property Visit</h3>
            <p class="text-xs text-purple-100 mt-1">Book an in-person or guided video walkthrough with zero brokerage.</p>
        </div>
        <form onsubmit="window.handleLeadSubmit(event, 'modal-schedule-visit', 'schedule_visit_form')" class="p-6 space-y-3.5">
            <input type="text" name="website_hp" style="display:none !important;" tabindex="-1">
            <input type="hidden" name="lead_source" value="schedule_visit_form">
            <input type="hidden" name="property_id" id="visit-prop-id" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Your Name *</label>
                    <input type="text" name="name" required value="{{ auth()->user()?->name }}" placeholder="Full Name"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Mobile Number *</label>
                    <input type="tel" name="mobile" required value="{{ auth()->user()?->phone }}" placeholder="Phone number"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Preferred Date *</label>
                    <input type="date" name="move_in_date" required min="{{ date('Y-m-d') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Preferred Time Slot</label>
                    <select name="message" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white">
                        <option value="Morning (10:00 AM - 1:00 PM)">Morning (10 AM - 1 PM)</option>
                        <option value="Afternoon (1:00 PM - 4:00 PM)" selected>Afternoon (1 PM - 4 PM)</option>
                        <option value="Evening (4:00 PM - 7:00 PM)">Evening (4 PM - 7 PM)</option>
                    </select>
                </div>
            </div>

            <div class="pt-2 space-y-2 text-xs">
                <label class="flex items-start gap-2 text-slate-600 dark:text-slate-400 cursor-pointer">
                    <input type="checkbox" name="whatsapp_opt_in" value="1" checked class="mt-0.5 rounded text-purple-600">
                    <span><i class="ph-bold ph-whatsapp-logo text-emerald-500"></i> Send visit confirmation & directions on WhatsApp</span>
                </label>
                <label class="flex items-start gap-2 text-slate-600 dark:text-slate-400 cursor-pointer">
                    <input type="checkbox" name="consent" value="1" required class="mt-0.5 rounded text-purple-600">
                    <span>I agree to be contacted by UnlockRentals to confirm my scheduled visit.</span>
                </label>
            </div>

            <div class="lead-modal-error hidden p-3 bg-red-50 text-xs font-bold text-red-600 rounded-xl"></div>

            <button type="submit" class="lead-submit-btn w-full py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:brightness-105 text-white font-extrabold text-sm rounded-xl transition-all shadow-md cursor-pointer flex items-center justify-center gap-2">
                <i class="ph-bold ph-calendar-check"></i> Confirm Visit Request
            </button>
        </form>
    </div>
</div>

<script>
(function() {
    window.openLeadModal = function(modalId, propId = null, locality = null, budget = null) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        if (propId) {
            const pInput = modal.querySelector('[name="property_id"]');
            if (pInput) pInput.value = propId;
        }
        if (locality) {
            const locInput = modal.querySelector('[name="preferred_locality"]');
            if (locInput) locInput.value = locality;
        }
        if (budget) {
            const bInput = modal.querySelector('[name="budget_max"]');
            if (bInput) bInput.value = budget;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closeLeadModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    window.handleLeadSubmit = async function(e, modalId, source) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('.lead-submit-btn');
        const err = form.querySelector('.lead-modal-error');
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        err.classList.add('hidden');
        btn.disabled = true;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Submitting...';

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.whatsapp_opt_in = form.querySelector('[name="whatsapp_opt_in"]')?.checked ? 1 : 0;
        data.consent = form.querySelector('[name="consent"]')?.checked ? 1 : 0;

        try {
            const res = await fetch('/api/leads', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const json = await res.json();
            if (!res.ok || !json.success) {
                throw new Error(json.message || Object.values(json.errors || {})[0]?.[0] || 'Submission failed.');
            }

            form.innerHTML = `
                <div class="text-center py-8 px-4">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3 shadow-md">
                        <i class="ph-bold ph-check text-2xl"></i>
                    </div>
                    <h4 class="text-base font-extrabold text-slate-900 dark:text-white">Request Received!</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Our verified property advisor will connect with you via call/WhatsApp shortly.</p>
                    <button type="button" onclick="window.closeLeadModal('${modalId}')" class="mt-5 px-6 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition cursor-pointer">
                        Close
                    </button>
                </div>
            `;
        } catch (error) {
            err.textContent = error.message;
            err.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    };
})();
</script>
