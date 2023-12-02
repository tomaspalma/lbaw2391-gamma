window.addEventListener("DOMContentLoaded", (event) => {
    const form = document.getElementById("friendForm");
    const button = form.querySelector("button");
    let methodField = form.attributes["data-method"];

    button.addEventListener("click", (e) => {
        e.preventDefault();
        console.log(form.action);
        console.log(methodField.value);
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
                        methodField.value = "delete";
                        form.action = form.action.replace(
                            /friends\/?$/,
                            "friends/requests"
                        );
                    } else {
                        button.textContent = "Add Friend";
                        methodField.value = "post";
                        form.action = form.action.replace(
                            /friends\/requests\/?$/,
                            "friends"
                        );
                    }
                }
            })
            .catch((error) => {
                console.log(error);
            });
    });
});
