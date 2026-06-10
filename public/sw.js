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
