<?php  
require '../config/config.php';
include("../handlers/question_post.php");

$limit = 8; //Number of posts to be loaded per call

$posts = new Post($con, $_REQUEST['userLoggedIn']);
$posts->getMainNewsfeed($_REQUEST, $limit);
?>