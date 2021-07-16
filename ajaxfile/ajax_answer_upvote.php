<?php  
require '../config/config.php';

$answer_id = $_POST['answer_id'];
$userUpvoted = $_POST['userUpvoted'];

$query = mysqli_query($con, "SELECT given_by FROM answer WHERE id='$answer_id'");
$row = mysqli_fetch_array($query);
$userAdded = $row['given_by'];

$check_already_upvoted = mysqli_query($con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id' AND answer_upvoted_by='$userUpvoted'");
$check_already_upvoted_num = mysqli_num_rows($check_already_upvoted);

if($check_already_upvoted_num>0){
	$insert_post = mysqli_query($con, "DELETE FROM answer_upvote WHERE answer_id = '$answer_id' AND answer_upvoted_by = '$userUpvoted' AND
                                   answer_added_by = '$userAdded' ");
	//Decrease upvote
	$upvote_answer_table = mysqli_query($con, "SELECT * FROM answer WHERE id='$answer_id'");
	$upvote_answer_table_row = mysqli_fetch_array($upvote_answer_table);
	$num_upvote_answer_table = $upvote_answer_table_row['upvote'];
	$num_upvote_answer_table--;
	$num_upvote_answer_table_insert = mysqli_query($con, "UPDATE answer SET upvote = $num_upvote_answer_table WHERE id = $answer_id ");
	
	//Return no of upvotes
	$check_num_upvote = mysqli_query($con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id'");
	$num_upvote = mysqli_num_rows($check_num_upvote);
	
	
	
	$num = 0;
	echo json_encode(array($num_upvote, $num));
}
else{

	
	$insert_post = mysqli_query($con, "INSERT INTO answer_upvote VALUES ('', '$answer_id', '$userAdded','$userUpvoted')");
	
	//increae upvote in user table
	$upvote_answer_table = mysqli_query($con, "SELECT * FROM answer WHERE id='$answer_id'");
	$upvote_answer_table_row = mysqli_fetch_array($upvote_answer_table);
	$num_upvote_answer_table = $upvote_answer_table_row['upvote'];
	$num_upvote_answer_table++;
	$num_upvote_answer_table_insert = mysqli_query($con, "UPDATE answer SET upvote = $num_upvote_answer_table WHERE id = $answer_id ");
	
	//Insert notification	
	$notification_user_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userUpvoted'");
	$notification_row_user = mysqli_fetch_array($notification_user_query);
	$notification_userPostedfullname = $notification_row_user['first_name'] . " " . $notification_row_user['last_name'];

	if($userUpvoted != $userAdded){
		$notification_date_added = date("Y-m-d H:i:s");
		$notification_body = "<a href='profile.php?profile_username=".$userUpvoted."'><b>".$notification_userPostedfullname."</b></a> upvoted your answer.";
		$notification_body = str_replace("'",'"',$notification_body);
		$notification_insert = mysqli_query($con, "INSERT INTO notification VALUES('', '$notification_body', '$userAdded','$notification_date_added','no')");
		//echo $notification_body;
	}
	
	//Return no of upvotes
	$check_num_upvote = mysqli_query($con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id'");
	$num_upvote = mysqli_num_rows($check_num_upvote);
	$num = 1;
	echo json_encode(array($num_upvote, $num));
}
?>