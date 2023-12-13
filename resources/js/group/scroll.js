import { togglePostCopyLink } from "../post/copy_link";
import { initReactionJs } from "../post/reactions";
import { addPaginationListener } from "../search/infinite_scroll";

const posts = document.getElementById("posts");

const currentUrl = window.location.href;
const group_id = currentUrl.split("/")[4];
const lastSlashIndex = currentUrl.lastIndexOf('/');

if (currentUrl.slice(lastSlashIndex + 1) === 'members') {
    // addPaginationListener(`/api/group/${group_id}/members`, posts, "?", (entityCard) => {
    //     initReactionJs(entityCard);
    // }).then(() => { }).catch((e) => console.error(e));
} else {
    addPaginationListener(`/api/group/${group_id}/posts`, posts, "?", (entityCard) => {
        initReactionJs(entityCard);
        togglePostCopyLink(entityCard.querySelectorAll(".post-copy-link-btn"));

    }).then(() => { }).catch((e) => console.error(e));
}
