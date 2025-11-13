<?php
// 1. MySQLi extension-for beginners other method requires data objects which advanced users can use


// Database connection settings
//variables to connect to the database
    $db_server = "campusnest.mysql.database.azure.com:3306";
    $db_user="campusnest";
    $db_password = "qwertyuiop";
    $db_name="db_webappdev_student_accomodation";
    $conn = "";

    // Create a connection to the MySQL database
    $conn = mysqli_connect($db_server, $db_user, $db_password, $db_name);

    // Check if the connection was successful
    if (!$conn) {
         die("Connection failed: " . mysqli_connect_error());
    }
    
?>