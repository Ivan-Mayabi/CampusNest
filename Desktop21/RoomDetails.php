<?php
session_start();
include('connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ________");   // Redirect to login page if not logged in. Check path
    exit;
}

$userID = $_SESSION['user_id'];

// Ensure roomid is passed
if (!isset($_GET['roomid'])) {
    die("No room ID specified.");
}

$roomID = intval($_GET['roomid']);

// Handling booking POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book'])) {
    $update = mysqli_query($conn, "UPDATE room SET RoomAvailability = 0 WHERE roomid = $roomID AND RoomAvailability = 1");

    if ($update && mysqli_affected_rows($conn) > 0) {
        echo "<script>alert('Room booked successfully'); window.location.href='_________';</script>"; // Redirect to MyHome page after booking. Put right path
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
    <link rel="stylesheet" href="Desktop21/RoomDetails.css"> <!-- Ensure correct css path -->
    <img src="Desktop21/logo.png" alt="Logo" class="logo"> <!-- Ensure correct logo path -->
</head>

<body>
    <div class ="container">
        <div class="sidebar">
          <h2>ROOM DETAILS</h2>
        </div>

        <div class ="main-content">
            <div class = "back-button">
                <a href = "__________"> <!-- Replace with correct path -->
                    <div class="box">  
                        <div class="icon">
                            <img src="Desktop21/left-arrow.png" alt="Back">  <!-- Replace with correct path -->
                        </div>
                    </div>
                </a>   
            </div>

            <div class= "room-details">
                <h3><?php echo htmlspecialchars($room['RoomName']); ?></h3>
                <p><strong>Price:</strong> <?php echo htmlspecialchars($room['roomprice']); ?></p>
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($room['RoomAvailability'] ? "Available" : "Booked")); ?></p>

                <?php if (!empty($room['roomPhoto'])): ?>
                    <img src= "data:image/jpeg;base64,<?php echo base64_encode($room['roomPhoto']); ?>" alt="Room Photo" style="max-width: 100%;">
                <?php endif; ?> 
            </div>

        </div>  
    </div>

</body>    
</html>