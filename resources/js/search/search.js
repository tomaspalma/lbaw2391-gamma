
export async function searchUsers(query, searchPreviewContent) {
    fetch(`/api/search/users/${query}`, {
        method: "GET",
    }).then(async (res) => {

        const users = await res.json();

        if (users.length == 0) {
            searchPreviewContent.innerHTML = getNoneFoundText("users");
        } else {
            searchPreviewContent.innerHTML = "";
            for (const user of users) {
                searchPreviewContent.innerHTML += `<h1>${user.username}</h1>`;
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

        if (posts.length == 0) {
            searchPreviewContent.innerHTML = getNoneFoundText("posts");
        } else {
            searchPreviewContent.innerHTML = "";
            for (const post of posts) {
                searchPreviewContent.innerHTML += `<h1>${post.title}</h1>`;
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
    console.log("This ran with type: ", type, " and query is: ", query);

    type = type.split("-").slice(2).join("-");

    const actions = {
        "users-preview-results": searchUsers,
        "posts-preview-results": searchPosts,
        "groups-preview-results": searchGroups,
    };

    console.log("Passed query will be: ", query);
    actions[type](query, searchPreviewContent);
}
