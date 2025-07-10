<?php
session_start();
require_once '../connection.php'; // Adjust path if needed

if (!isset($_SESSION['user_id'])) {
    echo "<p>You must be logged in to view student requests.</p>";
    exit;
}

$landlord_id = $_SESSION['user_id'];
$filter = $_POST['query'] ?? '';

$sql = "SELECT
            s.id AS student_id,
            s.name AS student_name,
            r.RoomNumber,
            r.RoomStatus,
            h.HouseName
        FROM roomregistration r
        JOIN students s ON r.StudentID = s.id
        JOIN house h ON r.HouseID = h.id
        WHERE h.LandlordID = ?";

$params = [$landlord_id];
$types = 'i';

if (!empty($filter)) {
    $sql .= " AND (s.name LIKE ? OR r.RoomNumber LIKE ? OR h.HouseName LIKE ?)";
    $filterParam = "%" . $filter . "%";
    $params[] = $filterParam;
    $params[] = $filterParam;
    $params[] = $filterParam;
    $types .= 'sss';
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p class='no-results'>No student requests found.</p>";
    exit;
}

while ($row = $result->fetch_assoc()) {
    echo "
    <div class='student-entry'>
        <div class='student-info'>
            <h3>{$row['student_name']}</h3>
            <p><strong>House:</strong> {$row['HouseName']}</p>
            <p><strong>Room:</strong> {$row['RoomNumber']}</p>
            <p><strong>Status:</strong> {$row['RoomStatus']}</p>
        </div>
        <div class='student-actions'>
            <button class='approve-btn' data-id='{$row['student_id']}'>APPROVE</button>
            <button class='evict-btn' data-id='{$row['student_id']}'>EVICT</button>
        </div>
    </div>
    ";
}

$stmt->close();
$conn->close();
?>
