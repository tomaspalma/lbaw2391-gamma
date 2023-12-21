import { addSnackbar } from "../components/snackbar";

function handleFriendRequest(action, username) {
	// Determine the URL and method based on the action
	let url, methodType;
	if (action === "accept") {
		url = `/api/users/${username}/friends/`;
		methodType = "POST";
	} else if (action === "decline") {
		url = `/api/users/${username}/friends/requests`;
		methodType = "PUT";
	}
	fetch(url, {
		method: methodType,
		headers: {
			"Content-Type": "application/json",
			"X-CSRF-TOKEN": document
				.querySelector('meta[name="csrf-token"]')
				.getAttribute("content"),
		},
	})
		.then((response) => {
			if (response.ok) {
				const friendRequestsCounter = document.getElementById(
					"friend-request-counter"
				);
				const counter = parseInt(friendRequestsCounter.textContent, 10);
				friendRequestsCounter.textContent = counter - 1;
				const requestElement = document.getElementById(
					`request-${username}`
				);
				const requestsContainer = requestElement.parentNode;
				requestsContainer.removeChild(requestElement);
				if (requestsContainer.children.length === 0) {
					const noRequestsMessage =
						document.getElementById("noRequestsMessage");
					noRequestsMessage.classList.remove("hidden");
					friendRequestsCounter.classList.add("hidden");
				}

				if (action === "accept") {
					addSnackbar(
						`You accepted ${username} friend request`,
						2000
					);
				} else if (action === "decline") {
					addSnackbar(
						`You declined ${username} friend request`,
						2000
					);
				}
			}
		})
		.catch((error) => {
			console.error("Error:", error);
		});
}

export function toggleFriendRequestButtons(friendRequestForms) {
	friendRequestForms.forEach((friendRequestForm) => {
		friendRequestForm.addEventListener("submit", function (event) {
			console.log(event);
			event.preventDefault();
			const username = this.getAttribute("data-username");
			const action = event.submitter.value;
			console.log(username);
			handleFriendRequest(action, username);
			const requestsTitle = document.getElementById(
				"friend-requests-title"
			);
			let requestsCount = requestsTitle.textContent
				.replace("Pending (", "")
				.replace(")", "");
			requestsCount = parseInt(requestsCount, 10);
			requestsCount = requestsCount <= 0 ? 0 : requestsCount - 1;
			requestsCount < 0 ? (requestsCount = 0) : requestsCount;
			requestsTitle.textContent = `Pending (${requestsCount})`;
		});
	});
}

const friendRequestForms = document.querySelectorAll(".friendRequestForm");

toggleFriendRequestButtons(friendRequestForms);
