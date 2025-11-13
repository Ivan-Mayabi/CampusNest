<?php
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['id'] ?? null;
    $roomId = $_POST['room_id'] ?? null;

    if ($studentId && $roomId) {
        //  Use correct column names based on your DB
        $stmt = $conn->prepare("UPDATE roomregistration SET RoomStatus = 'Approved' WHERE StudentID = ? AND RoomId = ?");
        $stmt->bind_param("ii", $studentId, $roomId);

        if ($stmt->execute()) {
            echo "Student approved successfully for this room.";
        } else {
            echo "Error updating room status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Missing student ID or room ID.";
    }
} else {
    echo "Invalid request method.";
}
?>
