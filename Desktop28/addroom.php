<?php
session_start();

// ✅ Only allow logged-in landlords
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/studentlogin.html");
    exit();
}

$houseid = $_GET["houseid"];
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room - Campus Nest</title>
    <link rel="stylesheet" href="AddRoom.css">
</head>
<body>
<div class="page-wrapper">
    <div class="sidebar">
        <ul>
            <li><a href="../Desktop27/EditHouse.php?houseid=<?php echo $houseid; ?>">EDIT HOUSE</a></li>
            <li class="active"><a href="">ADD ROOM</a></li>
            <li><a href="../Desktop26/Desktop26.php">MY HOME</a></li>
            <li><a href="../Logout/logout.php">LOGOUT</a></li>
        </ul>
    </div>

    <div class="container"> 
        <div class="form-header">
            <h1>ADD ROOM</h1>
            <div class="logo-placeholder">
                <img src="images/Campusnestlogo.jpg" width="140">
            </div>
        </div>

        <!-- 🔗 Form submits to PHP to insert room -->
        <form method="POST" action="registerRoom.php">
            <div class="form-group">
                <label for="roomName">Name</label>
                <input type="text" id="roomName" name="roomName" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="availability">Availability</label>
                <select id="availability" name="availability" required>
                    <option value="">Select availability</option>
                    <option value="available">Available</option>
                    <option value="vacant">Vacant</option>
                </select>
            </div>
            <button type="submit">ADD ROOM</button>
        </form>
    </div>
</div>
</body>
</html>
