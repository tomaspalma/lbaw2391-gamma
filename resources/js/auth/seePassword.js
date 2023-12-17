const passwordInput = document.getElementById("password");
const toggleButton = document.getElementById("togglePassword");
const passwordConfirmInput = document.getElementById("password-confirm");
const toggleButtonConfirm = document.getElementById("togglePasswordConfirm");

if (toggleButton) {
    toggleButton.addEventListener("click", function () {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleButton.classList = "fa-solid fa-eye cursor-pointer";
        } else {
            passwordInput.type = "password";
            toggleButton.classList = "fas fa-eye-slash cursor-pointer";
        }
    });
}

if (toggleButtonConfirm) {
    toggleButtonConfirm.addEventListener("click", function () {
        if (passwordConfirmInput.type === "password") {
            passwordConfirmInput.type = "text";
            toggleButtonConfirm.classList = "fa-solid fa-eye cursor-pointer";
        } else {
            passwordConfirmInput.type = "password";
            toggleButtonConfirm.classList = "fas fa-eye-slash cursor-pointer";
        }
    });
}
