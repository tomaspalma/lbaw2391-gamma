import { toggleDropdownArrow } from "../../components/dropdown";

function toggleAppbanAppealReasonDropdown(dropdowns) {

}

const dropdownArrows = document.querySelectorAll(".appban-dropdown-arrow");
for (const dropdownArrow of dropdownArrows) {
    const parentArticle = dropdownArrow.parentElement.parentElement.parentElement;
    toggleDropdownArrow(dropdownArrow, parentArticle.querySelector(".appban-appeal-reason"));
}

toggleAppbanAppealReasonDropdown();
