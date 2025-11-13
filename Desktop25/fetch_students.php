<?php
session_start();
require_once '../connection.php'; // Adjust path if needed

if (!isset($_SESSION['user_id'])) {
    echo "<p>You must be logged in to view student requests.</p>";
    exit;
}

$landlord_id = $_SESSION['user_id'];
$filter = $_POST['query'] ?? '';

//  Updated SQL query to also fetch roomid
$sql = "SELECT
            s.userid AS student_id,
            s.userFname AS student_fname,
            s.userLname AS student_lname,
            r.roomid AS room_id, -- added this
            r.RoomName,
            rr.RoomStatus,
            h.HouseName
        FROM roomregistration rr
        INNER JOIN room r ON r.roomid = rr.roomid
        INNER JOIN users s ON rr.StudentID = s.userid
        INNER JOIN house h ON r.houseid = h.houseid
        WHERE h.LandlordID = ?";

$params = [$landlord_id];
$types = 'i';

if (!empty($filter)) {
    $sql .= " AND (s.userFname LIKE ? OR s.userLname LIKE ? OR r.RoomName LIKE ? OR h.HouseName LIKE ?)";
    $filterParam = "%" . $filter . "%";
    $params[] = $filterParam;
    $params[] = $filterParam;
    $params[] = $filterParam;
    $params[] = $filterParam;
    $types .= 'ssss';
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
            <h3>" . htmlspecialchars($row['student_fname']) . " " . htmlspecialchars($row['student_lname']) . "</h3>
            <p><strong>House:</strong> " . htmlspecialchars($row['HouseName']) . "</p>
            <p><strong>Room:</strong> " . htmlspecialchars($row['RoomName']) . "</p>
            <p><strong>Status:</strong> " . htmlspecialchars($row['RoomStatus']) . "</p>
        </div>
        <div class='student-actions'>
            <button class='approve-btn' data-student-id='{$row['student_id']}' data-room-id='{$row['room_id']}'>APPROVE</button>
            <button class='evict-btn' data-student-id='{$row['student_id']}' data-room-id='{$row['room_id']}'>EVICT</button>
        </div>
    </div>
    ";
}

$stmt->close();
$conn->close();
?>
