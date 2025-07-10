<?php
require_once '../connection.php'; // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['id'] ?? null;

    if ($studentId) {
        //
        $roomId = $_POST['room_id'] ?? null;

        if ($roomId) {
            //  Update only the specific student-room registration
            $stmt = $conn->prepare("UPDATE roomregistration SET RoomStatus = 'Approved' WHERE StudentID = ? AND RoomID = ?");
            $stmt->bind_param("ii", $studentId, $roomId);

            if ($stmt->execute()) {
                echo "Student approved successfully for this room.";
            } else {
                echo "Error updating room status: " . $stmt->error;
            }
        } else {
            echo "Room ID missing. Cannot approve without a specific room.";
        }
    } else {
        echo "Student ID missing.";
    }
}
?>
