<?php
require 'connection.php';

// Get submitted data
$username_or_email = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM registration WHERE email = ? OR full_name = ?");
$stmt->bind_param("ss", $username_or_email, $username_or_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        echo "✅ Login successful. Welcome, " . htmlspecialchars($user['full_name']) . "!";
    } else {
        echo "❌ Incorrect password.";
    }
} else {
    echo "❌ User not found.";
}

$stmt->close();
$conn->close();
?>
