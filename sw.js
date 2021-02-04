const CACHE_NAME = 'wats-v0.0.1-alpha';
const RUNTIME = 'runtime';
const urlsToCache = [
       	'main.html',
        'manifest.webmanifest',
        'img/sm-icon.png',
        'main.css',
        'img/bg-galaxy.jpg'
];

self.addEventListener('install', (e) => {
	e.waitUntil(
		caches.open(CACHE_NAME)
    .then(cache => cache.addAll(urlsToCache))
		.then(self.skipWaiting())
	);
});

self.addEventListener('activate', (e) => {
  const currentCaches = [CACHE_NAME, RUNTIME];
	e.waitUntil(
		caches.keys().then(cacheNames => {
      return cacheNames.filter(cacheName => !currentCaches.includes(cacheName));
		}).then(cachesToDelete => {
      return Promise.all(cachesToDelete.map(cacheToDelete => {
        return caches.delete(cacheToDelete);
      }));
    }).then(() => self.clients.claim())
	);
});

self.addEventListener('fetch', (e) => {
  if(e.request.url.startsWith(self.location.origin)){
    e.respondWith(
      caches.match(e.request).then(cachedResponse => {
        if(cachedResponse){
          return cachedResponse;
        }

        return caches.open(RUNTIME).then(cache => {
          return fetch(e.request).then(response => {
            return cache.put(e.request, response.clone()).then(() => {
              return response;
            });
          });
        });
      })
    );
  }
});
