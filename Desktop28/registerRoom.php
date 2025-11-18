<?php
session_start();
require_once '../connection.php'; // adjust path if needed

// Make sure landlord is logged in for sure
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/Login.html");
    exit();
}

$houseid = $_GET["houseid"];


    $roomName = $_POST['roomName'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];
    if($availability=="available"){
        $roomavailability=1;
    }
    else{
        $roomavailability=0;
    }
    $landlordId = $_SESSION['user_id'];
    // print_r($_FILES);
    $imgData = file_get_contents($_FILES["roomPhoto"]["tmp_name"]);

    // ✅ Insert room for this landlord
    $stmt = $conn->prepare("INSERT INTO room (roomname, roomprice, roomavailability,houseid,roomphoto) VALUES (?, ?, ?, ?,?)");
    $stmt->bind_param("sssib", $roomName, $price, $roomavailability,$houseid,$imgData);
    $stmt->send_long_data(4,$imgData);


    if ($stmt->execute()) {
        // echo "✅ Room added successfully.";
        header("Location: ../Desktop26/Desktop26.php");
        exit;
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

?>
