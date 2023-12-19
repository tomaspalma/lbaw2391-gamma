import { addPaginationListener } from "../search/infinite_scroll";

const friends = document.getElementById("friends");

const currentUrl = window.location.href;
const username = currentUrl.split("/")[4];
const lastSlashIndex = currentUrl.lastIndexOf('/');

if (currentUrl.slice(lastSlashIndex + 1) === 'requests') {
    addPaginationListener(`/api/users/${username}/friends`, friends, "?", () => {
    }).then(() => { }).catch((e) => console.error(e));
} else {
    addPaginationListener(`/api/users/${username}/friends`, friends, "?", () => {
    }).then(() => { }).catch((e) => console.error(e));
}
