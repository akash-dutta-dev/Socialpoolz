<?php
include("../config/config.php");

$id = $_POST['id'];
$userLoggedIn = $_POST['userAdded'];
$qid = $_POST['qid'];

$user_query = mysqli_query($con,"SELECT topic_added FROM user_details WHERE username='$userLoggedIn'");
$user_query_row = mysqli_fetch_array($user_query);
$user_topic = $user_query_row['topic_added'];

$topic_query = mysqli_query($con,"SELECT * FROM topic WHERE id='$id'");
$topic_query_row = mysqli_fetch_array($topic_query);
$topic_str = $topic_query_row['name'];
$topic_question = $topic_query_row['question'];

$question_query = mysqli_query($con,"SELECT topic FROM question WHERE id='$qid'");
$question_query_row = mysqli_fetch_array($question_query);
$question_topic = $question_query_row['topic'];


if(!strstr($question_topic,$topic_str)){
	//Update user topics Follow
	$question_topic = $question_topic . $topic_str . ",";
	//Incease topic added by user in user_details table
	$user_topic++;
	//Increase no of question in topic column
	$topic_question++;
	
	$sql_query = mysqli_query($con,"UPDATE question SET topic='$question_topic' WHERE id='$qid'");
	
	$sql_query = mysqli_query($con,"UPDATE user_details SET topic_added='$user_topic' WHERE username='$userLoggedIn'");
	
	$sql_query = mysqli_query($con,"UPDATE topic SET question='$topic_question' WHERE id='$id'");

	//Return values
	echo $topic_str;
}


?>