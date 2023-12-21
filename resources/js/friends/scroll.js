import { addPaginationListener } from "../search/infinite_scroll";
import { toggleFriendRequestButtons } from "../friends/requests";

const currentUrl = window.location.href;
const username = currentUrl.split("/")[4];
const lastSlashIndex = currentUrl.lastIndexOf('/');

if (currentUrl.slice(lastSlashIndex + 1) === 'requests') {
    const requests = document.getElementById("friend-requests");
    addPaginationListener(`/api/users/${username}/friends/requestcards`, requests, "?", (entityCard) => {
        toggleFriendRequestButtons(entityCard.querySelectorAll(".friendRequestForm"));
    }).then(() => { }).catch((e) => console.error(e));
} else {
    const friends = document.getElementById("friends");
    addPaginationListener(`/api/users/${username}/friends`, friends, "?", () => {
    }).then(() => { }).catch((e) => console.error(e));
}
