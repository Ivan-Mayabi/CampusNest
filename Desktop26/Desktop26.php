<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Landlord') {
    header("Location: login.html");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "A1l2b3e4r5t6_", "db_webappdev_student_accomodation");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hardcoded landlord ID
$landlordID = 3;

// Fetch total properties
$sqlTotal = "SELECT COUNT(*) AS total FROM house WHERE LandLordID = $landlordID";
$totalResult = $conn->query($sqlTotal);
$totalHouses = $totalResult->fetch_assoc()['total'] ?? 0;

// Fetch total vacant rooms
$sqlVacant = "SELECT COUNT(*) AS vacant FROM room WHERE HouseId IN (SELECT houseid FROM house WHERE LandLordID = $landlordID) AND RoomAvailability = 1";
$vacantResult = $conn->query($sqlVacant);
$totalVacant = $vacantResult->fetch_assoc()['vacant'] ?? 0;

// Fetch house list
$sqlHouses = "SELECT houseid, HouseName, HouseDescription FROM house WHERE LandLordID = $landlordID";
$housesResult = $conn->query($sqlHouses);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Home - Landlord View</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    /* Same CSS as before */
    body { font-family: 'Segoe UI', sans-serif; display: flex; min-height: 100vh; background-color: #fff0f3; }
    .sidebar { background-color: #f2f2f2; padding: 30px 20px; width: 220px; display: flex; flex-direction: column; align-items: flex-start; }
    .sidebar img { width: 100px; height: 100px; border-radius: 12px; object-fit: cover; margin-bottom: 20px; }
    .sidebar a { font-weight: bold; color: #333; text-decoration: none; margin-bottom: 20px; font-size: 16px; }
    .sidebar a.active { background-color: #d9d9d9; padding: 8px 12px; border-radius: 8px; color: #c0395f; }
    .main-content { flex: 1; padding: 40px; background-color: white; }
    .top-stats { display: flex; justify-content: space-between; align-items: center; background-color: #f9d5d3; padding: 20px; border-radius: 12px; margin-bottom: 30px; }
    .stat-box { text-align: center; flex: 1; }
    .stat-number { font-size: 36px; font-weight: bold; color: #c0395f; }
    .stat-label { margin-top: 5px; color: #444; font-size: 14px; }
    .home-card { display: flex; align-items: center; background-color: #fff0f3; border: 1px solid #f9d5d3; border-radius: 12px; padding: 15px 20px; margin-bottom: 20px; }
    .home-card img { width: 80px; height: 80px; border-radius: 10px; margin-right: 20px; }
    .home-info { flex: 1; }
    .home-info h4 { margin-bottom: 5px; color: #c0395f; }
    .home-info p { font-size: 14px; color: #333; }
    .home-actions { display: flex; flex-direction: column; gap: 10px; }
    .home-actions button { padding: 8px 12px; border: none; background-color: #c0395f; color: white; border-radius: 8px; cursor: pointer; font-size: 14px; }
    .add-home-btn { margin-bottom: 20px; padding: 10px 16px; background-color: #e7a57b; color: white; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; }
  </style>
</head>
<body>

  <div class="sidebar">
    <img src="./logo.jpg" alt="Logo">
    <a href="#">REQUESTS</a>
    <a href="#" class="active">MY HOME</a>
    <a href="logout.php">SIGN OUT</a>
  </div>

  <div class="main-content">

    <div class="top-stats">
      <div class="stat-box">
        <div class="stat-number"><?php echo $totalHouses; ?></div>
        <div class="stat-label">Total Properties Owned</div>
      </div>
      <div class="stat-box">
        <div class="stat-number"><?php echo $totalVacant; ?></div>
        <div class="stat-label">Vacant Rooms</div>
      </div>
    </div>

    <button class="add-home-btn">Add Homes</button>

    <?php while ($house = $housesResult->fetch_assoc()): ?>
      <div class="home-card">
        <img src="./House1.jpg" alt="House Image">
        <div class="home-info">
          <h4><?php echo htmlspecialchars($house['HouseName']); ?></h4>
          <p>Description: <?php echo htmlspecialchars($house['HouseDescription']); ?></p>
        </div>
        <div class="home-actions">
          <button>Edit</button>
          <button>Add Rooms</button>
        </div>
      </div>
    <?php endwhile; ?>

  </div>

</body>
</html>
