<?php  
require '../config/config.php';

$question_id = $_POST['id'];
$userClicked = $_POST['userClicked'];

$chech_user = mysqli_query($con, "SELECT * FROM answer_later WHERE question_id='$question_id' AND user_added='$userClicked'");
$chech_user_num = mysqli_num_rows($chech_user);

if($chech_user_num == 1){
	$insert_post = mysqli_query($con, "DELETE FROM answer_later WHERE question_id = '$question_id' AND user_added = '$userClicked'");
	echo '0';
}
else{
	$insert_post = mysqli_query($con, "INSERT INTO answer_later VALUES ('', '$question_id', '$userClicked')");

	//Insert notification
	$notification_question_query = mysqli_query($con, "SELECT * FROM question WHERE id='$question_id'");
	$notification_row_question = mysqli_fetch_array($notification_question_query);
	$notification_question = $notification_row_question['question_body'] ;

	$notification_date_added = date("Y-m-d H:i:s");
	$notification_body = "<a href='search.php?qid=".$question_id."'><b>Q:- '".$notification_question."'</b> added to answer later</a>";
	$notification_body = str_replace("'",'"',$notification_body);
	$notification_insert = mysqli_query($con, "INSERT INTO notification VALUES('', '$notification_body', '$userClicked','$notification_date_added','no')");
			//echo $notification_body;
	
	echo '1';
	
}
?>