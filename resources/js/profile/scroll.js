import { initReactionJs } from "../post/reactions";
import { addPaginationListener } from "../search/infinite_scroll";
import { togglePostCopyLink } from "../post/copy_link";

const url = window.location.href;
const username = url.split("/")[4].split("?")[0];
const filter = "";

console.log(url.split("/"));

console.log("hello");

addPaginationListener(
    `/api/users/${username}/posts/${filter}`,
    document.getElementById("posts"),
    '?',
    (entityCard) => {
        initReactionJs(entityCard);
        togglePostCopyLink(entityCard.querySelectorAll(".post-copy-link-btn"));
    }
).then(() => { }).catch((e) => console.error(e));
