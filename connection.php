<?php
// 1. MySQLi extension-for beginners other method requires data objects which advanced users can use


// Database connection settings
//variables to connect to the database
    $ssl_ca_path= __DIR__."/certs/DigiCertGlobalRootG2.crt.pem";
    $db_server = "campusnest.mysql.database.azure.com";
    $db_user="campusnest";
    $db_password = "qwertyuI0p";
    $db_name="db_webappdev_student_accomodation";
    $db_port=3306;
    $conn = "";

    // Create a connection to the MySQL database
    $conn = mysqli_init();

    if(!mysqli_ssl_set($conn,NULL,NULL,$ssl_ca_path,NULL,NULL)){
        die("Setting of Certificate failed".mysqli_error($conn));
    }   

    // Check if the connection was successful
    if (!mysqli_real_connect($conn,$db_server, $db_user, $db_password, $db_name,$db_port)) {
        print("AAAAAAAA");
         die("Connection failed: " . mysqli_connect_error());
    }
    
?>