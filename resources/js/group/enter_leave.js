window.addEventListener("DOMContentLoaded", (event) => {
    const form = document.getElementById("groupForm");
    if (form) {
        const button = form.querySelector("button");
        let methodField = form.attributes["data-method"];
        const routePattern = '/group/';
        const groupID = window.location.pathname.substring(routePattern.length);
        const number_members = document.getElementById("n_members")


        button.addEventListener("click", (e) => {
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
                if (response.status === 200) {
                    return response.json();
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .then((data) => {

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

                number_members.attributes["data_n_members"].value = (parseInt(number_members.attributes["data_n_members"].value, 10) + parseInt(data['sum'], 10)).toString()
                number_members.textContent = number_members.attributes["data_n_members"].value + " members"
                e.preventDefault();
            })
            .catch((error) => {
                console.error('There was a problem with the fetch operation:', error);
            });
        });
    }
});
