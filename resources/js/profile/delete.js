import {
  configureConfirmationForm,
  populateModalText,
} from "../components/confirmation_modal";

const confirmationModal = document.getElementById("confirmation-modal");

const deleteConfirmationTriggerButton = document.getElementById("deleteLink");

deleteConfirmationTriggerButton.addEventListener("click", (e) => {
  e.preventDefault();
  console.log("deleteUserAction");
  deleteUserAction(deleteConfirmationTriggerButton);
});

export function deleteUserAction(deleteConfirmationTriggerButton) {
  const urlParts = window.location.pathname.split("/");
  console.log(urlParts);
  const username = urlParts[urlParts.length - 1];
  console.log(username);

  populateModalText(`
            <div class="flex flex-col align-middle">
                <p>Are you sure you want to delete your account?</p>
            </div>
        `);
  configureConfirmationForm(
    `/users/${username}`,
    "DELETE",
    "delete_self",
    "bg-red-500",
    "text-red-500"
  );
  confirmationModal.classList.remove("hidden");
}
