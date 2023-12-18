window.addEventListener("DOMContentLoaded", (event) => {

    const cards = document.getElementsByClassName("request")

    for (const card of cards) {
        const acceptForm = card.getElementsByClassName('accept_form')[0]
        const removeForm = card.getElementsByClassName('remove_form')[0]
        handleForm(card, acceptForm, removeForm);
    }
});

function handleForm(card, acceptForm, removeForm) {

    let acceptButton = acceptForm.getElementsByClassName("accept")[0]
    let removeButton = removeForm.getElementsByClassName("remove")[0]

    acceptButton.addEventListener("click", (e) => {
        e.preventDefault();

        handleButton(card, acceptButton, removeButton, acceptForm, true)
    });

    removeButton.addEventListener("click", (e) => {
        e.preventDefault();

        handleButton(card, acceptButton, removeButton, removeForm, false)
    });
}

function handleButton(card, ButtonAccept, ButtonRemove, form, isAccept) {
        let methodField = form.attributes["data-method"];

        fetch(form.action, {
            method: methodField.value,
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
        .then((response) => {
            console.log(response.status);
            if (response.status === 200) {
                return response.json();
            } else {
                throw new Error('Network response was not ok');
            }
        })
        .then((data) => {
            ButtonAccept.remove();
            ButtonRemove.remove()
            if(isAccept){
                console.log("entered here")
                const successMessage = document.createElement('p');
                successMessage.textContent = 'Added to group successfully';
                successMessage.classList.add('text-green-500', 'font-bold', 'mt-2');
                form.appendChild(successMessage);
            }
            else{
                const successMessage = document.createElement('p');
                successMessage.textContent = 'Deleted to group successfully';
                successMessage.classList.add('text-red-500', 'font-bold', 'mt-2');
                form.appendChild(successMessage);
            }
            e.preventDefault();
        })

        .catch((error) => {
            console.error('There was a problem with the fetch operation:', error);
        });
}