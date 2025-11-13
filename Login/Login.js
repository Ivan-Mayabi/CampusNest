const passwordField = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("change", () => {
    passwordField.type = togglePassword.checked ? "text" : "password";
});
