<?php  
require '../config/config.php';

$question_id = $_POST['id'];
$question_detail_query = mysqli_query($con,"SELECT question_body,posted_by FROM question WHERE id='$question_id'");
$question_detail_row = mysqli_fetch_array($question_detail_query);
$question_body = $question_detail_row['question_body'];
$posted_by = $question_detail_row['posted_by'];

$notification_date_added = date("Y-m-d H:i:s");
$notification_body = "Your Question <b>Q:-".$question_body."</b> have been deleted successfully.";
$notification_body = str_replace("'",'"',$notification_body);

mysqli_query($con,"UPDATE question SET deleted = 'yes' WHERE id='$question_id'");
mysqli_query($con,"INSERT INTO notification VALUES('', '$notification_body','$posted_by','$notification_date_added','no')")

?>