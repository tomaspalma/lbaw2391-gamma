import { getSearchResults } from "./search";

const searchMenuName = 'main-search';

let currentSearchPreview = `${searchMenuName}-users-preview-results`;

const searchPreviewContent = document.getElementById(`${searchMenuName}-search-preview-content`);

const searchPreviewResults = document.getElementById(`${searchMenuName}-search-results`);

function showSearchPreview(searchPreviewResults, searchValue) {
    // searchPreviewResults.style.display = 'block';

    const borderType = "border-t-4";
    const borderColor = "border-black";

    const previewOptions = searchPreviewResults.querySelectorAll(".preview-results-option");

    getSearchResults(currentSearchPreview, searchValue, searchPreviewContent);

    for (const previewOption of previewOptions) {
        previewOption.addEventListener("click", (e) => {
            e.stopPropagation();

            previewOptions.forEach(previewOption => {
                previewOption.classList.remove(borderType, borderColor);
            });

            if (!previewOption.classList.contains(borderType) && !previewOption.classList.contains(borderColor)) {
                previewOption.classList.add(borderType, borderColor);

                getSearchResults(previewOption.id, searchValue, searchPreviewContent);
            }

            currentSearchPreview = previewOption.id;
        });
    }
}

const searchValue = document.querySelector('meta[name="search"]').getAttribute("content");
showSearchPreview(searchPreviewResults, searchValue);
