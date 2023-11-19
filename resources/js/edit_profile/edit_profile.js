const username = document.getElementById("username");
if (username) {
  username.addEventListener("input", async (e) => {
    e.preventDefault();

    await checkUsernameExists();
  });
}

async function checkUsernameExists(data) {
  const newUsername = document.getElementById("username");
  const oldUsername = document.getElementById("old-username").value;
  const errorMessage = document.getElementById("username-error");
  if (oldUsername === newUsername.value) {
    errorMessage.textContent = "";
    newUsername.setCustomValidity("");
    return;
  }
  try {
    const response = await fetch("/api/users/username/" + newUsername.value);
    const responseData = await response.json();

    if (responseData.length === 0) {
      errorMessage.textContent = "";
      newUsername.setCustomValidity("");
    } else {
      errorMessage.textContent = "Username is already in use";
      newUsername.setCustomValidity("Username is already in use");
    }
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}
