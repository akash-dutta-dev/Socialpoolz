<?php

require '../config/config.php';

$userLoggedIn = $_POST['user'];
$is_online = 'no';
$is_online = mysqli_query($con,"UPDATE users SET is_online='$is_online' WHERE username = '$userLoggedIn'");

?>