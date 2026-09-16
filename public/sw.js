const CACHE_NAME = 'unlockrentals-v1.0.3';
const OFFLINE_URL = '/offline';

const ASSETS_TO_CACHE = [
    OFFLINE_URL,
    '/',
    '/css/unlock-rental.css',
    '/favicon.ico',
    '/favicon.png',
    '/images/logo.png',
    'https://cdn.tailwindcss.com',
    'https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.0/src/bold/style.css',
    'https://unpkg.com/@phosphor-icons/web@2.1.1/src/style.css'
];

// Install Event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[Service Worker] Pre-caching offline fallback and key resources');
            return cache.addAll(ASSETS_TO_CACHE);
        }).then(() => self.skipWaiting())
    );
});

// Activate Event
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('[Service Worker] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event Interception
self.addEventListener('fetch', (event) => {
    // Only handle GET requests
    if (event.request.method !== 'GET') return;

    const requestUrl = new URL(event.request.url);

    // 1. Navigation requests (HTML pages)
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch((error) => {
                    console.log('[Service Worker] Navigation failed; serving offline fallback page.', error);
                    return caches.match(OFFLINE_URL);
                })
        );
        return;
    }

    // 2. Static Assets Caching (Stale-While-Revalidate Strategy)
    if (ASSETS_TO_CACHE.some(asset => event.request.url.includes(asset)) || 
        event.request.destination === 'style' || 
        event.request.destination === 'script' || 
        event.request.destination === 'image' || 
        event.request.destination === 'font') {
        
        event.respondWith(
            caches.open(CACHE_NAME).then((cache) => {
                return cache.match(event.request).then((cachedResponse) => {
                    const fetchedResponse = fetch(event.request).then((networkResponse) => {
                        // Cache a copy of the updated resource
                        if (networkResponse.status === 200) {
                            cache.put(event.request, networkResponse.clone());
                        }
                        return networkResponse;
                    }).catch(() => {
                        // Fail silently for background fetches
                    });

                    // Return cached response instantly, fallback to network fetch
                    return cachedResponse || fetchedResponse;
                });
            })
        );
    }
});

/* ─────────────────────────────────────────────────────────────
 * OTP PUSH NOTIFICATION & BACKGROUND AUTO-FILL
 * ───────────────────────────────────────────────────────────── */

// Handle Web Push Event
self.addEventListener('push', (event) => {
    let payload = {};
    try {
        payload = event.data ? event.data.json() : {};
    } catch (e) {
        payload = {
            title: 'UnlockRentals Security Code',
            body: event.data ? event.data.text() : 'Your verification code has arrived.'
        };
    }

    const title = payload.title || 'UnlockRentals Security Code';
    const bodyText = payload.body || 'Your verification code has arrived.';
    
    // Extract OTP digits (4 to 6 digits)
    const otpMatch = (payload.otp || bodyText.match(/\b\d{4,6}\b/) || [])[0] || payload.otp || '';

    const options = {
        body: bodyText,
        icon: payload.icon || '/favicon.ico',
        badge: '/favicon.ico',
        vibrate: [100, 50, 100, 50, 100],
        tag: 'unlockrentals-otp-verify',
        renotify: true,
        requireInteraction: true,
        data: {
            otp: otpMatch,
            url: payload.click_action || payload.url || '/'
        },
        actions: [
            { action: 'autofill', title: '⚡ Auto-Fill & Submit' },
            { action: 'dismiss', title: 'Dismiss' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(title, options).then(() => {
            // Broadcast auto-fill message to all active windows
            if (otpMatch) {
                return self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clients) => {
                    clients.forEach((client) => {
                        client.postMessage({
                            type: 'AUTOFILL_OTP',
                            otp: otpMatch,
                            autoSubmit: true
                        });
                    });
                });
            }
        })
    );
});

// Handle Notification Click (Focus window, auto-fill & auto-submit)
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const otp = event.notification.data ? event.notification.data.otp : null;
    const targetUrl = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/';

    if (event.action === 'dismiss') {
        return;
    }

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            // Find existing active client window
            for (const client of clientList) {
                if ('focus' in client) {
                    if (otp) {
                        client.postMessage({
                            type: 'AUTOFILL_OTP',
                            otp: otp,
                            autoSubmit: true
                        });
                    }
                    return client.focus();
                }
            }
            // If no window is open, open a new window
            if (self.clients.openWindow) {
                const urlWithOtp = targetUrl + (targetUrl.includes('?') ? '&' : '?') + (otp ? 'autofill_otp=' + encodeURIComponent(otp) : '');
                return self.clients.openWindow(urlWithOtp);
            }
        })
    );
});

// Handle Message from Client Window (e.g., to display notification via Service Worker)
self.addEventListener('message', (event) => {
    if (!event.data) return;

    if (event.data.type === 'SHOW_OTP_NOTIFICATION') {
        const payload = event.data.payload || {};
        const title = payload.title || 'UnlockRentals Security Code';
        const otp = payload.otp || '';
        const body = payload.body || (otp ? `${otp} is your UnlockRentals verification code.` : 'Your verification code has arrived.');

        self.registration.showNotification(title, {
            body: body,
            icon: payload.icon || '/favicon.ico',
            badge: '/favicon.ico',
            vibrate: [100, 50, 100],
            tag: 'unlockrentals-otp-verify',
            renotify: true,
            requireInteraction: true,
            data: {
                otp: otp,
                url: payload.url || '/'
            },
            actions: [
                { action: 'autofill', title: '⚡ Auto-Fill & Submit' },
                { action: 'dismiss', title: 'Dismiss' }
            ]
        });
    }
});
