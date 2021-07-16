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
	$num = 1;
	$close_button = "<button class='topic_close_btn' id='topic_close$topic_str$userLoggedIn'>x</button>";
	$to_append = "<div class='topic_user_list'>
				 ".$close_button."<a href='topic.php?topic=".$topic_str."'>".$topic_str."</a>
				 </div>
				 <br>
				 ";
	//Return values
	echo json_encode(array($to_append, $num));
	
}

?>