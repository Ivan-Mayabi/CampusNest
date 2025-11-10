<?php
session_start();

// Redirect already logged-in users based on role
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['user_role']) {
        case 'R001': // Landlord
            header("Location: ../Desktop25/landlorddashboard.html");
            exit;
        case 'R002': // Student
            header("Location: ../Desktop18/PropertySearch.html");
            exit;
        case 'R003': // Admin
            header("Location: ../admin/admin.php");
            exit;
    }
}

// Capture error messages
$error = $_GET['error'] ?? '';
$email = htmlspecialchars($_GET['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Nest - Login</title>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
<div class="login-container">
    <div class="login-form">
        <h1>Campus Nest</h1>
        <h2>Welcome Back!</h2>

        <?php if ($error): ?>
            <div class="error-message">
                <?php
                switch($error) {
                    case 'missing_fields': echo "Please fill in all fields."; break;
                    case 'invalid_credentials': echo "Invalid email or password."; break;
                    case 'user_not_found': echo "User not found."; break;
                    case 'access_denied': echo "Your account is inactive."; break;
                    case 'invalid_role': echo "Unknown role. Contact admin."; break;
                    default: echo "An unexpected error occurred.";
                }
                ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="process_login.php">
            <div class="form-group">
                <label for="email">Email <span>*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter your email" value="<?= $email ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password <span>*</span></label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <input type="checkbox" id="togglePassword"> Show Password
            </div>

            <button type="submit" class="btn-login">Login</button>

            <div class="form-footer">
                Forgot password? <a href="#">Click here</a><br>
                New here? <a href="../RegisterPage/register.html">Sign Up</a>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    togglePassword.addEventListener('change', function() {
        password.type = this.checked ? 'text' : 'password';
    });
</script>
</body>
</html>
