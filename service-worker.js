/**
 * Service Worker untuk Simpati Trans PWA
 * Menyediakan offline functionality dan caching
 */

const CACHE_NAME = 'simpati-trans-v3';
const RUNTIME_CACHE = 'simpati-trans-runtime-v3';

// Assets yang akan di-cache saat install
const STATIC_ASSETS = [
  '/',
  '/index.php',
  '/css/style.css',
  '/css/daftarmobil.css',
  '/css/chat.css',
  '/js/dashboard.js',
  '/js/chat.js',
  '/img/logo1.png',
  '/img/logo2.png',
  '/manifest.json'
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Installing...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[Service Worker] Caching static assets');
        return cache.addAll(STATIC_ASSETS);
      })
      .then(() => self.skipWaiting())
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Activating...');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames
          .filter((cacheName) => {
            return cacheName !== CACHE_NAME && cacheName !== RUNTIME_CACHE;
          })
          .map((cacheName) => {
            console.log('[Service Worker] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          })
      );
    })
    .then(() => self.clients.claim())
  );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }

  // Skip cross-origin requests (except same-origin)
  if (url.origin !== location.origin) {
    return;
  }

  // Skip API requests (they should always go to network
  if (url.pathname.startsWith('/app/api/')) {
    return;
  }

  // For CSS/JS files with query strings (version busting), always fetch from network
  if (url.search && (url.pathname.endsWith('.css') || url.pathname.endsWith('.js'))) {
    event.respondWith(
      fetch(request).catch(() => {
        // Fallback to cache if network fails
        return caches.match(request);
      })
    );
    return;
  }

  event.respondWith(
    caches.match(request)
      .then((cachedResponse) => {
        // Return cached version if available
        if (cachedResponse) {
          return cachedResponse;
        }

        // Otherwise fetch from network
        return fetch(request)
          .then((response) => {
            // Don't cache non-successful responses
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

            // Clone the response
            const responseToCache = response.clone();

            // Cache the response
            caches.open(RUNTIME_CACHE)
              .then((cache) => {
                cache.put(request, responseToCache);
              });

            return response;
          })
          .catch(() => {
            // If network fails and it's a navigation request, return offline page
            if (request.mode === 'navigate') {
              return caches.match('/index.php') || caches.match('/');
            }
          });
      })
  );
});

// Handle background sync (optional)
self.addEventListener('sync', (event) => {
  console.log('[Service Worker] Background sync:', event.tag);
  // Implement background sync logic here if needed
});

// Handle push notifications (optional)
self.addEventListener('push', (event) => {
  console.log('[Service Worker] Push notification received');
  // Implement push notification logic here if needed
});

