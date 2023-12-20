import Pusher from 'pusher-js';
import { getCsrfToken, getUsername } from './utils';

import './bootstrap';
import { toggleUnblockConfirmationButtons } from './admin/user/unblock';
import { toggleDeleteUserAppealButtons } from './admin/user/remove_appeal';
import { toggleDropdownArrow } from './components/dropdown';
import { toggleAppbanAppealReasonDropdown } from './admin/user/show_appeal_reason';
import { toggleBlockTriggerButtons } from './admin/user/block';
import { configureConfirmationForm, populateModalText } from './components/confirmation_modal';

function toggleLogoutMisclickConfirmation() {
    const logoutAction = document.getElementById("logout-action");
    const confirmationModal = document.getElementById("confirmation-modal");

    if (!logoutAction || !confirmationModal) {
        return;
    }

    logoutAction.addEventListener("submit", (e) => {
        e.preventDefault();

        populateModalText(`
            <div class="flex flex-col align-middle">
                <p>Are you sure you want logout?</p> 
            </div>
        `);

        configureConfirmationForm("/logout", "POST", "logout", "bg-red-500", "text-red-500");
        confirmationModal.classList.remove("hidden");
    });
}

toggleLogoutMisclickConfirmation();

const pusherAppKey = "42e95b477c2a2640c461";
const cluster = "eu";

const pusher = new Pusher(pusherAppKey, {
    cluster: cluster,
    encrypted: true,
    auth: {
        headers: {
            'X-CSRF-Token': getCsrfToken()
        },
    },
    debug: true
});

function onPage(name) {
    const url = window.location.href;
    const urlParts = url.split("/");

    return urlParts[urlParts.length - 1] === name;
}

const adminChannel = pusher.subscribe('private-admin');
adminChannel.bind('appeal-notification', function(data) {
    console.log(data);

    if (onPage("user")) {
        const appealCounter = document.getElementById("appeal-counter");
        const currentCounter = parseInt(appealCounter.textContent, 10);

        appealCounter.textContent = `${currentCounter + 1}`;
    } else if (onPage("appeals")) {
        const appealCounter = document.getElementById("appeal-counter");
        const currentCounter = parseInt(appealCounter.textContent, 10);

        appealCounter.textContent = `${currentCounter + 1}`;

        const appealNotFoundText = document.getElementById("no-appeals-found-text");

        if (appealNotFoundText) {
            appealNotFoundText.remove();
        }

        const content = document.getElementById("content");

        if (document.getElementById("no-appeals-found-text")) {
            document.getElementById("no-appeals-found-text").remove();
        }

        const element = document.createElement("div");
        element.innerHTML = data.message.appeal_card;

        toggleUnblockConfirmationButtons(element.querySelectorAll(".unblock-confirmation-trigger"));
        toggleBlockTriggerButtons(element.querySelectorAll(".block-reason-trigger"));
        toggleDeleteUserAppealButtons(element.querySelectorAll(".remove-confirmation-trigger"));
        toggleAppbanAppealReasonDropdown(element.querySelectorAll(".appban-dropdown-arrow"));

        content.prepend(element);
    }
});

const notificationCounter = document.getElementById("notification-counter");
const channel = pusher.subscribe(`private-user.${getUsername()}`);
channel.bind('reaction-notification', function(data) {
    const message = data.message;
    if (message.user.username !== data.author) {
        notificationCounter.classList.remove("hidden");
        const counter = parseInt(notificationCounter.textContent, 10);
        notificationCounter.textContent = (counter + 1);

        if (onPage("notifications")) {
            const notificationsCards = document.getElementById("notification-cards");
            console.log(notificationsCards.children);

            notificationsCards.insertAdjacentHTML('afterbegin', message.reaction_not_view);

            console.log(notificationsCards.children);
        }
    }
});

channel.bind('friend-request-notification', function(data) {
    const message = data.message;
    if (message.user.username !== data.author) {
        if (message.is_accepted === null) {
            const friendRequestCounter = document.getElementById("friend-request-counter");
            friendRequestCounter.classList.remove("hidden");
            const counter = parseInt(friendRequestCounter.textContent, 10);
            friendRequestCounter.textContent = (counter + 1);
        }
        else {
            notificationCounter.classList.remove("hidden");
            const counter = parseInt(notificationCounter.textContent, 10);
            notificationCounter.textContent = (counter + 1);
        }

        if (onPage("notifications")) {
            const notificationsCards = document.getElementById("notification-cards");
            notificationsCards.insertAdjacentHTML('afterbegin', message.friend_request_not_view);
        }
    }
});

channel.bind('group-request-notification', function(data){
    const message = data.message;
    if (message.user.username !== data.author) {
        notificationCounter.classList.remove("hidden");
        const counter = parseInt(notificationCounter.textContent, 10);
        notificationCounter.textContent = (counter + 1);
        if (onNotificationsPage()) {
            const notificationsCards = document.getElementById("notification-cards");
            notificationsCards.insertAdjacentHTML('afterbegin', message.comment_not_view);
        }
    }
})

channel.bind('comment-notification', function(data) {
    const message = data.message;
    if (message.user.username !== data.author) {
        notificationCounter.classList.remove("hidden");
        const counter = parseInt(notificationCounter.textContent, 10);
        notificationCounter.textContent = (counter + 1);

        if (onPage("notifications")) {
            const notificationsCards = document.getElementById("notification-cards");
            notificationsCards.insertAdjacentHTML('afterbegin', message.comment_not_view);
        }
    }
})
