<?php  
require '../config/config.php';
include("../handlers/question_post.php");

$limit = 10; //Number of posts to be loaded per call

$posts = new Post($con, $_REQUEST['userLoggedIn']);
$posts->getAnswerLater($_REQUEST, $limit);
?>