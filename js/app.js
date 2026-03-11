// ==============================
// SIAP PESAN Global App Script
// ==============================

// indikator online / offline
window.addEventListener("online", function(){

console.log("Koneksi Internet Aktif");

showToast("Koneksi internet kembali");

});

window.addEventListener("offline", function(){

console.log("Koneksi Internet Terputus");

showToast("Anda sedang offline");

});



// ==============================
// Loading Page Effect
// ==============================

document.addEventListener("DOMContentLoaded", function(){

document.body.classList.add("loaded");

});



// ==============================
// Simple Toast Notification
// ==============================

function showToast(message){

let toast = document.createElement("div");

toast.className = "app-toast";

toast.innerText = message;

document.body.appendChild(toast);

setTimeout(function(){

toast.remove();

},3000);

}



// ==============================
// Loading Button
// ==============================

function loadingButton(btn){

btn.disabled = true;

btn.innerHTML = "Loading...";

}



// ==============================
// AJAX Helper
// ==============================

function ajax(url, method="GET", data=null){

return fetch(url,{

method:method,
headers:{
"Content-Type":"application/json"
},
body:data ? JSON.stringify(data) : null

})

.then(res=>res.json());

}