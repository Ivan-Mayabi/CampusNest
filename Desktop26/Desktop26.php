<?php
require "../connection.php";

session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['user_role'] !== 'R001') {
    header("Location: ../Login/Login.html");
    exit;
}

$landlordID = $_SESSION["user_id"];

// Total properties
$sqlTotal = "SELECT COUNT(*) AS total FROM house WHERE LandLordID = ?";
$stmtTotal = $conn->prepare($sqlTotal);
$stmtTotal->bind_param("i", $landlordID);
$stmtTotal->execute();
$totalResult = $stmtTotal->get_result();
$totalHouses = $totalResult->fetch_assoc()['total'] ?? 0;

// Total vacant rooms
$sqlVacant = "SELECT COUNT(*) AS vacant FROM room WHERE HouseId IN (SELECT houseid FROM house WHERE LandLordID = ?) AND RoomAvailability = 1";
$stmtVacant = $conn->prepare($sqlVacant);
$stmtVacant->bind_param("i", $landlordID);
$stmtVacant->execute();
$vacantResult = $stmtVacant->get_result();
$totalVacant = $vacantResult->fetch_assoc()['vacant'] ?? 0;

// House list
$sqlHouses = "SELECT houseid, HouseName, HouseDescription, housePhoto FROM house WHERE LandLordID = ?";
$stmtHouses = $conn->prepare($sqlHouses);
$stmtHouses->bind_param("i", $landlordID);
$stmtHouses->execute();
$housesResult = $stmtHouses->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Homes - Landlord View</title>
  <style>
    /* Embed your provided CSS here */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: "Segoe UI", sans-serif; background-color: #fff8e7; height: 100vh; display: flex; }
    .page-wrapper { display: flex; width: 100%; }

    .sidebar { width: 250px; background-color: #b3395b; padding: 20px 0; min-height: 100vh; }
    .logo-container { width: 140px; height: 100px; margin: 20px auto 10px; }
    .logo-container img { width: 100%; height: 100%; object-fit: contain; }

    .sidebar ul { list-style: none; padding: 0; }
    .sidebar ul li { margin: 20px 0; }
    .sidebar ul li a { color: white; text-decoration: none; padding: 12px 20px; display: block; }
    .sidebar ul li.active a, .sidebar ul li a:hover { background-color: #e37c74; border-radius: 6px; }

    .container { flex: 1; padding: 40px; display: flex; flex-direction: column; gap: 20px; overflow-y: auto; }

    .filter-wrapper { border: 3px solid #b2395b; border-radius: 8px; display: flex; align-items: center; padding: 0; max-width: 700px; height: 60px; overflow: hidden; background-color: white; }
    .filter-wrapper input[type="text"] { padding: 10px 20px; width: 100%; font-size: 20px; border: none; outline: none; color: black; font-family: "Segoe UI", sans-serif; }
    .filter-wrapper button { background-color: transparent; border: none; font-size: 28px; padding: 0 20px; cursor: pointer; color: #b2395b; transition: color 0.3s; }
    .filter-wrapper button:hover { color: #e38277; }

    .student-entry { display: flex; justify-content: space-between; background-color: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); max-width: 700px; gap: 20px; align-items: center; }

    .student-info h3 { margin-bottom: 10px; color: #333; }
    .student-info p { margin: 5px 0; color: #555; }

    .student-actions { display: flex; flex-direction: column; gap: 8px; }
    .student-actions button { padding: 8px 14px; border-radius: 6px; border: 1px solid #b2395b; background-color: transparent; color: #b2395b; font-weight: bold; cursor: pointer; transition: background-color 0.3s ease; }
    .student-actions button:hover { background-color: #e37c74; color: white; border: none; }

    .no-results { color: #b2395b; font-weight: bold; padding: 20px; }
  </style>
</head>
<body>

<div class="page-wrapper">
  <div class="sidebar">
    <div class="logo-container">
      <img src="../Desktop25/images/Campusnestlogo.jpg" alt="Logo" width="140">
    </div>
    <ul>
      <li><a href="../Desktop25/landlorddashboard.html">REQUESTS</a></li>
      <li class="active"><a href="#">MY HOMES</a></li>
      <li><a href="../Logout/logout.php">SIGN OUT</a></li>
    </ul>
  </div>

  <div class="container">
    <div class="student-entry" style="justify-content:center; gap:40px;">
      <div class="student-info">
        <h3><?php echo $totalHouses; ?></h3>
        <p>Total Properties Owned</p>
      </div>
      <div class="student-info">
        <h3><?php echo $totalVacant; ?></h3>
        <p>Vacant Rooms</p>
      </div>
      <div class="student-actions">
        <a href="../Desktop29/desktop29.php"><button>Add Homes</button></a>
      </div>
    </div>

    <?php while ($house = $housesResult->fetch_assoc()):
      $housePhoto = base64_encode($house["housePhoto"]); ?>
      <div class="student-entry">
        <div class="student-info">
          <h3><?php echo htmlspecialchars($house['HouseName']); ?></h3>
          <p><?php echo htmlspecialchars($house['HouseDescription']); ?></p>
        </div>
        <img src="data:image/png;base64,<?php echo $housePhoto; ?>" alt="House Image" width="100" height="100" style="border-radius:12px;">
        <div class="student-actions">
          <a href="../Desktop27/EditHouse.php?houseid=<?php echo $house['houseid']; ?>"><button>Edit</button></a>
          <a href="../Desktop28/addroom.php?houseid=<?php echo $house['houseid']; ?>"><button>Add Rooms</button></a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</body>
</html>
