<?php
require ('../connection.php');
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/studentlogin.html"); // Redirect to login page if not logged in
    exit;
}

$landlordID = $_SESSION['user_id'];

// fetch house details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['houseid'])) {
    $houseID = intval($_GET['houseid']);
    $result = mysqli_query($conn, "SELECT * FROM house WHERE houseid = $houseID");
    $house = mysqli_fetch_assoc($result);

    if (!$house) {
        die("House not found");
    }
}

// process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $houseID = intval($_POST['houseid']);
    $houseName = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $rooms = intval($_POST['rooms']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if ($rooms <= 0) {
        echo "<script>alert('Number of rooms must be a positive number'); window.history.back();</script>";
        exit;
    }

    $sql = "UPDATE house SET
        HouseName = '$houseName',
        Location = '$location',
        Rooms = $rooms,
        Description = '$description'";

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = addslashes(file_get_contents($_FILES['photo']['tmp_name']));
        $sql .= ", HousePhoto = '$photo'";
    }

    $sql .= " WHERE houseid = $houseID";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('House details updated successfully'); window.location.href='_______';</script>"; // Replace with your correct page
        exit;
    } else {
        echo "Error updating house: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit House</title>
    <link rel="stylesheet" href="EditHouse.css">
</head>
<body>

    <div class="container">
        <div class="sidebar">
            <h2>EDIT HOUSE</h2>
            <ul>
                <li><a href="#">EDIT HOUSE</a></li>
                <li><a href="../Desktop28/addroom.php?houseid=<?php echo $houseID;?>">ADD ROOM</a></li>
                <li><a href="../Desktop26/Desktop26.php">MY HOME</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="back-button">
                <a href="LandingPage.html">
                    <div class="box">
                        <div class="icon">
                            <img src="back.png" alt="Back Icon">
                        </div>
                    </div>
                </a>
                <!--   <p class="back-text">BACK</p>  -->
            </div>

            <form action ="EditHouse.php" method="POST" enctype="multipart/form-data">
                <input type ="hidden" name ="houseid" value ="<?php echo $house['houseid']; ?>">
                
                <label>Name of House</label>
                <input type="text" name="name" placeholder="Enter house name" value="<?php echo htmlspecialchars($house['HouseName']); ?>" required>

                <label>Location</label>
                <input type="text" name="location" placeholder="Enter location" value="<?php echo htmlspecialchars($house['HouseLocation']); ?>" required>

                <label>Number of Rooms</label>
                <input type="number" name="rooms" placeholder="Enter number of rooms" value="<?php echo htmlspecialchars($house['NumberOfRooms']); ?>" min="1" required>

                <label>Description</label>
                <textarea name="description" rows="6"><?php echo htmlspecialchars($house['HouseDescription']); ?></textarea>

                <label>Update Photo</label>
                <input type="file" name="photo"><br>

                <button type="submit">Update House</button>
            </form>
        </div>
    </div>

</body>
</html>