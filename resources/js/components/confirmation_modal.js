import { addSnackbar } from "../components/snackbar";

const leaveModalButtons = document.querySelectorAll(
    ".close-confirmation-modal"
);
const modal = document.getElementById("confirmation-modal");
const confirmationMessage = document.getElementById(
    "confirmation-modal-delete-message"
);
const confirmationForm = document.getElementById("confirmation-form");

const confirmButton = document.getElementById("action-confirmation-modal");
const infoIcon = document.getElementById("info-icon");

if (leaveModalButtons) {
    for (const leaveModalButton of leaveModalButtons) {
        leaveModalButton.addEventListener("click", () => {
            modal.classList.add("hidden");
            restoreConfirmationForm();
        });
    }
}

const callbackTypesAction = {
    logout: () => {
        window.location.href = "/";
    },
    ban_group_member: (form) => {
        console.log("Blocked!");
        const username = form.action.split("/")[6];

        const userCard = document.querySelector(
            `article[data-username="${username}"]`
        );

        userCard.remove();
        form.remove();

        addSnackbar(`You removed ${username} from the group!`, 2000);

    },
    promote_group_member: (form) => {
        const username = form.action.split("/")[6];
        const userCard = document.querySelector(
            `article[data-username="${username}"]`
        );

        userCard.querySelector(".normal-user-actions").classList.add("hidden");

        const groupIndicator = document.createElement("span");
        groupIndicator.classList.add("group-status-text");
        groupIndicator.textContent = "Owner";

        userCard.querySelector(".display-name").appendChild(groupIndicator);

        addSnackbar(`You promoted ${username}!`, 2000);
    },
    remove_appeal: (form) => {
        const username = form.action.split("/")[5];
        const userCard = document.querySelector(
            `article[data-username="${username}"]`
        );

        const content = document.getElementById("content");
        const appealCounter = document.getElementById("appeal-counter");
        if (parseInt(appealCounter.textContent, 10) === 1) {
            appealCounter.textContent = "0";
            content.innerHTML = `<p id="no-appeals-found-text" class="text-center">No appeals found.</p>`
        }

        userCard.remove();

        addSnackbar(`You removed the appeal from ${username}!`, 2000);
    },
    delete_user: (confirmationForm) => {
        const username = confirmationForm.action.split("/")[4];
        const userCard = document.querySelector(
            `article[data-username="${username}"]`
        );

        userCard.remove();

        addSnackbar(`You deleted ${username}!`, 2000);
    },
    unblock_user: (confirmationForm) => {
        const username = confirmationForm.action.split("/")[4];
        const userCard = document.querySelector(
            `article[data-username="${username}"]`
        );

        const unblockButton = userCard.querySelector(
            ".unblock-confirmation-trigger"
        );
        const blockButton = userCard.querySelector(".block-reason-trigger");

        unblockButton.setAttribute("hidden", true);
        blockButton.removeAttribute("hidden");

        addSnackbar(`You unblocked ${username}!`, 2000);
    },
    delete_post: (confirmationForm) => {
        window.location.href = window.location.origin + "/feed";
    },
    block_user: (form) => {
        const username = form.action.split("/")[4];

        const userCard = document.querySelector(
            `article[data-username="${username}"]`
        );

        const unblockButton = userCard.querySelector(
            ".unblock-confirmation-trigger"
        );
        const blockButton = userCard.querySelector(".block-reason-trigger");

        unblockButton.removeAttribute("hidden");
        blockButton.setAttribute("hidden", true);

        form.remove();

        addSnackbar(`You blocked ${username}!`, 2000);
    },
    delete_self: (confirmationForm) => {
        fetch("/logout", {
            headers: {
                "X-CSRF-Token": `${confirmationForm.elements["_token"].value}`,
            },
            method: "POST",
        })
            .then((res) => {
                if (res.ok) {
                    window.location.href = window.location.origin + "/feed";
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    },
    delete_user_profile: (confirmationForm) => {
        window.location.href = window.location.origin + "/feed";
    },
};

if (confirmationForm) {
    confirmationForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        e.stopPropagation();

        fetch(confirmationForm.action, {
            headers: {
                "X-CSRF-Token": `${confirmationForm.elements["_token"].value}`,
            },
            method: `${confirmationForm.getAttribute("data-method")}`,
        })
            .then((res) => {
                if (res.ok) {
                    modal.classList.add("hidden");

                    callbackTypesAction[
                        confirmationForm.getAttribute("data-callback-type")
                    ](confirmationForm);
                }
            })
            .catch((e) => {
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
    "text-red-500",
];

function clearColorConfiguration() {
    for (const color of colorsUsedInModal) {
        confirmButton.classList.remove(color);
        infoIcon.classList.remove(color);
    }
}

function restoreColorConfiguration() {
    for (const color of colorsUsedInModal) {
        confirmButton.classList.remove(color);
        infoIcon.classList.remove(color);
    }
}

export function restoreConfirmationForm() {
    restoreColorConfiguration();

    confirmationForm.classList.remove("hidden");
}

export function configureConfirmationForm(
    action,
    method,
    callbackType,
    confirmColor,
    iconColor
) {
    clearColorConfiguration();

    confirmationForm.action = action;
    confirmationForm.setAttribute("data-method", method);
    confirmationForm.setAttribute("data-callback-type", callbackType);

    confirmButton.classList.add(confirmColor);

    infoIcon.classList.add(iconColor);
}

export function overrideConfirmationForm(
    form,
    action,
    requestParams,
    callbackType,
    thenCallback
) {
    clearColorConfiguration();

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData();

        formData.append("reason", form.elements["reason"].value);
        requestParams.body = formData;

        fetch(action, requestParams)
            .then(async (res) => {
                if (res.ok) {
                    modal.classList.add("hidden");
                    confirmationForm.classList.remove("hidden");

                    callbackTypesAction[callbackType](form);

                    confirmationForm.classList.remove("hidden");
                    restoreColorConfiguration();
                } else {
                    await thenCallback(res);
                }

            })
            .catch((e) => {
                console.error(e);
            });
    });

    confirmationForm.classList.add("hidden");
}
