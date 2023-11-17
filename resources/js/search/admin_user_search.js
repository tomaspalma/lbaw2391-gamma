import { deleteUserAction } from "../admin/user/delete";
import { unblockUserAction } from "../admin/user/unblock";
import { searchUsers } from "./search";

const searchInput = document.getElementById("search-user-admin");
const searchResults = document.getElementById("admin-search-user-results");

searchInput.addEventListener("input", function(e) {
    searchUsers(e.target.value, searchResults, true);

    const deleteUserForms = document.querySelectorAll(".delete-user-form");
    for (const deleteUserForm of deleteUserForms) {
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
