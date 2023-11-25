import { deleteUserAction } from "../admin/user/delete";
import { unblockUserAction } from "../admin/user/unblock";

const csrfMeta = document.querySelector("meta[name='csrf-token']");

export function createPostCard(post) {
    return `<article class="post-card border border-black rounded-md my-4 p-2 cursor-pointer">
        <div class="flex align-middle justify-between space-x-4">
            <div class="flex space-x-4">
                <img src="${post.author.image}" class="rounded-full w-10 h-10">
                    <a class="hover:underline" href="/user/${post.author.username}">
                        ${post.author.username}
                    </a>
            </div>
            <span>
                <time>${post.date.split(" ")[0]}</time>
            </span>
        </div>
        <header class="my-4">
            <h1 class="text-2xl">
                <a href="/post/${post.id}" class="hover:underline">${post.title}</a>
            </h1>
        </header>
        <p class="my-4">
            ${post.content}
        </p>
    </article> `;

}

export async function searchUsers(query, searchPreviewContent, admin_page) {
    const finalQuery = (query) ? `/${query}` : '';
    const url = (admin_page) ? `/api/admin/search/users${finalQuery}` : `/api/search/users${finalQuery}`;

    fetch(url, {
        method: "GET",
    }).then(async (res) => {

        if (res.ok) {

            const users = await res.json();

            if (users.length == 0) {
                searchPreviewContent.innerHTML = getNoneFoundText("users");
            } else {
                searchPreviewContent.innerHTML = "";
                for (const user of users) {
                    searchPreviewContent.innerHTML += `
        <article data-user-image="${user.image}" data-username="${user.username}" class="my-4 p-2 border-b flex justify-between align-middle space-x-2">
            <div class="flex flex-row space-x-2 align-middle">
                <img class="rounded-full w-10 h-10" src="${user.image}" alt="Profile Picture">
                    <h1>
                        <a href="/users/${user.username}" class="underline">
                            ${user.username}
                        </a>
                    </h1>
            </div>
    ${admin_page ?
                            `<div class="order-3 space-x-8">
        <button>
            <a target="_blank" href="/users/${user.username}/edit">Edit</a>
        </button>
        <button class="block-reason-trigger" ${user.is_app_banned ? 'hidden' : ''}>
            Block
        </button>
        <button class="unblock-confirmation-trigger" ${user.is_app_banned ? '' : 'hidden'} >
            Unblock
        </button>
        <button class="delete-confirmation-trigger">
            Delete
        </button>
    </div>` : ''
                        }
</article> `;

                }

                console.log(searchPreviewContent.innerHTML);
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

export async function searchPosts(query, searchPreviewContent) {
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
                searchPreviewContent.innerHTML = ``;
                for (const post of posts) {
                    searchPreviewContent.innerHTML += createPostCard(post);
                }
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

            const groups = await res.json();

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

export async function getSearchResults(type, query, searchPreviewContent) {
    console.log("Before type is: ", type);
    type = type.split("-").slice(2).join("-");

    console.log(type);

    const actions = {
        "users-preview-results": searchUsers,
        "posts-preview-results": searchPosts,
        "groups-preview-results": searchGroups,
    };

    actions[type](query, searchPreviewContent, false);
}
