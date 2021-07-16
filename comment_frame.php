<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>

	<style type="text/css">
	* {
		font-size: 12px;
		font-family: Arial, Helvetica, Sans-serif;
	}
		body{
			background-color: #e8e8e8;
		}
		.no_comments_message{
			font-size: 13px;
			font-weight: bold;
			color: #a09797;
		}
		textarea{
			width: 80%;
			height: 50px;
			margin-top: 11px;
			margin-left: 11px;
			border-radius: 5px;
			outline: none;
		}

		input[type='submit']{
			height: 46px;
			float: right;
			margin: 13px 4px 0 0px;
			border-radius: 3px;
			border: none;
			background-color: #bb3030;
			font-size: 17px;
			/* font-weight: bold; */
			color: #fff;
			padding-left: 40px;
			padding-right: 40px;
				}
		.comment_section img{
			float: left;
			margin-left: 13px;
			margin-right: 13px;
			border-radius: 25px;
		}
		hr {
			clear: both;
			visibility: hidden;
		}
		a{
			color:#000;
		}
	</style>
	
	<?php  
	require 'config/config.php';
	include("handlers/question_post.php");

	if (isset($_SESSION['username'])) {
		$userLoggedIn = $_SESSION['username'];
		$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
		$user = mysqli_fetch_array($user_details_query);
		
	}
	else {
		header("Location: register.php");
	}

	?>
	

	<?php  
	//Get id of post
	if(isset($_GET['answer_id'])) {
		$answer_id = $_GET['answer_id'];
	}

	if(isset($_POST['postComment' . $answer_id])) {
		$post_body = $_POST['post_body'];
		$post_body = mysqli_escape_string($con, $post_body);
		$date_time_now = date("Y-m-d H:i:s");
		$insert_post = mysqli_query($con, "INSERT INTO comments VALUES ('', '$post_body', '$date_time_now', '$userLoggedIn', '$answer_id')");
		
	}
	?>
	<form action="comment_frame.php?answer_id=<?php echo $answer_id; ?>" id="comment_form" name="postComment<?php echo $answer_id; ?>" method="POST" style="min-width:660px">
		<textarea name="post_body" required></textarea>
		<input type="submit" name="postComment<?php echo $answer_id; ?>" value="Post">
	</form>

	<!-- Load comments -->
	<?php  
	$get_comments = mysqli_query($con, "SELECT * FROM comments WHERE answer_id='$answer_id' ORDER BY id DESC");
	$count = mysqli_num_rows($get_comments);

	if($count != 0) {

		while($comment = mysqli_fetch_array($get_comments)) {

			$comment_body = $comment['comment_body'];
			$date_added = $comment['date_added'];
			$added_by = $comment['added_by'];
			$user_query = mysqli_query($con, "SELECT * FROM users WHERE username ='$added_by' ORDER BY id DESC");
			$user_row = mysqli_fetch_array($user_query);
			$user_pic = $user_row['profile_pic'];
			$user_name = $user_row['first_name'] . " " . $user_row['last_name'];
			

			//Timeframe
			$date_time_now = date("Y-m-d H:i:s");
			$start_date = new DateTime($date_added); //Time of post
			$end_date = new DateTime($date_time_now); //Current time
			$interval = $start_date->diff($end_date); //Difference between dates 
			if($interval->y >= 1) {
				if($interval == 1)
					$time_message = $interval->y . " year ago"; //1 year ago
				else 
					$time_message = $interval->y . " years ago"; //1+ year ago
			}
			else if ($interval->m >= 1) {
				if($interval->d == 0) {
					$days = " ago";
				}
				else if($interval->d == 1) {
					$days = $interval->d . " day ago";
				}
				else {
					$days = $interval->d . " days ago";
				}


				if($interval->m == 1) {
					$time_message = $interval->m . " month". $days;
				}
				else {
					$time_message = $interval->m . " months". $days;
				}

			}
			else if($interval->d >= 1) {
				if($interval->d == 1) {
					$time_message = "Yesterday";
				}
				else {
					$time_message = $interval->d . " days ago";
				}
			}
			else if($interval->h >= 1) {
				if($interval->h == 1) {
					$time_message = $interval->h . " hour ago";
				}
				else {
					$time_message = $interval->h . " hours ago";
				}
			}
			else if($interval->i >= 1) {
				if($interval->i == 1) {
					$time_message = $interval->i . " minute ago";
				}
				else {
					$time_message = $interval->i . " minutes ago";
				}
			}
			else {
				if($interval->s < 30) {
					$time_message = "Just now";
				}
				else {
					$time_message = $interval->s . " seconds ago";
				}
			}


			?>
			<div class="comment_section">
				<a href="profile.php?profile_username=<?php echo $added_by; ?>" target="_parent"><img src="<?php echo $user_pic;?>" title="<?php echo $posted_by; ?>" style="float:left;" height="30"></a>
				<a href="profile.php?profile_username=<?php echo $added_by; ?>" target="_parent"> <b> <?php echo $user_name; ?> </b></a>
				&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message . "<br>" . $comment_body; ?> 
				<hr>
			</div>
			<?php

		}
	}
	else {
		echo "<div class='no_comments_message'><center><br><br>No Comments to Show!</center></div>";
	}

	?>






</body>
</html>