import { initReactionJs } from "../post/reactions";
import { addPaginationListener } from "../search/infinite_scroll";
import { getUsername } from "../utils";

const url = window.location.href;
const post_id = url.split("/")[4];

console.log(url.split("/"));
console.log("comment scroll");

await addPaginationListener(
    `/api/post/${post_id}/comments/`,
    document.getElementById("comments"),
    '?',
    (entityCard) => {
        initReactionJs(entityCard);
    }
);
