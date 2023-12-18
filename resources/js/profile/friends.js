import { addSnackbar } from "../components/snackbar";

window.addEventListener("DOMContentLoaded", (event) => {
    event.preventDefault();
    const form = document.getElementById("friendForm");
    if (form) {
        const button = form.querySelector("button");
        let methodField = form.attributes["data-method"];
        const username = document.getElementById("username").textContent;
        button.addEventListener("click", (e) => {
            e.preventDefault();
            fetch(form.action, {
                method: methodField.value,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => {
                    if (response.ok) {
                        // Update the button text based on the action
                        if (methodField.value.toUpperCase() === "POST") {
                            button.textContent = "Cancel Friend Request";
                            methodField.value = "DELETE";
                        } else if (
                            methodField.value.toUpperCase() === "DELETE"
                        ) {
                            if (button.textContent.includes("Remove Friend")) {
                                const friendsLink = document.getElementById("friends-link");
                                const friendsCount = parseInt(friendsLink.textContent, 10);
                                const span = document.createElement("span");
                                span.className = friendsLink.className;
                                span.textContent = friendsCount - 1 + " friends";
                                friendsLink.parentNode.replaceChild(span, friendsLink);
                            }
                            button.textContent = "Send Friend Request";
                            methodField.value = "POST";
                            form.action = `/api/users/${username}/friends/requests`;
                        }
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        });
    }
});
