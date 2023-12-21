let page = 2;

export function resetPageCounter() {
    page = 2;
}

export async function addPaginationListener(url, content, separator, normalizationCallback) {
    let enough = false;

    let scrolls = 0;

    window.addEventListener('scroll', () => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 900) {
            scrolls += 1;
            if (scrolls == 1 && !enough) {
                fetch(`${url}${separator}page=${page}`).then(async (res) => {
                    if (res.ok) {
                        const entities = await res.json();

                        if (entities.length === 0) {
                            enough = true;
                        }

                        for (const entityCard of entities) {
                            const entity = document.createElement("div");
                            entity.innerHTML = entityCard;

                            content.appendChild(entity);

                            if (normalizationCallback) {
                                normalizationCallback(content.lastChild);
                            }
                        }

                        page += 1;
                        scrolls = 0;
                        enough = false;
                    }
                }).catch((err) => {
                    console.error(err);
                });
            }
        }
    });
}
