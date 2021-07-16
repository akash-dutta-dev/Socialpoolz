<?php  
require '../config/config.php';

$userLoggedIn = $_POST['userLoggedIn'];

$query = mysqli_query($con, "SELECT * FROM notification WHERE viewed='no' AND user_to_show='$userLoggedIn'");
$num_notifications = mysqli_num_rows($query); 

echo $num_notifications;


?>