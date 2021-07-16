<?php
include("../config/config.php");

$topic_name = $_POST['topic_name'];
$userLoggedIn = $_POST['user'];

$user_query = mysqli_query($con,"SELECT topic FROM users WHERE username='$userLoggedIn'");
$user_query_row = mysqli_fetch_array($user_query);
$user_topic = $user_query_row['topic'];

$user_topic = str_replace($topic_name.",","",$user_topic);
$query = mysqli_query($con,"UPDATE users SET topic='$user_topic' WHERE username='$userLoggedIn' ");

?>