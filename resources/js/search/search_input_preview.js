import { getSearchResults } from "./search";

const searchMenuName = 'search-input';

let currentSearchPreview = `${searchMenuName}-users-preview-results`;

const searchPreviewResults = document.getElementById(`${searchMenuName}-search-results`);
const searchPreviewContent = document.getElementById(`${searchMenuName}-search-preview-content`);

const previewOptions = searchPreviewResults.querySelectorAll(".preview-results-option");

const search = document.getElementById("search-navbar");

const borderType = "border-t-4";
const borderColor = "border-black";

function showSearchPreview(searchPreviewResults) {
    searchPreviewResults.style.display = 'block';

    getSearchResults(currentSearchPreview, search.value, searchPreviewContent);
}

for (const previewOption of previewOptions) {
    console.log("Preview option is: ", previewOption)
    previewOption.addEventListener("click", (e) => {
        e.stopPropagation();

        console.log("Clicked");

        previewOptions.forEach(previewOption => {
            previewOption.classList.remove(borderType, borderColor);
        });

        if (!previewOption.classList.contains(borderType) && !previewOption.classList.contains(borderColor)) {
            previewOption.classList.add(borderType, borderColor);

            getSearchResults(previewOption.id, search.value, searchPreviewContent);
        }

        currentSearchPreview = `${searchMenuName}-${previewOption.id}`;
    });
}

function addNavbarSearchListener() {

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
        window.location.replace(`/search/${query}?toggled=${currentSearchPreview.split("-")[4]}`);
    });
}

addNavbarSearchListener();

