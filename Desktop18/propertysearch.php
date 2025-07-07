<?php
session_start();  // Add this to maintain session
require_once "../connection.php"; // adjust path if needed

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($conn, $_POST['query']);

    $sql = "SELECT * FROM house WHERE HouseName LIKE '%$search%'
            OR HouseLocation LIKE '%$search%'
            OR HouseDescription LIKE '%$search%'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($property = mysqli_fetch_assoc($result)) {

            // handle image blob (only if you're storing base64 or file paths – for now we skip it if NULL)
            $imgSrc = '';
            if (!empty($property['housePhoto'])) {
                $imgData = base64_encode($property['housePhoto']);
                $imgSrc = 'data:image/jpeg;base64,' . $imgData;
            } else {
                $imgSrc = 'images/default.jpg'; // fallback image if no photo
            }

            echo '<a href="../Desktop19/desktop19.php?houseId='.$property["houseid"].'" style="text-decoration:none;color:inherit">'.'<div class="property-card">
                    <img src="' . $imgSrc . '" alt="Property Image" />
                    <div class="property-info">
                        <h3>' . htmlspecialchars($property['HouseName']) . '</h3>
                        <ul>
                            <li><strong>Location:</strong> ' . htmlspecialchars($property['HouseLocation']) . '</li>
                            <li><strong>Description:</strong> ' . htmlspecialchars($property['HouseDescription']) . '</li>
                        </ul>
                    </div>
                </div>'.'</a>';
        }
    } else {
        echo "<p>No properties found matching your search.</p>";
    }
}
?>
