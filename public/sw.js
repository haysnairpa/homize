// Service Worker for Homize - Handles offline fallback
const CACHE_NAME = 'homize-offline-v1';
const OFFLINE_URL = '/offline';
const CACHED_ASSETS = [
    '/offline',
    '/css/app.css',
    '/js/app.js',
    '/css/custom.css',
    '/homizeiconblue.ico',
    '/js/offline-handler.js',
    '/manifest.json'
];

// Immediately cache the offline page on installation
const cacheOfflinePage = async () => {
    const cache = await caches.open(CACHE_NAME);
    try {
        await cache.add(new Request(OFFLINE_URL, { cache: 'reload' }));
    } catch (error) {
        console.error('Failed to cache offline page:', error);
    }
};

// Install event - Cache offline page and essential assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        (async () => {
            const cache = await caches.open(CACHE_NAME);
            console.log('Opened cache');
            // First cache the offline page with high priority
            await cacheOfflinePage();
            // Then cache other assets
            await cache.addAll(CACHED_ASSETS);
            await self.skipWaiting();
        })()
    );
});

// Activate event - Clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.filter((cacheName) => {
                    return cacheName !== CACHE_NAME;
                }).map((cacheName) => {
                    return caches.delete(cacheName);
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - Serve from cache if offline
self.addEventListener('fetch', (event) => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') return;
    
    // Skip cross-origin requests
    if (!event.request.url.startsWith(self.location.origin)) return;
    
    // Handle the fetch event with network-first strategy for navigation requests
    if (event.request.mode === 'navigate') {
        event.respondWith(
            (async () => {
                try {
                    // First try the network
                    const networkResponse = await fetch(event.request);
                    return networkResponse;
                } catch (error) {
                    console.log('Fetch failed; returning offline page instead.', error);
                    
                    // If network fails, get from cache
                    const cache = await caches.open(CACHE_NAME);
                    const cachedResponse = await cache.match(OFFLINE_URL);
                    return cachedResponse;
                }
            })()
        );
        return;
    }
    
    // For non-navigation requests, use a cache-first strategy
    event.respondWith(
        (async () => {
            const cache = await caches.open(CACHE_NAME);
            
            // Try to get the response from cache first
            const cachedResponse = await cache.match(event.request);
            if (cachedResponse) {
                return cachedResponse;
            }
            
            // If not in cache, try the network
            try {
                const networkResponse = await fetch(event.request);
                
                // Cache successful responses for future offline use
                if (networkResponse.ok) {
                    cache.put(event.request, networkResponse.clone());
                }
                
                return networkResponse;
            } catch (error) {
                // If network fails and it's an HTML request, show the offline page
                if (event.request.headers.get('accept')?.includes('text/html')) {
                    return cache.match(OFFLINE_URL);
                }
                
                // For other file types, return a simple offline response
                return new Response('Network error happened', {
                    status: 408,
                    headers: { 'Content-Type': 'text/plain' },
                });
            }
        })()
    );
});

// Listen for messages from the client
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
