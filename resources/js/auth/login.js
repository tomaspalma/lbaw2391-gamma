const passwordInput = document.getElementById('password')
const toggleButton = document.getElementById('togglePassword')


toggleButton.addEventListener('click', function () {
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.classList = "fa-solid fa-eye";
    } else {
        passwordInput.type = 'password';
        toggleButton.classList = 'fas fa-eye-slash';
    }
});