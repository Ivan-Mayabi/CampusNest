<?php
require_once "../connection.php"; // Update if needed

// Handle both empty and non-empty query
$search = '';
if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($conn, $_POST['query']);
}

$sql = "SELECT * FROM house";

if (!empty($search)) {
    $sql .= " WHERE HouseName LIKE '%$search%'
              OR HouseLocation LIKE '%$search%'
              OR HouseDescription LIKE '%$search%'";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($property = mysqli_fetch_assoc($result)) {

        // 👇 Base64 BLOB image support
        $imgSrc = '';
        if (!empty($property['housePhoto'])) {
            $imgData = base64_encode($property['housePhoto']);
            $imgSrc = 'data:image/jpeg;base64,' . $imgData;
        } else {
            $imgSrc = 'images/default.jpg'; // Fallback image
        }

        echo '<a href="../Desktop19/desktop19.php?houseId=' . $property["houseid"] . '" style="text-decoration:none;color:inherit">
                <div class="property-card">
                    <img src="' . $imgSrc . '" alt="Property Image" />
                    <div class="property-info">
                        <h3>' . htmlspecialchars($property['HouseName']) . '</h3>
                        <ul>
                            <li><strong>Location:</strong> ' . htmlspecialchars($property['HouseLocation']) . '</li>
                            <li><strong>Description:</strong> ' . htmlspecialchars($property['HouseDescription']) . '</li>
                        </ul>
                    </div>
                </div>
              </a>';
    }
} else {
    echo "<p>No properties found matching your search.</p>";
}
?>
