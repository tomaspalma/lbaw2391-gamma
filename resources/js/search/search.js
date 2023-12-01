import { deleteUserAction } from "../admin/user/delete";
import { unblockUserAction } from "../admin/user/unblock";
import { initReactionJs } from "../post/reactions";

const csrfMeta = document.querySelector("meta[name='csrf-token']");

export async function createPostCard(post, preview) {
    try {
        const res = await fetch(`/api/post/${post.id}/card/${preview}`);
        const text = await res.text();
        return text;
    } catch (e) {
        console.error(e);
    }
}

export async function searchUsers(query, searchPreviewContent, preview, admin_page) {
    const finalQuery = (query) ? `/${query}` : '';
    const url = (admin_page) ? `/api/admin/search/users${finalQuery}` : `/api/search/users${finalQuery}`;

    fetch(url, {
        method: "GET",
    }).then(async (res) => {

        if (res.ok) {
            const userCards = await res.json();

            if (userCards.length == 0) {
                searchPreviewContent.innerHTML = getNoneFoundText("users");
            } else {
                searchPreviewContent.innerHTML = "";
                for (const userCard of userCards) {
                    searchPreviewContent.innerHTML += userCard;
                }

                const deleteTriggerBtns = searchPreviewContent.querySelectorAll(".delete-confirmation-trigger");
                for (const deleteTriggerBtn of deleteTriggerBtns) {
                    deleteTriggerBtn.addEventListener("click", (e) => {
                        e.preventDefault();
                        deleteUserAction(deleteTriggerBtn);
                    });
                }

                const unblockTriggerBtns = searchPreviewContent.querySelectorAll(".unblock-confirmation-trigger");
                for (const unblockTriggerBtn of unblockTriggerBtns) {
                    unblockTriggerBtn.addEventListener("click", (e) => {
                        e.preventDefault();
                        unblockUserAction(unblockTriggerBtn);
                    });
                }

            }
        }
    }).catch((err) => {

    });
}

export async function searchPosts(query, searchPreviewContent, preview) {
    const finalQuery = (query) ? `/${query}` : '';
    const url = `/api/search/posts${finalQuery}`;

    fetch(url, {
        method: "GET",
    }).then(async (res) => {

        if (res.ok) {
            const posts = await res.json();

            if (posts.length == 0) {
                searchPreviewContent.innerHTML = getNoneFoundText("posts");
            } else {
                for (const postCards of posts) {
                    searchPreviewContent.innerHTML += postCards;
                }

                initReactionJs();
            }
        }
    }).catch((err) => {

    });
}

export async function searchGroups(query, searchPreviewContent) {
    const finalQuery = (query) ? `/${query}` : '';
    const url = `/api/search/groups${finalQuery}`;

    fetch(url, {
        method: "GET",
    }).then(async (res) => {
        if (res.ok) {
            const json = await res.json();
            const groups = json.data;

            if (groups.length == 0) {
                searchPreviewContent.innerHTML = getNoneFoundText("groups");
            } else {
                searchPreviewContent.innerHTML = "";
                for (const group of groups) {
                    searchPreviewContent.innerHTML += `
        <article class="my-4 p-2 border-b">
            <h1>
                <a href="/group/${group.name}" class="underline">${group.name}</a>
            </h1>
                </article>
        `;
                }
            }
        }
    }).catch((err) => {

    });
}

function getNoneFoundText(entity) {
    return `<p class="text-center">No ${entity} found.</p> `;
}

export async function getSearchResults(type, query, searchPreviewContent, preview) {
    console.log("Before type is: ", type);
    type = type.split("-").slice(2).join("-");

    console.log(type);

    const actions = {
        "users-preview-results": searchUsers,
        "posts-preview-results": searchPosts,
        "groups-preview-results": searchGroups,
    };

    actions[type](query, searchPreviewContent, preview, false);
}
