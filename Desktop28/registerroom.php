<?php
session_start();
require_once '../connection.php'; // adjust path if needed

// Make sure landlord is logged in
if (!isset($_SESSION['landlord_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $roomName = $_POST['roomName'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];
    $landlordId = $_SESSION['landlord_id'];

    // ✅ Insert room for this landlord
    $stmt = $conn->prepare("INSERT INTO rooms (landlord_id, name, price, availability) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $landlordId, $roomName, $price, $availability);

    if ($stmt->execute()) {
        echo "✅ Room added successfully.";
        // Optionally redirect:
        // header("Location: landlord-dashboard.php?success=1");
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
