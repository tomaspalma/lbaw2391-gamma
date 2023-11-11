import { deleteUserAction } from "../admin/user/delete";
import { searchUsers } from "./search";

const searchInput = document.getElementById("search-user-admin");
const searchResults = document.getElementById("admin-search-user-results");

// const originalDeleteUserForms = document.querySelectorAll(".delete-user-form");

const deleteConfirmationTriggerButtons = document.querySelectorAll(".delete-confirmation-trigger");


for (const deleteConfirmationTriggerButton of deleteConfirmationTriggerButtons) {
    deleteConfirmationTriggerButton.addEventListener("click", (e) => {
        e.preventDefault();

        deleteUserAction(deleteConfirmationTriggerButton);
    });
}

searchInput.addEventListener("input", function(e) {
    searchUsers(e.target.value, searchResults, true);

    const deleteUserForms = document.querySelectorAll(".delete-user-form");
    for (const deleteUserForm of deleteUserForms) {
        // deleteUserForm.removeEventListener("submit", deleteUser);

        console.log(deleteUserForm);
        console.log(deleteUserForm.querySelector("button"));

        deleteUserForm.addEventListener("submit", function(e) {
            e.preventDefault();


            deleteUserForm.querySelector("button").addEventListener("click", function(e) {
                e.preventDefault();
            });

            console.log("submitted");
        });
    }
});
