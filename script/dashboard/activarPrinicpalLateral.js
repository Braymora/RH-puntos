document.addEventListener("DOMContentLoaded", function() {
    const headerLogoButton = document.querySelector(".headerLogoActive");
    const header = document.querySelector(".header");
    const nav = document.querySelector(".nav");
    const main = document.querySelector(".main");
    const mainDates = document.querySelector(".main-dates");
    const mainTable = document.querySelector(".main_table");
    const headerLogoActive = document.querySelector(".headerLogoActive");
    
    let navOpen = false; // Variable para controlar el estado del nav
    
    headerLogoButton.addEventListener("click", function() {
        if (navOpen) {
            // Si el nav está abierto, lo volvemos a su posición original
            header.style.width = "100%"; // Puedes cambiar esto a su valor original
            header.style.left = "300px";

            main.style.width = "calc(100% - 300px)";
            main.style.left = "300px";

            mainDates.style.width = "100%";
            mainDates.style.left = "0px";
            
            mainTable.style.width = "100%";
            mainTable.style.left = "300px";

            headerLogoActive.style.transform = 'rotate(360deg)';

            nav.style.left = "0";
        } else {
            // Si el nav está cerrado, lo movemos fuera de la pantalla
            header.style.width = "100%";
            header.style.left = "0";

            main.style.width = "100%";
            main.style.left = "0";

            mainDates.style.width = "100%";
            mainDates.style.left = "0";
            
            mainTable.style.width = "100%";
            mainTable.style.left = "0";
            
            headerLogoActive.style.transform = 'rotate(180deg)';

            nav.style.left = "-200%";
        }
        
        navOpen = !navOpen; // Cambiamos el estado del nav
    });
});
