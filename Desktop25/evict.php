<?php
session_start();
require_once '../connection.php'; // Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['id'] ?? null;

    if (!$studentId) {
        echo "Invalid student ID.";
        exit;
    }

    // Update the room status to "Left"
    $stmt = $conn->prepare("UPDATE roomregistration SET RoomStatus = 'Left' WHERE StudentID = ?");
    $stmt->bind_param("i", $studentId);

    if ($stmt->execute()) {
        echo "Student evicted successfully!";
    } else {
        echo "Error evicting student.";
    }

    $stmt->close();
    $conn->close();
}
?>
