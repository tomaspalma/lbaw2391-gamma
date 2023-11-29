import { searchUsers } from "./search";

const searchInput = document.getElementById("search-user-admin");
const searchResults = document.getElementById("admin-search-user-results");

searchInput.addEventListener("input", function(e) {
    searchUsers(e.target.value, searchResults, null, true);

    const deleteUserForms = document.querySelectorAll(".delete-user-form");
    for (const deleteUserForm of deleteUserForms) {
        deleteUserForm.addEventListener("submit", function(e) {
            e.preventDefault();


            deleteUserForm.querySelector("button").addEventListener("click", function(e) {
                e.preventDefault();
            });
        });
    }
});
