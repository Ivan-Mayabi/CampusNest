<?php
require 'connection.php';

$full_name = $_POST['full_name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$gender = $_POST['gender'] ?? '';
$dob = $_POST['dob'] ?? '';
$user_role = $_POST['user_role'] ?? '';

// Handle image upload
$profile_image = '';
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $image_name = basename($_FILES['profile_image']['name']);
    $target_dir = "uploads/";
    $target_file = $target_dir . uniqid() . "_" . $image_name;

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
        $profile_image = $target_file; // Store the file path in DB
    } else {
        die("❌ Failed to upload image.");
    }
} else {
    die("❌ Profile image is required.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$stmt = $conn->prepare("INSERT INTO registration (full_name, email, password, gender, dob, user_role, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $full_name, $email, $hashed_password, $gender, $dob, $user_role, $profile_image);

if ($stmt->execute()) {
    echo "✅ Registered Successfully!";
    echo '<br><a href="login.html">Login here</a>';


} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
