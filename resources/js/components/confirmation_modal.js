const leaveModalButtons = document.querySelectorAll(".close-confirmation-modal");
const modal = document.getElementById("confirmation-modal");
const confirmationMessage = document.getElementById("confirmation-modal-delete-message");
const confirmationForm = document.getElementById("confirmation-form");

const confirmButton = document.getElementById("action-confirmation-modal");
const infoIcon = document.getElementById("info-icon");

if (leaveModalButtons) {
    for (const leaveModalButton of leaveModalButtons) {
        leaveModalButton.addEventListener("click", () => {
            modal.classList.add("hidden");
        });
    }
}

const callbackTypesAction = {
    "delete_user": (confirmationForm) => {
        const username = confirmationForm.action.split("/")[4];
        const userCard = document.querySelector(`article[data-username="${username}"]`);

        userCard.remove();
    },
    "unblock_user": (confirmationForm) => {
        const username = confirmationForm.action.split("/")[4];
        const userCard = document.querySelector(`article[data-username="${username}"]`);

        const unblockButton = userCard.querySelector(".unblock-confirmation-trigger");
        const blockButton = userCard.querySelector(".block-reason-trigger");

        unblockButton.setAttribute('hidden', true);
        blockButton.removeAttribute('hidden');
    },
    "delete_post": (confirmationForm) => {
        window.location.href = window.location.origin + "/feed";
    },
    "block_user": (form) => {
        const username = form.action.split("/")[4];
        const userCard = document.querySelector(`article[data-username="${username}"]`);

        const unblockButton = userCard.querySelector(".unblock-confirmation-trigger");
        const blockButton = userCard.querySelector(".block-reason-trigger");

        unblockButton.removeAttribute('hidden');
        blockButton.setAttribute('hidden', true);
    }
};

if (confirmationForm) {
    confirmationForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        e.stopPropagation();

        fetch(confirmationForm.action, {
            headers: {
                'X-CSRF-Token': `${confirmationForm.elements["_token"].value}`,
            },
            method: `${confirmationForm.getAttribute("data-method")}`
        }).then((res) => {
            if (res.ok) {
                modal.classList.add("hidden");

                callbackTypesAction[confirmationForm.getAttribute("data-callback-type")](confirmationForm);
            }
        }).catch((e) => {
            console.error(e);
        });
    });
}

export function populateModalText(messageContent) {
    confirmationMessage.innerHTML = messageContent;
}

const colorsUsedInModal = [
    "bg-blue-500",
    "bg-red-500",
    "text-blue-500",
    "text-red-500"
];

function clearColorConfiguration() {
    for (const color of colorsUsedInModal) {
        confirmButton.classList.remove(color);
        infoIcon.classList.remove(color);
    }
}

export function configureConfirmationForm(action, method, callbackType, confirmColor, iconColor) {
    clearColorConfiguration();

    confirmationForm.action = action;
    confirmationForm.setAttribute("data-method", method);
    confirmationForm.setAttribute("data-callback-type", callbackType);

    confirmButton.classList.add(confirmColor);

    infoIcon.classList.add(iconColor);
}

export function overrideConfirmationForm(form, action, requestParams, callbackType) {
    clearColorConfiguration();

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append("reason", form.elements["reason"].value);
        requestParams.body = formData;

        fetch(action, requestParams).then((res) => {
            if (res.ok) {
                modal.classList.add("hidden");
                confirmationForm.classList.remove("hidden");

                callbackTypesAction[callbackType](confirmationForm);
            }
        }).catch((e) => {
            console.error(e);
        });
    });

    confirmationForm.classList.add("hidden");
}
