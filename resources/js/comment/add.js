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

                /*
                <div class="flex max-w-full overflow-auto space-x-4">
                    <img src="{{ $comment->author->image ?? 'hello' }}" class="rounded-full self-center w-8 h-8">
                    <div class="grow">
                        <p class="text-gray-600">{{ $comment->owner->username }}</p>
                        <p>{{ $comment->content }}</p>
                    </div>
                    <button type="button" class="delete-comment-button bg-red-500 text-white self-center py-1 px-2 rounded-md" comment-id="{{ $comment->id }}">Delete</button>
                </div>
                <hr class="my-2">
                */
                let commentDiv = document.createElement('div');
                commentDiv.classList.add('flex', 'max-w-full', 'overflow-auto', 'space-x-4');
                let image = document.createElement('img');
                image.setAttribute('src', comment.data.image);
                image.classList.add('rounded-full', 'self-center', 'w-8', 'h-8');
                commentDiv.appendChild(image);
                let div = document.createElement('div');
                div.classList.add('grow');
                let p = document.createElement('p');
                p.classList.add('text-gray-600');
                p.innerText = comment.data.display_name;
                div.appendChild(p);
                p = document.createElement('p');
                p.innerText = comment.data.content;
                div.appendChild(p);
                commentDiv.appendChild(div);
                let button = document.createElement('button');
                button.classList.add('delete-comment-button', 'bg-red-500', 'text-white', 'self-center', 'py-1', 'px-2', 'rounded-md');
                button.setAttribute('comment-id', comment.data.id);
                button.innerText = 'Delete';
                button.addEventListener('click', deleteComment);
                commentDiv.appendChild(button);
                document.getElementById('comments').appendChild(commentDiv);

                let hr = document.createElement('hr');
                hr.classList.add('my-2');
                document.getElementById('comments').appendChild(hr);

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