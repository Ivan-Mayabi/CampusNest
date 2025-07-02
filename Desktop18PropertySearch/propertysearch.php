<?php
require_once "../connection.php"; // This file should have your DB connection code

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($conn, $_POST['query']);

    $sql = "SELECT * FROM properties WHERE name LIKE '%$search%' OR location LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($property = mysqli_fetch_assoc($result)) {
            echo '<div class="property-card">
                    <img src="' . htmlspecialchars($property['image_url']) . '" alt="Property Image" />
                    <div class="property-info">
                        <h3>' . htmlspecialchars($property['name']) . '</h3>
                        <ul>
                            <li><strong>Location:</strong> ' . htmlspecialchars($property['location']) . '</li>
                            <li><strong>Description:</strong> ' . htmlspecialchars($property['description']) . '</li>
                        </ul>
                    </div>
                </div>';
        }
    } else {
        echo "<p>No properties found matching your search.</p>";
    }
}
?>
