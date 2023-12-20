import {
    configureConfirmationForm,
    populateModalText,
} from "../components/confirmation_modal";

export function deleteCommentAction(deleteConfirmationTriggerButton) {
    const confirmationModal = document.getElementById("confirmation-modal");

    const commentId =
        deleteConfirmationTriggerButton.target.getAttribute("comment-id");

    populateModalText(`
        <div class="flex flex-col align-middle">
            <p>Are you sure you want to delete this comment?</p>
        </div>
    `);
    configureConfirmationForm(
        `/comment/${commentId}`,
        "DELETE",
        "delete_comment",
        "bg-red-500",
        "text-red-500"
    );
    confirmationModal.classList.remove("hidden");
}
