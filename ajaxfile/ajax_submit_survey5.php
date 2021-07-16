<?php  
require '../config/config.php';

$author_level = $_POST['author_level'];
$user = $_POST['userLoggedInSurvey5'];

if($author_level != ""){
	
	$query = mysqli_query($con,"UPDATE user_details SET author_level='$author_level' WHERE username='$user'");
	
}

?>