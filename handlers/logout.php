<?php
require '../config/config.php';
$user = $_SESSION['username'];
$is_online = 'no';
$is_online = mysqli_query($con,"UPDATE users SET is_online='$is_online' WHERE username='$user'");

session_start();
session_destroy();
setcookie('username', '', time() - 3600);
header("Location: ../register.php")
?>