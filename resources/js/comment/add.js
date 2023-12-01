import { deleteComment } from './delete.js';

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

                let buttonDiv = document.createElement('div');
                buttonDiv.classList.add('flex', 'gap-x-1', 'justify-self-end', 'relative');
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
                editLink.setAttribute('href', '/comment/' + comment.data.id + '/edit');
                editLink.classList.add('block', 'px-4', 'py-2', 'text-sm', 'text-gray-700', 'hover:bg-gray-100', 'hover:text-gray-900', 'hover:no-underline');
                editLink.textContent = 'Edit';
                let deleteLink = document.createElement('a');
                deleteLink.classList.add('delete-comment-button', 'block', 'px-4', 'py-2', 'text-sm', 'text-gray-700', 'cursor-pointer', 'hover:bg-gray-100', 'hover:text-gray-900', 'hover:no-underline');
                deleteLink.setAttribute('comment-id', comment.data.id);
                deleteLink.textContent = 'Delete';
                deleteLink.addEventListener('click', deleteComment);
                dropdownDiv.appendChild(editLink);
                dropdownDiv.appendChild(deleteLink);
                dropdownDiv.addEventListener('click', function (event) {
                    const isClickInside = dropdownDiv.contains(event.target);
                    
                    if (!isClickInside) {
                        dropdownDiv.style.display = "none";
                    }
                }
                );

                buttonDiv.appendChild(button);
                buttonDiv.appendChild(dropdownDiv);

                let contentPara = document.createElement('p');
                contentPara.classList.add('col-span-2', 'break-words');
                contentPara.textContent = comment.data.content;

                commentDiv.appendChild(headDiv);
                commentDiv.appendChild(buttonDiv);
                commentDiv.appendChild(contentPara);

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
