import { togglePostCopyLink } from "../post/copy_link";
import { initReactionJs } from "../post/reactions";
import { addPaginationListener } from "../search/infinite_scroll";
import { toggleBanConfirmationTriggerButtons } from "./block";
import { togglePromoteButtons } from "./promote";

const memberCards = document.getElementById("member-cards");

const currentUrl = window.location.href;
const group_id = currentUrl.split("/")[4];
const lastSlashIndex = currentUrl.lastIndexOf('/');

if (currentUrl.slice(lastSlashIndex + 1) === 'members') {
    addPaginationListener(`/api/group/${group_id}/members`, memberCards, "?", (entityCard) => {
        togglePromoteButtons(entityCard.querySelectorAll(".promote-group-member-confirmation-trigger-btn"));
        toggleBanConfirmationTriggerButtons(entityCard.querySelectorAll(".ban-confirmation-trigger"));
    }).then(() => { }).catch((e) => console.error(e));
} else {
    addPaginationListener(`/api/group/${group_id}/memberCards`, memberCards, "?", (entityCard) => {
        initReactionJs(entityCard);
        togglePostCopyLink(entityCard.querySelectorAll(".post-copy-link-btn"));

    }).then(() => { }).catch((e) => console.error(e));
}
