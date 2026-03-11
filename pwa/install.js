let deferredPrompt = null;

window.addEventListener("beforeinstallprompt", (e) => {

    e.preventDefault();
    deferredPrompt = e;

    const popup = document.getElementById("installPWA");

    if(popup){
        popup.style.display = "flex";
    }

});

document.addEventListener("DOMContentLoaded", function(){

    const installBtn = document.getElementById("installBtn");

    if(installBtn){

        installBtn.addEventListener("click", async () => {

            if(!deferredPrompt){
                console.log("Install belum tersedia");
                return;
            }

            deferredPrompt.prompt();

            const { outcome } = await deferredPrompt.userChoice;

            if(outcome === "accepted"){
                console.log("User installed app");
            }

            deferredPrompt = null;

        });

    }

});

document.addEventListener("DOMContentLoaded", function(){

    const closeBtn = document.getElementById("closePWA");

    if(closeBtn){
        closeBtn.addEventListener("click", function(){
            document.getElementById("installPWA").style.display="none";
        });
    }

});