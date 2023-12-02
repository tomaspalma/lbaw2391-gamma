console.log("Hello");

export function toggleDropdownArrow(arrow, elementToShow) {
    arrow.addEventListener("click", function() {
        if (elementToShow.classList.contains("hidden")) {
            elementToShow.classList.remove("hidden");

            arrow.classList.remove("fa-angle-down");
            arrow.classList.add("fa-angle-up");
        } else {
            elementToShow.classList.add("hidden");

            arrow.classList.remove("fa-angle-up");
            arrow.classList.add("fa-angle-down");
        }
    });
}
