<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Student') {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Homes - Student View</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      min-height: 100vh;
      background-color: #fff0f3;
    }

    a{
      text-decoration:none;
    }
    a:hover{
      transform:translate(0,-10);
    }

    .sidebar {
      background-color: #f2f2f2;
      padding: 30px 20px;
      width: 220px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .sidebar img {
      width: 100px;
      height: 100px;
      border-radius: 12px;
      object-fit: cover;
      margin-bottom: 20px;
    }

    .sidebar a {
      font-weight: bold;
      color: #333;
      text-decoration: none;
      margin-bottom: 20px;
      font-size: 16px;
    }

    .sidebar a.active {
      background-color: #d9d9d9;
      padding: 8px 12px;
      border-radius: 8px;
      color: #c0395f;
    }

    .main-content {
      flex: 1;
      padding: 40px;
      background-color: white;
    }

    .main-content h2 {
      margin-bottom: 20px;
      color: #c0395f;
    }

    .property-card {
      display: flex;
      align-items: center;
      background-color: #fff0f3;
      border: 1px solid #f9d5d3;
      border-radius: 12px;
      padding: 15px 20px;
      margin-bottom: 20px;
    }

    .property-card img {
      width: 80px;
      height: 80px;
      border-radius: 10px;
      margin-right: 20px;
    }

    .property-info {
      flex: 1;
    }

    .property-info h4 {
      margin-bottom: 5px;
      color: #c0395f;
    }

    .property-info p {
      font-size: 14px;
      color: #333;
    }

    .property-status {
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: bold;
      color: white;
    }

    .status-approved {
      background-color: #c0395f;
    }

    .status-pending {
      background-color: #e7a57b;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <img src="./logo.jpg" alt="logo">
    <a href="#" class="active">MY HOMES</a>
    <a href="#">ROOMMATES</a>
    <a href="#">BOOKINGS</a>
    <a href="logout.php">SIGN OUT</a>
  </div>

  <div class="main-content">
    <h2>My Homes</h2>

    <a href="Desktop19/desktop19.php"><div class="property-card">
      <img src="./Room1.jpg" alt="Home Image">
      <div class="property-info">
        <h4>Booked House 1</h4>
        <p>Located next to cafeteria, 10 min walk to class</p>
      </div>
      <div class="property-status status-approved">
        Approved
      </div>
    </div></a>

    <div class="property-card">
      <img src="./Room2.jpg" alt="Home Image">
      <div class="property-info">
        <h4>Booked House 2</h4>
        <p>Spacious room with balcony view</p>
      </div>
      <div class="property-status status-pending">
        Pending Approval
      </div>
    </div>

  </div>

</body>
</html>
