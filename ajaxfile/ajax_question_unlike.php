<?php  
require '../config/config.php';

$question_id = $_POST['id'];
$userLiked = $_POST['userLiked'];

$query = mysqli_query($con, "SELECT posted_by FROM question WHERE id='$question_id'");
$row = mysqli_fetch_array($query);
$userAdded = $row['posted_by'];

$insert_post = mysqli_query($con, "DELETE FROM question_like WHERE question_id = '$question_id' AND question_added_by = '$userAdded' AND
                                   question_liked_by = '$userLiked' ");

$check_num_likes = mysqli_query($con, "SELECT * FROM question_like WHERE question_id='$question_id'");
$num_likes = mysqli_num_rows($check_num_likes);
	
echo $num_likes;
?>