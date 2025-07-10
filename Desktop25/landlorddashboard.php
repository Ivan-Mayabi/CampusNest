<?php
session_start();
require_once '../connection.php'; // adjust this path to your actual connection file

header('Content-Type: application/json');

// Ensure the landlord is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$landlord_id = $_SESSION['user_id'];
$filter = $_GET['filter'] ?? '';

try {
    // Get students who booked rooms in houses owned by the landlord
    $sql = "
        SELECT
            r.id AS registration_id,
            s.name AS student_name,
            s.email AS student_email,
            s.phone AS student_phone,
            h.HouseName,
            r.RoomNumber,
            r.RoomStatus
        FROM roomregistration r
        JOIN students s ON r.StudentID = s.id
        JOIN house h ON r.HouseID = h.HouseID
        WHERE h.landlord_id = ?
    ";

    if (!empty($filter)) {
        $sql .= " AND (s.name LIKE ? OR r.RoomNumber LIKE ?)";
        $stmt = $conn->prepare($sql);
        $like = "%$filter%";
        $stmt->bind_param("iss", $landlord_id, $like, $like);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $landlord_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
        print_r($students);
    }

    echo json_encode(['students' => $students]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
