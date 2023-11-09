import { searchUsers } from "./search";

const searchInput = document.getElementById("search-user-admin");
const searchResults = document.getElementById("admin-search-user-results");

const originalDeleteUserForms = document.querySelectorAll(".delete-user-form");

for (const deleteUserForm of originalDeleteUserForms) {
    deleteUserForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        fetch(`/users/${deleteUserForm.parentElement.querySelector("h1").textContent.trim()}`, {
            headers: {
                'X-CSRF-Token': `${deleteUserForm.elements["_token"].value}`
            },
            method: "DELETE"
        }).then((res) => {
            deleteUserForm.parentElement.remove();
        }).catch((e) => {
            console.error(e);
        });
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
