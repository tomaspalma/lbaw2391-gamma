import Pusher from 'pusher-js';
import { getCsrfToken, getUsername } from './utils';

import './bootstrap';

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

function onNotificationsPage() {
    const url = window.location.href;
    const urlParts = url.split("/");

    return urlParts[urlParts.length - 1] === 'notifications';
}

const notificationCounter = document.getElementById("notification-counter");
const channel = pusher.subscribe(`private-user.${getUsername()}`);
channel.bind('reaction-notification', function(data) {
    const message = data.message;
    if (message.user.username !== data.author) {
        notificationCounter.classList.remove("hidden");
        const counter = parseInt(notificationCounter.textContent, 10);
        notificationCounter.textContent = (counter + 1);

        if (onNotificationsPage()) {
            const notificationsCards = document.getElementById("notification-cards");
            console.log(notificationsCards.children);

            notificationsCards.insertAdjacentHTML('afterbegin', message.reaction_not_view);

            console.log(notificationsCards.children);
        }
    }
});
channel.bind('friend-request-notification', function(data) {
    const message = data.message;
    if (message.notification_receiver.username !== data.author) {
        notificationCounter.classList.remove("hidden");
        const counter = parseInt(notificationCounter.textContent, 10);
        notificationCounter.textContent = (counter + 1);

        if (onNotificationsPage()) {
            const notificationsCards = document.getElementById("notification-cards");
            notificationsCards.insertAdjacentHTML('afterbegin', message.friend_request_not_view);
        }
    }
});

channel.bind('comment-notification', function(data) {
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
