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
                const requestElement = document.getElementById(
                    `request-${username}`
                );
                const requestsContainer = requestElement.parentNode;
                requestsContainer.removeChild(requestElement);
                if (requestsContainer.children.length === 0) {
                    const noRequestsMessage =
                        document.getElementById("noRequestsMessage");
                    noRequestsMessage.classList.remove("hidden");
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

const friendRequestForm = document.getElementById("friendRequestForm");
if (friendRequestForm) {
    friendRequestForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const username = this.getAttribute("data-username");
        const action = event.submitter.value;
        handleFriendRequest(action, username);
        const requestsTitle = document.getElementById("friend-requests-title");
        let requestsCount = requestsTitle.textContent
            .replace("Pending (", "")
            .replace(")", "");
        requestsCount = parseInt(requestsCount, 10);
        requestsTitle.textContent = `Pending (${requestsCount - 1})`;
    });
}
