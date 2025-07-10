<?php
$host = "localhost";
$port = "3310";
$user = "root";
$password = "Manvin";
$dbname = "db_webAppDev_student_Accomodation";

$conn = new mysqli($host, $user, $password, $dbname, $port);//order matters in the mysqli!

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
