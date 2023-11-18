const hamburgerMenu = document.getElementById("hamburger-toggle");
const menuContent = document.getElementById("navbar-menu");

const hamburgerIcon = "fa-bars";
const closeIcon = "fa-x";

if (hamburgerMenu !== null) {
    hamburgerMenu.addEventListener("click", (e) => {
        if (menuContent.classList.contains("hidden")) {
            menuContent.classList.remove("hidden");
            hamburgerMenu.classList.remove(hamburgerIcon);
            hamburgerMenu.classList.add(closeIcon);
        } else {
            menuContent.classList.add("hidden")
            hamburgerMenu.classList.add(hamburgerIcon);
            hamburgerMenu.classList.remove(closeIcon);
        }
    });
}
