const dropdownButton = document.getElementById("dropdownButton");
const dropdownContent = document.getElementById("dropdownContent");

if (dropdownButton && dropdownContent) {
    dropdownButton.addEventListener("click", function () {
        const display = dropdownContent.style.display;

        if (display == "none") {
            dropdownContent.style.display = "block";
        } else {
            dropdownContent.style.display = "none";
        }
    });

    document.addEventListener("click", function (event) {
        const isClickInside =
            dropdownButton.contains(event.target) ||
            dropdownContent.contains(event.target);

        if (!isClickInside) {
            dropdownContent.style.display = "none";
        }
    });
}
