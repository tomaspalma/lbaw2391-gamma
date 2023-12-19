import { toggleDropdownArrow } from "./components/dropdown";

let arrows = document.querySelectorAll(".faq-dropdown-arrow");
let elementsToShow = document.querySelectorAll(".elementToShow");
if (arrows && elementsToShow)
    for (let i = 0; i < arrows.length; i++) {
        toggleDropdownArrow(arrows[i], elementsToShow[i]);
    }
