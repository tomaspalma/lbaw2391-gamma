import { initReactionJs } from "../post/reactions";
import { addPaginationListener } from "../search/infinite_scroll";
import { getUsername } from "../utils";

const url = window.location.href;
const post_id = url.split("/")[4];

addPaginationListener(
    `/api/post/${post_id}/comments/`,
    document.getElementById("comments"),
    '?',
    (entityCard) => {
        initReactionJs(entityCard);
        const card = document.createElement("div");
        card.innerHTML = entityCard;
        togglePostCopyLink(card.querySelectorAll(".post-copy-link-btn"));
    }
).then(() => { }).catch((e) => console.error(e));
