export function getCsrfToken() {
    const metaTag = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    return metaTag;
}

export function getCurrentSearchQuery() {
    const searchTag = document.querySelector('meta[name="search"]');
    return searchTag.getAttribute("content");
}

export function getUsername() {
    const username = document.querySelector("meta[name='username']").getAttribute("content");

    return username;
}

export function addCounter(counter, sum) {
    const currentValue = parseInt(counter.textContent, 10);
    counter.textContent = (currentValue + sum) > 0 ? currentValue + sum : 0;
}

export function subtractCounter(counter, sub, zeroLimit) {
    const currentValue = parseInt(counter.textContent, 10);
    counter.textContent = (currentValue - sub) > 0 ? currentValue - sub : 0;
}
