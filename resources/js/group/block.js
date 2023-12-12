import { blockUserAction } from "../admin/user/block";

const banConfirmationTriggerButtons = document.querySelectorAll(".ban-confirmation-trigger");

for (const banConfirmationTriggerButton of banConfirmationTriggerButtons) {
    banConfirmationTriggerButton.addEventListener("click", function() {
        const group_id = banConfirmationTriggerButton.getAttribute("data-group-id");
        const username = banConfirmationTriggerButton.getAttribute("data-username");

        blockUserAction(banConfirmationTriggerButton, `/group/${group_id}/members/${username}/block`, 'POST', "ban_group_member");
    });
}
