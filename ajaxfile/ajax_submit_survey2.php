<?php  
require '../config/config.php';

$form_input_user_country = $_POST['form_input_user_country'];
$form_input_user_state = $_POST['form_input_user_state'];
$form_input_user_city = $_POST['form_input_user_city'];

$user = $_POST['userLoggedInSurvey2'];
//if($form_input_user_country != "" &&  $form_input_user_state != "" && $form_input_user_city != ""){
	
	$query = mysqli_query($con,"UPDATE user_details SET country='$form_input_user_country',states='$form_input_user_state',cities='$form_input_user_city' WHERE username='$user'");
	
//}
echo $user;
?>