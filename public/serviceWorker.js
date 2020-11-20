const staticDevCoffee = "dev-coffee-site-v1";
const assets = [
  "/",
  "/index.html",
  "/admin.html",
  "/interview.html",
  "/real_interview.html",
  "/test_done.html",
  "/test_no.html",
  "/test_yes.html",
  "/css/style.css",
  "/js/app.js",
  "/images/1.png",
  "/images/loading.gif",
  "/sounds/error.mp3",
  "/sounds/shutter.mp3",
];

self.addEventListener("install", installEvent => {
  installEvent.waitUntil(
    caches.open(staticDevCoffee).then(cache => {
      cache.addAll(assets);
    })
  );
});

self.addEventListener("fetch", fetchEvent => {
  fetchEvent.respondWith(
    caches.match(fetchEvent.request).then(res => {
      return res || fetch(fetchEvent.request);
    })
  );
});
