
<script src="../assets/js/socialpoolz.js"></script>

<?php
	require '../config/config.php';
	$userLoggedIn = $_POST['userLoggedIn']
?>
<div class="load_topic_form">
	<form action="ajax_load_topic_box.php" method="POST" class="load_topic_form_form">	
		<input type="text" name="topix_text" placeholder="Search a Topic here.. (Ex:- Science, Technology, etc) " onkeyup="getLiveTopic(this.value, '<?php echo $userLoggedIn; ?>')"
			   autocomplete="off" id="id_topix_text" style="width:100%; height: 30px;background-color: #e9eef3; padding-left:10px;" class="load_topic_form_form_input">
	</form>



	<div class="search_topic_results_footer_empty" style = "height:230px;width:101%;overflow: scroll;">
		<?php
			
			$query_user_topic = mysqli_query($con, "SELECT topic FROM users WHERE username='$userLoggedIn'");
			$row_user_topic = mysqli_fetch_array($query_user_topic);
			$user_topic = $row_user_topic['topic'];
	
			$usersReturnedQuery = mysqli_query($con, "SELECT * FROM topic ORDER BY followers LIMIT 20");
				
				

			while($row = mysqli_fetch_array($usersReturnedQuery)) {
				$newstr = $row['name'];
				$id = $row['id'];
				$topic_pic = $row['topic_pic'];

				if(!strstr($user_topic,$newstr)){

					?>
						<script>
							$(document).on('click', '#followButton<?php echo $id; ?><?php echo $userLoggedIn; ?>', function() { 

								var userLoggedIn = '<?php echo $userLoggedIn; ?>';

								var data = 'userFollowed='+ userLoggedIn + '&id=' + <?php echo $id; ?>;

								 $.ajax({
									type:"POST",
									cache:false,
									url:"ajaxfile/ajax_topic_followed.php",
									data:data,    // multiple data sent using ajax
									success: function (msg) {

										var result = $.parseJSON(msg);
										if(result[1]==0){
											$("#followButton<?php echo $id; ?><?php echo $userLoggedIn; ?>").text("Follow");
										}
										else{
											$("#followButton<?php echo $id; ?><?php echo $userLoggedIn; ?>").text("Unfollow");
											$('.topic_user').append(result[0]);
											$(".topicResultDisplayClass<?php echo $id; ?>").hide();
											console.log("dad");

										}
									},
									error: function() {
									alert('follow Failure');
									}
								 });

							  });

						</script>
					<?php

					//$user_query = mysqli_query($con,"SELECT * FROM users WHERE username = '$userLoggedIn'");
					///$user_query_array = mysqli_fetch_array($user_query);
					//$user_topic = $user_query_array['topic'];

					$btnToShow = "<button class='followButton' name='followButton' id='followButton$id$userLoggedIn'>Follow</button>";

					echo "<div class='topicResultDisplay topicResultDisplayClass$id' id='topicResultDisplay$id'>

								<div class='topicLivePicture'>
									<img src='" . $topic_pic . "'>
								</div>

								<div class='topicLiveSearchText'>
									" . $newstr . "
								</div>

								<div class='topicFollowButton'>
									" . $btnToShow . "
								</div>

							</div>
							";
				}

			}

		?>
	</div>
</div>