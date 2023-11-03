import { getSearchResults } from "./search";

const searchMenuName = 'search-input';

let currentSearchPreview = `${searchMenuName}-users-preview-results`;

const searchPreviewContent = document.getElementById(`${searchMenuName}-search-preview-content`);

function showSearchPreview(searchPreviewResults, searchInput) {
    searchPreviewResults.style.display = 'block';

    const borderType = "border-t-4";
    const borderColor = "border-black";

    const previewOptions = searchPreviewResults.querySelectorAll(".preview-results-option");

    getSearchResults(currentSearchPreview, searchInput.value, searchPreviewContent);

    for (const previewOption of previewOptions) {
        previewOption.addEventListener("click", (e) => {
            e.stopPropagation();

            previewOptions.forEach(previewOption => {
                previewOption.classList.remove(borderType, borderColor);
            });

            if (!previewOption.classList.contains(borderType) && !previewOption.classList.contains(borderColor)) {
                previewOption.classList.add(borderType, borderColor);

                getSearchResults(previewOption.id, searchInput.value, searchPreviewContent);
            }

            currentSearchPreview = previewOption.id;
        });
    }
}

function addNavbarSearchListener() {
    const search = document.getElementById("search-navbar");
    const searchPreviewResults = document.getElementById(`${searchMenuName}-search-results`);

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
            showSearchPreview(searchPreviewResults, e);
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

