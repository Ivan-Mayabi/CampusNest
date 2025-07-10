<?php
session_start();
require_once '../connection.php'; // Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['id'] ?? null;

    if (!$studentId) {
        echo "Invalid student ID.";
        exit;
    }

    // Update the room status to "Approved"
    $stmt = $conn->prepare("UPDATE roomregistration SET RoomStatus = 'Approved' WHERE StudentID = ? AND RoomID = ?");
    $stmt->bind_param("ii", $studentId, $roomId);

    if ($stmt->execute()) {
        echo "Student approved successfully!";
    } else {
        echo "Error approving student.";
    }

    $stmt->close();
    $conn->close();
}
?>
