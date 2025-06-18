<?php
require "../connection.php";

/*echo "<pr>";
print_r($_POST);
print_r($_FILES);
echo "</pr>";*/

if(isset($_FILES["file_chosen"]) && ($_FILES["file_chosen"]["error"])==0){
    $imgData = file_get_contents($_FILES["file_chosen"]["tmp_name"]);
    $imgData = $conn->real_escape_string($imgData);

    $sql = "insert into photos(photo) values (?);";
    $pstmt = $conn->prepare($sql);
    $pstmt->bind_param("b",$imgData);
    $pstmt->send_long_data(0,$imgData);

    if($pstmt->execute()){
        echo "Where's the cigars boys!";
        header("Location: ../DesktopStudentLogin/studentlogin.html");
        exit;
    }
    else{
        echo "We are nearing Valhalla";
    }
}
else{
    echo "Not quite my liege";
}
?>