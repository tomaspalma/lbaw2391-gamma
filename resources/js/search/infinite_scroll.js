export async function addPaginationListener(url, content, separator, normalizationCallback) {
    let page = 2;
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
                            content.innerHTML += entityCard;
                        }

                        if (normalizationCallback) {
                            normalizationCallback();
                        }

                        page += 1;
                        scrolls = 0;
                    }
                }).catch((err) => {
                    console.error(err);
                });
            }
        }
    });
}
