<?php
require '../connection.php';
session_start(); // You forgot this, sessions won't work without it

// Get submitted data
$email = strtolower($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM users WHERE userEmail = ?"); // ✅ Correct column name
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    // Remove the hashing, to allow quick debugging
    if ($password==$user["userPassword"]) {
        echo "✅ Login successful. Welcome, " . htmlspecialchars($user["userEmail"]) . "!";

        // Save to session
        $_SESSION["user_id"] = $user["userID"];
        $_SESSION["user_role"] = $user["userRoleId"];
        $_SESSION["user_email"] = $user["userEmail"];
        $_SESSION["user_name"] = $user["userFname"]

        // Redirect based on role
        if ($user["userRoleId"] == "R002") { 
            header("Location: ../Desktop18/PropertySearch.html");
            exit;
        } else if ($user["userRoleId"] == "R001") { 
            header("Location: ../Desktop25/landlorddashboard.html");
            exit;
        }
    } else {
        echo "❌ Incorrect password.";
    }
} else {
    echo "❌ User not found.";
}

$stmt->close();
$conn->close();
?>
