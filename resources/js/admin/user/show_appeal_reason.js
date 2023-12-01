import { toggleDropdownArrow } from "../../components/dropdown";

export function toggleAppbanAppealReasonDropdown(dropdownArrows) {
    for (const dropdownArrow of dropdownArrows) {
        const parentArticle = dropdownArrow.parentElement.parentElement.parentElement;
        toggleDropdownArrow(dropdownArrow, parentArticle.querySelector(".appban-appeal-reason"));
    }
}

const dropdownArrows = document.querySelectorAll(".appban-dropdown-arrow");
toggleAppbanAppealReasonDropdown(dropdownArrows);
