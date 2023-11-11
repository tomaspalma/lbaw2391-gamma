import { configureConfirmationForm, populateModalText } from "../../components/confirmation_modal";

const confirmationModal = document.getElementById("confirmation-modal");

export function blockUserAction(blockConfirmationTriggerButton) {
    const username = blockConfirmationTriggerButton.parentElement.parentElement.getAttribute("data-username");
    const profileImage = blockConfirmationTriggerButton.parentElement.parentElement.getAttribute("data-user-image");

    populateModalText(`
            <div class="flex flex-col align-middle">
                <img class="center rounded-full w-10 h-10" src="${profileImage}" />
                
            </div>
        `);
    configureConfirmationForm(`/users/${username}`, "DELETE", "delete_user");
    confirmationModal.classList.remove("hidden");
}
