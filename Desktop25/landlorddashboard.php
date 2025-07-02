<?php
$db = new PDO('mysql:host=localhost;dbname=campus_nest;charset=utf8', 'root', ''); // Update credentials
$action = $_GET['action'] ?? '';

if (!isset($_SESSION['landlord_id'])) {
    echo json_encode([]);
    exit;
}

$landlord_id = $_SESSION['landlord_id'];

try {
    switch ($action) {
        case 'get_students':
            $filter = $_GET['filter'] ?? '';
            $query = "SELECT * FROM students WHERE landlord_id = :landlord_id";
            $params = [':landlord_id' => $landlord_id];

            if (!empty($filter)) {
                $query .= " AND (name LIKE :filter OR location LIKE :filter)";
                $params[':filter'] = "%$filter%";
            }

            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($students);
            break;

        default:
            echo json_encode(['error' => 'Invalid action']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
