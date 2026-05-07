/**
 * WebApp Service Worker
 * Caching strategy for offline support
 */

const CACHE_NAME = 'webapp-v1';
const urlsToCache = [
  '/',
  '/build/css/app.min.css',
  '/build/js/app.min.js',
  '/build/images/logo.svg',
  '/offline.html'
];

// Install event
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// Activate event
self.addEventListener('activate', (event) => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Fetch event - Cache First then Network
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        // Cache hit - return response
        if (response) {
          return response;
        }
        
        // Clone the request
        const fetchRequest = event.request.clone();
        
        return fetch(fetchRequest).then((response) => {
          // Check if valid response
          if (!response || response.status !== 200 || response.type !== 'basic') {
            return response;
          }
          
          // Clone the response
          const responseToCache = response.clone();
          
          caches.open(CACHE_NAME)
            .then((cache) => {
              cache.put(event.request, responseToCache);
            });
          
          return response;
        });
      })
      .catch(() => {
        // Offline fallback
        if (event.request.mode === 'navigate') {
          return caches.match('/offline.html');
        }
      })
  );
});

// Background sync
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-data') {
    event.waitUntil(syncData());
  }
});

// Push notification
self.addEventListener('push', (event) => {
  const options = {
    body: event.data.text(),
    icon: '/build/images/icons/icon-192x192.png',
    badge: '/build/images/icons/badge.png',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'explore',
        title: 'Xem chi tiết',
        icon: '/build/images/icons/checkmark.png'
      },
      {
        action: 'close',
        title: 'Đóng',
        icon: '/build/images/icons/close.png'
      }
    ]
  };
  
  event.waitUntil(
    self.registration.showNotification('WebApp Notification', options)
  );
});

// Notification click
self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  
  if (event.action === 'explore') {
    event.waitUntil(
      clients.openWindow('/dashboard')
    );
  }
});

// Helper function to sync data
async function syncData() {
  // Sync offline data to server
  const db = await openDB();
  const offlineData = await getOfflineData(db);
  
  for (const data of offlineData) {
    try {
      await fetch('/api/sync', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
          'Content-Type': 'application/json'
        }
      });
      await deleteOfflineData(db, data.id);
    } catch (error) {
      console.error('Sync failed:', error);
    }
  }
}