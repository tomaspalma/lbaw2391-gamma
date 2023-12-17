export function toggleDropdownArrow(arrow, elementToShow) {
    arrow.addEventListener("click", function () {
        toggleElement(arrow, elementToShow);
    });
    arrow.addEventListener("keydown", function (event) {
        if (event.key === "Enter" || event.key === " ") {
            event.preventDefault();
            toggleElement(arrow, elementToShow);
        }
    });
}

function toggleElement(button, elementToShow) {
    let arrow = button.querySelector(".arrow");
    if (elementToShow.classList.contains("hidden")) {
        elementToShow.classList.remove("hidden");
        arrow.classList.remove("fa-angle-down");
        arrow.classList.add("fa-angle-up");
    } else {
        elementToShow.classList.add("hidden");
        arrow.classList.remove("fa-angle-up");
        arrow.classList.add("fa-angle-down");
    }
}
