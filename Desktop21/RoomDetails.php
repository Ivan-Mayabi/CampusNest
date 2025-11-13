<?php
session_start();
include('../connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: ../Login/Login.php");
    exit;
}

$userEmail = $_SESSION['user_email'];
$studentid = $_SESSION['user_id'];

// Check if roomid is passed
if (!isset($_GET['roomid'])) {
    die("No room ID specified.");
}
$roomID = intval($_GET['roomid']);

// Handle booking
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book'])) {
    // First: Set the room as unavailable (0 = not available)
    $update = mysqli_query($conn, "UPDATE room SET RoomAvailability = 0 WHERE roomid = $roomID AND RoomAvailability = 1");

    // Second: Insert booking with status 'Pending'
    $update1 = mysqli_query($conn, "INSERT INTO roomregistration (roomid, studentid, RoomStatus) VALUES ($roomID, $studentid, 'Pending')");

    if ($update && $update1 && mysqli_affected_rows($conn) > 0) {
        // Success
        header("Location: ../Desktop22/Stud_homepage.php");
        exit;
    } else {
        echo "<script>alert('Room is already booked or booking failed'); window.history.back();</script>";
        exit;
    }
}


// Fetch room detail
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
    <title>Room Details</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff8e7;
            margin: 0;
            padding: 0;
        }

        .logo {
            width: 150px;
            height: auto;
            margin: 20px;
            display: block;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #b3395b;
            padding: 20px;
            min-height: 100vh;
            color: white;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-top: 20px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            background-color: white;
        }

        .room-details {
            margin-bottom: 30px;
        }

        .room-details h3 {
            color: #b3395b;
            margin-bottom: 10px;
        }

        .room-details p {
            margin-bottom: 10px;
            color: #333;
        }

        .room-details img {
            max-width: 400px;
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-top: 20px;
        }

        .book-form button {
            padding: 10px 20px;
            background-color: #b3395b;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .book-form button:hover {
            background-color: #e37c74;
        }

        .error-msg {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <img src="Campusnestlogo.jpg" alt="Logo" class="logo">
            <h2>ROOM DETAILS</h2>
        </div>

        <div class="main-content">
            <div class="room-details">
                <h3><?php echo htmlspecialchars($room['RoomName']); ?></h3>
                <p><strong>Price:</strong> <?php echo htmlspecialchars($room['RoomPrice']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($room['RoomAvailability'] ? "Available" : "Booked"); ?></p>

                <?php if (!empty($room['roomPhoto'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($room['roomPhoto']); ?>" alt="Room Photo">
                <?php endif; ?>
            </div>

            <?php if ($room['RoomAvailability']): ?>
                <form method="POST" class="book-form">
                    <button type="submit" name="book">Book Room</button>
                </form>
            <?php else: ?>
                <p class="error-msg">This room is already booked.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
