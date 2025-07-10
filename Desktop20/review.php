<?php
session_start();

require("../connection.php"); //CONNECT FIRST

$successMessage = "";
$errorMessage = "";

// $student_id=$_SESSION['student_id'] ?? null ;
$student_id=$_SESSION['user_id'] ?? null; // Get the student ID from the session
$house_id=$_GET['houseid'] ?? null; // Get the house ID from the query parameters

//Only insert if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $review_rating = $_POST['review_rating'] ?? null;
    $comment_description = $_POST['comment_description'] ?? null;


    if (!empty($review_rating) && !empty($comment_description)) {
        $sql = "INSERT INTO review ( StudentID, HouseID, ReviewRating, CommentDescription)
                VALUES ( '$student_id', '$house_id', '$review_rating', '$comment_description')";

        if (mysqli_query($conn, $sql)) {
            $successMessage = "Review posted successfully!";
        } else {
            $errorMessage = "Error: " . mysqli_error($conn);
        }
    } else {
        $errorMessage = $student_id ?"Please fill in all fields.":"You must be logged in to post a review.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="review.css">
    <title>Review</title>

</head>

<body>

    <div class="page-wrapper">
        <div class ="sidebar">
            <ul>           
                <li class ="active"><a href="#">REVIEW</a></li>
                </ul>
        </div>
        

        <div class ="container">
            <div class ="form-header">
                <h1>REVIEW</h1>
                <a href="../Desktop19/desktop19.html" class="back-btn">← BACK</a>


        </div>

        <form action="" method="POST" id="review">
            <label for="review_rating">REVIEW RATING</label>
        <div class="star-rating">
            <input type="radio" id="star5" name="review_rating" value="5" required>
            <label for = "star5">&#9733</label>
            <input type="radio" id="star4" name="review_rating" value="4">
            <label for = "star4">&#9733</label>
            <input type="radio" id="star3" name="review_rating" value="3" >
            <label for = "star3">&#9733</label>
            <input type="radio" id="star2" name="review_rating" value="2">
            <label for = "star2">&#9733</label>
            <input type="radio" id="star1" name="review_rating" value="1">
            <label for = "star1">&#9733</label>

        </div>

        <div class="form-group">
            <label for="comment_description">COMMENT DESCRIPTION</label>
            <textarea id="comment_description" name="comment_description" required></textarea>
        </div>
                 <button type="submit">POST</button>


        </form>

        <?php

//this file shows all the registered users in a table format after connecting to the database

// includes the connection file


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT StudentID, HouseID, ReviewRating, CommentDescription, CommentTime FROM review";
$result = mysqli_query($conn, $query);  
// print_r($result);



if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}   
?>
<div class="other-reviews">
    <h1 >Other reviews</h1>

<!-- Display the table of reviews -->
 <div>
    <table>
    <tr>
        <th>Student ID</th>
        <th>House ID</th>
        <th>Review Rating</th>
        <th>Comment Description</th>
        <th>Comment Time</th>
    </tr>
    <?php
    
    ?>
    <!--data is fetched using mysqli_fetch_assoc() which turns each row into an associative array-->
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['StudentID']); ?></td>
            <td><?php echo htmlspecialchars($row['HouseID']); ?></td>
            <td><?php echo htmlspecialchars($row['ReviewRating']); ?></td>
            <td><?php echo htmlspecialchars($row['CommentDescription']); ?></td>
            <td><?php echo htmlspecialchars($row['CommentTime']); ?></td>
        </tr>
        <!--End of the loop-->
    <?php endwhile; ?>
</table>
    </div>
</div>

<?php

// Free result
mysqli_free_result($result);

//close the connection
mysqli_close($conn);
?>


</div>



</body>
</html>
