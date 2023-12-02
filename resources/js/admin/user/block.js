import { overrideConfirmationForm, populateModalText } from "../../components/confirmation_modal";

const confirmationModal = document.getElementById("confirmation-modal");

export function toggleBlockTriggerButtons(blockConfirmationTriggerButtons) {
    for (const blockConfirmationTriggerButton of blockConfirmationTriggerButtons) {
        blockConfirmationTriggerButton.addEventListener("click", (e) => {
            e.preventDefault();

            blockUserAction(blockConfirmationTriggerButton);
        });
    }
}

const blockConfirmationTriggerButtons = document.querySelectorAll(".block-reason-trigger");
toggleBlockTriggerButtons(blockConfirmationTriggerButtons);

export function blockUserAction(blockConfirmationTriggerButton) {
    let username = blockConfirmationTriggerButton.parentElement.parentElement.parentElement.getAttribute("data-username");
    const profileImage = blockConfirmationTriggerButton.parentElement.parentElement.parentElement.getAttribute("data-user-image");

    const csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute("content");

    const form = `
        <form id="block_user_form" method="POST" action="/users/${username}/block">
            <input name="_token" value="${csrfToken}" hidden>
            <label for="block-reason">Provide a reason for blocking ${username}</label>
            <textarea name="reason" id="block-reason" rows="6" class="mt-2 block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
            <button type="submit" class="bg-black p-2 text-white mt-2 rounded-s w-full">Submit</button>
        </form>
    `;

    populateModalText(`
            <div class="flex flex-col align-middle">
                <img class="center rounded-full w-10 h-10" src="${profileImage}" />
                ${form}
            </div >
        `);

    overrideConfirmationForm(document.getElementById("block_user_form"), `/users/${username}/block`, {
        headers: {
            'X-CSRF-Token': `${csrfToken}`,
        },
        method: "POST",
    }, "block_user");
    confirmationModal.classList.remove("hidden");
}
