<?php
require ('../connection.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/Login.html");
    exit;
}

$landlordID = $_SESSION['user_id'];

// Fetch house details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['houseid'])) {
    $houseID = intval($_GET['houseid']);
    $result = mysqli_query($conn, "SELECT * FROM house WHERE houseid = $houseID");
    $house = mysqli_fetch_assoc($result);
    if (!$house) {
        die("House not found");
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $houseID = intval($_POST['houseid']);
    $houseName = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "UPDATE house SET HouseName = '$houseName', HouseLocation = '$location', HouseDescription = '$description'";

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = addslashes(file_get_contents($_FILES['photo']['tmp_name']));
        $sql .= ", housePhoto = '$photo'";
    }

    $sql .= " WHERE houseid = $houseID";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('House updated successfully'); window.location.href='../Desktop26/Desktop26.php';</script>";
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
<title>Edit House</title>
<style>
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

form { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-width: 600px; display: flex; flex-direction: column; gap: 15px; }

label { color: #333; font-weight: bold; }
input[type="text"], textarea, input[type="file"] {
    padding: 10px;
    border: 1px solid #b3395b;
    border-radius: 8px;
    font-size: 16px;
}
textarea { resize: none; }

button {
    padding: 12px;
    background-color: #b3395b;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}
button:hover { background-color: #e37c74; }
</style>
</head>
<body>

<div class="page-wrapper">
    <div class="sidebar">
        <div class="logo-container">
            <img src="../Desktop25/images/Campusnestlogo.jpg" alt="Logo">
        </div>
        <ul>
            <li class="active"><a href="#">EDIT HOUSE</a></li>
            <li><a href="../Desktop28/addroom.php?houseid=<?php echo $house['houseid']; ?>">ADD ROOM</a></li>
            <li><a href="../Desktop26/Desktop26.php">MY HOMES</a></li>
        </ul>
    </div>

    <div class="container">
        <form action="EditHouse.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="houseid" value="<?php echo htmlspecialchars($house['houseid']); ?>">

            <label>Name of House</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($house['HouseName']); ?>" required>

            <label>Location</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($house['HouseLocation']); ?>" required>

            <label>Description</label>
            <textarea name="description" rows="5"><?php echo htmlspecialchars($house['HouseDescription']); ?></textarea>

            <label>Update Photo</label>
            <input type="file" name="photo">

            <button type="submit">Update House</button>
        </form>
    </div>
</div>

</body>
</html>
