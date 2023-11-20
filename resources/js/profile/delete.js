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
  const username = urlParts[urlParts.length - 1];

  const authUser = document.getElementById("auth-user").value;
  let text;
  let type;
  if (authUser !== username) {
    text = `
    <div class="flex flex-col align-middle">
        <p>Are you sure you want to delete <a class="hover:underline" href="/users/${username}">${username}</a>?</p>
    </div>
    `;
    type = "delete_user_profile";
  } else {
    text = `
    <div class="flex flex-col align-middle">
        <p>Are you sure you want to delete your account?</p>
    </div>
    `;
    type = "delete_self";
  }
  populateModalText(text);
  configureConfirmationForm(
    `/users/${username}`,
    "DELETE",
    type,
    "bg-red-500",
    "text-red-500"
  );
  confirmationModal.classList.remove("hidden");
}
