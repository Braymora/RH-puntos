/******====menu configuración perfil====*******/
document.addEventListener("DOMContentLoaded", function () {
    const iconmenu = document.querySelector(".iconmenu");
    const navListItemsList1 = document.querySelector(".headerLogo-profile__lista");

    let menuOpenProfiles = false;

    iconmenu.addEventListener("click", function () {
        if (menuOpenProfiles) {
            navListItemsList1.style.display = "none";
        } else {
            navListItemsList1.style.display = "block";
        }
        menuOpenProfiles = !menuOpenProfiles;
    })

});


/******====menu hamburguesa====*******/
document.addEventListener("DOMContentLoaded", function () {
    const iconmenuMobile = document.querySelector(".headerLogoMenu");
    const nav = document.querySelector(".nav");

    let menuOpen = false; // Variable para controlar el estado del menú

    iconmenuMobile.addEventListener("click", function () {
        if (menuOpen) {
            nav.style.display = "none";
        } else {
            nav.style.display = "block";
        }

        menuOpen = !menuOpen; // Cambiamos el estado del menú
    });
});

