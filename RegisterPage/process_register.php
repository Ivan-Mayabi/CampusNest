<?php
require '../connection.php';

// Collect form data
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = strtolower($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$user_role = $_POST['user_role'] ?? 'R002';  // Default to Student role

// // Hash the password (optional - depending if you want hashed passwords)
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//Remove hashed passwords, easier than changing databases to allow sign ups

// Prepare the insert statement
$stmt = $conn->prepare("INSERT INTO users (userFname, userLname, userPhone, userEmail, userPassword, userRoleId) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $first_name, $last_name, $phone, $email, $password, $user_role);

// Execute the query
if ($stmt->execute()) {
    echo "✅ Registration successful!";
    header("Location: ../login/studentlogin.html"); // Adjust this to your correct login page
    exit;
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
