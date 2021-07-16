<?php  
require '../config/config.php';

$userLoggedIn = $_POST['userLoggedIn'];
$user_to_show = $_POST['userToShow'];

//Users Logged In Details
$user_loggedin_detail_query = mysqli_query($con, "SELECT following_list,following,first_name,last_name FROM users WHERE username='$userLoggedIn'");
$user_loggedin_detail_row = mysqli_fetch_array($user_loggedin_detail_query);
$user_loggedin_following_list = $user_loggedin_detail_row['following_list'];
$user_loggedin_following_number = $user_loggedin_detail_row['following'];
$user_loggedin_first_name = $user_loggedin_detail_row['first_name'];
$user_loggedin_last_name = $user_loggedin_detail_row['last_name'];
$user_loggedin_fullname =  $user_loggedin_first_name . " " . $user_loggedin_last_name;

//Users profile to show details
$user_profile_detail_query = mysqli_query($con, "SELECT follower_list,follower FROM users WHERE username='$user_to_show'");
$user_profile_detail_row = mysqli_fetch_array($user_profile_detail_query);
$user_profile_follower_list = $user_profile_detail_row['follower_list'];
$user_profile_follower_number = $user_profile_detail_row['follower'];


if(strstr($user_loggedin_following_list,$user_to_show)){
	$user_to_remove = ','.$user_to_show.',';
	$user_loggedin_following_list = str_replace($user_to_remove,',',$user_loggedin_following_list);
	mysqli_query($con,"UPDATE users SET following_list = '$user_loggedin_following_list' WHERE username='$userLoggedIn'");
	
	$user_loggedin_following_number--;
	mysqli_query($con,"UPDATE users SET following = '$user_loggedin_following_number' WHERE username='$userLoggedIn'");
	
	$user_to_remove = ','.$userLoggedIn.',';
	$user_profile_follower_list = str_replace($user_to_remove,',',$user_profile_follower_list);
	mysqli_query($con,"UPDATE users SET follower_list = '$user_profile_follower_list' WHERE username='$user_to_show'");
	
	$user_profile_follower_number--;
	mysqli_query($con,"UPDATE users SET follower = '$user_profile_follower_number' WHERE username='$user_to_show'");
	
	$num = '0';
	echo json_encode(array($user_profile_follower_number, $num));
	
}
else{
	//Add string to user who logged in.
	$user_loggedin_following_list = $user_loggedin_following_list . $user_to_show.',';
	mysqli_query($con,"UPDATE users SET following_list = '$user_loggedin_following_list' WHERE username='$userLoggedIn'");
	
	//Increase following of user by one who is logged in.
	$user_loggedin_following_number++;
	mysqli_query($con,"UPDATE users SET following = '$user_loggedin_following_number' WHERE username='$userLoggedIn'");
	
	//Add string to user whose profile is shown.
	$user_profile_follower_list = $user_profile_follower_list . $userLoggedIn.',';
	mysqli_query($con,"UPDATE users SET follower_list = '$user_profile_follower_list' WHERE username='$user_to_show'");
	
	//Increase following of user by one whose profile is shown.
	$user_profile_follower_number++;
	mysqli_query($con,"UPDATE users SET follower = '$user_profile_follower_number' WHERE username='$user_to_show'");
	

	//Insert notification
	$notification_date_added = date("Y-m-d H:i:s");
	$notification_body = "<a href='profile.php?profile_username=".$userLoggedIn."'><b>".$user_loggedin_fullname."</b> started following you.</a>";
	$notification_body = str_replace("'",'"',$notification_body);
	$notification_insert = mysqli_query($con, "INSERT INTO notification VALUES('', '$notification_body', '$user_to_show','$notification_date_added','no')");
			//echo $notification_body;
	
	$num = '1';
	echo json_encode(array($user_profile_follower_number, $num));
	
}
?>