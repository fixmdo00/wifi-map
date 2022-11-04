const staticCacheName = 'site-static';
const assets = [
    '/',
    '/index.php',
    '/js/app.js',
    '/pages/map.php',
    '/pages/speed.html',
    'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js',
    '/heat/Leaflet.heat-gh-pages/dist/leaflet-heat.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',
    'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css',
];

// tests
self.addEventListener('install', evt => {
    // console.log('service worker has been installed');
    evt.waitUntil(
        caches.open(staticCacheName).then(cache => {
            console.log('caching shell assets');
            cache.addAll(assets);
        }).catch( err => {
            console.log('gagal');
        })
    );
});

// activate service worker
self.addEventListener('activate', evt => {
    console.log('service worker has been activated');
});

self.addEventListener('fetch', evt => {
    // console.logg('fetch event', evt);
    evt.respondWith(
        caches.match(evt.request).then( cachesRes => {
            return cachesRes || fetch(evt.request);
        })
    )
});