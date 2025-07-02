<?php
// 1. MySQLi extension-for beginners other method requires data objects which advanced users can use


// Database connection settings
//variables to connect to the database
    $db_server = "localhost:3310";
    $db_user="root";
    $db_password = "Manvin";
    $db_name="db_webAppDev_student_Accomodation";
    $conn = "";

    // Create a connection to the MySQL database
    $conn = mysqli_connect($db_server, $db_user, $db_password, $db_name);

    // Check if the connection was successful
    if (!$conn) {
         die("Connection failed: " . mysqli_connect_error());
    }
    
?>