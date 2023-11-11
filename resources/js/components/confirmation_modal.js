const leaveModalButton = document.getElementById("close-confirmation-modal");
const modal = document.getElementById("confirmation-modal");
const confirmationMessage = document.getElementById("confirmation-modal-delete-message");
const confirmationForm = document.getElementById("confirmation-form");

if (leaveModalButton) {
    leaveModalButton.addEventListener("click", () => {
        modal.classList.add("hidden");
    });
}

const callbackTypesAction = {
    "delete_user": (confirmationForm) => {
        const username = confirmationForm.action.split("/")[4];
        const userCard = document.querySelector(`article[data-username="${username}"]`);

        userCard.remove();
    }
};

if (confirmationForm) {
    confirmationForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        e.stopPropagation();

        console.log("submtited");

        fetch(confirmationForm.action, {
            headers: {
                'X-CSRF-Token': `${confirmationForm.elements["_token"].value}`,
            },
            method: `${confirmationForm.getAttribute("data-method")}`
        }).then((res) => {
            modal.classList.add("hidden");

            callbackTypesAction[confirmationForm.getAttribute("data-callback-type")](confirmationForm);
        }).catch((e) => {
            console.error(e);
        });
    });
}

export function populateModalText(messageContent) {
    confirmationMessage.innerHTML = messageContent;
}

export function configureConfirmationForm(action, method, callbackType) {
    confirmationForm.action = action;
    confirmationForm.setAttribute("data-method", method);
    confirmationForm.setAttribute("data-callback-type", callbackType);
}
