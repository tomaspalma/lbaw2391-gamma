import { configureConfirmationForm, populateModalText } from "../../components/confirmation_modal";

const confirmationModal = document.getElementById("confirmation-modal");

const unblockConfirmationTriggerButtons = document.querySelectorAll(".unblock-confirmation-trigger");
for (const unblockConfirmationTriggerButton of unblockConfirmationTriggerButtons) {
    unblockConfirmationTriggerButton.addEventListener("click", (e) => {
        e.preventDefault();

        unblockUserAction(unblockConfirmationTriggerButton);
    });
}

export function unblockUserAction(unblockConfirmationTriggerButton) {
    const username = unblockConfirmationTriggerButton.parentElement.parentElement.getAttribute("data-username");
    const profileImage = unblockConfirmationTriggerButton.parentElement.parentElement.getAttribute("data-user-image");

    populateModalText(`
            <div class="flex flex-col align-middle">
                <img class="center rounded-full w-10 h-10" src="${profileImage}" />
                <p>Are you sure you want to unblock ${username}?</p>
            </div>
        `);
    configureConfirmationForm(`/users/${username}/unblock`, "POST", "unblock_user", "bg-blue-500", "text-blue-500");
    confirmationModal.classList.remove("hidden");
}
