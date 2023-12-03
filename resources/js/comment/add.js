import { initReactionJs } from '../post/reactions.js';
import { deleteComment } from './delete.js';

function addComment() {
    let form = document.getElementById('comment-form');
    let formData = new FormData(form);

    // block if comment is empty
    if (formData.get('content') == '') {
        return;
    }

    fetch('/comment', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData,
    })
        .then((response) => {
            if (response.ok) {
                // add comment to the page
                response.json()
                    .then((commentCard) => {
                        const comments = document.getElementById("comments");
                        const comment = document.createElement("div");
                        comment.innerHTML = commentCard;
                        comments.prepend(comment);

                        // if there is no comment yet remove the no comment message
                        if (document.getElementById('no-comment')) {
                            document.getElementById('no-comment').remove();
                        }

                        // clear comment form
                        document.getElementById('comment-form').reset();

                        initReactionJs();
                    })

            }
        })
        .catch((error) => {
            console.log(error);
        });
}

const commentButton = document.getElementById('comment-button').addEventListener('click', addComment);
if (commentButton) {
    commentButton.addEventListener('click', addComment);
}
