<?php  
require '../config/config.php';

$read_level = $_POST['read_level'];
$user = $_POST['userLoggedInSurvey4'];

if($read_level != ""){
	
	$query = mysqli_query($con,"UPDATE user_details SET reader_level='$read_level' WHERE username='$user'");
	
}

?>