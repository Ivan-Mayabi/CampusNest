<?php
session_start();
require_once '../connection.php'; // Adjust the path if needed

header('Content-Type: application/json');

if (!isset($_SESSION['landlord_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$landlord_id = $_SESSION['landlord_id'];
$landlord_name = $_SESSION['landlord_name'] ?? 'Landlord';

$action = $_GET['action'] ?? '';

try {
    if ($action === 'get_properties') {
        $filter = $_GET['filter'] ?? '';
        $sql = "SELECT * FROM house WHERE landlord_id = ?";

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
        $houses = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode([
            'landlord_name' => $landlord_name,
            'houses' => $houses
        ]);
    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
