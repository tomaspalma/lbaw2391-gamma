export function editCommentForm(editButton) {
    let commentDiv = editButton.target.parentElement.parentElement.parentElement;
    let contentPara = commentDiv.querySelector('.comment-content');
    let dropDown = commentDiv.querySelector('.dropdown');
    let content = contentPara.textContent;
    let editInput = commentDiv.querySelector('.edit-comment');
    let saveButton = commentDiv.querySelector('.save-comment');
    editInput.value = content;
    contentPara.classList.add('hidden');
    dropDown.classList.add('hidden');
    saveButton.classList.remove('hidden');
    editInput.classList.remove('hidden');
    
}

export function editComment(saveButton) {
    saveButton.stopPropagation();
    saveButton.preventDefault();
    let commentDiv = saveButton.target.parentElement;
    let contentPara = commentDiv.querySelector('.comment-content');
    let dropDown = commentDiv.querySelector('.dropdown');
    let editInput = commentDiv.querySelector('.edit-comment');
    let commentId = saveButton.target.getAttribute('comment-id');
    fetch('/comment/' + commentId + '/edit', {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            content: editInput.value
        })
    })
    .then((response) => {
        if(response.ok) {
            // edit comment on the page
            response.json()
            .then((comment) =>{
                contentPara.textContent = comment.data.content;
                contentPara.classList.remove('hidden');
                dropDown.classList.remove('hidden');
                saveButton.classList.add('hidden');
                editInput.classList.add('hidden');
            });
        }
    })
    .catch((error) => {
        console.log(error);
    });
}