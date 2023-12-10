import { toggleDropdownButtons } from '../components/dropdown_dots.js';
import { initReactionJs } from '../post/reactions.js';
import { deleteComment } from './delete.js';
import { editCommentForm } from './edit.js';
import { editComment } from './edit.js';

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

                        initReactionJs(comment);

                        toggleDropdownButtons(comment.querySelectorAll(".dropdownButton"), comment.querySelectorAll(".dropdownContent"));

                        toggleEditComment(comment.querySelectorAll(".edit-comment-button"));
                        toggleDeleteComment(comment.querySelectorAll(".delete-comment-button"));

                        const dropdownContent = comment.querySelector(".dropdownContent");
                        toggleSaveComment(dropdownContent.parentElement.parentElement.querySelectorAll(".save-comment"));
                        console.log("AHHH: ", dropdownContent.parentElement.parentElement.querySelectorAll(".save-comment"));
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

function toggleDeleteComment(deleteCommentButtons) {
    if (!deleteCommentButtons) {
        return;
    }

    for (const deleteButton of deleteCommentButtons) {
        deleteButton.addEventListener('click', deleteComment);
    }
}

function toggleEditComment(editCommentButtons) {
    if (!editCommentButtons) {
        return;
    }

    for (const editButton of editCommentButtons) {
        editButton.addEventListener('click', editCommentForm);
    }
}

function toggleSaveComment(saveCommentButtons) {
    if (!saveCommentButtons) {
        return;
    }

    for (const saveButton of saveCommentButtons) {
        saveButton.addEventListener('click', editComment);
    }
}

const deleteCommentButtons = document.querySelectorAll('.delete-comment-button');
const editCommentButtons = document.querySelectorAll('.edit-comment-button');
const saveCommentButtons = document.querySelectorAll('.save-comment');

toggleDeleteComment(deleteCommentButtons);
toggleEditComment(editCommentButtons);
toggleSaveComment(saveCommentButtons);
