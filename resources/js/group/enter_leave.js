window.addEventListener("DOMContentLoaded", (event) => {
    const form = document.getElementById("groupForm");
    if (form) {
        const button = form.querySelector("button");
        let methodField = form.attributes["data-method"];
        const routePattern = '/group/';
        const groupID = window.location.pathname.substring(routePattern.length);

        console.log(methodField.value)

        button.addEventListener("click", (e) => {
            console.log("Button Clicked")
            e.preventDefault()

            fetch(form.action, {
                method: methodField.value,
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
            .then((response) => {
                console.log(response.status)
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
                const new_action = data['new_action']
                
                if (methodField.value.toUpperCase() === "POST") {
                    methodField.value = "DELETE";
                    form.action = `/group/${groupID}/${new_action}`
                } else if (methodField.value.toUpperCase() === "DELETE") {
                    methodField.value = "POST";
                    form.action = `/group/${groupID}/${new_action}`
                }
                e.preventDefault();
            })
            .catch((error) => {
                console.error('There was a problem with the fetch operation:', error);
            });
        });
    }
});
