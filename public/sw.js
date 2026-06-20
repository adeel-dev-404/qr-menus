const CACHE_NAME = 'qrmenu-pwa-v2';

// Install event - caching the offline Shell & assets
self.addEventListener('install', event => {
    self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - cache first, network fallback with offline routing for /m/ redirect tokens
self.addEventListener('fetch', event => {
    // Only handle HTTP/HTTPS requests (avoid chrome-extension or other schemes)
    if (!event.request.url.startsWith('http')) return;

    const url = new URL(event.request.url);

    // List of path prefixes that should completely bypass the service worker cache
    const bypassPrefixes = [
        '/login',
        '/register',
        '/forgot-password',
        '/reset-password',
        '/logout',
        '/invite',
        '/pending',
        '/dashboard',
        '/profile',
        '/order',
        '/waiter',
        '/up'
    ];

    const shouldBypass = bypassPrefixes.some(prefix => url.pathname.startsWith(prefix));

    if (shouldBypass) {
        return; // Let browser fetch directly from network without caching or service worker interference
    }

    // If it's a redirect / scan tracking URL
    if (url.pathname.includes('/m/')) {
        event.respondWith(
            fetch(event.request).catch(async () => {
                // If offline, attempt to retrieve a cached menu page starting with /r/
                const cache = await caches.open(CACHE_NAME);
                const keys = await cache.keys();
                
                const cachedMenuRequest = keys.find(req => {
                    const reqUrl = new URL(req.url);
                    return reqUrl.pathname.includes('/r/');
                });

                if (cachedMenuRequest) {
                    return cache.match(cachedMenuRequest);
                }

                // Default fallback if no cached menu page exists
                return new Response(
                    '<h1>You are offline</h1><p>Please connect to the internet to scan and view the menu.</p>',
                    {
                        headers: { 'Content-Type': 'text/html' }
                    }
                );
            })
        );
        return;
    }

    // For other requests: cache first, fall back to network, and dynamically cache HTML / images / assets
    event.respondWith(
        caches.match(event.request).then(cachedResponse => {
            if (cachedResponse) {
                // Serve cached copy, then refresh it in the background if it's stylesheet/script
                if (url.pathname.endsWith('.css') || url.pathname.endsWith('.js')) {
                    fetch(event.request).then(networkResponse => {
                        if (networkResponse.status === 200) {
                            caches.open(CACHE_NAME).then(cache => cache.put(event.request, networkResponse));
                        }
                    }).catch(() => {}); // ignore network errors during background updates
                }
                return cachedResponse;
            }

            return fetch(event.request).then(networkResponse => {
                // Don't cache range responses or non-success requests
                if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
                    return networkResponse;
                }

                // Dynamically cache menu views, images, and static resources
                const isMenuPage = url.pathname.startsWith('/r/');
                const isStorage = url.pathname.startsWith('/storage/');
                const isBuild = url.pathname.startsWith('/build/');
                const isStaticAsset = url.pathname.endsWith('.css') || 
                                      url.pathname.endsWith('.js') || 
                                      url.pathname.endsWith('.png') || 
                                      url.pathname.endsWith('.jpg') || 
                                      url.pathname.endsWith('.jpeg') || 
                                      url.pathname.endsWith('.gif') || 
                                      url.pathname.endsWith('.svg') || 
                                      url.pathname.endsWith('.woff2') || 
                                      url.pathname.endsWith('.json');

                const shouldCache = event.request.method === 'GET' && (
                    isMenuPage || 
                    isStorage || 
                    isBuild || 
                    isStaticAsset
                );

                if (shouldCache) {
                    const responseToCache = networkResponse.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseToCache);
                    });
                }

                return networkResponse;
            }).catch(() => {
                // Return fallback message for failed HTML navigations
                if (event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html')) {
                    return new Response(
                        '<h1>Connection Lost</h1><p>You are currently offline. Please check your internet connection.</p>',
                        {
                            headers: { 'Content-Type': 'text/html' }
                        }
                    );
                }
            });
        })
    );
});
