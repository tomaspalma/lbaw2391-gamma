email.addEventListener("input", async (e) => {
    const temp = document.getElementById("email").value;
    await checkEmailExists(temp);
});

username.addEventListener("input", async (e) => {
    const temp = document.getElementById("username").value;
    await checkUsernameExists(temp);
});


async function checkEmailExists(data) {
    const element = document.getElementById("email");
    const errorMessage = document.getElementById("email-error");

    try {
        const response = await fetch('/api/users/email/' + data);
        const responseData = await response.json();

        if (responseData.length === 0) {
            errorMessage.textContent = "";
            element.setCustomValidity("");
        } else {
            errorMessage.textContent = "Email is already in use";
            element.setCustomValidity("Email is already in use");
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}


async function checkUsernameExists(data) {
    const element = document.getElementById("username");
    const errorMessage = document.getElementById("username-error");
    try {
        const response = await fetch('/api/users/username/' + data);
        const responseData = await response.json();

        if (responseData.length === 0) {
            errorMessage.textContent = "";
            element.setCustomValidity("");
        } else {
            errorMessage.textContent = "Username is already in use";
            element.setCustomValidity("Username is already in use");
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}