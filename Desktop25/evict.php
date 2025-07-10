<?php
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;

    // Update the RoomStatus to 'evicted' for the given ID
    $stmt = $conn->prepare("UPDATE roomregistration SET RoomStatus = 'left' WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Student evicted successfully.";
    } else {
        echo "Error evicting student.";
    }
}
?>
