/**
 * Service Worker untuk Simpati Trans PWA
 * Menyediakan offline functionality dan caching
 */

const CACHE_NAME = 'simpati-trans-v4';
const RUNTIME_CACHE = 'simpati-trans-runtime-v4';

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

// Install event - CACHING DISABLED: Skip caching
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Installing... (Caching disabled)');
  // Skip caching, just activate immediately
  event.waitUntil(self.skipWaiting());
});

// Activate event - CACHING DISABLED: Delete all caches
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Activating... (Clearing all caches)');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      // Delete ALL caches since caching is disabled
      return Promise.all(
        cacheNames.map((cacheName) => {
          console.log('[Service Worker] Deleting cache:', cacheName);
          return caches.delete(cacheName);
        })
      );
    })
    .then(() => self.clients.claim())
  );
});

// Fetch event - CACHING DISABLED: Always fetch from network
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

  // CACHING DISABLED: Always fetch from network, no caching
  event.respondWith(
    fetch(request).catch((error) => {
      console.log('[Service Worker] Network fetch failed (no cache fallback):', error);
      // Return error response instead of cache
      return new Response('Network error - Caching is disabled', {
        status: 408,
        statusText: 'Request Timeout'
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

