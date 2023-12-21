import { addPaginationListener } from "../search/infinite_scroll";


const currentUrl = window.location.href;
const username = currentUrl.split("/")[4];
const lastSlashIndex = currentUrl.lastIndexOf('/');

if (currentUrl.slice(lastSlashIndex + 1) === 'requests') {
    const requests = document.getElementById("friend-requests");
    addPaginationListener(`/api/users/${username}/friends/requestcards`, requests, "?", () => {
    }).then(() => { }).catch((e) => console.error(e));
} else {
    const friends = document.getElementById("friends");
    addPaginationListener(`/api/users/${username}/friends`, friends, "?", () => {
    }).then(() => { }).catch((e) => console.error(e));
}
