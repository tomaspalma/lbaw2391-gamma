import { initReactionJs } from "../post/reactions";
import { addPaginationListener } from "../search/infinite_scroll";
import { getUsername } from "../utils";

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
    }
);
