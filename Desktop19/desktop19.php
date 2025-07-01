<?php
session_start();

require "../CampusNest_WebApplicationDevelopment_BICS2.1D/connection.php";

$houseId = 1;

$sql = "select avg(reviewRating) as average_rating from review where houseId=?";
$pstmt = $conn->prepare($sql);
$pstmt->bind_param("i",$houseId);
$pstmt->execute();
$resultSet = $pstmt->get_result();
$average_rating = $resultSet->fetch_assoc();
$pstmt->close();

$sql = "select houseName, houseLocation, houseDescription, housePhoto from house where houseId=?";
$pstmt = $conn->prepare($sql);
$pstmt->bind_param("i",$houseId);
$pstmt->execute();
$resultSet = $pstmt->get_result();
$house_details = $resultSet->fetch_assoc();
$pstmt->close();

$sql = "select l.userphone as landlord_phone from users as l inner join house as h on(l.userId=h.landlordId) where h.houseId=?";
$pstmt = $conn->prepare($sql);
$pstmt->bind_param("i",$houseId);
$pstmt->execute();
$resultSet = $pstmt->get_result();
$landlord_phone = $resultSet->fetch_assoc();
$pstmt->close();

$sql = "select count(roomId) as No_rooms from room where houseId=?";
$pstmt = $conn->prepare($sql);
$pstmt->bind_param("i",$houseId);
$pstmt->execute();
$resultSet = $pstmt->get_result();
$number_rooms = $resultSet->fetch_assoc();
$pstmt->close();

$sql = "select roomName, roomPrice, roomAvailability, roomPhoto from room where houseId=? and roomAvailability=?";
$pstmt = $conn->prepare($sql);
$available = 1;
$pstmt->bind_param("ii",$houseId,$available);
$pstmt->execute();
$resultSet1 = $pstmt->get_result();

?>

<!DOCTYPE HTML>

<html>
    <head>
        <title>Desktop 29</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div id="sidebar_div">
            <!-- This is the sidebar -->
             <sidebar>
                <div id="first_div_sidebar">
                    <label>House Details</label>
                    <p >Aggregate Review</p>
                    <p class="hse_info">
                        <?php echo $average_rating["average_rating"];?>
                    </p>
                    <p>Name of House</p>
                    <p class="hse_info">
                        <?php echo $house_details["houseName"];?>
                    </p>
                    <?php
                        $base64 = base64_encode($house_details["housePhoto"]);
                        echo "<img src='data:image/png;base64,".$base64."' width='250px' height='250px' style='padding:5px'>";
                    ?>
                </div>
            </sidebar>
        </div>
        <!-- This is the second part, that has the form -->
         
        <div id="main_div">
            <div id="form_div">
                <!-- Division for the back link -->
                <!-- <div>
                    <a href="">Back</a>
                </div> -->


                <!-- Division for Name of House -->
                <div style="display:inline">
                    <label for="hse_location" style="display:inline">Location:</label>
                    <p id="hse_location" class="hse_info" style="display:inline">
                        <?php echo $house_details["houseLocation"];?>
                    </p>
                </div>

                <!-- Division for the House Location -->
                <div style="display:inline">
                    <label for="hse_rooms" style="display:inline; margin-left:15vw;">Number of Rooms:</label>
                    <p id="hse_rooms" class="hse_info" style="display:inline">
                        <?php echo $number_rooms["No_rooms"];?>
                    </p>
                    <br>
                    <br>
                </div>

                <!-- Division for the Landlord Contact -->
                <div>
                    <label for="hse_landlordContact" style="display:inline">Landlord Contact:</label>
                    <p id="hse_landlordContact" class="hse_info" style="display:inline; margin-left:5vw;">
                        <?php echo $landlord_phone["landlord_phone"];?>
                    </p>
                    <br>
                    <br>
                </div>

                <!-- Division for description text area -->
                <div>
                    <label for="hse_description">Description:</label>
                    <p id="hse_description" class="hse_info">
                        <?php echo $house_details["houseDescription"];?>
                    </p>
                    <br>
                    <br>
                </div>                
            </div>

            <!-- Division for the rooms available -->
            <div id="rooms_available">
                <label for="hse_roomsAvailable" style="margin-bottom:20px">Rooms Available with Description:</label>
                <?php
                    while($row = $resultSet1->fetch_assoc()){
                        $roomBase64 = base64_encode($row["roomPhoto"]);
                        echo 
                        "<div id='rooms' style='margin-bottom:3vh'>".
                            "<div id='room_image' style='margin-right:30px'>".
                                "<img src='data:image/jpeg;base64," .$roomBase64."'alt='i will put one' width='100px' height='100px'>".
                            "</div>".
                            "<div id='room_details'>".
                                "<label style='display:inline'>RoomName:</label>".
                                "<p style='display:inline'>".htmlspecialchars($row['roomName'])."</p>".
                                "<br>".
                                "<label style='display:inline'>RoomPrice:</label>".
                                "<p style='display:inline'>".htmlspecialchars($row['roomPrice'])."</p>".
                                "<br>".
                                "<label style='display:inline'>RoomAvailability:</label>".
                                "<p style='display:inline'>".htmlspecialchars($row['roomAvailability'])."</p>".
                            "</div>".
                        "</div>";
                    }
                ?>
            </div>

           <!-- Division for the reviews -->
            <div>
                <a href="">Leave review</a>
            </div>

        </div>
    </body>
</html>