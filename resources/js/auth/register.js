email.addEventListener("input", async (e) => {
    const temp = document.getElementById("email").value;
    if (temp) {
        await checkEmailExists(temp);
    }
});

username.addEventListener("input", async (e) => {
    const temp = document.getElementById("username").value;
    if (temp) {
        await checkUsernameExists(temp);
    }
});

async function checkEmailExists(data) {
    const element = document.getElementById("email");
    const errorMessage = document.getElementById("email-error");
    const response = await fetch("/api/users/email/" + data);

    if (response.status === 404) {
        errorMessage.textContent = "";
        element.setCustomValidity("");
    } else if (response.status === 200) {
        errorMessage.textContent = "Email is already in use";
        element.setCustomValidity("Email is already in use");
    } else {
        console.error("Error fetching data:", error);
    }
}

async function checkUsernameExists(data) {
    const element = document.getElementById("username");
    const errorMessage = document.getElementById("username-error");
    const response = await fetch("/api/users/username/" + data);

    if (response.status === 404) {
        errorMessage.textContent = "";
        element.setCustomValidity("");
    } else if (response.status === 200) {
        errorMessage.textContent = "Username is already in use";
        element.setCustomValidity("Username is already in use");
    } else {
        console.error("Error fetching data:", error);
    }
}
