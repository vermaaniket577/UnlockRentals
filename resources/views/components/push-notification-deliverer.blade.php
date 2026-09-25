<!-- Real-Time Push Notification & In-App Alert Deliverer (Web & Mobile App) -->
<script>
    (function() {
        let isFetching = false;
        let lastDeliveredId = null;

        // Subtle pleasant audio bell using Web Audio API (Zero external asset dependency)
        function playNotificationChime() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();
                if (ctx.state === 'suspended') {
                    ctx.resume().catch(() => {});
                }

                const now = ctx.currentTime;
                // Tone 1: High crisp bell
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, now); // D5
                gain1.gain.setValueAtTime(0.08, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.45);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.45);

                // Tone 2: Harmonic chime
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880, now + 0.08); // A5
                gain2.gain.setValueAtTime(0.1, now + 0.08);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.65);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now + 0.08);
                osc2.stop(now + 0.65);
            } catch (e) {
                // Audio autoplay might be restricted before first gesture; fail gracefully
            }
        }

        let lastCheckTime = 0;
        const MIN_CHECK_INTERVAL_MS = 90000; // 90 seconds minimum throttle

        window.checkLatestPushNotification = async function() {
            if (isFetching || (document.hidden && document.visibilityState !== 'visible')) return;
            const now = Date.now();
            if (lastCheckTime && (now - lastCheckTime < MIN_CHECK_INTERVAL_MS)) return;
            lastCheckTime = now;
            isFetching = true;

            try {
                const isMobile = /Android|iPhone|iPad/i.test(navigator.userAgent);
                const deviceParam = isMobile ? 'app' : 'web';
                const res = await fetch(`/api/push/latest?device=${deviceParam}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();
                if (!data || !data.success || !data.notification) return;

                const n = data.notification;
                const dismissedKey = 'dismissed_push_alert_' + n.id;
                
                // Do not re-prompt if user explicitly dismissed this alert
                if (localStorage.getItem(dismissedKey)) return;

                // Avoid re-rendering if already showing this notification ID
                if (lastDeliveredId === n.id && document.getElementById('in-app-push-toast')) return;
                lastDeliveredId = n.id;

                // 1. Trigger ServiceWorker System Notification if permission granted
                if ('Notification' in window && Notification.permission === 'granted' && navigator.serviceWorker && navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage({
                        type: 'SHOW_CUSTOM_NOTIFICATION',
                        payload: {
                            title: n.title,
                            body: n.body,
                            icon: n.icon,
                            image: n.image_url,
                            url: n.action_url
                        }
                    });
                } else if ('Notification' in window && Notification.permission === 'default') {
                    // Soft request permission on first interaction
                    Notification.requestPermission().then(p => {
                        if (p === 'granted' && navigator.serviceWorker && navigator.serviceWorker.controller) {
                            navigator.serviceWorker.controller.postMessage({
                                type: 'SHOW_CUSTOM_NOTIFICATION',
                                payload: {
                                    title: n.title,
                                    body: n.body,
                                    icon: n.icon,
                                    image: n.image_url,
                                    url: n.action_url
                                }
                            });
                        }
                    }).catch(() => {});
                }

                // 2. Display Beautiful Floating Native-style In-App Toast (Guaranteed for both Web & Mobile App)
                window.showInAppPushToast(n);
                playNotificationChime();
            } catch (err) {
                console.debug('[Push Delivery check]:', err);
            } finally {
                isFetching = false;
            }
        };

        window.showInAppPushToast = function(n) {
            const existing = document.getElementById('in-app-push-toast');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.id = 'in-app-push-toast';
            toast.className = 'fixed top-4 right-4 sm:top-6 sm:right-6 max-w-sm sm:max-w-md w-[calc(100%-2rem)] bg-white/98 dark:bg-slate-900/98 backdrop-blur-xl border border-blue-500/30 dark:border-blue-500/40 shadow-2xl rounded-2xl p-4 z-[999999] transition-all duration-300 transform -translate-y-8 opacity-0 flex flex-col gap-3 font-sans';
            toast.style.boxShadow = '0 20px 40px -10px rgba(37, 99, 235, 0.3), 0 0 0 1px rgba(37, 99, 235, 0.15)';

            toast.innerHTML = `
                <div class="flex items-start gap-3 relative">
                    ${n.image_url 
                        ? `<img src="${escapeHtml(n.image_url)}" alt="Banner" class="w-12 h-12 rounded-xl object-cover border border-slate-100 dark:border-slate-800 flex-shrink-0 shadow-sm" onerror="this.style.display='none'">` 
                        : `<div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-md shadow-blue-500/20"><i class="ph-bold ph-bell-ringing"></i></div>`
                    }
                    <div class="flex-1 min-w-0 pr-6">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                            <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 dark:text-blue-400">Push Notification</span>
                            <span class="text-[10px] text-slate-400">&bull; Just now</span>
                        </div>
                        <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white leading-snug">${escapeHtml(n.title)}</h4>
                        <p class="text-[11px] text-slate-600 dark:text-slate-300 line-clamp-2 mt-0.5 font-medium leading-relaxed">${escapeHtml(n.body)}</p>
                    </div>
                    <button type="button" onclick="dismissPushToast(${n.id || 0})" class="absolute top-0 right-0 p-1.5 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" title="Dismiss">
                        <i class="ph-bold ph-x text-sm"></i>
                    </button>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="dismissPushToast(${n.id || 0})" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition-colors cursor-pointer">Later</button>
                    <a href="${escapeHtml(n.action_url || '/')}" onclick="dismissPushToast(${n.id || 0})" class="px-3.5 py-1.5 rounded-lg text-xs font-extrabold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md shadow-blue-600/20 transition-all flex items-center gap-1.5 cursor-pointer">
                        <span>Open Details</span>
                        <i class="ph-bold ph-arrow-right text-xs"></i>
                    </a>
                </div>
            `;

            document.body.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                setTimeout(() => {
                    toast.classList.remove('-translate-y-8', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                }, 50);
            });

            // Auto-dismiss after 25 seconds
            setTimeout(() => {
                dismissPushToast(n.id || 0);
            }, 25000);
        };

        window.dismissPushToast = function(id) {
            if (id) {
                localStorage.setItem('dismissed_push_alert_' + id, '1');
            }
            const toast = document.getElementById('in-app-push-toast');
            if (toast) {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('-translate-y-8', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }
        };

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str || '';
            return div.innerHTML;
        }

        // 1. Initial check when page loads
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => setTimeout(window.checkLatestPushNotification, 1200));
        } else {
            setTimeout(window.checkLatestPushNotification, 1200);
        }

        // 2. Periodic polling every 3 minutes (only if tab is actively visible)
        setInterval(() => {
            if (!document.hidden && document.visibilityState === 'visible') {
                window.checkLatestPushNotification();
            }
        }, 180000);

        // 3. Trigger check when user switches back to window (throttled)
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                window.checkLatestPushNotification();
            }
        });
    })();
</script>
