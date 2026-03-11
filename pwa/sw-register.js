if("serviceWorker" in navigator){

navigator.serviceWorker
.register("/pwa/service-worker.js")
.then(function(reg){

console.log("PWA Ready");

});

}