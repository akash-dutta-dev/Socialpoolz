<?php
include("../config/config.php");

$id = $_POST['id'];
$userLoggedIn = $_POST['userFollowed'];

$user_query = mysqli_query($con,"SELECT topic FROM users WHERE username='$userLoggedIn'");
$user_query_row = mysqli_fetch_array($user_query);
$user_topic = $user_query_row['topic'];

$topic_query = mysqli_query($con,"SELECT name FROM topic WHERE id='$id'");
$topic_query_row = mysqli_fetch_array($topic_query);
$topic_str = $topic_query_row['name'];

if(!strstr($user_topic,$topic_str)){
	//Update user topics Follow
	$user_topic = $user_topic . $topic_str . ",";
	$sql_query = mysqli_query($con,"UPDATE users SET topic='$user_topic' WHERE username='$userLoggedIn'");

	echo '1';
	
}

else{
	
	//Update user topics Unfollow
	$user_topic = str_replace($topic_str.",","",$user_topic);
	$sql_query = mysqli_query($con,"UPDATE users SET topic='$user_topic' WHERE username='$userLoggedIn'");

	echo '0';
}
?>