// public/service-worker.js

self.addEventListener('install', (event) => {
  console.log('Service worker installing...');
  // You can add caching logic here for offline capabilities
});

self.addEventListener('fetch', (event) => {
  // This is a basic fetch event listener.
  // For a full offline experience, you would add logic to serve cached assets.
  event.respondWith(fetch(event.request));
});
