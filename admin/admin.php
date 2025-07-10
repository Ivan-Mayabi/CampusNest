<?php
require("../connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'] ?? null;
    $fname = $_POST['userFname'] ?? '';
    $lname = $_POST['userLname'] ?? '';
    $email = $_POST['userEmail'] ?? '';
    $phone = $_POST['userPhone'] ?? '';
    $role = $_POST['userRoleId'] ?? '';
    $password = $_POST['userPassword'] ?? '';
    $access = isset($_POST['userAccess']) ? 1 : 0;

    if ($user_id && $fname && $lname && $email && $phone && $role && $password) {
        $stmt = $conn->prepare("UPDATE users SET userFname = ?, userLname = ?, userEmail = ?, userPhone = ?, userRoleId = ?, userAccess = ?, userPassword = ? WHERE userID = ?");
        $stmt->bind_param("sssssssi", $fname, $lname, $email, $phone, $role, $access, $password, $user_id);
        if ($stmt->execute()) {
            $message = "User with ID $user_id updated successfully.";
        } else {
            $message = "Error updating user: " . $stmt->error;
        }
        $stmt->close();
    }
}

$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ADMIN - Manage Users</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="page-wrapper">
    <div class="sidebar">
        <ul>
            <li class="active"><a href="#">MANAGE USERS</a></li>
        </ul>
    </div>

    <div class="container">
        <div class="form-header">
            <h1>ADMINISTRATION</h1>
        </div>

        <?php if (!empty($message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <h2>All Registered Users</h2>
        <table border="1">
            <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Password</th>
                <th>Access</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <form method="POST">
                        <td><?php echo htmlspecialchars($row['userID']); ?><input type="hidden" name="user_id" value="<?php echo $row['userID']; ?>"></td>
                        <td><input type="text" name="userFname" value="<?php echo htmlspecialchars($row['userFname']); ?>" required></td>
                        <td><input type="text" name="userLname" value="<?php echo htmlspecialchars($row['userLname']); ?>" required></td>
                        <td><input type="email" name="userEmail" value="<?php echo htmlspecialchars($row['userEmail']); ?>" required></td>
                        <td><input type="text" name="userPhone" value="<?php echo htmlspecialchars($row['userPhone']); ?>" required></td>
                        <td><input type="text" name="userRoleId" value="<?php echo htmlspecialchars($row['userRoleId']); ?>" required></td>
                        <td><input type="text" name="userPassword" value="<?php echo htmlspecialchars($row['userPassword']); ?>" required></td>
                        <td>
                            <input type="checkbox" name="userAccess" value="1" <?php echo $row['userAccess'] ? 'checked' : ''; ?>>
                        </td>
                        <td>
                            <button type="submit" name="update_user">Update</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$result->free();
$conn->close();
?>
</body>
</html>
