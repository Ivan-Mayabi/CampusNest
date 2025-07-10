
const toggle = document.getElementById("togglePassword");
const passwordField = document.getElementById("password");

toggle.addEventListener("change", function () {
    //toggle the type attribute
    const type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;
});
