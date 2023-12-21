import { handleForm } from "../group/group_requests";
import { addPaginationListener } from "../search/infinite_scroll";
import { addCounter } from "../utils";

const content = document.getElementById("content");

const currentUrl = window.location.href;
const group_id = currentUrl.split("/")[4];
const lastSlashIndex = currentUrl.lastIndexOf('/');

if (currentUrl.slice(lastSlashIndex + 1) === 'requests') {
    const requestsCounter = document.getElementById("group-requests-counter");
    addPaginationListener(`/api/groups/requestscards`, content, "?", (entityCard) => {
        addCounter(requestsCounter, 1);
        handleForm(
            entityCard,
            entityCard.querySelector(".accept_form"),
            entityCard.querySelector(".remove_form")
        );
    }).then(() => { }).catch((e) => console.error(e));
} else {
    const groupCounter = document.getElementById("group-counter");
    addPaginationListener(`/api/groups_cards`, content, "?", (entityCard) => {
        addCounter(groupCounter, 1);
    }).then(() => { }).catch((e) => console.error(e));
}
