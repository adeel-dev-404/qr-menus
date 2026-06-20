const CACHE_NAME = 'qrmenu-pwa-v1';

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

    // If it's a redirect / scan tracking URL
    if (url.pathname.startsWith('/m/')) {
        event.respondWith(
            fetch(event.request).catch(async () => {
                // If offline, attempt to retrieve a cached menu page starting with /r/
                const cache = await caches.open(CACHE_NAME);
                const keys = await cache.keys();
                
                const cachedMenuRequest = keys.find(req => {
                    const reqUrl = new URL(req.url);
                    return reqUrl.pathname.startsWith('/r/');
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
                const shouldCache = event.request.method === 'GET' && (
                    url.pathname.startsWith('/r/') || 
                    url.pathname.includes('/storage/') ||
                    url.pathname.includes('/build/') ||
                    event.request.headers.get('accept').includes('text/html') ||
                    event.request.headers.get('accept').includes('image/')
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
                if (event.request.headers.get('accept').includes('text/html')) {
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
