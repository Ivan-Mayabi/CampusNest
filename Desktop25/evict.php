<?php
session_start();
require_once '../connection.php'; // Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['id'] ?? null;
    $roomId = $_POST['room_id'] ?? null;

    if (!$studentId || !$roomId) {
        echo "Missing student ID or room ID.";
        exit;
    }

    // Update the room status to "Left" for this specific student and room
    $stmt = $conn->prepare("UPDATE roomregistration SET RoomStatus = 'Left' WHERE StudentID = ? AND RoomID = ?");
    $stmt->bind_param("ii", $studentId, $roomId);

    if ($stmt->execute()) {
        echo "Student evicted successfully!";
    } else {
        echo "Error evicting student: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
