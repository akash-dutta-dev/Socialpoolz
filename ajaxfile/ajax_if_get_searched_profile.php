<?php  
require '../config/config.php';
include("../handlers/question_post.php");

$limit = 5; //Number of posts to be loaded per call

$posts = new Post($con, $_REQUEST['userLoggedIn']);
$posts->getSearchedProfiles($_REQUEST, $limit, $_POST['search']);
?>