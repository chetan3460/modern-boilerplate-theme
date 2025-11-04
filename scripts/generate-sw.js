/**
 * Generate Service Worker with automatic version from package.json
 * Run after build: node scripts/generate-sw.js
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Read version from package.json
const packageJson = JSON.parse(
  fs.readFileSync(path.join(__dirname, '../package.json'), 'utf8')
);
const version = packageJson.version;

console.log(`🚀 Generating service worker with version ${version}...`);

// Service worker template
const serviceWorkerContent = `/**
 * Service Worker for Resplast Theme
 * Caches Vite assets with smart cache invalidation
 * Version-based cache management
 * 
 * Version: ${version} (auto-generated from package.json)
 */

const CACHE_VERSION = '${version}';
const CACHE_NAME = \`resplast-v\${CACHE_VERSION}\`;
const ASSET_CACHE = \`\${CACHE_NAME}-assets\`;
const IMAGE_CACHE = \`\${CACHE_NAME}-images\`;
const PAGE_CACHE = \`\${CACHE_NAME}-pages\`;

// Assets to cache immediately on install
const CRITICAL_ASSETS = [
  // Will be populated dynamically on install
  // Main assets are cached on-demand during fetch events
];

// Cache strategies
const CACHE_STRATEGIES = {
  CACHE_FIRST: 'cache-first',
  NETWORK_FIRST: 'network-first',
  STALE_WHILE_REVALIDATE: 'stale-while-revalidate',
};

/**
 * Install Event - Cache critical assets
 */
self.addEventListener('install', (event) => {
  console.log('[SW] Installing service worker v${version}...');
  
  event.waitUntil(
    caches.open(ASSET_CACHE)
      .then((cache) => {
        console.log('[SW] Service worker installed - assets will be cached on-demand');
        if (CRITICAL_ASSETS.length > 0) {
          return cache.addAll(CRITICAL_ASSETS);
        }
        return Promise.resolve();
      })
      .then(() => self.skipWaiting())
  );
});

/**
 * Activate Event - Clean up old caches
 */
self.addEventListener('activate', (event) => {
  console.log('[SW] Activating service worker v${version}...');
  
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName.startsWith('resplast-') && !cacheName.includes(CACHE_VERSION)) {
            console.log('[SW] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

/**
 * Fetch Event - Intercept and serve from cache
 */
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);
  
  if (request.method !== 'GET') return;
  
  if (url.pathname.includes('/wp-admin/') || url.pathname.includes('/wp-login.php')) {
    return;
  }
  
  if (isViteAsset(url)) {
    event.respondWith(cacheFirstStrategy(request, ASSET_CACHE));
  } else if (isImage(url)) {
    event.respondWith(cacheFirstStrategy(request, IMAGE_CACHE));
  } else if (isHTMLPage(url)) {
    event.respondWith(networkFirstStrategy(request, PAGE_CACHE));
  } else {
    event.respondWith(fetch(request));
  }
});

/**
 * Cache First Strategy
 */
async function cacheFirstStrategy(request, cacheName) {
  try {
    const cache = await caches.open(cacheName);
    const cachedResponse = await cache.match(request);
    
    if (cachedResponse) {
      const fileName = request.url.split('/').pop();
      console.log('✅ [SW Cache Hit]', fileName, '(~5ms)');
      return cachedResponse;
    }
    
    console.log('📥 [SW Fetching]', request.url.split('/').pop());
    const networkResponse = await fetch(request);
    
    if (networkResponse && networkResponse.status === 200) {
      cache.put(request, networkResponse.clone());
      const fileName = request.url.split('/').pop();
      console.log('💾 [SW Cached]', fileName);
    }
    
    return networkResponse;
  } catch (error) {
    console.error('[SW] Cache First error:', error);
    return new Response('Offline - Asset not available', {
      status: 503,
      statusText: 'Service Unavailable'
    });
  }
}

/**
 * Network First Strategy
 */
async function networkFirstStrategy(request, cacheName) {
  try {
    const cache = await caches.open(cacheName);
    
    try {
      const networkResponse = await fetch(request);
      
      if (networkResponse && networkResponse.status === 200) {
        cache.put(request, networkResponse.clone());
      }
      
      return networkResponse;
    } catch (networkError) {
      console.log('[SW] Network failed, serving from cache:', request.url);
      const cachedResponse = await cache.match(request);
      
      if (cachedResponse) {
        return cachedResponse;
      }
      
      return new Response('Offline - Page not available', {
        status: 503,
        statusText: 'Service Unavailable',
        headers: { 'Content-Type': 'text/html' }
      });
    }
  } catch (error) {
    console.error('[SW] Network First error:', error);
    return new Response('Error loading page', {
      status: 500,
      statusText: 'Internal Server Error'
    });
  }
}

function isViteAsset(url) {
  return url.pathname.includes('/dist/') && 
         (url.pathname.endsWith('.js') || 
          url.pathname.endsWith('.css') ||
          url.pathname.includes('/js/') ||
          url.pathname.includes('/css/'));
}

function isImage(url) {
  return /\\.(jpg|jpeg|png|gif|webp|avif|svg)$/i.test(url.pathname);
}

function isHTMLPage(url) {
  return url.pathname.endsWith('/') || 
         url.pathname.endsWith('.html') || 
         !url.pathname.includes('.');
}

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'CLEAR_CACHE') {
    event.waitUntil(
      caches.keys().then((cacheNames) => {
        return Promise.all(
          cacheNames.map((cacheName) => {
            if (cacheName.startsWith('resplast-')) {
              console.log('[SW] Clearing cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
    );
  }
  
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
`;

// Write to assets/js/service-worker.js
const assetsPath = path.join(__dirname, '../assets/js/service-worker.js');
fs.writeFileSync(assetsPath, serviceWorkerContent);
console.log('✅ Generated assets/js/service-worker.js');

// Copy to theme root
const rootPath = path.join(__dirname, '../service-worker.js');
fs.writeFileSync(rootPath, serviceWorkerContent);
console.log('✅ Copied to service-worker.js');

console.log(`\n🎉 Service worker v${version} generated successfully!\n`);
