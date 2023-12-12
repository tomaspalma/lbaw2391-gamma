import { configureConfirmationForm, populateModalText } from "../components/confirmation_modal";
import { getCsrfToken } from "../utils";

const promoteButtons = document.querySelectorAll(".promote-group-member-confirmation-trigger-btn");

const confirmationModal = document.getElementById("confirmation-modal");

export function togglePromoteButtons(promoteButtons) {
    for (const promoteButton of promoteButtons) {
        promoteButton.addEventListener("click", () => {

            const group_id = promoteButton.getAttribute("data-group-id");
            const username = promoteButton.getAttribute("data-username");

            populateModalText(`
            <div class="flex flex-col align-middle">
                <p>Are you sure you want to promote ${username} to group owner?</p>
            </div>
        `);
            configureConfirmationForm(`/group/${group_id}/members/${username}/promote`, "POST", "promote_group_member", "bg-blue-500", "text-blue-500");
            confirmationModal.classList.remove("hidden");
        });
    }
}

togglePromoteButtons(promoteButtons);
