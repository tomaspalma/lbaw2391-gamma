const profileEditForm = document.getElementById("edit_profile_form");

if (profileEditForm) {
  profileEditForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const isUsernameValid = await checkUsername();

    if (isUsernameValid && validatePassword()) {
      profileEditForm.submit();
    }
  });
}

function validatePassword() {
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById(
    "password_confirmation"
  ).value;
  const errorMessage = document.getElementById("password-confirmation-error");

  if (password != confirmPassword) {
    errorMessage.textContent = "Passwords do not match!";
    return false;
  }

  errorMessage.textContent = "";
  return true;
}

async function checkUsername() {
  const newUsername = document.getElementById("username").value;
  const oldUsername = document.getElementById("old_username").value;

  if (newUsername === oldUsername) {
    // The username is the same, no need to check
    return true;
  }

  const errorContainer = document.getElementById("username-error");

  // Reset error message
  errorContainer.textContent = "";

  // Check if new username is empty
  if (!newUsername.trim()) {
    errorContainer.textContent = "Username cannot be empty.";
    return false; // No need to check if the new username is empty
  }

  try {
    // Send Fetch GET request
    const response = await fetch("/api/users/" + newUsername);

    if (response == null) {
      // Username is unique
      errorContainer.textContent = "";
      return true;
    }
    // Username is not unique
    errorContainer.textContent = "This username is already taken.";
    return false;
  } catch (error) {
    console.error("Fetch error:", error);
    return false;
  }
}
