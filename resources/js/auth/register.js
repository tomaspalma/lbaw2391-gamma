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
    try {
        let response = await fetch('api/users/email/' + data);
        let responseData = await response.json();

        if (responseData.length === 0) {
            element.style.color = "green";
        } else {
            element.style.color = "red";
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}

async function checkUsernameExists(data) {
    let element = document.getElementById("username");
    try {
        let response = await fetch('api/users/username/' + data);
        let responseData = await response.json();

        if (responseData.length === 0) {
            element.style.color = "green";
        } else {
            element.style.color = "red";
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}
