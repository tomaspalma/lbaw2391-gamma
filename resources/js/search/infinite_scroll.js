import { createPostCard } from "./search";

export function addPaginationListener(url, content, htmlCreateCallback) {
    let page = 2;
    let enough = false;

    let scrolls = 0;

    window.addEventListener('scroll', () => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
            scrolls += 1;
            if (scrolls == 1 && !enough) {
                fetch(`${url}?page=${page}`).then(async (res) => {
                    if (res.ok) {
                        const json = await res.json();
                        const entities = json.data;

                        if (entities.length === 0) {
                            enough = true;
                        }

                        for (const entity of entities) {
                            content.innerHTML += createPostCard(entity);
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
