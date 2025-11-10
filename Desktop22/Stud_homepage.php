<?php
session_start();
require_once "../connection.php";

// Check logged-in user
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'R002') {
    header("Location: ../Login/Login.html");
    exit;
}

$userId = $_SESSION['user_id'];

// Get homes booked by this student
$sql = "
SELECT h.HouseName, h.HouseDescription, rm.roomPhoto, rm.roomName, rm.roomPrice, rr.RoomStatus
FROM house h
inner JOIN room rm ON h.houseid = rm.HouseId
inner JOIN roomregistration rr ON rm.roomid = rr.RoomId
WHERE rr.StudentID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Homes - Campus Nest</title>
    <link rel="stylesheet" href="Stud_homepage.css" />
    <style>
        /* Base styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", sans-serif; background-color: #fff8e7; height: 100vh; display: flex; }
        .page-wrapper { display: flex; width: 100%; }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #b3395b;
            padding: 20px 0;
            min-height: 100vh;
        }
        .logo-container {
            width: 140px;
            height: 100px;
            margin: 20px auto;
            background-image: url('../Desktop15/logo.png');
            background-size: cover;
            border-radius: 6px;
        }
        .sidebar ul { list-style: none; }
        .sidebar ul li { margin: 20px 0; text-align: center; }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
        }
        .sidebar ul li.active a,
        .sidebar ul li a:hover {
            background-color: #e37c74;
            border-radius: 6px;
        }

        /* Main content */
        .container {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .container h2 {
            color: #b3395b;
            margin-bottom: 20px;
        }

        /* Property cards */
        .property-card {
            display: flex;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            align-items: center;
            gap: 20px;
            max-width: 700px;
        }
        .property-card img {
            width: 140px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .property-details h3 {
            margin-bottom: 8px;
            color: #333;
        }
        .property-details ul {
            list-style-type: disc;
            padding-left: 20px;
            color: #555;
        }

        /* Status badge */
        .property-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            color: white;
        }
        .status-approved { background-color: #b3395b; }
        .status-pending { background-color: #e7a57b; }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="sidebar">
        <div class="logo-container"></div>
        <ul>
            <li><a href="../Desktop18/PropertySearch.html">SEARCH</a></li>
            <li class="active"><a href="#">MY HOMES</a></li>
            <li><a href="../Logout/logout.php">SIGN OUT</a></li>
        </ul>
    </div>

    <div class="container">
        <h2>My Homes</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="property-card">
                    <?php
                        $imgData = base64_encode($row['roomPhoto']);
                    ?>
                    <img src="data:image/png;base64,<?php echo $imgData;?>" alt="Home">
                    <div class="property-details">
                        <h3><?php echo htmlspecialchars($row['HouseName']).": ".htmlspecialchars($row['roomName']) ?></h3>
                        <ul>
                            <li><strong>Price</strong> <?php echo htmlspecialchars($row['roomPrice']);?></li>
                            <li><strong>Description:</strong> <?php echo htmlspecialchars($row['HouseDescription']);?></li>
                        </ul>
                    </div>
                    <div class="property-status <?php echo ($row['RoomStatus'] === 'Approved') ? 'status-approved' : 'status-pending'; ?>">
                        <?php echo htmlspecialchars($row['RoomStatus']); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No houses booked yet.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
