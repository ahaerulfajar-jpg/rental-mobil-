const CACHE_NAME = "Simpati-Trans-v1";

const urlsToCache = [
"/",
"/offline.html",
"/js/app.js"
];

self.addEventListener("install", event => {

event.waitUntil(

caches.open(CACHE_NAME).then(cache => {

return Promise.all(

urlsToCache.map(url => {

return fetch(url)
.then(response => {

if(response.ok){
return cache.put(url, response);
}

})

.catch(err => console.log("Cache gagal:", url));

})

);

})

);

});



self.addEventListener("fetch", event => {

event.respondWith(

caches.match(event.request).then(response => {

return response || fetch(event.request)
.catch(() => caches.match("/offline.html"));

})

);

});