<?php
// 1. MySQLi extension-for beginners other method requires data objects which advanced users can use


// Database connection settings
//variables to connect to the database
    $db_server = "campusnest.mysql.database.azure.com";
    $db_user="campusnest";
    $db_password = "qwertyuI0p";
    $db_name="db_webappdev_student_accomodation";
    $db_port=3306;
    $conn = "";

    // Create a connection to the MySQL database
    $conn = mysqli_connect($db_server, $db_user, $db_password, $db_name,$db_port);

    // Check if the connection was successful
    if (!$conn) {
        print("AAAAAAAA");
        //  die("Connection failed: " . mysqli_connect_error());
    }
    
?>