window.addEventListener("DOMContentLoaded", (event) => {
    const form = document.getElementById("groupForm");
    if (form) {
        const button = form.querySelector("button");
        let methodField = form.attributes["data-method"];
        const username = document.getElementById("username").textContent;
        const routePattern = '/group/';
        const groupID = url.substring(routePattern.length);
        button.addEventListener("click", (e) => {
            e.preventDefault();
            HTMLFormControlsCollection.log(button);
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
                        if (methodField.value.toUpperCase() === "POST") {
                            button.textContent = "Cancel Friend Request";
                            methodField.value = "DELETE";
                        } else if (
                            methodField.value.toUpperCase() === "DELETE"
                        ) {
                            button.textContent = "Add Friend";
                            methodField.value = "POST";
                            form.action = `/group/${groupID}/leave`;
                        }
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        });
    }
});