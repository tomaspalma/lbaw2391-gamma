import { deleteComment } from './delete.js';
import { editCommentForm } from './edit.js';
import { editComment } from './edit.js';

function addComment() {
    let form = document.getElementById('comment-form');
    let formData = new FormData(form);

    // block if comment is empty
    if(formData.get('content') == '') {
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
        if(response.ok) {
            // add comment to the page
            response.json()
            .then((comment) =>{

                // if there is no comment yet remove the no comment message
                if(document.getElementById('no-comment')) {
                    document.getElementById('no-comment').remove();
                }

                let commentDiv = document.createElement('div');
                commentDiv.classList.add('grid', 'grid-cols-2');

                // Head of comment, contains image, username and date
                let headDiv = document.createElement('div');
                headDiv.classList.add('flex', 'flex-row', 'flex-nowrap', 'gap-x-4');
                let image = document.createElement('img');
                image.setAttribute('src', comment.data.image);
                image.classList.add('rounded-full', 'self-center', 'w-8', 'h-8');
                let para = document.createElement('p');
                para.classList.add('text-gray-600');
                para.textContent = comment.data.username;
                let span = document.createElement('span');
                span.classList.add('text-gray-600');
                let time = document.createElement('time');
                time.textContent = comment.data.date;
                span.appendChild(time);

                headDiv.appendChild(image);
                headDiv.appendChild(para);
                headDiv.appendChild(span);

                // Dropdown button and content of comment
                let buttonDiv = document.createElement('div');
                buttonDiv.classList.add('dropdown', 'flex', 'gap-x-1', 'justify-self-end', 'relative');
                let button = document.createElement('button');
                button.classList.add('dropdownButton', 'text-black', 'font-bold', 'py-2', 'px-4', 'rounded');
                let i = document.createElement('i');
                i.classList.add('fas', 'fa-ellipsis-v');
                button.appendChild(i);
                button.addEventListener('click', function () {
                    const display = button.nextSibling.style.display;

                    if (display == "none") {
                        button.nextSibling.style.display = "block";
                    } else {
                        button.nextSibling.style.display = "none";
                    }
                }
                );

                let dropdownDiv = document.createElement('div');
                dropdownDiv.classList.add('dropdownContent', 'absolute', 'right-0', 'mt-2', 'w-56', 'rounded-md', 'shadow-lg', 'bg-white', 'ring-1', 'ring-black', 'ring-opacity-5');
                dropdownDiv.style.display = 'none';
                let editLink = document.createElement('a');
                editLink.classList.add('edit-comment-button', 'block', 'px-4', 'py-2', 'text-sm', 'text-gray-700', 'cursor-pointer', 'hover:bg-gray-100', 'hover:text-gray-900', 'hover:no-underline');
                editLink.textContent = 'Edit';
                editLink.addEventListener('click', editCommentForm);
                let deleteLink = document.createElement('a');
                deleteLink.classList.add('delete-comment-button', 'block', 'px-4', 'py-2', 'text-sm', 'text-gray-700', 'cursor-pointer', 'hover:bg-gray-100', 'hover:text-gray-900', 'hover:no-underline');
                deleteLink.setAttribute('comment-id', comment.data.id);
                deleteLink.textContent = 'Delete';
                deleteLink.addEventListener('click', deleteComment);
                dropdownDiv.appendChild(editLink);
                dropdownDiv.appendChild(deleteLink);
                document.addEventListener('click', function (event) {
                    const isClickInside = dropdownDiv.contains(event.target) || button.contains(event.target);
                    
                    if (!isClickInside) {
                        dropdownDiv.style.display = "none";
                    }
                }
                );

                buttonDiv.appendChild(button);
                buttonDiv.appendChild(dropdownDiv);

                // Hidden save button
                let saveButton = document.createElement('button');
                saveButton.classList.add('save-comment', 'hidden', 'justify-self-end', 'text-black', 'font-bold', 'py-2', 'px-4', 'rounded');
                saveButton.setAttribute('comment-id', comment.data.id);
                saveButton.textContent = 'Save';
                saveButton.addEventListener('click', editComment);

                // Content of comment
                let contentPara = document.createElement('p');
                contentPara.classList.add('comment-content', 'col-span-2', 'break-words');
                contentPara.textContent = comment.data.content;

                // Hidden edit comment form
                let textarea = document.createElement('textarea');
                textarea.classList.add('edit-comment', 'hidden', 'col-span-2', 'break-words', 'hidden');
                textarea.setAttribute('comment-id', comment.data.id);
                textarea.setAttribute('name', 'content');

                commentDiv.appendChild(headDiv);
                commentDiv.appendChild(buttonDiv);
                commentDiv.appendChild(saveButton);
                commentDiv.appendChild(contentPara);
                commentDiv.appendChild(textarea);

                let hr = document.createElement('hr');
                hr.classList.add('my-2');

                document.getElementById('comments').prepend(hr);
                document.getElementById('comments').prepend(commentDiv);

                // clear comment form
                document.getElementById('comment-form').reset();
            })

        }
    })
    .catch((error) => {
        console.log(error);
    });
}

const commentButton = document.getElementById('comment-button').addEventListener('click', addComment);
if(commentButton) {
    commentButton.addEventListener('click', addComment);
}

let deleteCommentButtons = document.getElementsByClassName('delete-comment-button');
for(const deleteButton of deleteCommentButtons) {
    deleteButton.addEventListener('click', deleteComment);
}

const editCommentButtons = document.getElementsByClassName('edit-comment-button');
for(const editButton of editCommentButtons) {
    editButton.addEventListener('click', editCommentForm);
}

const saveCommentButtons = document.getElementsByClassName('save-comment');
for(const saveButton of saveCommentButtons) {
    saveButton.addEventListener('click', editComment);
}
