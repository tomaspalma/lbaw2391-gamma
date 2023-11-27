import { getSearchResults } from "./search";

const searchMenuName = 'main-search';

const toggledParameter = {
    "users": "users-preview-results",
    "posts": "posts-preview-results",
    "groups": "groups-preview-results",
};

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const toggled = urlParams.get('toggled');

let currentSearchPreview = `${searchMenuName}-${toggledParameter[toggled] ?? "users-preview-results"}`;

const searchPreviewContent = document.getElementById(`${searchMenuName}-search-preview-content`);

const searchPreviewResults = document.getElementById(`${searchMenuName}-search-results`);

function showSearchPreview(searchPreviewResults, searchValue) {
    const borderType = "border-t-4";
    const borderColor = "border-black";

    const previewOptions = searchPreviewResults.querySelectorAll(".preview-results-option");

    for (const previewOption of previewOptions) {
        previewOption.addEventListener("click", (e) => {
            e.stopPropagation();

            previewOptions.forEach(previewOption => {
                previewOption.classList.remove(borderType, borderColor);
            });

            if (!previewOption.classList.contains(borderType) && !previewOption.classList.contains(borderColor)) {
                previewOption.classList.add(borderType, borderColor);

                window.location.replace("/");
            }

            currentSearchPreview = previewOption.id;
        });
    }
}

const searchValue = document.querySelector('meta[name="search"]').getAttribute("content");
showSearchPreview(searchPreviewResults, searchValue);
