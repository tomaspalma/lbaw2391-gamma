import { initReactionJs } from "../post/reactions";
import { addPaginationListener } from "../search/infinite_scroll";

const url = window.location.href;
const username = url.split("/")[4].split("?")[0];
const filter = "";

console.log(url.split("/"));

console.log("hello");

await addPaginationListener(
    `/api/users/${username}/posts/${filter}`,
    document.getElementById("posts"),
    '?',
    (entityCard) => {
        initReactionJs(entityCard);
        const card = document.createElement("div");
        card.innerHTML = entityCard;
        togglePostCopyLink(card.querySelectorAll(".post-copy-link-btn"));
    }
);
