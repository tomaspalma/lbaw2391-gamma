window.addEventListener("DOMContentLoaded", (event) => {
    const form = document.getElementById("groupForm");


    if (form) {
        const button = form.querySelector("button");
        let methodField = form.attributes["data-method"];
        const routePattern = '/group/';
        const groupID = window.location.pathname.substring(routePattern.length);

        console.log(methodField.value)

        button.addEventListener("click", (e) => {
            console.log("button clicked")
            e.preventDefault()

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
                console.log(response)
                if (response.status === 200) {
                    return response.json();
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .then((data) => {
                console.log(data)

                button.classList = data['new_color']
                button.textContent = data['new_text']
                if (methodField.value.toUpperCase() === "POST") {
                    methodField.value = "DELETE";
                    form.action = `/group/${groupID}/leave`
                } else if (methodField.value.toUpperCase() === "DELETE") {
                    methodField.value = "POST";
                    form.action = `/group/${groupID}/enter`
                }
                e.preventDefault();
            })
            .catch((error) => {
                console.error('There was a problem with the fetch operation:', error);
            });
        });
    }
});
