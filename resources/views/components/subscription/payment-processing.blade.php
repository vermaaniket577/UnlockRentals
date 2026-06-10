<div id="processing-overlay" class="hidden fixed inset-0 z-[10000] items-center justify-center bg-slate-950/70 p-4 backdrop-blur-xl" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="relative w-full max-w-md rounded-[2rem] border border-white/20 bg-white/92 p-7 text-center shadow-2xl backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/92" style="animation: successScaleIn .36s cubic-bezier(0.16, 1, 0.3, 1) both;">
        <div class="absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>
        
        <div class="mx-auto relative z-10 w-24 h-24 mb-6 flex items-center justify-center">
            <div class="absolute w-24 h-24 secure-ring rounded-full opacity-20"></div>
            <div class="absolute w-20 h-20 border-2 border-dashed border-blue-500/30 rounded-full animate-[spin_8s_linear_infinite]"></div>
            <div class="relative w-12 h-12 bg-gradient-to-br from-blue-600 to-sky-500 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-500/20">
                <i class="ph-bold ph-shield-check text-white text-2xl animate-pulse"></i>
            </div>
        </div>

        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-blue-600">Secure Checkout</p>
        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 dark:text-white" id="processing-status-text">Creating Secure Payment Order...</h2>
        <p class="mt-2 text-xs text-slate-500">Please do not refresh the page or click back. Payment is protected with SSL encryption.</p>

        <!-- Progress Tracker -->
        <div class="relative mt-6 w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
            <div id="processing-progress-bar" class="bg-gradient-to-r from-blue-600 to-sky-500 h-full w-0 transition-all duration-300"></div>
        </div>
    </div>
</div>
