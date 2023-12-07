export function deleteComment(deleteButton) {
    let commentId = deleteButton.target.getAttribute('comment-id');
    fetch('/comment/' + commentId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
    .then((response) => {
        if(response.ok) {
            // delete hr between comment
            document.querySelector(`[comment-id="${commentId}"]`).parentElement.parentElement.parentElement.nextElementSibling.remove();
            // delete comment from the page
            document.querySelector(`[comment-id="${commentId}"]`).parentElement.parentElement.parentElement.remove();

            // if there is no comment left add the no comment message
            if(document.getElementById('comments').childElementCount == 0) {
                let p = document.createElement('p');
                p.setAttribute('id', 'no-comment');
                p.innerText = 'No comments yet.';
                document.getElementById('comments').appendChild(p);
            }
        }
    })
    .catch((error) => {
        console.log(error);
    });
}
