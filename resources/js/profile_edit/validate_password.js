function validatePassword() {
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById(
    "password_confirmation"
  ).value;

  if (password != confirmPassword) {
    alert("Passwords do not match!");
    return false;
  }

  return true;
}
const profileEditForm = document.getElementById("profile_edit_form");
profileEditForm.addEventListener("submit", (e) => {
  e.preventDefault();

  if (validatePassword()) {
    profileEditForm.submit();
  }
});
