<?php
session_start();
require("../connection.php");

// Initialize variables
$message = '';
$messageType = '';

// ✅ Security: Check if user is admin (uncomment when authentication is implemented)
// if (!isset($_SESSION['userRoleId']) || $_SESSION['userRoleId'] != 1) {
//     header("Location: login.php");
//     exit();
// }

// ✅ Delete user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_user'])) {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    
    if ($user_id) {
       
/** @var mysqli $conn */

        $stmt = $conn->prepare("DELETE FROM users WHERE userID = ?");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            $message = "✅ User with ID $user_id deleted successfully.";
            $messageType = 'success';
        } else {
            $message = "❌ Error deleting user: " . $stmt->error;
            $messageType = 'error';
        }
        $stmt->close();
    }
}

// ✅ Update user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
    // Sanitize and validate inputs
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $fname = trim($_POST['userFname'] ?? '');
    $lname = trim($_POST['userLname'] ?? '');
    $email = filter_input(INPUT_POST, 'userEmail', FILTER_VALIDATE_EMAIL);
    $phone = trim($_POST['userPhone'] ?? '');
    $role = filter_input(INPUT_POST, 'userRoleId', FILTER_VALIDATE_INT);
    $password = $_POST['userPassword'] ?? '';
    $access = !empty($_POST['userAccess']) ? 1 : 0;
    
    // Validation
    if (!$user_id || !$fname || !$lname || !$email || !$phone || !$role || !$password) {
        $message = "❌ All fields are required and must be valid.";
        $messageType = 'error';
    } else {
        // Hash password if it's been changed (check if it's not already hashed)
        if (strlen($password) < 60) { // bcrypt hashes are 60 chars
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $hashed_password = $password;
        }
        
        $stmt = $conn->prepare("UPDATE users SET userFname = ?, userLname = ?, userEmail = ?, userPhone = ?, userRoleId = ?, userAccess = ?, userPassword = ? WHERE userID = ?");
        $stmt->bind_param("ssssiisi", $fname, $lname, $email, $phone, $role, $access, $hashed_password, $user_id);
        
        if ($stmt->execute()) {
            $message = "✅ User with ID $user_id updated successfully.";
            $messageType = 'success';
        } else {
            $message = "❌ Error updating user: " . $stmt->error;
            $messageType = 'error';
        }
        $stmt->close();
    }
}

// ✅ Load users with role information
$query = "SELECT u.*, 
          CASE 
              WHEN u.userRoleId = 1 THEN 'Admin'
              WHEN u.userRoleId = 2 THEN 'User'
              ELSE 'Unknown'
          END as roleName
          FROM users u
          ORDER BY u.userID ASC";
$result = $conn->query($query);

if (!$result) {
    die("Error loading users: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Manage Users</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="page-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul>
                <li class="active"><a href="#">👥 MANAGE USERS</a></li>
                <li><a href="dashboard.php">📊 DASHBOARD</a></li>
                <li><a href="reports.php">📈 REPORTS</a></li>
                <li><a href="settings.php">⚙️ SETTINGS</a></li>
                <li><a href="logout.php">🚪 LOGOUT</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container">
            <div class="form-header">
                <h1>👨‍💼 USER ADMINISTRATION</h1>
            </div>

            <!-- Alert Messages -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <h2>All Registered Users (<?php echo $result->num_rows; ?>)</h2>

            <!-- Users Table -->
            <div class="table-wrapper">
                <table>
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
                            <form method="POST" onsubmit="return confirm('Are you sure you want to update this user?');">
                                <td>
                                    <span class="user-id"><?php echo htmlspecialchars($row['userID']); ?></span>
                                    <input type="hidden" name="user_id" value="<?php echo $row['userID']; ?>">
                                </td>
                                <td>
                                    <input type="text" name="userFname" 
                                           value="<?php echo htmlspecialchars($row['userFname']); ?>" 
                                           required maxlength="50">
                                </td>
                                <td>
                                    <input type="text" name="userLname" 
                                           value="<?php echo htmlspecialchars($row['userLname']); ?>" 
                                           required maxlength="50">
                                </td>
                                <td>
                                    <input type="email" name="userEmail" 
                                           value="<?php echo htmlspecialchars($row['userEmail']); ?>" 
                                           required maxlength="100">
                                </td>
                                <td>
                                    <input type="text" name="userPhone" 
                                           value="<?php echo htmlspecialchars($row['userPhone']); ?>" 
                                           required pattern="[0-9+\-\s()]+" 
                                           title="Please enter a valid phone number">
                                </td>
                                <td>
                                    <select name="userRoleId" required>
                                        <option value="1" <?php echo $row['userRoleId'] == 1 ? 'selected' : ''; ?>>Admin</option>
                                        <option value="2" <?php echo $row['userRoleId'] == 2 ? 'selected' : ''; ?>>User</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="password" name="userPassword" 
                                           value="<?php echo htmlspecialchars($row['userPassword']); ?>" 
                                           required placeholder="Enter new password">
                                </td>
                                <td>
                                    <div class="access-indicator">
                                        <span class="access-dot <?php echo $row['userAccess'] ? 'active' : 'inactive'; ?>"></span>
                                        <input type="checkbox" name="userAccess" value="1" 
                                               <?php echo $row['userAccess'] ? 'checked' : ''; ?>>
                                    </div>
                                </td>
                                <td>
                                    <button type="submit" name="update_user">💾 Update</button>
                            </form>
                            <form method="POST" style="display:inline;" 
                                  onsubmit="return confirm('⚠️ Are you sure you want to DELETE this user? This action cannot be undone!');">
                                <input type="hidden" name="user_id" value="<?php echo $row['userID']; ?>">
                                <button type="submit" name="delete_user" class="delete-btn">🗑️ Delete</button>
                            </form>
                                </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide alert messages after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });

        // Prevent accidental form resubmission
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
<?php
$result->free();
$conn->close();
?>