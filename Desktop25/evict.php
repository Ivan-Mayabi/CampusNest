<?php
session_start();
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['id'] ?? null;
    $roomId = $_POST['room_id'] ?? null;

    if (!$studentId || !$roomId) {
        echo "Missing student ID or room ID.";
        exit;
    }

    // ✅ Step 1: Update roomregistration status to 'Left'
    $stmt1 = $conn->prepare("UPDATE roomregistration SET RoomStatus = 'Left' WHERE StudentID = ? AND RoomID = ?");
    $stmt1->bind_param("ii", $studentId, $roomId);
    if (!$stmt1->execute()) {
        echo "Error updating roomregistration: " . $stmt1->error;
        exit;
    }
    $stmt1->close();

    // ✅ Step 2: Mark the room as available again
    $stmt2 = $conn->prepare("UPDATE room SET RoomAvailability = 1 WHERE roomid = ?");
    $stmt2->bind_param("i", $roomId);
    if ($stmt2->execute()) {
        echo "Student evicted and room marked as available.";
    } else {
        echo "Error updating room availability: " . $stmt2->error;
    }
    $stmt2->close();

    $conn->close();
}
?>
