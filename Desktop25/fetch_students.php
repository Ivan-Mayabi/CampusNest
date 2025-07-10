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
            s.userid AS student_id,
            s.userFname AS student_fname,
            s.userLname AS student_lname,
            r.RoomName,
            rr.RoomStatus,
            h.HouseName
        FROM roomregistration rr
        inner join room r on r.roomid = rr.roomid
        inner JOIN users s ON rr.StudentID = s.userid
        inner join house h on r.houseid=h.houseid
        WHERE h.LandlordID = ?";

$params = [$landlord_id];
$types = 'i';

if (!empty($filter)) {
    $sql .= " AND (s.name LIKE ? OR r.RoomName LIKE ? OR h.HouseName LIKE ?)";
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
            <h3>{$row['student_fname']} {$row['student_lname']}</h3>
            <p><strong>House:</strong> {$row['HouseName']}</p>
            <p><strong>Room:</strong> {$row['RoomName']}</p>
            <p><strong>Status:</strong> {$row['RoomStatus']}</p>
        </div>
        <div class='student-actions'>
            <button class='approve-btn' data-id= " <? = $row['student_id']?>" data-id= " <?=$row['roomid']?>" >APPROVE</button>
            <button class='evict-btn' data-id="<? = $row['student_id']?"  data-id = "<? = $row['roomid']?">EVICT</button>
        </div>
    </div>
    ";
}

$stmt->close();
$conn->close();
?>
