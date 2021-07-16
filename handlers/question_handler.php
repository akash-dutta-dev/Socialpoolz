<?php

if(isset($_POST['question_change_button'])){
	$question_body = $_POST['change_question_question'];
	$id_question = $_POST['change_question_id'];
	$userLoggedIn = $_POST['userLoggedIn'];
	
	$question_query = mysqli_query($con,"SELECT * FROM question WHERE id='$id_question'");
	$question_query_row = mysqli_fetch_array($question_query);
	
	$question_added_by = $question_query_row['posted_by'];
	$question_old_question = $question_query_row['question_body'];
	
	//USer
	$user_query = mysqli_query($con,"SELECT * FROM users WHERE username='$userLoggedIn'");
	$user_query_row = mysqli_fetch_array($user_query);
	$fullname = $user_query_row['first_name'] . " " . $user_query_row['last_name'];
	
	$query = mysqli_query($con,"UPDATE question SET old_question='$question_old_question' WHERE id='$id_question'");
	
	$query = mysqli_query($con,"UPDATE question SET question_body='$question_body' WHERE id='$id_question'");
	
	//Enter notification
	if($userLoggedIn !== $fullname){
		
		$notification_body = "<a href='profile.php?profile_username=".$userLoggedIn."'><b>".$fullname."</b></a> has edited you Question <b>".$question_old_question."</b> to <a href='search.php?qid=".$id_question."'><b>".$question_body."</b></a>";

		$notification_body = str_replace("'",'"',$notification_body);
		
		$notification_date_added = date("Y-m-d H:i:s");
		
		$query = mysqli_query($con,"INSERT INTO notification VALUES ('','$notification_body','$question_added_by','$notification_date_added','no')");

	}
	//header("Refresh:0");
}

?>