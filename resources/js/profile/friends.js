window.addEventListener("DOMContentLoaded", (event) => {
    const form = document.getElementById("friendForm");
    const button = form.querySelector("button");

    button.addEventListener("click", (e) => {
        e.preventDefault();

        fetch(form.action, {
            method: form.method,
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
                    if (form.method.toUpperCase() === "POST") {
                        button.textContent = "Cancel Friend Request";
                        form.method = "delete";
                    } else {
                        button.textContent = "Add Friend Request";
                        form.method = "post";
                    }
                }
            })
            .catch((error) => {
                console.log(error);
            });
    });
});
