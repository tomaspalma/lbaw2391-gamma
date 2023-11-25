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
            // delete comment from the page
            document.querySelector(`[comment-id="${commentId}"]`).parentElement.remove();
        }
    })
    .catch((error) => {
        console.log(error);
    });
}

let deleteButtons = document.getElementsByClassName('delete-comment-button');
for(const deleteButton of deleteButtons) {
    deleteButton.addEventListener('click', deleteComment);
}
