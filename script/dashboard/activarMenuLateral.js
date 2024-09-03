
/*===============SCRIPT MENU GESTION==============*/
document.addEventListener("DOMContentLoaded", function () {
  const opcionesGestion = document.querySelector(".opcionesGestion");
  const navListItemsListGestion = document.querySelector(".navList-itemsListGestion");

  let menuOpenSubmenuGestion = false;

  opcionesGestion.addEventListener("click", function () {
    if (menuOpenSubmenuGestion) {
      navListItemsListGestion.style.display = "none";
    } else {
      navListItemsListGestion.style.display = "block";
    }
    menuOpenSubmenuGestion = !menuOpenSubmenuGestion;
  })

});


/*===============SCRIPT MENU OPCIONES==============*/

document.addEventListener("DOMContentLoaded", function () {
  const opcionesLink = document.querySelector(".opciones");
  const navListItemsList = document.querySelector(".navList-itemsList");

  let menuOpenSubmenu = false;

  opcionesLink.addEventListener("click", function () {
    if (menuOpenSubmenu) {
      navListItemsList.style.display = "none";
    } else {
      navListItemsList.style.display = "block";
    }
    menuOpenSubmenu = !menuOpenSubmenu;
  })

});