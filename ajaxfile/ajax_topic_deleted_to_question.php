<?php
include("../config/config.php");

$topic_deleted = $_POST['topicDeleted'];
$userLoggedIn = $_POST['userDeleted'];
$qid = $_POST['qid'];

$user_query = mysqli_query($con,"SELECT topic_deleted FROM user_details WHERE username='$userLoggedIn'");
$user_query_row = mysqli_fetch_array($user_query);
$user_topic = $user_query_row['topic_deleted'];

$topic_query = mysqli_query($con,"SELECT * FROM topic WHERE name='$topic_deleted'");
$topic_query_row = mysqli_fetch_array($topic_query);
$topic_str = $topic_query_row['name'];
$topic_question = $topic_query_row['question'];

$question_query = mysqli_query($con,"SELECT topic FROM question WHERE id='$qid'");
$question_query_row = mysqli_fetch_array($question_query);
$question_topic = $question_query_row['topic'];

if(strstr($question_topic,$topic_str)){
	
	//Update user topics Follow
	$topic_to_delete = $topic_deleted . ",";
	$question_topic = str_replace($topic_to_delete,"",$question_topic);
	//Incease topic added by user in user_details table
	$user_topic++;
	//Increase no of question in topic column
	$topic_question--;
	
	$sql_query = mysqli_query($con,"UPDATE question SET topic='$question_topic' WHERE id='$qid'");
	
	$sql_query = mysqli_query($con,"UPDATE user_details SET topic_deleted='$user_topic' WHERE username='$userLoggedIn'");
	
	$sql_query = mysqli_query($con,"UPDATE topic SET question='$topic_question' WHERE name='$topic_deleted'");

	//Return values
	echo $topic_str;
}


?>