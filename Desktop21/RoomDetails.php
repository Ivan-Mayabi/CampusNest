<?php
session_start();
include('../connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: ../Login/studentlogin.html");
    exit;
}

$userEmail = $_SESSION['user_email'];

// Check if roomid is passed
if (!isset($_GET['roomid'])) {
    die("No room ID specified.");
}
$roomID = intval($_GET['roomid']);

// Handling booking POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book'])) {
    $update = mysqli_query($conn, "UPDATE room SET RoomAvailability = 0 WHERE roomid = $roomID AND RoomAvailability = 1");

    if ($update && mysqli_affected_rows($conn) > 0) {
        echo "<script>alert('Room booked successfully'); window.location.href='../Desktop22/Stud_homepage.php';</script>";
        exit;
    } else {
        echo "<script>alert('Room is already booked'); window.history.back();</script>";
        exit;
    }
}

// Fetch room details
$result = mysqli_query($conn, "SELECT * FROM room WHERE roomid = $roomID");
$room = mysqli_fetch_assoc($result);

if (!$room) {
    die("Room not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link rel="stylesheet" href="RoomDetails.css">
</head>

<body>
    <img src="Campusnestlogo.jpg" alt="Logo" class="logo"> 

    <div class="container">
        <div class="sidebar">
            <h2>ROOM DETAILS</h2>
        </div>

        <div class="main-content">
            <div class="room-details">
                <h3><?php echo htmlspecialchars($room['RoomName']); ?></h3>
                <p><strong>Price:</strong> <?php echo htmlspecialchars($room['RoomPrice']); ?></p>
                <p><strong>Status:</strong> <?php echo nl2br(htmlspecialchars($room['RoomAvailability'] ? "Available" : "Booked")); ?></p>

                <?php if (!empty($room['roomPhoto'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($room['roomPhoto']); ?>" alt="Room Photo">
                <?php endif; ?> 
            </div>

            <?php if ($room['RoomAvailability']): ?>
                <form method="POST" class="book-form">
                    <button type="submit" name="book" class="book-button">Book</button>
                </form>
            <?php else: ?>
                <p style="color: red; font-weight:bold;">This room is already booked</p>   
            <?php endif; ?> 
        </div>  
    </div>
</body>    
</html>
