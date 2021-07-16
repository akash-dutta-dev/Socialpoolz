<?php
include("../config/config.php");

error_reporting(E_PARSE | E_ERROR);

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];
$action = $_POST['action'];
$questionId = $_POST['questionId'];

$question_topic_query = mysqli_query($con,"SELECT topic FROM question WHERE id='$questionId'");
$question_topic_row = mysqli_fetch_array($question_topic_query);
$question_topic = $question_topic_row['topic'];

/*******************************************************
***********WHILE ADDING TOPICS TO QUESTION**************
********************************************************/

if($action == "add"){
	$names = explode(" ", $query);

	//If query contains one word
	if(count($names) == 1) 
		$usersReturnedQuery = mysqli_query($con, "SELECT * FROM topic WHERE name LIKE '$query%' OR
																			   name LIKE '%$query%' LIMIT 4");
	//If there are two words
	else if(count($names) == 2)
		$usersReturnedQuery = mysqli_query($con, "SELECT * FROM topic WHERE (name LIKE '$query%' OR
																				name LIKE '%$query%' OR
																				name LIKE '$names[0]%' AND  name LIKE '%$names[1]%' OR
																				name LIKE '%$names[0]%' AND  name LIKE '%$names[1]%' OR
																				name LIKE '$names[1]%' AND  name LIKE '%$names[0]%' OR
																				name LIKE '%$names[1]%' AND  name LIKE '%$names[0]%')

																				LIMIT 4");
	//If query has three words
	else if(count($names) == 3)
		$usersReturnedQuery = mysqli_query($con, "SELECT * FROM topic WHERE 

												 name LIKE '%$names[0]%' AND  name LIKE '%$names[1]%' AND name LIKE '%$names[2]%'  LIMIT 4");
	else
		$usersReturnedQuery = '0';


	if($usersReturnedQuery != '0')
	{
		if($query != ""){
			
			
				
				while($row = mysqli_fetch_array($usersReturnedQuery)) {
					$newstr = $row['name'];
					$id = $row['id'];
					
					$query = mysqli_query($con,"SELECT * FROM topic WHERE id='$id'");
					$query_row = mysqli_fetch_array($query);
					$topic_name_dyn = $query_row['name'];
					if(!strstr($question_topic,$newstr)){
					
					$topic_pic = $row['topic_pic'];

						for ($x = 0; $x < count($names); $x++) {
						   // if($names[$x]!='')
							//{
								$oldstr = $newstr;
								$start_pos = strpos($oldstr,$names[$x]);
								$end_pos = strlen($names[$x])+$start_pos;

								if(substr($oldstr,$start_pos,3) != '>')
								$newstr = substr($oldstr, 0, $start_pos) . '<b>' . substr($oldstr, $start_pos,strlen($names[$x])) . '</b>' . substr($oldstr, $end_pos);
							//}
							//What are some amazing job in India under salary 40,000INR ??

						}

						?>
							<script>
								 $(document).on('click', '#addButton<?php echo $id; ?><?php echo $userLoggedIn; ?><?php echo $questionId; ?>', function() { 

									var userLoggedIn = '<?php echo $userLoggedIn; ?>';
									var data = 'userAdded='+ userLoggedIn + '&id=' + <?php echo $id; ?> + '&qid=' + <?php echo $questionId; ?>;

										 $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_topic_added_to_question.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												if(msg!="nothing"){
													
													console.log(msg);
													$('.topicResultDisplayClass<?php echo $id; ?>').hide();

													$('.questionAddedTopicsInModal<?php echo $questionId; ?><?php echo $userLoggedIn; ?>').append("<div class='topicsAlreadyAddedInQuestion topicsAlreadyAddedInQuestionId<?php echo $questionId; ?><?php echo $topic_name_dyn; ?><?php echo $userLoggedIn; ?>'>	<button class='topicsAlreadyAddedInQuestionButton' id='topicsAlreadyAddedInQuestionButton<?php echo $questionId; ?><?php echo $topic_name_dyn; ?><?php echo $userLoggedIn; ?>'>x</button>												"+msg+"																									</div>");
												
													$(function () {
													  $('<script>')
														
														.text('$(document).on("click", "#topicsAlreadyAddedInQuestionButton<?php echo $questionId; ?><?php echo $topic_name_dyn; ?><?php echo $userLoggedIn; ?>", function() {var userLoggedIn = "<?php echo $userLoggedIn; ?>";var topicDeleted = "<?php echo $topic_name_dyn; ?>";var data = "userDeleted="+ userLoggedIn + "&topicDeleted=" + topicDeleted + "&qid=" + <?php echo $questionId; ?>;$.ajax({type:"POST",cache:false,url:"ajaxfile/ajax_topic_deleted_to_question.php",data:data,success: function (msg) {console.log(msg+"ht");$(".topicsAlreadyAddedInQuestionId<?php echo $questionId; ?><?php echo $topic_name_dyn; ?><?php echo $userLoggedIn; ?>").remove();},error: function() {alert("Deleting Failure");}});});')
														.appendTo('body');
													});
													
												}

											},
											error: function() {
												alert('Adding Failure');
											}
										 });

									   });

							</script>
						<?php

						$user_query = mysqli_query($con,"SELECT * FROM users WHERE username = '$userLoggedIn'");
						$user_query_array = mysqli_fetch_array($user_query);
						$user_topic = $user_query_array['topic'];

							$btnToShow = "<button class='followButton' name='addButton' id='addButton$id$userLoggedIn$questionId'>Add</button>";

								echo "<div class='topicResultDisplay topicResultDisplayClass$id' id='topicResultDisplay$id' style='width:16%;'>

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

		}
	}
	else
		echo "<div class='resultDisplay'>
				No result found
			</div>";
	}

/*******************************************************
***********WHILE SEARCHING TOPICS TO FOLLOW*************
********************************************************/

else{
	$names = explode(" ", $query);

	//If query contains one word
	if(count($names) == 1) 
		$usersReturnedQuery = mysqli_query($con, "SELECT * FROM topic WHERE name LIKE '$query%' OR
																			   name LIKE '%$query%' LIMIT 4");
	//If there are two words
	else if(count($names) == 2)
		$usersReturnedQuery = mysqli_query($con, "SELECT * FROM topic WHERE (name LIKE '$query%' OR
																				name LIKE '%$query%' OR
																				name LIKE '$names[0]%' AND  name LIKE '%$names[1]%' OR
																				name LIKE '%$names[0]%' AND  name LIKE '%$names[1]%' OR
																				name LIKE '$names[1]%' AND  name LIKE '%$names[0]%' OR
																				name LIKE '%$names[1]%' AND  name LIKE '%$names[0]%')

																				LIMIT 4");
	//If query has three words
	else if(count($names) == 3)
		$usersReturnedQuery = mysqli_query($con, "SELECT * FROM topic WHERE 

												 name LIKE '%$names[0]%' AND  name LIKE '%$names[1]%' AND name LIKE '%$names[2]%'  LIMIT 4");
	else
		$usersReturnedQuery = '0';


	if($usersReturnedQuery != '0')
	{
		if($query != ""){

			while($row = mysqli_fetch_array($usersReturnedQuery)) {
				$newstr = $row['name'];
				$id = $row['id'];
				$topic_pic = $row['topic_pic'];

				for ($x = 0; $x < count($names); $x++) {
				   // if($names[$x]!='')
					//{
						$oldstr = $newstr;
						$start_pos = strpos($oldstr,$names[$x]);
						$end_pos = strlen($names[$x])+$start_pos;

						if(substr($oldstr,$start_pos,3) != '>')
						$newstr = substr($oldstr, 0, $start_pos) . '<b>' . substr($oldstr, $start_pos,strlen($names[$x])) . '</b>' . substr($oldstr, $end_pos);
					//}
					//What are some amazing job in India under salary 40,000INR ??

				}

				?>
					<script>
						$(document).on('click', '#followButton<?php echo $id; ?><?php echo $userLoggedIn; ?>', function() { 
							

								//var question_id = $('#question_id_name').attr("value");
								//var user = $('#userLoggedin').attr("value");
								var userLoggedIn = '<?php echo $userLoggedIn; ?>';
								
								var data = 'userFollowed='+ userLoggedIn + '&id=' + <?php echo $id; ?>;
								//console.log(data);
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

										}

									},
									error: function() {
									alert('follow Failure');
									}
								 });

							   });
						
					</script>
				<?php

				$user_query = mysqli_query($con,"SELECT * FROM users WHERE username = '$userLoggedIn'");
				$user_query_array = mysqli_fetch_array($user_query);
				$user_topic = $user_query_array['topic'];

				if(strstr($user_topic,$oldstr)){
					$btnToShow = "<button class='followButton' name='followButton' id='followButton$id$userLoggedIn'>Unfollow</button>";
				}
				else{
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

		}
	}
	else
		echo "<div class='resultDisplay'>
				No result found
			</div>";
}
?>