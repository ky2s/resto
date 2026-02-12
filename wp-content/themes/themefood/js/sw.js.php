<?php
header('Service-Worker-Allowed: /');
header('Content-Type: application/javascript');
?>
importScripts("https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js");

workbox.routing.registerRoute(
    /\.(?:css|css.php|js|woff|woff2|ttf|otf|json)$/,
    new workbox.strategies.StaleWhileRevalidate({
        "cacheName": "assets",
        plugins: [
            new workbox.expiration.Plugin({
                maxEntries: 1000,
                maxAgeSeconds: 60 * 60 * 24 * 30,
				purgeOnQuotaError: true
            })
        ]
    })
);

workbox.routing.registerRoute(
    /\.(?:png|jpg|jpeg|gif|bmp|webp|svg|ico)$/,
    new workbox.strategies.CacheFirst({
        "cacheName": "images",
        plugins: [
            new workbox.expiration.Plugin({
                maxEntries: 1000,
                maxAgeSeconds: 60 * 60 * 24 * 30,
				purgeOnQuotaError: true
            })
        ]
    })
);

workbox.routing.registerRoute(
  new RegExp('^https://cdn.statically.io/.*|.*b-cdn.net/.*'),
  new workbox.strategies.CacheFirst({
	"cacheName": "CDN",
    plugins: [
      new workbox.cacheableResponse.Plugin({
        statuses: [0, 200]
      }),
	  new workbox.expiration.Plugin({
			maxEntries: 1000,
			maxAgeSeconds: 60 * 60 * 24 * 30,
			purgeOnQuotaError: true
	  })
    ]
  }),
);

workbox.routing.registerRoute(
    /wp-json/,
    new workbox.strategies.NetworkFirst({
        "cacheName": "data",
        plugins: [
            new workbox.expiration.Plugin({
                maxEntries: 1000,
                maxAgeSeconds: 60 * 60 * 24
            })
        ]
    })
);