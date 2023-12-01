import { configureConfirmationForm, populateModalText } from "../components/confirmation_modal";

console.log("Hello");

const confirmationModal = document.getElementById("confirmation-modal");

export function deletePostAction(deleteConfirmationTriggerButton) {
    const postId = deleteConfirmationTriggerButton.parentElement.parentElement.getAttribute("post-id");

    populateModalText(`
            <div class="flex flex-col align-middle">
                <p>Are you sure you want to delete this post?</p>
            </div>
        `);
    configureConfirmationForm(`/post/${postId}`, "DELETE", "delete_post", "bg-red-500", "text-red-500");
    confirmationModal.classList.remove("hidden");
}

const deletePostButton = document.querySelector(".delete-post-button");

if(deletePostButton) {
    deletePostButton.addEventListener("click", (e) => {
        e.preventDefault();
        deletePostAction(deletePostButton);
    });
}
