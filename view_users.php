<?php
// Include the database connection
require_once "connection.php";

// Query to get all users
$sql = "SELECT id, full_name, email, gender, dob FROM registration";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>View Registered Users</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: floralwhite;
      padding: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      border: 1px solid #ccc;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background-color: #c0395f;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9d5d3;
    }
  </style>
</head>
<body>

<h2>Registered Users</h2>

<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Full Name</th><th>Email</th><th>Gender</th><th>Date of Birth</th></tr>";

    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".htmlspecialchars($row['id'])."</td>";
        echo "<td>".htmlspecialchars($row['full_name'])."</td>";
        echo "<td>".htmlspecialchars($row['email'])."</td>";
        echo "<td>".htmlspecialchars($row['gender'])."</td>";
        echo "<td>".htmlspecialchars($row['dob'])."</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No users found.</p>";
}

$conn->close();
?>

</body>
</html>
