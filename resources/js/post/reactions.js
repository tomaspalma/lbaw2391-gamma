import { getCsrfToken } from "../utils";

const reactionPopupMenuToggle = document.getElementById("toggle-reaction-popup");
const reactionPopupMenu = document.getElementById("other-reactions-popup-menu");

reactionPopupMenuToggle.addEventListener("click", function() {
    reactionPopupMenu.classList.contains("hidden")
        ? reactionPopupMenu.classList.remove("hidden")
        : reactionPopupMenu.classList.add("hidden");
});

async function interactReaction(reaction) {
    const reactionType = reaction.getAttribute("data-reaction-type");
    const id = reaction.getAttribute("data-entity-id");
    fetch(`/post/${id}/reaction`, {
        method: 'POST',
        headers: {
            'X-CSRF-Token': getCsrfToken()
        },
        body: JSON.stringify(
            {
                "type": `${reactionType}`
            }
        )
    })
}

const reactions = document.querySelectorAll(".reaction");
for (const reaction of reactions) {
    reaction.addEventListener("click", async () => {
        await interactReaction(reaction);
    });
}
