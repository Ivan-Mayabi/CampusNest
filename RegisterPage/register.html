<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registration</title>
    <link rel="stylesheet" href="register.css" />
</head>
<body>
    <div class="container">

        <h2>Registration</h2>
        <h3>Join us today!</h3>

        <p id="error-box" class="error" style="display:none;"></p>

        <form action="process_register.php" method="POST" id="registerForm">
            
            <div class="form-group">
                <label for="first_name">First Name<span>*</span></label>
                <input type="text" id="first_name" name="first_name" required />
            </div>

            <div class="form-group">
                <label for="last_name">Last Name<span>*</span></label>
                <input type="text" id="last_name" name="last_name" required />
            </div>

            <div class="form-group">
                <label for="phone">Phone Number<span>*</span></label>
                <input type="text" id="phone" name="phone" required />
            </div>

            <div class="form-group">
                <label for="email">Email<span>*</span></label>
                <input type="email" id="email" name="email" required />
            </div>

            <div class="form-group">
                <label for="password">Password<span>*</span></label>
                <input type="password" id="password" name="password" required />
            </div>

            <div class="form-group password-toggle">
                <label for="togglePassword">Show Password</label>
                <input type="checkbox" id="togglePassword" />
            </div>

            <div class="form-group">
                <label for="user_role">Role<span>*</span></label>
                <select id="user_role" name="user_role" required>
                    <option value="">Select Role</option>
                    <option value="R002">Student</option>
                    <option value="R001">Landlord</option>
                    <option value="R003">Admin</option>
                </select>
            </div>

            <button type="submit" class="form-button">Register</button>
        </form>

        <div class="form-footer">
            Already registered? <a href="../Login/Login.php">Login here</a>
        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
        const togglePassword = document.getElementById("togglePassword");
        const password = document.getElementById("password");

        togglePassword.addEventListener("change", () => {
            password.type = togglePassword.checked ? "text" : "password";
        });
    </script>

    <!-- Error Handling Script -->
    <script>
        const params = new URLSearchParams(window.location.search);
        const err = params.get("error");
        const errorBox = document.getElementById("error-box");

        if (err) {
            let message = "";

            switch (err) {
                case "missing_fields":
                    message = "❌ All fields are required.";
                    break;
                case "invalid_email":
                    message = "❌ Invalid email format.";
                    break;
                case "exists":
                    message = "❌ Email or phone number already exists.";
                    break;
                case "failed":
                    message = "❌ Registration failed. Try again.";
                    break;
            }

            errorBox.textContent = message;
            errorBox.style.display = "block";
        }
    </script>

    <style>
        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>

</body>
</html>
