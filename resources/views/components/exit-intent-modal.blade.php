{{-- Exit-Intent Smart Property Match Modal --}}
<div id="exit-intent-modal" class="fixed inset-0 z-[99999] hidden items-center justify-center bg-slate-950/70 backdrop-blur-md p-4 transition-all duration-300" role="dialog" aria-modal="true" aria-labelledby="exit-modal-title">
    <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 animate-modal-enter" id="exit-modal-card">
        
        {{-- Top Gradient Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white relative">
            <button type="button" onclick="window.closeExitIntentModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition-all cursor-pointer" aria-label="Close modal">
                <i class="ph-bold ph-x text-base"></i>
            </button>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white text-[11px] font-extrabold uppercase tracking-wider mb-2">
                <i class="ph-bold ph-sparkle"></i> Quick Property Match
            </div>
            <h3 id="exit-modal-title" class="text-xl sm:text-2xl font-black tracking-tight leading-tight">
                Still Looking for a Home?
            </h3>
            <p class="text-xs sm:text-sm text-blue-100 mt-1 font-medium">
                Tell us what you need and we'll find matching verified properties with zero brokerage!
            </p>
        </div>

        {{-- Form Body --}}
        <div class="p-6">
            <form id="exit-intent-form" onsubmit="window.submitExitIntentForm(event)">
                {{-- Anti-spam honeypot --}}
                <input type="text" name="website_hp" style="display:none !important;" tabindex="-1" autocomplete="off">
                <input type="hidden" name="lead_source" value="exit_intent_popup">

                <div class="space-y-3.5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Your Name *</label>
                            <input type="text" name="name" required placeholder="e.g. Rahul Sharma"
                                   value="{{ auth()->user()?->name }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Mobile Number *</label>
                            <input type="tel" name="mobile" required placeholder="10-digit phone"
                                   value="{{ auth()->user()?->phone }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">City</label>
                            <input type="text" name="preferred_city" placeholder="e.g. Gurgaon" value="Gurgaon"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Purpose</label>
                            <select name="purpose" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                <option value="rent">Rent</option>
                                <option value="buy">Buy</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Max Budget</label>
                            <input type="number" name="budget_max" placeholder="₹ per month"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                    </div>

                    {{-- Consents --}}
                    <div class="pt-2 space-y-2 text-xs">
                        <label class="flex items-start gap-2 text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" name="whatsapp_opt_in" value="1" checked class="mt-0.5 rounded text-blue-600 focus:ring-blue-500">
                            <span><i class="ph-bold ph-whatsapp-logo text-emerald-500"></i> Send verified matching properties on WhatsApp</span>
                        </label>
                        <label class="flex items-start gap-2 text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" name="consent" value="1" required class="mt-0.5 rounded text-blue-600 focus:ring-blue-500">
                            <span>I agree to be contacted by UnlockRentals regarding my property inquiry through phone, SMS, or WhatsApp.</span>
                        </label>
                    </div>

                    <div id="exit-form-error" class="hidden p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 text-xs font-bold text-red-600 rounded-xl"></div>

                    <button type="submit" id="exit-submit-btn" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:brightness-105 active:scale-[0.99] text-white font-extrabold text-sm rounded-xl transition-all shadow-md shadow-blue-500/25 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="ph-bold ph-magnifying-glass"></i> Show Matching Properties
                    </button>
                </div>
            </form>

            {{-- Matching Properties Result Container --}}
            <div id="exit-results-container" class="hidden pt-4 space-y-3">
                <div class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                    <i class="ph-bold ph-check-circle text-base"></i> We found these matching properties for you:
                </div>
                <div id="exit-properties-list" class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto pr-1"></div>
                <div class="pt-2">
                    <button type="button" onclick="window.closeExitIntentModal()" class="w-full py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl hover:bg-slate-200 transition">
                        Continue Browsing
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    window.closeExitIntentModal = function() {
        const modal = document.getElementById('exit-intent-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    window.submitExitIntentForm = async function(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('exit-submit-btn');
        const err = document.getElementById('exit-form-error');
        const results = document.getElementById('exit-results-container');
        const list = document.getElementById('exit-properties-list');
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        err.classList.add('hidden');
        btn.disabled = true;
        btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Finding Properties...';

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.whatsapp_opt_in = form.querySelector('[name="whatsapp_opt_in"]').checked ? 1 : 0;
        data.consent = form.querySelector('[name="consent"]').checked ? 1 : 0;

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

            // Hide form and show results
            form.classList.add('hidden');
            results.classList.remove('hidden');

            if (json.matching_properties && json.matching_properties.length > 0) {
                list.innerHTML = json.matching_properties.map(p => `
                    <a href="${p.url}" target="_blank" class="p-3 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-2xl flex items-center gap-3 hover:border-blue-500 transition group">
                        <img src="${p.image}" class="w-14 h-14 rounded-xl object-cover shrink-0">
                        <div class="min-w-0 flex-1">
                            <h5 class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-blue-600">${p.title}</h5>
                            <p class="text-[11px] text-slate-500 truncate">${p.locality || p.location}</p>
                            <p class="text-xs font-black text-blue-600 mt-0.5">₹${p.price}</p>
                        </div>
                    </a>
                `).join('');
            } else {
                list.innerHTML = `<p class="text-xs text-slate-500 col-span-2 text-center py-4">Our advisor will WhatsApp you customized options shortly!</p>`;
            }
        } catch (error) {
            err.textContent = error.message;
            err.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '<i class="ph-bold ph-magnifying-glass"></i> Show Matching Properties';
        }
    };
})();
</script>
