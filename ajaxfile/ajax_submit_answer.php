<?php  
require '../config/config.php';
include("../handlers/question_post.php");



    
	$post = new Post($con, $_POST['user']);
	$post->submitAnswer($_POST['answer_body'],$_POST['question_id'],$_POST['anonymous']);


?>