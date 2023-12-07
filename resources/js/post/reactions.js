import { getCsrfToken } from "../utils";

function reactionAlreadyPresent(entityId, reactionType, entityType) {
    const article = document.querySelector(`[data-entity-id="${entityId}"][data-entity="${entityType}"]`);
    console.log("articlefsdffdfsf3r4ttk9: ", article)
    const reactionsList = article.querySelector(".reactions-list");

    for (const alreadyPresentReaction of reactionsList.children) {
        if (alreadyPresentReaction.classList.contains(`${entityId}-${reactionType}`)) {

            return alreadyPresentReaction;
        }
    }

    return null;
}

const reactionIconColor = {
    "LIKE": {
        "icon": "fa-thumbs-up",
        "color": "text-blue-700"
    },
    "DISLIKE": {
        "icon": "fa-thumbs-down",
        "color": "text-red-500"
    },
    "HEART": {
        "icon": "fa-heart",
        "color": "text-purple-700"
    },
    "STAR": {
        "icon": "fa-star",
        "color": "text-yellow-700"
    }
}

function spawnReaction(entityId, reactionType, entityType) {
    const article = document.querySelector(`[data-entity-id="${entityId}"][data-entity="${entityType}"]`);
    console.log("fhusdfhsjdf: ", article);
    const reactionsList = article.querySelector(".reactions-list");

    const {
        icon,
        color
    } = reactionIconColor[reactionType];

    const newReaction = document.createElement("div");
    newReaction.classList.add(`${entityId}-${reactionType}`);
    newReaction.innerHTML = `
            <i class="fa-solid ${icon} ${color}"></i>
            <span class="reaction-count">1</span>
    `;

    reactionsList.append(newReaction);
}

async function add_reaction(reaction, reactionType, id, entityType) {
    fetch(`/${entityType}/${id}/reaction`, {
        method: 'POST',
        headers: {
            'X-CSRF-Token': getCsrfToken(),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(
            {
                "type": `${reactionType}`
            }
        )
    }).then((res) => {
        if (res.ok) {
            const presentReaction = reactionAlreadyPresent(id, reactionType, entityType);

            if (presentReaction) {
                const counter = presentReaction.querySelector(".reaction-count");

                const currentCounter = parseInt(counter.textContent, 10);
                counter.textContent = `${(currentCounter + 1)}`;
            } else {
                spawnReaction(id, reactionType, entityType);
            }

            reaction.classList.add("highlighted", `${reactionType.toLowerCase()}-highlighted`);
            reaction.classList.remove(`${reactionType.toLowerCase()}-nonhighlighted`);
        }

    }).catch((err) => {
        console.error(err);
    });
}

async function remove_reaction(reaction, reactionType, id, entityType) {
    fetch(`/${entityType}/${id}/reaction`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-Token': getCsrfToken()
        },
        body: JSON.stringify(
            {
                "type": `${reactionType}`,
            }
        )
    }).then((res) => {
        if (res.ok) {
            console.log(reaction.classList);
            reaction.classList.remove("highlighted");
            reaction.classList.remove(`${reactionType.toLowerCase()}-highlighted`);
            reaction.classList.add(`${reactionType.toLowerCase()}-nonhighlighted`);

            const article = document.querySelector(`[data-entity-id="${id}"][data-entity="${entityType}"]`);
            console.log("article: ", article);
            const reactionListItem = article.querySelector(`[class="${id}-${reactionType}"]`);
            const counter = reactionListItem.querySelector(".reaction-count");
            const currentCounterValue = parseInt(counter.textContent, 10);
            if (currentCounterValue > 1) {
                counter.textContent = `${currentCounterValue - 1}`;
            } else {
                reactionListItem.remove();
            }
        }
    }).catch((err) => {
        console.error(err);
    });
}

async function interactReaction(reaction) {
    const reactionType = reaction.getAttribute("data-reaction-type");
    const id = reaction.getAttribute("data-entity-id");
    const entityType = reaction.getAttribute("data-entity");

    const highlighted = reaction.classList.contains("highlighted");

    if (highlighted) {
        await remove_reaction(reaction, reactionType, id, entityType);
    } else {
        await add_reaction(reaction, reactionType, id, entityType);
    }
}

export function initReactionJs(entityCard) {
    const parent = entityCard ? entityCard : document;

    const reactionPopupMenuToggles = parent.querySelectorAll(".toggle-reaction-popup");
    for (const reactionPopupMenuToggle of reactionPopupMenuToggles) {
        const reactionPopupMenu = reactionPopupMenuToggle.querySelector(".other-reactions-popup-menu");

        reactionPopupMenuToggle.addEventListener("click", function() {
            reactionPopupMenu.classList.contains("hidden")
                ? reactionPopupMenu.classList.remove("hidden")
                : reactionPopupMenu.classList.add("hidden");
        });
    }


    const reactions = parent.querySelectorAll(".reaction");
    for (const reaction of reactions) {
        reaction.addEventListener("click", async () => {
            await interactReaction(reaction);
        });
    }
}

initReactionJs();
