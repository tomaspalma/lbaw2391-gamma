export async function searchUsers(query, searchPreviewContent) {
    fetch(`/api/search/users/${query}`, {
        method: "GET",
    }).then(async (res) => {

        const users = await res.json();
        console.log("Users: ", users);

        if (users.length == 0) {
            searchPreviewContent.innerHTML = getNoneFoundText("users");
        } else {
            searchPreviewContent.innerHTML = "";
            for (const user of users) {
                searchPreviewContent.innerHTML += `
                <article class="my-4 p-2 border-b flex align-middle space-x-2">
                    <img 
                        class="rounded-full w-10 h-10"
                        src="https://upload.wikimedia.org/wikipedia/commons/a/af/Tux.png" alt="Profile Picture"
                    >
                    <h1>
                        <a href="/user/${user.username}" class="underline">
                            ${user.username}
                        </a>
                    </h1>
                </article>`;
                // searchPreviewContent.innerHTML += `<h1>${user.username}</h1>`
            }
        }
    }).catch((err) => {

    });

}

export async function searchPosts(query, searchPreviewContent) {
    fetch(`/api/search/posts/${query}`, {
        method: "GET",
    }).then(async (res) => {
        const posts = await res.json();

        console.log("Posts: ", posts);

        if (posts.length == 0) {
            searchPreviewContent.innerHTML = getNoneFoundText("posts");
        } else {
            searchPreviewContent.innerHTML = ``;
            for (const post of posts) {
                searchPreviewContent.innerHTML += `
                    <article class="post-card border border-black rounded-md my-4 p-2 cursor-pointer">
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
                                <a href="/post/${post.title}"class="hover:underline">${post.title}</a>
                            </h1>
                        </header>
                        <p class="my-4">
                            ${post.content}
                        </p>
                    </article>`;
            }
        }
    }).catch((err) => {

    });
}

export async function searchGroups(query, searchPreviewContent) {
    fetch(`/api/search/groups/${query}`, {
        method: "GET",
    }).then(async (res) => {

        const groups = await res.json();
        console.log("Groups are: ", groups);

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
    }).catch((err) => {

    });
}

function getNoneFoundText(entity) {
    return `<p class="text-center">No ${entity} found.</p>`
}

export async function getSearchResults(type, query, searchPreviewContent) {
    type = type.split("-").slice(2).join("-");

    const actions = {
        "users-preview-results": searchUsers,
        "posts-preview-results": searchPosts,
        "groups-preview-results": searchGroups,
    };

    actions[type](query, searchPreviewContent);
}
