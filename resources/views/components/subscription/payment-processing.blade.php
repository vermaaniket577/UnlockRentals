<div id="processing-overlay" class="hidden fixed inset-0 z-[10000] items-center justify-center bg-slate-950/40 p-4 backdrop-blur-md" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="relative w-full max-w-md rounded-[2rem] border border-slate-100 bg-white p-8 text-center shadow-2xl" style="animation: successScaleIn .36s cubic-bezier(0.16, 1, 0.3, 1) both; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);">
        <div class="absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>
        
        <div class="mx-auto relative z-10 w-24 h-24 mb-6 flex items-center justify-center">
            <div class="absolute w-24 h-24 secure-ring rounded-full opacity-10"></div>
            <div class="absolute w-20 h-20 border-2 border-dashed border-blue-500/20 rounded-full animate-[spin_8s_linear_infinite]"></div>
            <div class="relative w-12 h-12 bg-gradient-to-br from-blue-600 to-sky-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/10">
                <i class="ph-bold ph-shield-check text-white text-2xl animate-pulse"></i>
            </div>
        </div>

        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-blue-600">Secure Checkout</p>
        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-900" id="processing-status-text">Creating Secure Payment Order...</h2>
        <p class="mt-3 text-xs leading-5 text-slate-500">Please do not refresh the page or click back. Payment is protected with SSL encryption.</p>

        <!-- Progress Tracker -->
        <div class="relative mt-6 w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
            <div id="processing-progress-bar" class="bg-gradient-to-r from-blue-600 to-sky-500 h-full w-0 transition-all duration-300"></div>
        </div>
    </div>
</div>
