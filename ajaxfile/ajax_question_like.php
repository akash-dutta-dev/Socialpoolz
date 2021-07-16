<?php  
require '../config/config.php';

$question_id = $_POST['id'];
$userLiked = $_POST['userLiked'];

$query = mysqli_query($con, "SELECT * FROM question WHERE id='$question_id'");
$row = mysqli_fetch_array($query);
$userAdded = $row['posted_by'];
$question_body = $row['question_body'];

$insert_post = mysqli_query($con, "INSERT INTO question_like VALUES ('', '$question_id', '$userAdded','$userLiked')");

$check_num_likes = mysqli_query($con, "SELECT * FROM question_like WHERE question_id='$question_id'");
$num_likes = mysqli_num_rows($check_num_likes);

//Insert notification
$notification_user_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLiked'");
	$notification_row_user = mysqli_fetch_array($notification_user_query);
	$notification_userPostedfullname = $notification_row_user['first_name'] . " " . $notification_row_user['last_name'];

	if($userLiked != $userAdded){
		$notification_date_added = date("Y-m-d H:i:s");
		$notification_body = "<a href='profile.php?profile_username=".$userLiked."'><b>".$notification_userPostedfullname."</b></a><a href='search.php?qid=".$question_id."'>
		liked your question Q:-'".$question_body."'</a>";
		$notification_body = str_replace("'",'"',$notification_body);
		$notification_insert = mysqli_query($con, "INSERT INTO notification VALUES('', '$notification_body', '$userAdded','$notification_date_added','no')");
		//echo $notification_body;
	}
	
echo $num_likes;
?>