<?php
session_start();
require '../connection.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

// Sanitize inputs
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

// Validate inputs
if (empty($email) || empty($password)) {
    header("Location: login.php?error=missing_fields");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: login.php?error=invalid_credentials&email=" . urlencode($email));
    exit;
}

try {
    // Fetch user from database
    $stmt = $conn->prepare("SELECT userID, userFname, userLname, userEmail, userRoleId, userPassword, userAccess FROM users WHERE userEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        header("Location: login.php?error=user_not_found&email=" . urlencode($email));
        exit;
    }

    $user = $result->fetch_assoc();

    // Check if account is active
    if ($user['userAccess'] != 1) {
        header("Location: login.php?error=access_denied&email=" . urlencode($email));
        exit;
    }

    // Verify password (supports hashed passwords)
    if (!password_verify($password, $user['userPassword'])) {
        header("Location: login.php?error=invalid_credentials&email=" . urlencode($email));
        exit;
    }

    // Set session variables
    $_SESSION['user_id']    = $user['userID'];
    $_SESSION['user_role']  = $user['userRoleId'];
    $_SESSION['user_email'] = $user['userEmail'];
    $_SESSION['user_fname'] = $user['userFname'];
    $_SESSION['user_lname'] = $user['userLname'];
    $_SESSION['login_time'] = time();

    // Optional: Update last login time
    $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE userID = ?");
    $updateStmt->bind_param("i", $user['userID']);
    $updateStmt->execute();
    $updateStmt->close();

    // Redirect based on role
    switch ($user['userRoleId']) {
        case 'R001': // Landlord
            header("Location: ../Desktop25/landlorddashboard.html");
            break;
        case 'R002': // Student
            header("Location: ../Desktop18/PropertySearch.html");
            break;
        case 'R003': // Admin
            header("Location: ../admin/admin.php");
            break;
        default:
            session_destroy();
            header("Location: login.php?error=invalid_role");
            break;
    }
    exit;

} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    header("Location: Login.php?error=system_error");
    exit;
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>
