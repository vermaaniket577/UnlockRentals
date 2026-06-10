{{-- PWA Installation Banner Drawer --}}
<div id="pwa-install-drawer" class="hidden fixed bottom-20 left-4 right-4 md:left-auto md:right-6 md:bottom-6 md:max-w-sm z-50 transform translate-y-32 opacity-0 transition-all duration-500 ease-out">
    <div class="bg-gradient-to-br from-slate-900/95 to-slate-950/98 backdrop-blur-2xl border border-white/10 rounded-2xl p-5 shadow-2xl shadow-blue-500/10">
        
        {{-- Header & Close --}}
        <div class="flex justify-between items-start gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-blue-500 to-indigo-600 p-0.5 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <div class="w-full h-full rounded-[10px] bg-slate-900 flex items-center justify-center overflow-hidden">
                        {{-- Custom SVG Icon inside --}}
                        <svg viewBox="0 0 100 100" class="w-7 h-7 text-blue-500 fill-current">
                            <path d="M50,15 L20,38 L20,85 L42,85 L42,60 L58,60 L58,85 L80,85 L80,38 Z M50,22 L73,40 L73,80 L63,80 L63,55 L37,55 L37,80 L27,80 L27,40 Z" />
                            <circle cx="50" cy="48" r="7" class="animate-pulse" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-black text-white tracking-tight">UnlockRentals App</h3>
                    <p class="text-[10px] text-zinc-400 font-medium">Verified Rentals & Stays</p>
                </div>
            </div>
            <button onclick="dismissPwaPrompt()" class="text-zinc-500 hover:text-white transition-colors duration-200" aria-label="Close">
                <i class="ph-bold ph-x text-base"></i>
            </button>
        </div>

        {{-- Benefits list --}}
        <div class="my-4 space-y-2">
            <div class="flex items-center gap-2 text-xs text-zinc-300">
                <i class="ph ph-check text-blue-500 font-bold"></i>
                <span>Offline Property Search</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-zinc-300">
                <i class="ph ph-check text-blue-500 font-bold"></i>
                <span>Direct Owner Contact Alerts</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-zinc-300">
                <i class="ph-fill ph-star text-amber-400"></i>
                <span class="text-zinc-400 text-[10px]">4.9 Rating &middot; Fast (~2MB)</span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-2 mt-4">
            <button onclick="dismissPwaPrompt()" class="flex-1 py-2.5 rounded-xl border border-white/5 bg-white/5 hover:bg-white/10 text-xs font-bold text-zinc-300 hover:text-white transition-all duration-200">
                Maybe Later
            </button>
            <button onclick="triggerPwaInstall()" class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-xs font-bold text-white shadow-lg shadow-blue-500/20 active:scale-[0.98] transition-all duration-200 animate-pulse-slow">
                Install App
            </button>
        </div>

    </div>
</div>

<style>
    @keyframes pulseSlow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
        50% { box-shadow: 0 0 0 8px rgba(37, 99, 235, 0); }
    }
    .animate-pulse-slow {
        animation: pulseSlow 2.5s infinite;
    }
</style>

<script>
    let deferredPrompt;
    const pwaDrawer = document.getElementById('pwa-install-drawer');

    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt = e;
        
        // Check if user has previously dismissed it in this session
        if (!sessionStorage.getItem('pwa-prompt-dismissed')) {
            showPwaPrompt();
        }
    });

    function showPwaPrompt() {
        if (!pwaDrawer) return;
        pwaDrawer.classList.remove('hidden');
        setTimeout(() => {
            pwaDrawer.classList.remove('translate-y-32', 'opacity-0');
            pwaDrawer.classList.add('translate-y-0', 'opacity-100');
        }, 100);
    }

    function dismissPwaPrompt() {
        if (!pwaDrawer) return;
        pwaDrawer.classList.remove('translate-y-0', 'opacity-100');
        pwaDrawer.classList.add('translate-y-32', 'opacity-0');
        sessionStorage.setItem('pwa-prompt-dismissed', 'true');
        setTimeout(() => {
            pwaDrawer.classList.add('hidden');
        }, 500);
    }

    async function triggerPwaInstall() {
        if (!deferredPrompt) return;
        // Show the install prompt
        deferredPrompt.prompt();
        // Wait for the user to respond to the prompt
        const { outcome } = await deferredPrompt.userChoice;
        if (outcome === 'accepted') {
            console.log('User accepted the install prompt');
        } else {
            console.log('User dismissed the install prompt');
        }
        deferredPrompt = null;
        dismissPwaPrompt();
    }

    window.addEventListener('appinstalled', (evt) => {
        console.log('UnlockRentals app installed successfully');
        dismissPwaPrompt();
    });
</script>
