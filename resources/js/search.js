let currentSearchPreview = "users-preview-results";
const searchPreviewContent = document.getElementById("search-preview-content");

async function searchUsers(query) {
    fetch(`/api/search/users/${query}`, {
        method: "GET",
    }).then(async (res) => {

        const users = await res.json();

        searchPreviewContent.innerHTML = "";
        for (const user of users) {
            searchPreviewContent.innerHTML += `<h1>${user.username}</h1>`;
        }
    }).catch((err) => {

    });

}
async function searchPosts(query) {
    fetch(`/api/search/posts/${query}`, {
        method: "GET",
    }).then(async (res) => {

        const groups = await res.json();

        searchPreviewContent.innerHTML = "";
        for (const group of groups) {
            searchPreviewContent.innerHTML += ``;
        }
    }).catch((err) => {

    });
}

async function searchGroups(query) {
    fetch(`/api/search/groups/${query}`, {
        method: "GET",
    }).then(async (res) => {

        const groups = await res.json();

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
    }).catch((err) => {

    });
}

async function getSearchResults(type, query) {
    console.log("This ran with type: ", type, " and query is: ", query);

    const actions = {
        "users-preview-results": searchUsers,
        "posts-preview-results": searchPosts,
        "groups-preview-results": searchGroups,
    };

    console.log("Passed query will be: ", query);
    actions[type](query);
}

function showSearchPreview(searchPreviewResults, searchInput) {
    searchPreviewResults.style.display = 'block';

    const borderType = "border-t-4";
    const borderColor = "border-black";

    const previewOptions = searchPreviewResults.querySelectorAll(".preview-results-option");

    getSearchResults(currentSearchPreview, searchInput.value);

    for (const previewOption of previewOptions) {
        previewOption.addEventListener("click", (e) => {
            e.stopPropagation();

            previewOptions.forEach(previewOption => {
                previewOption.classList.remove(borderType, borderColor);
            });

            if (!previewOption.classList.contains(borderType) && !previewOption.classList.contains(borderColor)) {
                previewOption.classList.add(borderType, borderColor);

                getSearchResults(previewOption.id, searchInput.value);
            }

            currentSearchPreview = previewOption.id;
        });
    }
}

function addNavbarSearchListener() {
    const search = document.getElementById("search-navbar");
    const searchPreviewResults = document.getElementById("search-preview-results");

    search.addEventListener("input", (e) => {
        const value = e.target.value;

        if (value.trim() === '') {
            searchPreviewResults.style.display = 'none';
        } else {
            console.log("Search is: ", search);
            showSearchPreview(searchPreviewResults, search);
        }
    });

    // search.addEventListener("blur", (e) => {
    //     searchPreviewResults.style.display = 'none';
    // });

    search.addEventListener("focus", (e) => {
        if (e.target.value.trim() !== '' && searchPreviewResults.style.display === '') {
            searchPreviewResults.style.display = 'block';
            showSearchPreview(searchPreviewResults);
        }
    });

    const searchForm = document.getElementById("search-form");

    searchForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const query = searchForm["search"].value;
        window.location.replace(`/search/${query}`);
    });
}

addNavbarSearchListener();
