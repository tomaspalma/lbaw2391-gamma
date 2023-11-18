email.addEventListener("input", async (e) => {
    var temp = document.getElementById("email").value;
    await checkEmailExists(temp);
});

username.addEventListener("input", async (e) => {
    var temp = document.getElementById("username").value;
    await checkUsernameExists(temp);
});

async function checkEmailExists(data) {
    let element = document.getElementById("email");
    let errorMessage = document.getElementById("email-error");

    try {
        let response = await fetch('api/users/email/' + data);
        let responseData = await response.json();

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
    let element = document.getElementById("username");
    let errorMessage = document.getElementById("username-error");
    try {
        let response = await fetch('api/users/username/' + data);
        let responseData = await response.json();

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
