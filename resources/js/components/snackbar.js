const snackbarContentHolder = document.getElementById("snackbar-holder");

function createSnackbarDiv(message) {
    const snackbar = document.createElement("div");
    snackbar.classList.add("snackbar");

    const snackbarMessage = document.createElement("p");
    snackbarMessage.textContent = message;

    const closeButton = document.createElement("button");
    closeButton.classList.add("close-snackbar");
    closeButton.innerHTML = '<i class="x-icon"></i>';

    closeButton.addEventListener("click", closeSnackbar);

    snackbar.appendChild(snackbarMessage);
    snackbar.appendChild(closeButton);

    return snackbar;
}

function closeSnackbar(e) {
    const button = e.target;

    const snackbar = button.parentElement.classList.contains("snackbar")
        ? button.parentElement : button.parentElement.parentElement;

    snackbar.remove();

    if (snackbarContentHolder.children.length === 0) {
        snackbarContentHolder.classList.add("hidden");
    }
}

export function addSnackbar(message, disappearTime) {

    snackbarContentHolder.classList.remove("hidden");
    const snackbar = createSnackbarDiv(message);
    snackbarContentHolder.appendChild(snackbar);

    setTimeout(() => {
        console.log("Children: ", snackbarContentHolder.children);
        if (snackbarContentHolder.children.length <= 1) {
            snackbarContentHolder.classList.add("hidden");
        }
        snackbar.remove();
    }, disappearTime);
}
