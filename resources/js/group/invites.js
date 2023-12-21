import { addSnackbar } from "../components/snackbar";
import { getCsrfToken } from "../utils";

const searchInput = document.getElementById("invite-user-search");

if (searchInput) {
    searchInput.addEventListener("input", (e) => {
        e.preventDefault();

        const id = searchInput.getAttribute("data-entity-group");
        const content = document.getElementById("invitees");

        fetch(`/api/group/${id}/invite/${e.target.value}`).then(async (res) => {
            const cards = await res.json();

            content.innerHTML = "";

            if (cards.length === 0) {
                content.innerHTML += `<p class="text-center">No users found.</p>`
            }

            for (const card of cards) {
                const div = document.createElement("div");
                div.innerHTML = card;
                toggleInviteButton(div.querySelectorAll(".invite-form"));
                content.appendChild(div);
            }

        }).catch((e) => console.error(e));
    });
}

const inviteForms = document.querySelectorAll(".invite-form");
const invitees = document.getElementById("invitees");

export function toggleInviteButton(inviteForms) {
    if(!inviteForms) {
        return;
    }

    for (const inviteForm of inviteForms) {
        inviteForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const id = inviteForm.getAttribute("data-group-id");
            const username = inviteForm.getAttribute("data-username");

            fetch(`/group/${id}/invite/${username}`, {
                method: "POST",
                headers: {
                    "X-CSRF-Token": `${getCsrfToken()}`
                }
            }).then((res) => {
                if (res.ok) {
                    document.querySelector(`[data-username="${username}"]`).remove();

                    if (invitees.children.length === 0) {
                        invitees.innerHTML = `<p class="text-center">No users found.</p>`
                    }

                    addSnackbar(`You invited ${username}`, 2000);
                }
            }).catch((e) => {
                console.error(e);
            })
        });
    }
}

toggleInviteButton(inviteForms);

const acceptInvite = document.querySelectorAll(".accept-invite-form");
const rejectInvite = document.querySelectorAll(".reject-invite-form");

export function toggleAcceptInviteForms(forms) {
    if(!forms) {
        return;
    }

    for (const form of forms) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            const username = form.getAttribute("data-username");
            const id = form.getAttribute("data-group-id");

            fetch(`/group/${id}/invite/${username}`, {
                method: "PUT",
                headers: {
                    "X-CSRF-Token": `${getCsrfToken()}`
                }
            }).then((res) => {
                form.parentElement.parentElement.querySelector(".success-invite-text").classList.remove("hidden");
                form.parentElement.classList.add("hidden");

                addSnackbar("You accepted the invite to the group", 2000);
            }).catch((e) => console.error(e));
        });
    }
}

export function toggleRejectInviteForms(forms) {
    if(!forms) {
        return;
    }

    for (const form of forms) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            const username = form.getAttribute("data-username");
            const id = form.getAttribute("data-group-id");

            fetch(`/group/${id}/invite/${username}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-Token": `${getCsrfToken()}`
                }
            }).then((res) => {
                if(res.ok) {
                    form.parentElement.parentElement.parentElement.remove();

                    addSnackbar("You rejected the invite to the group", 2000);
                }
            }).catch((e) => console.error(e));
        });
    }
}

toggleAcceptInviteForms(acceptInvite);
toggleRejectInviteForms(rejectInvite);