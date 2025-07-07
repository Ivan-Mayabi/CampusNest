<?php
include('connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$landlordID = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $roomName = mysqli_real_escape_string($conn, $_POST['name']);
    $roomPrice = intval($_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $houseID = isset($_POST['houseid']) ? intval($_POST['houseid']) : 1; // default is 1

    if ($roomPrice <= 0) {
        echo "<script>alert('Price must be a positive number!'); window.history.back();</script>";
        exit;
    }

    if (empty($roomName)) {
        echo "<script>alert('Room name cannot be empty.'); window.history.back();</script>";
        exit;
    }

    // handle photo upload
    $photo = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photo = addslashes(file_get_contents($_FILES['photo']['tmp_name']));
    }

    $roomAvailability = 1;

    $sql = "INSERT INTO room (RoomName, RoomPrice, RoomAvailability, HouseID, roomPhoto)
            VALUES ('$roomName', $roomPrice, $roomAvailability, $houseID, " . 
            ($photo ? "'$photo'" : "NULL") . ")";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Room added successfully!'); window.location.href='RoomDetails.html';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }

    mysqli_close($conn);
}
?>
