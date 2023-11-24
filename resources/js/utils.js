export function getCsrfToken() {
    const metaTag = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    return metaTag;
}
