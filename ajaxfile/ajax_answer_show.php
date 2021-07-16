
<?php  
require '../config/config.php';

$answer_id = $_POST['answer_id'];
$userLogin = $_POST['userLogin'];
	
$query = mysqli_query($con, "SELECT * FROM answer WHERE id='$answer_id'");
$row = mysqli_fetch_array($query);

$userPosted = $row['given_by'];
$user_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLogin'");
$row_user = mysqli_fetch_array($user_query);
$userPostedfullname = $row_user['first_name'] . " " . $row_user['last_name'];


$views = $row['views'];
$views++;

$insert_post = mysqli_query($con, "UPDATE answer SET views = $views WHERE id = $answer_id ");

if($userLogin != $userPosted){
	$date_added = date("Y-m-d H:i:s");
	$notification_body = "<a href='profile.php?profile_username=".$userLogin."'><b>".$userPostedfullname."</b></a> viewed your Answer.";
	$notification_body = str_replace("'",'"',$notification_body);
	//$insert_noti = mysqli_query($con, "INSERT INTO notification VALUES('', '$notification_body', '$userPosted','$date_added','no')");
	
}
	
?>
