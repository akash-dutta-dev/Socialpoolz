<?php  
require '../config/config.php';
include("../handlers/question_post.php");


if(isset($_POST['question_body'])) {
    
	$post = new Post($con, $_POST['user_from']);
	$post->submitPost($_POST['question_body'],$_POST['question_link'],$_POST['question_tags']);
}
	
?>