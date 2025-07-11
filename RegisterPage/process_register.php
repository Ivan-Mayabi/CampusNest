<?php
require '../connection.php';

// Collect form data
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = strtolower($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$user_role = $_POST['user_role'] ?? 'R002'; // Default to Student role

// Check if email or phone already exists
$check_stmt = $conn->prepare("SELECT userId FROM users WHERE userEmail = ? OR userPhone = ?");
$check_stmt->bind_param("ss", $email, $phone);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    // Duplicate found
    header("Location:../RegisterPage/register.php?error=exists");
    exit;
} else {
    // Correct variable name here

    $access = 1;  // All users have access

    // Now include userAccess in the insert
    $stmt = $conn->prepare("INSERT INTO users (userFname, userLname, userPhone, userEmail, userPassword, userRoleId, userAccess) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $first_name, $last_name, $phone, $email, $password, $user_role, $access);

    if ($stmt->execute()) {
        header("Location: ../Login/Login.html");
        exit;
    } else {
        header("Location:../RegisterPage/register.php?error=failed");
        exit;
    }

    $stmt->close();
}

$check_stmt->close();
$conn->close();
?>
