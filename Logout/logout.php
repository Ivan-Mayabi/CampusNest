<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Prevent going back to a protected page after logout
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

// Redirect to login page
header("Location: ../Login/Login.php");
exit;
