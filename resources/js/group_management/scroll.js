import { addPaginationListener } from "../search/infinite_scroll";
import { addCounter } from "../utils";

const content = document.getElementById("content");

const currentUrl = window.location.href;
const group_id = currentUrl.split("/")[4];
const lastSlashIndex = currentUrl.lastIndexOf('/');

if (currentUrl.slice(lastSlashIndex + 1) === 'requests') {
    addPaginationListener(`/groups_cards`, content, "?", (entityCard) => {
    }).then(() => { }).catch((e) => console.error(e));
} else {
    const groupCounter = document.getElementById("group-counter");
    addPaginationListener(`/api/groups_cards`, content, "?", (entityCard) => {
        addCounter(groupCounter, 1);
    }).then(() => { }).catch((e) => console.error(e));
}
