<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit House</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            background-color: #b3395b;
            width: 250px;
            padding: 20px;
            box-sizing: border-box;
        }

        .sidebar h2 {
            color: whitesmoke;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 20px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: whitesmoke;
            font-weight: bold;
        }

        .sidebar ul li a:hover {
            color: #78053f;
        }

        .main-content {
            flex: 1;
            background-color: whitesmoke;
            padding: 40px;
            position: relative;
        }

        form {
            margin-top: 100px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 500px;
        }

        label {
            font-weight: normal;
            text-align: left;
            color: #333;
        }

        input[type="text"],
        input[type="number"] {
            padding: 8px;
            border: 1px solid #b3395b;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }

        textarea {
            padding: 8px;
            border: 1px solid #b3395b;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            resize: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #c0395f;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        button:disabled {
            background-color: gray;
            cursor: not-allowed;
        }

        button:hover {
            background-color: #e7a57b;
        }
    </style>
</head>
<body>

<?php
require ('../connection.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/Login.html");
    exit;
}

$landlordID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['houseid'])) {
    $houseID = intval($_GET['houseid']);
    $result = mysqli_query($conn, "SELECT * FROM house WHERE houseid = $houseID");
    $house = mysqli_fetch_assoc($result);

    if (!$house) {
        die("House not found");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $houseID = intval($_POST['houseid']);
    $houseName = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "UPDATE house SET
        HouseName = '$houseName',
        HouseLocation = '$location',
        HouseDescription = '$description'";

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = addslashes(file_get_contents($_FILES['photo']['tmp_name']));
        $sql .= ", housePhoto = '$photo'";
    }

    $sql .= " WHERE houseid = $houseID";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('House details updated successfully'); window.location.href='../Desktop26/Desktop26.php';</script>";
        exit;
    } else {
        echo "Error updating house: " . mysqli_error($conn);
    }
}
?>

<div class="container">
    <div class="sidebar">
        <h2>EDIT HOUSE</h2>
        <ul>
            <li><a href="#">EDIT HOUSE</a></li>
            <li><a href="../Desktop28/addroom.php?houseid=<?php echo $houseID;?>">ADD ROOM</a></li>
            <li><a href="../Desktop26/Desktop26.php">MY HOMES</a></li>
        </ul>
    </div>

    <div class="main-content">
        <form action="EditHouse.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="houseid" value="<?php echo htmlspecialchars($house['houseid']); ?>">

            <label>Name of House</label>
            <input type="text" name="name" placeholder="Enter house name" value="<?php echo htmlspecialchars($house['HouseName']); ?>" required>

            <label>Location</label>
            <input type="text" name="location" placeholder="Enter location" value="<?php echo htmlspecialchars($house['HouseLocation']); ?>" required>

            <label>Description</label>
            <textarea name="description" rows="6" placeholder="Enter house description"><?php echo htmlspecialchars($house['HouseDescription']); ?></textarea>

            <label>Update Photo</label>
            <input type="file" name="photo"><br>

            <button type="submit">Update House</button>
        </form>
    </div>
</div>

</body>
</html>
