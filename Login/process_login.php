<?php
require '../connection.php';
session_start(); // ✅ Required for session management

$email = strtolower($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Find user by email
$stmt = $conn->prepare("SELECT * FROM users WHERE userEmail = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // ✅ Plain password check + access check
    if ($password === $user["userPassword"] && $user["userAccess"] == 1) {
        echo "✅ Login successful. Welcome, " . htmlspecialchars($user["userEmail"]) . "!";

        // Save user info to session
        $_SESSION["user_id"] = $user["userID"];
        $_SESSION["user_role"] = $user["userRoleId"];
        $_SESSION["user_email"] = $user["userEmail"];
        $_SESSION["user_name"] = $user["userFname"];

        // ✅ Redirect by role
        if ($user["userRoleId"] == "R002") { 
            header("Location: ../Desktop18/PropertySearch.html");
            exit;
        } elseif ($user["userRoleId"] == "R001") { 
            header("Location: ../Desktop25/landlorddashboard.html");
            exit;
        } elseif ($user["userRoleId"] == "R003") {
            header("Location: ../admin/admin.php");
            exit;
        } else {
            echo "❌ Unknown role.";
        }

    } else {
        echo "❌ Incorrect password or access denied.";
    }
} else {
    echo "❌ User not found.";
}

$stmt->close();
$conn->close();
?>
