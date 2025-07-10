<?php
session_start();
require_once 'connection.php'; // adjust the path if needed

header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$landlord_id = $_SESSION['user_id'];
$landlord_name = $_SESSION['user_name'] ?? 'Landlord';

$action = $_GET['action'] ?? '';

try {
    if ($action === 'get_properties') {
        // 💡 Load landlord's houses
        $filter = $_GET['filter'] ?? '';
        $sql = "SELECT * FROM house WHERE landlord_id = ?";

        if (!empty($filter)) {
            $sql .= " AND (HouseName LIKE ? OR HouseLocation LIKE ?)";
            $stmt = $conn->prepare($sql);
            $like = "%$filter%";
            $stmt->bind_param("iss", $landlord_id, $like, $like);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $landlord_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $houses = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'landlord_name' => $landlord_name,
            'houses' => $houses
        ]);
    }

    elseif ($action === 'get_students') {
        // 💡 Load students linked to landlord's properties
        $filter = $_GET['filter'] ?? '';
        $sql = "SELECT * FROM students WHERE landlord_id = ?";

        if (!empty($filter)) {
            $sql .= " AND (name LIKE ? OR location LIKE ?)";
            $stmt = $conn->prepare($sql);
            $like = "%$filter%";
            $stmt->bind_param("iss", $landlord_id, $like, $like);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $landlord_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $students = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'landlord_name' => $landlord_name,
            'students' => $students
        ]);
    }

    else {
        echo json_encode(['error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
