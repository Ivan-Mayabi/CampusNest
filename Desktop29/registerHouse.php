<?php
require "../connection.php";
session_start();

$landlordId = $_SESSION["user_id"];

$houseName = $_POST["Name"];
$houseLocation =$_POST["Location"];
$houseDescription=$_POST["Description"];

if(isset($_FILES["file_chosen"]) && ($_FILES["file_chosen"]["error"])==0){
    $imgData = file_get_contents($_FILES["file_chosen"]["tmp_name"]);

    $sql = "insert into house(houseName,houseLocation,houseDescription,landlordId,housePhoto) values (?,?,?,?,?)";
    $pstmt = $conn->prepare($sql);
    $pstmt->bind_param("sssib",$houseName, $houseLocation, $houseDescription, $landlordId, $imgData);
    $pstmt->send_long_data(4,$imgData);

    if($pstmt->execute()){
        echo "Successful send to database";
        header("Location: ../DesktopStudentLogin/studentlogin.html");
        exit;
    }
    else{
        echo "Unsuccessful send to database";
    }
}
else{
    echo "Not quite my liege";
}
?>