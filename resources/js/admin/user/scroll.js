import { addPaginationListener } from "../../search/infinite_scroll";
import { toggleBlockTriggerButtons } from "./block";
import { toggleDeleteConfirmationButtons } from "./delete";
import { toggleDeleteUserAppealButtons } from "./remove_appeal";
import { toggleUnblockConfirmationButtons } from "./unblock";

const currentUrl = window.location.href;
const lastSlashIndex = currentUrl.lastIndexOf('/');

if (currentUrl.slice(lastSlashIndex + 1) === 'user') {
    const users = document.getElementById("admin-search-user-results");
    addPaginationListener("/api/admin/user", users, "?", (entityCard) => {
        toggleBlockTriggerButtons(entityCard.querySelectorAll(".block-reason-trigger"));
        toggleUnblockConfirmationButtons(entityCard.querySelectorAll(".unblock-confirmation-trigger"));
        toggleDeleteConfirmationButtons(entityCard.querySelectorAll(".delete-confirmation-trigger"));
    }).then(() => { }).catch((e) => console.error(e));
} else if (currentUrl.slice(lastSlashIndex + 1) === 'appeals') {
    const appeals = document.getElementById("content");
    addPaginationListener("/api/admin/user/appeals", appeals, "?", (entityCard) => {
        toggleAppbanAppealReasonDropdown(entityCard.querySelectorAll(".appban-dropdown-arrow"));
        toggleDeleteUserAppealButtons(entityCard.querySelectorAll(".remove-confirmation-trigger"));
    }).then(() => { }).catch((e) => console.error(e));
}
