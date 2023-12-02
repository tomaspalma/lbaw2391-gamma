import { initReactionJs } from "../post/reactions";
import { addPaginationListener } from "../search/infinite_scroll";
import { getUsername } from "../utils";

const username = getUsername();
const filter = "";

console.log("hello");

await addPaginationListener(
    `/api/users/${username}/posts/${filter}`,
    document.getElementById("posts"),
    '?',
    () => {
        initReactionJs();
    }
);
