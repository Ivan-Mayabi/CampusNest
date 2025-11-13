<?php
require '../connection.php';

// Collect & sanitize form data
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$email      = strtolower(trim($_POST['email'] ?? ''));
$password   = $_POST['password'] ?? '';
$user_role  = $_POST['user_role'] ?? 'R002'; // Default = Student

// Validate required fields
if (empty($first_name) || empty($last_name) || empty($phone) || empty($email) || empty($password) || empty($user_role)) {
    header("Location: ../RegisterPage/register.php?error=missing_fields");
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../RegisterPage/register.php?error=invalid_email");
    exit;
}

// Check if email or phone already exists
$check_stmt = $conn->prepare("SELECT userId FROM users WHERE userEmail = ? OR userPhone = ?");
$check_stmt->bind_param("ss", $email, $phone);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    header("Location: ../RegisterPage/register.php?error=exists");
    exit;
}

$check_stmt->close();

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$userAccess = 1;

$stmt = $conn->prepare("
    INSERT INTO users (
        userFname, userLname, userPhone, userEmail, 
        userPassword, userRoleId, userAccess
    ) VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("ssssssi",
    $first_name,
    $last_name,
    $phone,
    $email,
    $hashedPassword,
    $user_role,
    $userAccess
);

if ($stmt->execute()) {
    header("Location: ../Login/Login.php?registered=success");
    exit;
} else {
    header("Location: ../RegisterPage/register.php?error=failed");
    exit;
}

$stmt->close();
$conn->close();
?>
