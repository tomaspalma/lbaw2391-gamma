import { configureConfirmationForm, populateModalText } from "../../components/confirmation_modal";

const confirmationModal = document.getElementById("confirmation-modal");
const deleteUserAppealTriggerButtons = document.querySelectorAll(".remove-confirmation-trigger");

for (const deleteUserAppealTriggerButton of deleteUserAppealTriggerButtons) {
    deleteUserAppealTriggerButton.addEventListener("click", (e) => {
        console.log("Clicked");
        e.preventDefault();

        deleteUserAppealAction(deleteUserAppealTriggerButton);
    });
}

export function deleteUserAppealAction(deleteUserAppealTriggerButton) {
    const username = deleteUserAppealTriggerButton.parentElement.parentElement.parentElement.getAttribute("data-username");

    populateModalText(`
            <div class="flex flex-col align-middle">
                <p class="text-center">Are you sure you want to delete this appeal</p> 
            </div>
        `);
    configureConfirmationForm(`/api/users/${username}/appeal`, "DELETE", "remove_appeal", "bg-red-500", "text-red-500");
    confirmationModal.classList.remove("hidden");
}
