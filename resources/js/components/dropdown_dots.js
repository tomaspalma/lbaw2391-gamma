const dropdownButtons = document.querySelectorAll(".dropdownButton");
const dropdownContents = document.querySelectorAll(".dropdownContent");

console.log("buttons: ", dropdownButtons);
console.log("contents: ", dropdownContents);

export function toggleDropdownButtons(dropdownButtons, dropdownContents) {
    for (let i = 0; i < dropdownButtons.length; i++) {
        const dropdownButton = dropdownButtons[i];
        const dropdownContent = dropdownContents[i];
        dropdownButton.addEventListener("click", function() {
            const display = dropdownContent.style.display;

            if (display == "none") {
                dropdownContent.style.display = "block";
            } else {
                dropdownContent.style.display = "none";
            }
        });

        document.addEventListener("click", function(event) {
            const isClickInside =
                dropdownButton.contains(event.target) ||
                dropdownContent.contains(event.target);

            if (!isClickInside) {
                dropdownContent.style.display = "none";
            }
        });
    }
}

toggleDropdownButtons(dropdownButtons, dropdownContents);



