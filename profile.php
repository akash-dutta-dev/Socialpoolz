<?php
include("header.php");


if(isset($_GET['profile_username'])){
	$profile_to_show = $_GET['profile_username'];
	$user_query = mysqli_query($con,"SELECT * FROM users WHERE username = '$profile_to_show'");
	if(mysqli_num_rows($user_query) == 0){
		header("Location: handlers/notfound.php");
	}
	$user = mysqli_fetch_array($user_query);
	$user_to_show = $user['username'];
	$user_to_show_following_list = $user['follower_list'];
	$user_to_show_follower = $user['follower'];
	
	//$user_to_show_description = $user_extra_details['description'];
	
	$user_to_show_extra_details_query = mysqli_query($con,"SELECT * FROM user_details WHERE username = '$user_to_show'");
	$user_to_show_extra_details = mysqli_fetch_array($user_to_show_extra_details_query);
	$user_to_show_description = $user_to_show_extra_details['description'];
}
//$_SESSION['set_profile_pic_extra'] = "";

?>

        <div class="header_cover" style="width:100%;height:50px;">
        </div>

		<?php
			if( isset($_SESSION['set_profile_pic']) ){
				echo  "
						<script>
							setTimeout(function() {
								$('#crop_pic').modal('show');
							}, 200);
							
							$(window).bind('beforeunload', function(){          
                
								  $.ajax({
									type: 'POST',
									url: 'ajaxfile/ajax_changing_pages.php',
									async:false,
									cache: false,
									success: function(msg) {

									},
									error: function() {

									}
								  });

							});
						</script>
						";
				
				$imgSrc = $_SESSION['set_profile_pic'];
				$original_width = $_SESSION['profile_pic_width'];
				$original_height = $_SESSION['profile_pic_height'];
			}
			
		?>		
				
       <script>

		   $(document).ready(function() {
     
			   $(document).on('change', '#file',function(){
				   
				    var user = '<?php echo $userLoggedIn; ?>';
				    // var name = document.getElementById("image").files[0].name;
  					var form_data = new FormData();
				    form_data.append("file", document.getElementById('file').files[0]);
				    form_data.append('user', user);
				    //console.log(form_data);
					$.ajax({
						type: "POST",
						url: "ajaxfile/ajax_show_picture_for_cropping.php",
						data: form_data,
						processData: false,
    					contentType: false,
						cache: false,

						success: function(msg) {
							//var result = $.parseJSON(msg);
							//var pic_path = result[0];
							//var pic_width = result[1];
							//var pic_height = result[2];
							//alert(msg);
							location.reload();					
							
						},
						error: function() {
							alert("Upload Fail");
						}
					});
				});
			   
			   $('#save_profile_pic_button').click(function () {
                   
                   
                   
					$.ajax({
						type: "POST",
						url: "ajaxfile/ajax_upload_profile_pic.php",
						data: $('form.submit_profile_photo').serialize(),
						cache: false,
                        
						success: function(msg) {
							console.log(msg);
						},
						error: function() {
							alert("Upload Fail");
						}
					});
				});
			   
			   $('#follow_button<?php echo $userLoggedIn; ?><?php echo $user_to_show; ?>').click(function () {

				  var userToShow = '<?php echo $user_to_show; ?>';
				  var userLoggedIn = '<?php echo $userLoggedIn; ?>';

				  var data = 'userLoggedIn='+ userLoggedIn + '&userToShow=' + userToShow;
				  //console.log(data);
				  $.ajax({
					type:"POST",
					cache:false,
					url:"ajaxfile/ajax_profile_user_follow.php",
					data:data,    // multiple data sent using ajax
					success: function (msg) {
						console.log(msg);
						var result = $.parseJSON(msg);
						var follower = result[0];
						if(result[1]=='0'){
						    $("#follow_button<?php echo $userLoggedIn; ?><?php echo $user_to_show; ?>").html("Follow");
							$("#profile_follower_display_id").text(follower);
							//toast('Question Removed');
						 }
						else{
							$("#follow_button<?php echo $userLoggedIn; ?><?php echo $user_to_show; ?>").html("Unfollow");
							$("#profile_follower_display_id").text(follower);
							//toast('Question Added');
						}

					},
					error: function() {
						alert('Follow Button Failure');
					}
				  });

				});
		    });
		  
		   
	   </script>
		
		<div class='profile_upper_section'>

			<div class='profile_pic_left' uk-lightbox>
				<?php
					if($user_to_show == $userLoggedIn){
						
						echo "
							<div class='edit_profile_pic'>
								<form class='edit_profile_pic_form' action='' name='edit_profile_pic_form' method='post'>
									<input type='file' class='edit_profile_pic_button' id='file' name='file' style='height:12px'/>
									<input type='hidden' name='userLoggedIn' value= '".$userLoggedIn."'>
								</form>
							</div>
						";
					}
				?>
				<a href="<?php echo $user['profile_pic']; ?>"><img src="<?php echo $user['profile_pic']; ?>"></a>
				
				<?php
				
				if($userLoggedIn != $user_to_show){
					
					if(strstr($user_to_show_following_list,$userLoggedIn)){
						echo "<div class='profile_follow_button_div'>
								<button type='button' class='profile_follow_button' id=follow_button$userLoggedIn$user_to_show>
									Unfollow
								</button>
								<div class='profile_follower_display' id='profile_follower_display_id'>
									".$user_to_show_follower."
								</div>
							  </div>";
					}
					else{
						echo "<div class='profile_follow_button_div'>
								<button type='button' class='profile_follow_button' id=follow_button$userLoggedIn$user_to_show>
									Follow
								</button>
								<div class='profile_follower_display' id='profile_follower_display_id'>
									".$user_to_show_follower."
								</div>
							  </div>";
					}
				}
				else{
					?>
					<div class='profile_edit_description'>
						<p>You can edit your Bio.<br><span class='linkButNotLink' id='changeDescriptionLink'>Click here</span>
						
					</div>
					<?php
				}
				?>
			</div>

			<div class='profile_details_about_right'>
				
				<div class="profile_details_about_right_upperpart">
				
					<div class="profile_details_about_right_name">
						<?php echo $user['first_name'] . " " . $user['last_name']; ?>
</div>

					<div class="profile_details_about_right_username">
						<?php echo '@' . $user['username']; ?>
</div>

					<div class="profile_details_about_right_email">
						<?php echo $user['email']; ?>
					</div>

					<div class="profile_details_about_right_joined">
						<?php
							//Timeframe
							$date_time = $user['signup_date'];
							$date_time_now = date("Y-m-d H:i:s");
							$start_date = new DateTime($date_time); //Time of post
							$end_date = new DateTime($date_time_now); //Current time
							$interval = $start_date->diff($end_date); //Difference between dates 
							if($interval->y >= 1) {
								if($interval->y == 1)
									$time_message = $interval->y . " year ago"; //1 year ago
								else 
									$time_message = $interval->y . " years ago"; //1+ year ago
							}
							else if ($interval->m >= 1) {
								if($interval->d == 0) {
									$days = " ago ";
								}
								else if($interval->d == 1) {
									$days = $interval->d . " day ago ";
								}
								else {
									$days = $interval->d . " days ago ";
								}


								if($interval->m == 1) {
									$time_message = $interval->m . " month ". $days;
								}
								else {
									$time_message = $interval->m . " months ". $days;
								}

							}
							else if($interval->d >= 1) {
								if($interval->d == 1) {
									$time_message = "Yesterday";
								}
								else {
									$time_message = $interval->d . " days ago ";
								}
							}
							else if($interval->h >= 1) {
								if($interval->h == 1) {
									$time_message = $interval->h . " hour ago ";
								}
								else {
									$time_message = $interval->h . " hours ago ";
								}
							}
							else if($interval->i >= 1) {
								if($interval->i == 1) {
									$time_message = $interval->i . " minute ago ";
								}
								else {
									$time_message = $interval->i . " minutes ago ";
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

							echo 'Joined ' . $time_message;
						?>
</div>
					
				</div>
				
				<div class="profile_details_about_right_description" style="display:none" id="profile_user_description_hidden">
					<div class="profile_details_about_right_description_textarea">
						<form class='profile_submit_description' action="" method="POST">
							<textarea class="profile_details_about_right_description_textarea_box" name='profile_user_description'><?php echo $user_to_show_description; ?></textarea>
							<input type='hidden' name='profile_user_description_userlogged' value='<?php echo $userLoggedIn; ?>'>
						</form>
					</div>
					<div class="profile_details_about_right_description_button">
						<button type='button' id='submit_profile_description'>Save</button>
						<button type='button' id='cancel_profile_description'>Cancel</button>
					</div>
				</div>
				<div class='profile_details_about_right_description_show' id="profile_user_description_shown">
					<?php echo $user_to_show_description; ?>
				</div>
				
			</div>

			<div class="profile_cover"></div>
			
			<div class='profile_details_total_right'>
			
			<div class='profile_details_total_right_question'>
				<h4>Total Questions</h4>
				<div class='profile_details_total_right_number'><?php echo $user['question']; ?></div>
			</div>
			
			<?php
				$total_upvote = 0;
				$total_views = 0;
				$query = mysqli_query($con,"SELECT * FROM answer WHERE given_by='$profile_to_show'");
			
				while($row = mysqli_fetch_array($query)){
					$views = $row['views'];
					$upvote = $row['upvote'];
					
					$total_upvote = $total_upvote + $upvote;
					$total_views = $total_views + $views;
				}
			?>
			
			<div class='profile_details_total_right_answer'>
				<h4>Total Answer</h4>
				<div class='profile_details_total_right_number'><?php echo $user['answer']; ?></div>
			</div>
			
			<div class='profile_details_total_right_upvotes'>
				<h4>Total Upvotes</h4>
				<div class='profile_details_total_right_number'><?php echo $total_upvote; ?></div>
			</div>
			
			<div class='profile_details_total_right_views'>
				<h4>Total Views</h4>
				<div class='profile_details_total_right_number'><?php echo $total_views; ?></div>
			</div>
			
		</div>
			
		</div>

		<div class="profile_left_options">
			<h5>Feeds</h5>
            <hr align="center">
            <ul class="question_left_panel_ul">
				<a href="profile.php?profile_username=<?php echo $user_to_show; ?>"> <li class="question_left_panel_li question_tablinks question_activ" style="color:#6da5ff;">Answers</li></a>
                <a href="profile_question.php?profile_username=<?php echo $user_to_show; ?>"><li class="question_left_panel_li question_tablinks" >Question</li></a>
				<a href="profile_follower.php?profile_username=<?php echo $user_to_show; ?>"><li class="question_left_panel_li question_tablinks" >Followers</li></a>
				<a href="profile_following.php?profile_username=<?php echo $user_to_show; ?>"><li class="question_left_panel_li question_tablinks" >Followings</li></a>
				<a href="profile_topics.php?profile_username=<?php echo $user_to_show; ?>"><li class="question_left_panel_li question_tablinks" >Topics</li></a>
            </ul>
		</div>

		<div class='profile_feed'>
			<?php 
				if(($user_to_show != $userLoggedIn) && $user['answer']==0 ){
					echo '<h5><b>'.$user['first_name'].', did not answer any question till now</b></h5>';
				}
			
				else{
					echo '	<div class="posts_area"></div>
						<img id="loading" src="assets/images/loading.gif" style="width:100px;">';
				}
			?>
		</div>

		<script>
			//<div class="posts_area"></div>
			//		<img id="loading" src="assets/images/loading.gif" style="width:100px;">
			var user_to_show = '<?php echo $user_to_show; ?>';

			$(document).ready(function() {

				$('#loading').show();

				//Original ajax request for loading first posts 
				$.ajax({
					url: "ajaxfile/ajax_if_getProfileAnswer.php",
					type: "POST",
					data: "page=1&userLoggedIn=" + user_to_show,
					cache:false,

					success: function(data) {
						$('#loading').hide();
						$('.posts_area').html(data);
					}
				});

				$(window).scroll(function() {
					var height = $('.posts_area').height(); //Div containing posts
					var scroll_top = $(this).scrollTop();
					var page = $('.posts_area').find('.nextPage').val();
					var noMorePosts = $('.posts_area').find('.noMorePosts').val();

					if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
						$('#loading').show();

						var ajaxReq = $.ajax({
							url: "ajaxfile/ajax_if_getProfileAnswer.php",
							type: "POST",
							data: "page=" + page + "&userLoggedIn=" + $user_to_show,
							cache:false,

							success: function(response) {
								$('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
								$('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 

								$('#loading').hide();
								$('.posts_area').append(response);
							}
						});

					} //End if 

					return false;

				}); //End (window).scroll(function())


			});

		</script>
		
		<!--Cropping Modals-->
			<div class="modal fade" id="crop_pic" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true" style="border-radius: 			0px;width: 50%;padding: 10px;">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header">
                      <h3 class="modal-title" id="exampleModalLabel">Crop the pic</h3>
                    </div>
					
					<div class="crop_pic_image_div" id="CroppingContainer">
						<div id="CroppingArea" style="position:relative;">
							<img class="my_image" src="<?php echo $imgSrc; ?>" style="width:<?php echo $original_width; ?>px;height:<?php echo $original_height; ?>px;object-fit: contain;" id="jcrop_target"/>
						</div>
					</div>	
						
					
						<!--<button class="uk-button uk-button-default uk-modal-close" data-dismiss="modal">Close</button>-->
						
						<div id="CropImageForm">  
							<form class="submit_profile_photo" action="" method="post" onsubmit="return checkCoords();">
								<input type="hidden" id="x" name="x" value=""/>
								<input type="hidden" id="y" name="y" value=""/>
								<input type="hidden" id="w" name="w" value=""/>
								<input type="hidden" id="h" name="h" value=""/>
								<input type="hidden" value="jpeg" name="type" /> 
								<input type="hidden" value="<?=$imgSrc?>" id="save_scr" name="src" />
								<input type="hidden" value="<?php echo $userLoggedIn; ?>" name="username" />
								<p class="uk-text-right">
									<button class="uk-button uk-button-default uk-modal-close" type="button" id='' data-toggle="modal" data-dismiss="modal">Cancel</button>
									<input type="submit" class="uk-button uk-button-primary" id="save_profile_pic_button"value="Save"  />	
								</p>	
							</form>
						</div>
								
                </div>
              </div>
            </div> 
       
    </body>
</html>
  
    
     
