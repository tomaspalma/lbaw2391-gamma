import { addPaginationListener } from "../../search/infinite_scroll";

const currentUrl = window.location.href;
const lastSlashIndex = currentUrl.lastIndexOf('/');

if (currentUrl.slice(lastSlashIndex + 1) === 'user') {
    const users = document.getElementById("admin-search-user-results");
    addPaginationListener("/api/admin/user", users, "?", (entityCard) => {
    }).then(() => { }).catch((e) => console.error(e));
} else if (currentUrl.slice(lastSlashIndex + 1) === 'appeals') {
    const appeals = document.getElementById("content");
    addPaginationListener("/api/admin/user/appeals", content, "?", (entityCard) => {
    }).then(() => { }).catch((e) => console.error(e));
}
