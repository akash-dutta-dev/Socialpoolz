<?php  
require '../config/config.php';

$answer_id = $_POST['answer_id'];
$userDownvoted = $_POST['userDownvoted'];

$query = mysqli_query($con, "SELECT given_by FROM answer WHERE id='$answer_id'");
$row = mysqli_fetch_array($query);
$userAdded = $row['given_by'];

$check_already_downvoted = mysqli_query($con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id' AND answer_downvoted_by='$userDownvoted'");
$check_already_downvoted_num = mysqli_num_rows($check_already_downvoted);

if($check_already_downvoted_num>0){
	$insert_post = mysqli_query($con, "DELETE FROM answer_downvote WHERE answer_id = '$answer_id' AND answer_downvoted_by = '$userDownvoted' AND
                                   answer_added_by = '$userAdded' ");
	$check_num_downvote = mysqli_query($con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id'");
	$num_downvote = mysqli_num_rows($check_num_downvote);
	$num = 0;
	echo json_encode(array($num_downvote, $num));
}
else{

	
	$insert_post = mysqli_query($con, "INSERT INTO answer_downvote VALUES ('', '$answer_id', '$userAdded','$userDownvoted')");

	$check_num_downvote = mysqli_query($con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id'");
	$num_downvote = mysqli_num_rows($check_num_downvote);
	$num = 1;
	echo json_encode(array($num_downvote, $num));
}
?>