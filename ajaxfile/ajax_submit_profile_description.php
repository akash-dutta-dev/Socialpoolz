<?php  
require '../config/config.php';

$description= $_POST['profile_user_description'];
$user = $_POST['profile_user_description_userlogged'];

$description = strip_tags($description);
$description = str_replace("'",'"',$description);
$query = mysqli_query($con,"UPDATE user_details SET description='$description' WHERE username='$user'");

echo $description;
?>