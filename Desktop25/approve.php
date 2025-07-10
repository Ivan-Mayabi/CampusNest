<?php
require_once '../connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $stmt = $conn->prepare("UPDATE roomregistration SET RoomStatus = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "Student approved successfully.";
}
?>