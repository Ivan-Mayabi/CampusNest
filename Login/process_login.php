<?php
require '../connection.php';

// Get submitted data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM users WHERE useremail = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if ($password== $user["userPassword"]) {
        echo "✅ Login successful. Welcome, " . htmlspecialchars($user["useremail"]) . "!";
        $_SESSION["useremail"]= $_POST["email"];
        print_r($user);
        if($user["userRoleID"]==2){ //This is to go to the student
            header("Location: ../ ");
            exit;
        }
        else if($user["userRoleID"]==1){ //This is to go to the landlord
            header("Location: ../");
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
