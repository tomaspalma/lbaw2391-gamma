const dropdownButtons = document.getElementsByClassName("dropdownButton");
const dropdownContents = document.getElementsByClassName("dropdownContent");

for(let i = 0; i < dropdownButtons.length; i++) {
    let dropdownButton = dropdownButtons[i];
    let dropdownContent = dropdownContents[i];
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
