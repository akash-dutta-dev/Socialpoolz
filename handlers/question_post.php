<?php
class Post
{
    private $con;
    private $user_from;
	
    public function __construct($con,$user_from){
        $this->con = $con;
        $this->user_from= $user_from;
	}
    
    public function submitPost($body,$link,$tags){
        $body = strip_tags($body); //removes html tags 
		$body = mysqli_real_escape_string($this->con, $body);
		$check_empty = preg_replace('/\s+/', '', $body); //Deltes all spaces 
        $thisUser=$this->user_from;
        
        //Tags optimization
        {
            $tags = ",".$tags.",";
            $tags = str_replace(",,",",",$tags);
            $tags = str_replace(",a",",A",$tags);
            $tags = str_replace(",b",",B",$tags);
            $tags = str_replace(",c",",C",$tags);
            $tags = str_replace(",d",",D",$tags);
            $tags = str_replace(",e",",E",$tags);
            $tags = str_replace(",f",",F",$tags);
            $tags = str_replace(",g",",G",$tags);
            $tags = str_replace(",h",",H",$tags);
            $tags = str_replace(",i",",I",$tags);
            $tags = str_replace(",j",",J",$tags);
            $tags = str_replace(",k",",K",$tags);
            $tags = str_replace(",l",",L",$tags);
            $tags = str_replace(",m",",M",$tags);
            $tags = str_replace(",n",",N",$tags);
            $tags = str_replace(",o",",O",$tags);
            $tags = str_replace(",p",",P",$tags);
            $tags = str_replace(",q",",Q",$tags);
            $tags = str_replace(",r",",R",$tags);
            $tags = str_replace(",s",",S",$tags);
            $tags = str_replace(",t",",T",$tags);
            $tags = str_replace(",u",",U",$tags);
            $tags = str_replace(",v",",V",$tags);
            $tags = str_replace(",w",",W",$tags);
            $tags = str_replace(",x",",X",$tags);
            $tags = str_replace(",y",",Y",$tags);
            $tags = str_replace(",z",",Z",$tags);
            
        }
        if($tags==""){
            $tags=",";
        }
		if($check_empty != "") 
		{


			//Current date and time
			$date_added = date("Y-m-d H:i:s");

			//insert post 
			$query = mysqli_query($this->con, "INSERT INTO question VALUES('', '$body', '$link', '$date_added', '$this->user_from', 'no','0','$tags','','no')");
			$returned_id = mysqli_insert_id($this->con);

            $user_details_query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$thisUser'");
		    $user_row = mysqli_fetch_array($user_details_query);
            $num_question = $user_row['question'];
			$num_question++;
			$insert = mysqli_query($this->con, "UPDATE users SET question = '$num_question' WHERE username ='$thisUser'");
            //$returned_id = mysqli_insert_id($this->con);
			
			//Insert notification
			$notification_date_added = date("Y-m-d H:i:s");
			$notification_body = "<a href='search.php?qid=".$returned_id."'>Your question <b>Q:-'".$body."'</b> submitted successfully</a>";
			$notification_body = str_replace("'",'"',$notification_body);
			$notification_insert = mysqli_query($this->con, "INSERT INTO notification VALUES('', '$notification_body', 
			'$this->user_from','$notification_date_added','no')");
				//echo $notification_body;
			}

	}

    public function getQuestion($data,$limit){
		
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM question WHERE deleted<>'yes' ORDER BY id DESC");

        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
			  
			   $(document).on('click', '.hide_similar_question', function() { 
					
						UIkit.alert(element, options);
						alert("fe");
					});
			  
			  
		  </script>
         <?php
			if(mysqli_num_rows($data_query) > 0) {

				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;

				while($row = mysqli_fetch_array($data_query)) {
					
					$topics_to_show = "";
					$id = $row['id'];
					$question_body = $row['question_body'];
					$question_body_unedited = $question_body;
					$question_link = $row['question_link'];
					$posted_by = $row['posted_by'];
					$date_time = $row['date_added'];
					$question_topic = $row['topic'];
					$thisUser = $this->user_from;

					$question_body_unedited = str_replace("'","",$question_body_unedited);
					$question_body_unedited = str_replace('"',"",$question_body_unedited);


					?>
						<script> 
							function toggle<?php echo $id; ?>() {

								var target = $(event.target);
									if (!target.is("a")) {
										var element = document.getElementById("toggleAnswer<?php echo $id; ?>");

										if(element.style.display == "block") 									
											element.style.display = "none";
										else 
											element.style.display = "block";
									}
							}
							
							/* When the user clicks on the button, 
							toggle between hiding and showing the dropdown content */
							function showQuestionExtraMenu() {
								document.getElementById("profile_icon_myDropdown").classList.toggle("profile_icon_show");
							}

							// Close the dropdown if the user clicks outside of it
							window.onclick = function(event) {
							  if (!event.target.matches('.profile_icon_dropbtn')) {

								var dropdowns = document.getElementsByClassName("profile_icon_dropdown-content");
								var i;
								for (i = 0; i < dropdowns.length; i++) {
								  var openDropdown = dropdowns[i];
								  if (openDropdown.classList.contains('profile_icon_show')) {
									openDropdown.classList.remove('profile_icon_show');
								  }
								}
							  }
							}

								$(document).ready(function() {
									$('#question_like<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_like.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var likes = msg + ' Likes';
											$("#question_like_shows<?php echo $thisUser; ?><?php echo $id; ?>").text(likes);
											location.reload();
											//$("#question_like<?php echo $thisUser; ?><?php echo $id; ?>").text("Unlike this question");

											//$("#question_like<?php echo $thisUser; ?><?php echo $id; ?>").attr("id","question_unlike<?php echo $thisUser; ?><?php //echo $id; ?>");

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});


									$('#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_unlike.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var likes = msg + ' Likes';
											$("#question_like_shows<?php echo $thisUser; ?><?php echo $id; ?>").text(likes);
											location.reload();
											//$("#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>").text("Like this question");

											//$("#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>").attr("id","question_like<?php echo $thisUser; ?><?php ///echo $id; ?>");

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});

									$('#answer_later<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userClicked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_answer_later.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {

											if(msg == '0'){
											   $("#answer_later<?php echo $thisUser; ?><?php echo $id; ?>").text("Answer this Later");
												toast('Question Removed');
											   }
											else{
												$("#answer_later<?php echo $thisUser; ?><?php echo $id; ?>").text("Question Added");
												toast('Question Added');
											}

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});
									
									$('#delete_question<?php echo $id; ?>').click(function () {

									  var data = 'id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_delete.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											location.reload();
										},
										error: function() {
											alert('Question Deletion Failure');
										}
									  });

									});
									
									$('#question_add_topic<?php echo $id; ?>').on('hidden', function () {
										$(".search_topic_results").html("");
										$('.search_topic_results').css({"height": "0px"});
									})
									
									$('#hide_question<?php echo $id; ?>').click(function () {
										$('.hide_question<?php echo $id; ?>').css("display","block");
									});
	
								});
							
							


						</script>

					<?php
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
					//Topic List
                    //Display link line
                    if($question_link != ""){
                        $question_link_line = "<div class='question_post_series_link'>
                                                    <a href='$question_link' target='_blank'>
                                                        <span uk-icon='link'></span>
                                                        $question_link
                                                    </a>
                                                </div>";
                    }
                    else{
                        $question_link_line = "";
                    }
					
					$question_topics = explode(",", $question_topic);
					foreach($question_topics as $i){
						if($i!=''){
							$topics_to_show .= "<div class='topicsAlreadyAddedInQuestion' id='topicsAlreadyAddedInQuestionId$id$i$userLoggedIn'>
													<button class='topicsAlreadyAddedInQuestionButton' id='topicsAlreadyAddedInQuestionButton$id$i$userLoggedIn'>x</button>
													".$i."													
												</div>";
							?>
								<script>
									 $(document).on('click', '#topicsAlreadyAddedInQuestionButton<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>', function() { 

										var userLoggedIn = '<?php echo $userLoggedIn; ?>';
										var topicDeleted = '<?php echo $i; ?>';
										var data = 'userDeleted='+ userLoggedIn + '&topicDeleted=' + topicDeleted + '&qid=' + <?php echo $id; ?>;

										 $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_topic_deleted_to_question.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												console.log(msg);
												$('#topicsAlreadyAddedInQuestionId<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>').hide();

											},
											error: function() {
												alert('Deleting Failure');
											}
										 });

									   });
									
								</script>
							<?php
						}
						
					}
					
					//Display number of likes
					$check_num_likes = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id'");
					$num_likes = mysqli_num_rows($check_num_likes);
					$num_like_string =  $num_likes . " Likes";

					//Display the like button
					$check_num_likes_button = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id' 
																		AND question_liked_by='$this->user_from'");
					if(mysqli_num_rows($check_num_likes_button)>0){
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_unlike$thisUser$id'>Unlike this question </button>";
					}
					else{
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_like$thisUser$id'>Like this question </button>";
					}

					//Check to Display answer later button
					$check_answer_later_button = mysqli_query($this->con, "SELECT * FROM answer_later WHERE question_id='$id' 
																		AND user_added='$this->user_from'");
					if(mysqli_num_rows($check_answer_later_button)>0){
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id' > Qustion Added </button>";
					}
					else{
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id'> Answer This Later </button>";
					}
					
					//Check to display delete button
					if(($thisUser == $posted_by) || $thisUser == 'akash_dutta'){
						$close_button_to_show = "<div class='question_post_series_deletebutton questionExtraMenu' id='questionCloseButton$id'>
													<div uk-icon='close' uk-toggle='target: #close_button$id'></div>
												</div>
												";
						
						$close_button_modal_to_show = "<div class='delete_button' id='close_button$id' uk-modal>
														<div class='uk-modal-dialog uk-modal-body'>
															<h3>This Question will be deleted permanently.</h3>
															<hr>
															<h5>Do you want to delete this following question?</h5>
															<h5>Q:-'.$question_body_unedited.'</h5>
															<div class='uk-modal-footer uk-text-right'>
																<p class='uk-text-right'>
																	<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
																	<button class='uk-button uk-button-primary' type='button' id='delete_question$id'>Yes, Delete</button>
																</p>
															</div>
														</div>
													</div>
													";
					}
					else{
						$close_button_to_show = "";
						$close_button_modal_to_show = "";
					}

						//Timeframe
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

						$time_message = "Posted ". $time_message;
						$str .= "<div class='question_post_series'>

									<div class='question_post_series_question_and_deletebutton'>
										<div class='question_post_series_question'>
											<a href='search.php?q=$question_body_unedited&qid=$id'>$question_body</a>
										</div>
										$close_button_to_show
										
									</div>
								   
									$close_button_modal_to_show
									$question_link_line			
												
									<div class='question_post_series_info'>
										<div class='question_post_series_time'>
											$time_message
										</div>
										<div class='question_post_series_likes' id='question_like_shows$thisUser$id'>
										  $num_like_string
										</div>
									</div>	
									<div class='question_post_series_button'>

										<button type='button' class='AddAnswer' style='background-color: #bb3030;color: #fff;'  onClick='javascript:toggle$id()'>
											Add a answer 
										</button>

										$like_button_to_show
										$answer_later_button_to_show
										
										<div uk-icon='menu' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>
					
											
										<div uk-drop='mode: click'>
											<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
												<ul class='questionUlDesign'>
													<a href='#'><li type='button' uk-toggle='target: #question_add_topic$id'>Add topics</li></a>
													<hr>
													<a href='#'><li type='button' id='hide_question$id'>Hide similar question</li></a>
													<hr>
													<a href='#' <li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
													<hr>
													<a href='#'><li type='button' uk-toggle='target: #report_question$id'>Report</li></a>
												</ul>
											</div>
										</div>

										<div id='question_add_topic$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Add some topic</h2>

												<h5>Q:-".$question_body_unedited."</h5>

												<div class='questionAddedTopicsInModal$id$userLoggedIn'>
													".$topics_to_show."
												</div>

												<div class='load_topic_form'>
													<form action='ajax_load_topic_box.php' method='POST' class='load_topic_form_form'>	
														<input type='text' name='topix_text' placeholder='Search a Topic to add.. (Ex:- Science, Technology, etc) ' onkeyup='getLiveTopic(this.value, ".'"'.$userLoggedIn.'"'.",".'"add"'.", ".'"'.$id.'"'.")'
															   autocomplete='off' id='id_topix_text' style='width:100%; height: 30px;background-color: #e9eef3;' class='load_topic_form_form_input'>
													</form>



													<div class='search_topic_results'>
													</div>
												</div>

												<div class='uk-modal-footer uk-text-right'>
													<p class='uk-text-right'>
														<button class='uk-button uk-button-default uk-modal-close' type='button'>DONE</button>
													</p>
												</div>
											</div>
										</div>

										<div class='uk-alert-warning hide_question$id' uk-alert style='display:none'>
											<a class='uk-alert-close' uk-close></a>
											<p style='margin-left:10px;font-size:17px'>You will stop seeing question related to<br>Q:-".$question_body_unedited."</p>
										</div>

										<div class='changeQuestion' id='change_question$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Change Question</h2>
												<h4>From:-</h4>
												<h5>Q:-".$question_body_unedited."</h5>
												<h4>To:-</h4>

												<form action='question.php' method='post'>
													<input type='text' name='change_question_question' value='$question_body_unedited'>
													<input type='hidden' name='change_question_id' value='$id'>
													<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
													<div class='uk-modal-footer uk-text-right'>
													<p class='uk-text-right'>
														<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
														<input class='uk-button uk-button-primary' type='submit' name='question_change_button' id='change_question'>
													</p>
												</div>
												</form>


											</div>
										</div>

										<div class='report_question' id='report_question$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Report this Question</h2>
												<h5>Q:-".$question_body_unedited."</h5>


												<form action='question.php' method='post'>
													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;' checked>Inappropriate Question</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Spelling Mistake or Grammatical Error</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Questoin Typed In Language other than English</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Abusive Words</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Sexual Intimation</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Marketing Stuff From Unauthorized User</label>



													<input type='hidden' name='change_question_id' value='$id'>
													<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
													<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
															<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
														</p>
													</div>
												</form>


											</div>
										</div>
										

									</div> 
								</div>

								<div class='question_post_answer' id='toggleAnswer$id' style='display:none;'>
									<iframe src='answer_frame.php?question_id=$id' id='question_answer_iframe' frameborder='0'></iframe>
								</div>

								<hr>";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more question to load</h5>";
				
			}
            //$str = $str .'<h5>No more question to load</h5>';
			echo $str;

        
    }
	
	public function getAnswerLater($data,$limit){

		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM answer_later WHERE user_added='$this->user_from' ORDER BY id DESC");
		$load_count = 0;
        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
			  
			   $(document).on('click', '.hide_similar_question', function() { 
					
						UIkit.alert(element, options);
						alert("fe");
					});
			  
			  
		  </script>
         <?php
			if(mysqli_num_rows($data_query) > 0) {

				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;

				while($row = mysqli_fetch_array($data_query)) {
					$question_id = $row['question_id'];
					$data_query_question = mysqli_query($this->con, "SELECT * FROM question WHERE id='$question_id'");
					$row_question = mysqli_fetch_array($data_query_question);

					$id = $row_question['id'];
					$question_body = $row_question['question_body'];
					$question_link = $row_question['question_link'];
					$posted_by = $row_question['posted_by'];
					$date_time = $row_question['date_added'];
					$question_topic = $row_question['topic'];
					$is_deleted = $row_question['deleted'];
					$question_body_unedited = $question_body;
					$thisUser = $this->user_from;
					$topics_to_show = "";
					
					$question_body_unedited = str_replace("'","",$question_body_unedited);
					$question_body_unedited = str_replace('"',"",$question_body_unedited);
					
					if($is_deleted == 'yes'){
						$load_count++;
						continue;
					}
					
					?>
						<script> 
							function toggle<?php echo $id; ?>() {

							var target = $(event.target);
								if (!target.is("a")) {
									var element = document.getElementById("toggleAnswer<?php echo $id; ?>");

									if(element.style.display == "block") 									
										element.style.display = "none";
									else 
										element.style.display = "block";
								}
							}
							
							/* When the user clicks on the button, 
							toggle between hiding and showing the dropdown content */
							function showQuestionExtraMenu() {
								document.getElementById("profile_icon_myDropdown").classList.toggle("profile_icon_show");
							}

							// Close the dropdown if the user clicks outside of it
							window.onclick = function(event) {
							  if (!event.target.matches('.profile_icon_dropbtn')) {

								var dropdowns = document.getElementsByClassName("profile_icon_dropdown-content");
								var i;
								for (i = 0; i < dropdowns.length; i++) {
								  var openDropdown = dropdowns[i];
								  if (openDropdown.classList.contains('profile_icon_show')) {
									openDropdown.classList.remove('profile_icon_show');
								  }
								}
							  }
							}

								$(document).ready(function() {
									$('#question_like<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_like.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var likes = msg + ' Likes';
											$("#question_like_shows<?php echo $thisUser; ?><?php echo $id; ?>").text(likes);
											location.reload();
											//$("#question_like<?php echo $thisUser; ?><?php echo $id; ?>").text("Unlike this question");

											//$("#question_like<?php echo $thisUser; ?><?php echo $id; ?>").attr("id","question_unlike<?php echo $thisUser; ?><?php //echo $id; ?>");

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});


									$('#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_unlike.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var likes = msg + ' Likes';
											$("#question_like_shows<?php echo $thisUser; ?><?php echo $id; ?>").text(likes);
											location.reload();
											//$("#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>").text("Like this question");

											//$("#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>").attr("id","question_like<?php echo $thisUser; ?><?php ///echo $id; ?>");

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});

									$('#answer_later<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userClicked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_answer_later.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {

											if(msg == '0'){
											   $("#answer_later<?php echo $thisUser; ?><?php echo $id; ?>").text("Answer this Later");
												toast('Question Removed');
											   }
											else{
												$("#answer_later<?php echo $thisUser; ?><?php echo $id; ?>").text("Question Added");
												toast('Question Added');
											}

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});
									
									$('#delete_question<?php echo $id; ?>').click(function () {

									  var data = 'id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_delete.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											location.reload();
										},
										error: function() {
											alert('Question Deletion Failure');
										}
									  });

									});
									
									$('#question_add_topic<?php echo $id; ?>').on('hidden', function () {
										$(".search_topic_results").html("");
										$('.search_topic_results').css({"height": "0px"});
									})
									
									$('#hide_question<?php echo $id; ?>').click(function () {
										$('.hide_question<?php echo $id; ?>').css("display","block");
									});
	
								});
							
							


						</script>

					<?php
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
					//Topic List
                    //Display link line
                    if($question_link != ""){
                        $question_link_line = "<div class='question_post_series_link'>
                                                    <a href='$question_link' target='_blank'>
                                                        <span uk-icon='link'></span>
                                                        $question_link
                                                    </a>
                                                </div>";
                    }
                    else{
                        $question_link_line = "";
                    }
					
					$question_topics = explode(",", $question_topic);
					foreach($question_topics as $i){
						if($i!=''){
							$topics_to_show .= "<div class='topicsAlreadyAddedInQuestion' id='topicsAlreadyAddedInQuestionId$id$i$userLoggedIn'>
													<button class='topicsAlreadyAddedInQuestionButton' id='topicsAlreadyAddedInQuestionButton$id$i$userLoggedIn'>x</button>
													".$i."													
												</div>";
							?>
								<script>
									 $(document).on('click', '#topicsAlreadyAddedInQuestionButton<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>', function() { 

										var userLoggedIn = '<?php echo $userLoggedIn; ?>';
										var topicDeleted = '<?php echo $i; ?>';
										var data = 'userDeleted='+ userLoggedIn + '&topicDeleted=' + topicDeleted + '&qid=' + <?php echo $id; ?>;

										 $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_topic_deleted_to_question.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												console.log(msg);
												$('#topicsAlreadyAddedInQuestionId<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>').hide();

											},
											error: function() {
												alert('Deleting Failure');
											}
										 });

									   });
									
								</script>
							<?php
						}
						
					}
					
					//Display number of likes
					$check_num_likes = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id'");
					$num_likes = mysqli_num_rows($check_num_likes);
					$num_like_string =  $num_likes . " Likes";

					//Display the like button
					$check_num_likes_button = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id' 
																		AND question_liked_by='$this->user_from'");
					if(mysqli_num_rows($check_num_likes_button)>0){
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_unlike$thisUser$id'>Unlike this question </button>";
					}
					else{
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_like$thisUser$id'>Like this question </button>";
					}

					//Check to Display answer later button
					$check_answer_later_button = mysqli_query($this->con, "SELECT * FROM answer_later WHERE question_id='$id' 
																		AND user_added='$this->user_from'");
					if(mysqli_num_rows($check_answer_later_button)>0){
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id' > Qustion Added </button>";
					}
					else{
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id'> Answer This Later </button>";
					}
					
					//Check to display delete button
					if(($thisUser == $posted_by) || $thisUser == 'akash_dutta'){
						$close_button_to_show = "<div class='question_post_series_deletebutton questionExtraMenu' id='questionCloseButton$id'>
													<div uk-icon='close' uk-toggle='target: #close_button$id'></div>
												</div>
												";
						
						$close_button_modal_to_show = "<div class='delete_button' id='close_button$id' uk-modal>
														<div class='uk-modal-dialog uk-modal-body'>
															<h3>This Question will be deleted permanently.</h3>
															<hr>
															<h5>Do you want to delete this following question?</h5>
															<h5>Q:-'.$question_body_unedited.'</h5>
															<div class='uk-modal-footer uk-text-right'>
																<p class='uk-text-right'>
																	<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
																	<button class='uk-button uk-button-primary' type='button' id='delete_question$id'>Yes, Delete</button>
																</p>
															</div>
														</div>
													</div>
													";
					}
					else{
						$close_button_to_show = "";
						$close_button_modal_to_show = "";
					}

						//Timeframe
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

						$time_message = "Posted ". $time_message;
						$str .= "<div class='question_post_series'>

									<div class='question_post_series_question_and_deletebutton'>
										<div class='question_post_series_question'>
											<a href='search.php?q=$question_body_unedited&qid=$id'>$question_body</a>
										</div>
										$close_button_to_show
										
									</div>
								   
									$close_button_modal_to_show
									$question_link_line			
												
									<div class='question_post_series_info'>
										<div class='question_post_series_time'>
											$time_message
										</div>
										<div class='question_post_series_likes' id='question_like_shows$thisUser$id'>
										  $num_like_string
										</div>
									</div>	
									<div class='question_post_series_button'>

										<button type='button' class='AddAnswer' style='background-color: #bb3030;color: #fff;'  onClick='javascript:toggle$id()'>
											Add a answer 
										</button>

										$like_button_to_show
										$answer_later_button_to_show
										
										<div uk-icon='menu' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>
					
											
											<div uk-drop='mode: click'>
												<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
													<ul class='questionUlDesign'>
														<a href='#'><li type='button' uk-toggle='target: #question_add_topic$id'>Add topics</li></a>
														<hr>
														<a href='#'><li type='button' id='hide_question$id'>Hide similar question</li></a>
														<hr>
														<a href='#' <li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #report_question$id'>Report</li></a>
													</ul>
												</div>
											</div>
											
											<div id='question_add_topic$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Add some topic</h2>
													
													<h5>Q:-".$question_body_unedited."</h5>
													
													<div class='questionAddedTopicsInModal$id$userLoggedIn'>
														".$topics_to_show."
													</div>
													
													<div class='load_topic_form'>
														<form action='ajax_load_topic_box.php' method='POST' class='load_topic_form_form'>	
															<input type='text' name='topix_text' placeholder='Search a Topic to add.. (Ex:- Science, Technology, etc) ' onkeyup='getLiveTopic(this.value, ".'"'.$userLoggedIn.'"'.",".'"add"'.", ".'"'.$id.'"'.")'
																   autocomplete='off' id='id_topix_text' style='width:100%; height: 30px;background-color: #e9eef3;' class='load_topic_form_form_input'>
														</form>



														<div class='search_topic_results'>
														</div>
													</div>
													
													<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>DONE</button>
														</p>
													</div>
												</div>
											</div>
											
											<div class='uk-alert-warning hide_question$id' uk-alert style='display:none'>
												<a class='uk-alert-close' uk-close></a>
												<p style='margin-left:10px;font-size:17px'>You will stop seeing question related to<br>Q:-".$question_body_unedited."</p>
											</div>
											
											<div class='changeQuestion' id='change_question$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Change Question</h2>
													<h4>From:-</h4>
													<h5>Q:-".$question_body_unedited."</h5>
													<h4>To:-</h4>
													
													<form action='question.php' method='post'>
														<input type='text' name='change_question_question' value='$question_body_unedited'>
														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
															<input class='uk-button uk-button-primary' type='submit' name='question_change_button' id='change_question'>
														</p>
													</div>
													</form>
													
													
												</div>
											</div>
											
											<div class='report_question' id='report_question$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Report this Question</h2>
													<h5>Q:-".$question_body_unedited."</h5>
													
													
													<form action='question.php' method='post'>
													 	<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;' checked>Inappropriate Question</label>
														
            										 	<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Spelling Mistake or Grammatical Error</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Questoin Typed In Language other than English</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Abusive Words</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Sexual Intimation</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Marketing Stuff From Unauthorized User</label>

														
														
														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
															<p class='uk-text-right'>
																<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
																<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
															</p>
														</div>
													</form>
													
													
												</div>
											</div>
										

									</div> 
								</div>

								<div class='question_post_answer' id='toggleAnswer$id' style='display:none;'>
									<iframe src='answer_frame.php?question_id=$id' id='question_answer_iframe' frameborder='0'></iframe>
								</div>

								<hr>";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more question to load</h5>";
				
			}
			if(mysqli_num_rows($data_query) == $load_count){
				$str = "<h5 style='text-align: centre;'>No question Added.<br>Add some Questions To Answer Later</h5>";
			}
            //$str = $str .'<h5>No more question to load</h5>';
			echo $str;

        
    }
	
	public function getLikedQuestion($data,$limit){
		
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_liked_by='$this->user_from' ORDER BY id DESC");
		$load_count = 0;
        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
			  
			   $(document).on('click', '.hide_similar_question', function() { 
					
						UIkit.alert(element, options);
						alert("fe");
					});
			  
			  
		  </script>
         <?php
			if(mysqli_num_rows($data_query) > 0) {

				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;

				while($row = mysqli_fetch_array($data_query)) {
					$topics_to_show = "";
					$question_id = $row['question_id'];
					$data_query_question = mysqli_query($this->con, "SELECT * FROM question WHERE id='$question_id'");
					$row_question = mysqli_fetch_array($data_query_question);
					

					$id = $row_question['id'];
					$question_body = $row_question['question_body'];
					$question_link = $row_question['question_link'];
					$posted_by = $row_question['posted_by'];
					$date_time = $row_question['date_added'];
					$question_topic = $row_question['topic'];
					$is_deleted = $row_question['deleted'];
					$thisUser = $this->user_from;
					$question_body_unedited = $question_body;

					$question_body_unedited = str_replace("'","",$question_body_unedited);
					$question_body_unedited = str_replace('"',"",$question_body_unedited);

					if($is_deleted == 'yes'){
						$load_count++;
						continue;
					}
					
					?>
						<script> 
							function toggle<?php echo $id; ?>() {

							var target = $(event.target);
								if (!target.is("a")) {
									var element = document.getElementById("toggleAnswer<?php echo $id; ?>");

									if(element.style.display == "block") 									
										element.style.display = "none";
									else 
										element.style.display = "block";
								}
							}
							
							/* When the user clicks on the button, 
							toggle between hiding and showing the dropdown content */
							function showQuestionExtraMenu() {
								document.getElementById("profile_icon_myDropdown").classList.toggle("profile_icon_show");
							}

							// Close the dropdown if the user clicks outside of it
							window.onclick = function(event) {
							  if (!event.target.matches('.profile_icon_dropbtn')) {

								var dropdowns = document.getElementsByClassName("profile_icon_dropdown-content");
								var i;
								for (i = 0; i < dropdowns.length; i++) {
								  var openDropdown = dropdowns[i];
								  if (openDropdown.classList.contains('profile_icon_show')) {
									openDropdown.classList.remove('profile_icon_show');
								  }
								}
							  }
							}

								$(document).ready(function() {
									$('#question_like<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_like.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var likes = msg + ' Likes';
											$("#question_like_shows<?php echo $thisUser; ?><?php echo $id; ?>").text(likes);
											location.reload();
											//$("#question_like<?php echo $thisUser; ?><?php echo $id; ?>").text("Unlike this question");

											//$("#question_like<?php echo $thisUser; ?><?php echo $id; ?>").attr("id","question_unlike<?php echo $thisUser; ?><?php //echo $id; ?>");

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});


									$('#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_unlike.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var likes = msg + ' Likes';
											$("#question_like_shows<?php echo $thisUser; ?><?php echo $id; ?>").text(likes);
											location.reload();
											//$("#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>").text("Like this question");

											//$("#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>").attr("id","question_like<?php echo $thisUser; ?><?php ///echo $id; ?>");

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});

									$('#answer_later<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userClicked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_answer_later.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {

											if(msg == '0'){
											   $("#answer_later<?php echo $thisUser; ?><?php echo $id; ?>").text("Answer this Later");
												toast('Question Removed');
											   }
											else{
												$("#answer_later<?php echo $thisUser; ?><?php echo $id; ?>").text("Question Added");
												toast('Question Added');
											}

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});
									
									$('#delete_question<?php echo $id; ?>').click(function () {

									  var data = 'id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_delete.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											location.reload();
										},
										error: function() {
											alert('Question Deletion Failure');
										}
									  });

									});
									
									$('#question_add_topic<?php echo $id; ?>').on('hidden', function () {
										$(".search_topic_results").html("");
										$('.search_topic_results').css({"height": "0px"});
									})
									
									$('#hide_question<?php echo $id; ?>').click(function () {
										$('.hide_question<?php echo $id; ?>').css("display","block");
									});
	
								});
							
							


						</script>

					<?php
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
					//Topic List
                    //Display link line
                    if($question_link != ""){
                        $question_link_line = "<div class='question_post_series_link'>
                                                    <a href='$question_link' target='_blank'>
                                                        <span uk-icon='link'></span>
                                                        $question_link
                                                    </a>
                                                </div>";
                    }
                    else{
                        $question_link_line = "";
                    }
					
					$question_topics = explode(",", $question_topic);
					foreach($question_topics as $i){
						if($i!=''){
							$topics_to_show .= "<div class='topicsAlreadyAddedInQuestion' id='topicsAlreadyAddedInQuestionId$id$i$userLoggedIn'>
													<button class='topicsAlreadyAddedInQuestionButton' id='topicsAlreadyAddedInQuestionButton$id$i$userLoggedIn'>x</button>
													".$i."													
												</div>";
							?>
								<script>
									 $(document).on('click', '#topicsAlreadyAddedInQuestionButton<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>', function() { 

										var userLoggedIn = '<?php echo $userLoggedIn; ?>';
										var topicDeleted = '<?php echo $i; ?>';
										var data = 'userDeleted='+ userLoggedIn + '&topicDeleted=' + topicDeleted + '&qid=' + <?php echo $id; ?>;

										 $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_topic_deleted_to_question.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												console.log(msg);
												$('#topicsAlreadyAddedInQuestionId<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>').hide();

											},
											error: function() {
												alert('Deleting Failure');
											}
										 });

									   });
									
								</script>
							<?php
						}
						
					}
					
					//Display number of likes
					$check_num_likes = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id'");
					$num_likes = mysqli_num_rows($check_num_likes);
					$num_like_string =  $num_likes . " Likes";

					//Display the like button
					$check_num_likes_button = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id' 
																		AND question_liked_by='$this->user_from'");
					if(mysqli_num_rows($check_num_likes_button)>0){
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_unlike$thisUser$id'>Unlike this question </button>";
					}
					else{
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_like$thisUser$id'>Like this question </button>";
					}

					//Check to Display answer later button
					$check_answer_later_button = mysqli_query($this->con, "SELECT * FROM answer_later WHERE question_id='$id' 
																		AND user_added='$this->user_from'");
					if(mysqli_num_rows($check_answer_later_button)>0){
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id' > Qustion Added </button>";
					}
					else{
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id'> Answer This Later </button>";
					}
					
					//Check to display delete button
					if(($thisUser == $posted_by) || $thisUser == 'akash_dutta'){
						$close_button_to_show = "<div class='question_post_series_deletebutton questionExtraMenu' id='questionCloseButton$id'>
													<div uk-icon='close' uk-toggle='target: #close_button$id'></div>
												</div>
												";
						
						$close_button_modal_to_show = "<div class='delete_button' id='close_button$id' uk-modal>
														<div class='uk-modal-dialog uk-modal-body'>
															<h3>This Question will be deleted permanently.</h3>
															<hr>
															<h5>Do you want to delete this following question?</h5>
															<h5>Q:-'.$question_body_unedited.'</h5>
															<div class='uk-modal-footer uk-text-right'>
																<p class='uk-text-right'>
																	<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
																	<button class='uk-button uk-button-primary' type='button' id='delete_question$id'>Yes, Delete</button>
																</p>
															</div>
														</div>
													</div>
													";
					}
					else{
						$close_button_to_show = "";
						$close_button_modal_to_show = "";
					}

						//Timeframe
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

						$time_message = "Posted ". $time_message;
						$str .= "<div class='question_post_series'>

									<div class='question_post_series_question_and_deletebutton'>
										<div class='question_post_series_question'>
											<a href='search.php?q=$question_body_unedited&qid=$id'>$question_body</a>
										</div>
										$close_button_to_show
										
									</div>
								   
									$close_button_modal_to_show
									$question_link_line			
												
									<div class='question_post_series_info'>
										<div class='question_post_series_time'>
											$time_message
										</div>
										<div class='question_post_series_likes' id='question_like_shows$thisUser$id'>
										  $num_like_string
										</div>
									</div>	
									<div class='question_post_series_button'>

										<button type='button' class='AddAnswer' style='background-color: #bb3030;color: #fff;'  onClick='javascript:toggle$id()'>
											Add a answer 
										</button>

										$like_button_to_show
										$answer_later_button_to_show
										
										<div uk-icon='menu' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>
					
											
											<div uk-drop='mode: click'>
												<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
													<ul class='questionUlDesign'>
														<a href='#'><li type='button' uk-toggle='target: #question_add_topic$id'>Add topics</li></a>
														<hr>
														<a href='#'><li type='button' id='hide_question$id'>Hide similar question</li></a>
														<hr>
														<a href='#' <li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #report_question$id'>Report</li></a>
													</ul>
												</div>
											</div>
											
											<div id='question_add_topic$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Add some topic</h2>
													
													<h5>Q:-".$question_body_unedited."</h5>
													
													<div class='questionAddedTopicsInModal$id$userLoggedIn'>
														".$topics_to_show."
													</div>
													
													<div class='load_topic_form'>
														<form action='ajax_load_topic_box.php' method='POST' class='load_topic_form_form'>	
															<input type='text' name='topix_text' placeholder='Search a Topic to add.. (Ex:- Science, Technology, etc) ' onkeyup='getLiveTopic(this.value, ".'"'.$userLoggedIn.'"'.",".'"add"'.", ".'"'.$id.'"'.")'
																   autocomplete='off' id='id_topix_text' style='width:100%; height: 30px;background-color: #e9eef3;' class='load_topic_form_form_input'>
														</form>



														<div class='search_topic_results'>
														</div>
													</div>
													
													<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>DONE</button>
														</p>
													</div>
												</div>
											</div>
											
											<div class='uk-alert-warning hide_question$id' uk-alert style='display:none'>
												<a class='uk-alert-close' uk-close></a>
												<p style='margin-left:10px;font-size:17px'>You will stop seeing question related to<br>Q:-".$question_body_unedited."</p>
											</div>
											
											<div class='changeQuestion' id='change_question$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Change Question</h2>
													<h4>From:-</h4>
													<h5>Q:-".$question_body_unedited."</h5>
													<h4>To:-</h4>
													
													<form action='question.php' method='post'>
														<input type='text' name='change_question_question' value='$question_body_unedited'>
														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
															<input class='uk-button uk-button-primary' type='submit' name='question_change_button' id='change_question'>
														</p>
													</div>
													</form>
													
													
												</div>
											</div>
											
											<div class='report_question' id='report_question$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Report this Question</h2>
													<h5>Q:-".$question_body_unedited."</h5>
													
													
													<form action='question.php' method='post'>
													 	<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;' checked>Inappropriate Question</label>
														
            										 	<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Spelling Mistake or Grammatical Error</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Questoin Typed In Language other than English</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Abusive Words</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Sexual Intimation</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Marketing Stuff From Unauthorized User</label>

														
														
														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
															<p class='uk-text-right'>
																<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
																<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
															</p>
														</div>
													</form>
													
													
												</div>
											</div>
										

									</div> 
								</div>

								<div class='question_post_answer' id='toggleAnswer$id' style='display:none;'>
									<iframe src='answer_frame.php?question_id=$id' id='question_answer_iframe' frameborder='0'></iframe>
								</div>

								<hr>";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more question to load</h5>";
				
			}
			if(mysqli_num_rows($data_query) == $load_count){
				$str = "<h5 style='text-align: centre;'>You havn't liked any Question yet.</h5>";
			}
            //$str = $str .'<h5>No more question to load</h5>';
			echo $str;

        
    }
	
	public function getMainNewsfeed($data,$limit){
		
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
		
		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM answer 
												WHERE views IN (SELECT MAX(views) FROM answer GROUP BY question_id) AND given_by <> '$userLoggedIn'
												ORDER BY date_added 
												DESC 
												");
		//SELECT * FROM answer WHERE answer.date_added > DATE_SUB(CURDATE(), INTERVAL 90 DAY)");

        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
		  </script>
         <?php

			if(mysqli_num_rows($data_query) > 0) {
				
				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;
				
				while($row = mysqli_fetch_array($data_query)) {
					$answer_id = $row['id'];
					$question_id = $row['question_id'];

					$data_query_question = mysqli_query($this->con, "SELECT * FROM question WHERE id='$question_id'");
					$row_question = mysqli_fetch_array($data_query_question);

					$id = $row_question['id'];
					$question_body = $row_question['question_body'];
					$question_body_unedited = $question_body;
					$question_link = $row_question['question_link'];
					$posted_by = $row_question['posted_by'];
					$date_time = $row_question['date_added'];
					$question_topic = $row_question['topic'];

					$answer_body = $row['answer_body'];
					$answer_date_time = $row['date_added'];
					$answer_given_by = $row['given_by'];
					$answer_upvote = $row['upvote'];
					$answer_anonymous = $row['anonymous'];
					$thisUser = $this->user_from;
                    
                    $data_query_user = mysqli_query($this->con, "SELECT * FROM users WHERE username='$answer_given_by'");
					$row_user = mysqli_fetch_array($data_query_user);
                    
                    $user_first_name = $row_user['first_name'];
                    $user_last_name = $row_user['last_name'];
                    $user_profile_pic = $row_user['profile_pic'];
                    $user_follower = $row_user['follower'];
 
                    $data_query_user_details = mysqli_query($this->con, "SELECT description FROM user_details WHERE username='$answer_given_by'");
					$row_user_details = mysqli_fetch_array($data_query_user_details);
                    
                    $user_description = $row_user_details['description'];

					$question_body_unedited = str_replace("'","",$question_body_unedited);
					$question_body_unedited = str_replace('"',"",$question_body_unedited);
					
					$topics_to_show ="";

					?>
						<script> 
							
							
							$(document).ready(function() {
									$('#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userUpvoted='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_answer_upvote.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var result = $.parseJSON(msg);
											var upvotes = result[0] + ' Upvotes';
											$("#question_post_series_number_upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text(upvotes);
											if(result[1]==0){
											   $("#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Upvote");
											   }
											else{
												$("#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Upvoted");
												toast("Upvoted");
												
											}
										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});

									$('#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userDownvoted='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_answer_downvote.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var result = $.parseJSON(msg);
											var downvotes = result[0] + ' Downvotes';
											$("#question_post_series_number_downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text(downvotes);
											if(result[1]==0){
											   $("#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Downvote");
											   }
											else{
												$("#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Downvoted");
											}
										},
										error: function() {
											alert('Submission Failure');
										}
									  });

									});

									$('#show_more<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

										  var thisUser = '<?php echo $thisUser; ?>';
										  var data = 'userLogin='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;

										  $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_answer_show.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												$("#hide_answer<?php echo $answer_id; ?>").hide();
												$("#show_answer<?php echo $answer_id; ?>").show();

											},
											error: function() {
												alert('Submission Failure');
											}

										  });
									});
									
									$('#add_bookmark<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

										  var thisUser = '<?php echo $thisUser; ?>';
										  var data = 'userLoggedIn='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;

										  $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_answer_bookmark.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												if(msg=='1'){
													toast("Bookmark Removed");
													$("#add_bookmark<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Add Bookmark");
												}
												else{
													toast("Bookmark Added");
													$("#add_bookmark<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Remove Bookmark");
												}
											},
											error: function() {
												alert('Bookmark Failure');
											}

										  });
									});
							});

						</script>

					<?php
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
					
					$question_topics = explode(",", $question_topic);
					foreach($question_topics as $i){
						if($i!=''){
							$topics_to_show .= "<div class='topicsAlreadyAddedInQuestion' id='topicsAlreadyAddedInQuestionId$id$i$userLoggedIn'>
													<button class='topicsAlreadyAddedInQuestionButton' id='topicsAlreadyAddedInQuestionButton$id$i$userLoggedIn'>x</button>
													".$i."													
												</div>";
							?>
								<script>
									 $(document).on('click', '#topicsAlreadyAddedInQuestionButton<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>', function() { 

										var userLoggedIn = '<?php echo $userLoggedIn; ?>';
										var topicDeleted = '<?php echo $i; ?>';
										var data = 'userDeleted='+ userLoggedIn + '&topicDeleted=' + topicDeleted + '&qid=' + <?php echo $id; ?>;

										 $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_topic_deleted_to_question.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												console.log(msg);
												$('#topicsAlreadyAddedInQuestionId<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>').hide();

											},
											error: function() {
												alert('Deleting Failure');
											}
										 });

									   });
									
								</script>
							<?php
						}
						
					}
                    
                    
					//Display number of views
					$check_num_views = mysqli_query($this->con, "SELECT * FROM answer WHERE id='$answer_id'");
					$num_views_row = mysqli_fetch_array($check_num_views);
					$num_views = $num_views_row['views'];
					$num_views =  $num_views . " Views";

					//Display numver of upvote
					$check_num_upvote = mysqli_query($this->con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id'");
					$num_upvote = mysqli_num_rows($check_num_upvote);
					$num_upvote = $num_upvote . " Upvotes";

					//Display numver of downvote
					$check_num_downvote = mysqli_query($this->con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id'");
					$num_downvote = mysqli_num_rows($check_num_downvote);
					$num_downvote = $num_downvote . " Downvotes";

					//Display numver of Comments
					$check_num_comment = mysqli_query($this->con, "SELECT * FROM comments WHERE answer_id='$answer_id'");
					$num_comment = mysqli_num_rows($check_num_comment);
					$num_comment = $num_comment . " Comments";
					
					
                    //Display link line
                    if($question_link != ""){
                        $question_link_line = "<div class='question_post_series_link'>
                                                    <a href='$question_link' target='_blank'>
                                                        <span uk-icon='link'></span>
                                                        $question_link
                                                    </a>
                                                </div>";
                    }
                    else{
                        $question_link_line = "";
                    }
					
					//Display who answered the question (user or anonymous)
					
					if($answer_anonymous == 'yes'){
						$user_profile_to_show = "<div class='question_post_series_link_a_tag'>
													<div class='question_post_series_user_added'>

														<div class='question_post_series_user_added_profile_pic'>
															<img src='assets/images/anonymous.jpg'>
														</div>
														<div class='question_post_series_user_added_details'>
															<div class='question_post_series_user_added_details_label'>
																Answered By
															</div>
															<div class='question_post_series_user_added_details_name'>
																Anonymous, <span id='username'>Hidden User</span>
															</div>
															<div class='question_post_series_user_added_details_description'>
																Details have been hidden according to <a href='policy.php' class='hidden_answer_policy'>privacy policy</a> but you can report this answer.
															</div>
														</div>				
														<div class='question_post_series_user_added_follow_button'>
														</div>

													</div>
												</div>";
					}
					else{
						$user_profile_to_show = "<div class='question_post_series_link_a_tag'>
													<a href='profile.php?profile_username=$answer_given_by'>
														<div class='question_post_series_user_added'>

															<div class='question_post_series_user_added_profile_pic'>
																<img src='$user_profile_pic'>
															</div>
															<div class='question_post_series_user_added_details'>
																<div class='question_post_series_user_added_details_label'>
																	Answered By
																</div>
																<div class='question_post_series_user_added_details_name'>
																	$user_first_name $user_last_name, <span id='username'>(@$answer_given_by)</span>
																	
																</div>
																<div class='question_post_series_user_added_details_description'>
																	$user_description
																</div>
															</div>				
															<div class='question_post_series_user_added_follow_button'>
															</div>

														</div>
													</a>
												</div>";
					}

					
					//Display bookmark button
					$bookmark_check_query = mysqli_query($this->con,"SELECT * FROM bookmarks WHERE answer_id='$answer_id' AND username='$userLoggedIn'");
					
					if(mysqli_num_rows($bookmark_check_query) == 0){
						$bookmark_button_text = "Add Bookmark";
					}
					else{
						$bookmark_button_text = "Remove Bookmark";
					}
					
					//Display the like button
					$check_num_likes_button = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id' 
																		AND question_liked_by='$this->user_from'");
					if(mysqli_num_rows($check_num_likes_button)>0){
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_unlike$thisUser$id'>Unlike this question </button>";
					}
					else{
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_like$thisUser$id'>Like this question </button>";
					}


					$check_answer_later_button = mysqli_query($this->con, "SELECT * FROM answer_later WHERE question_id='$id' 
																		AND user_added='$this->user_from'");
					if(mysqli_num_rows($check_answer_later_button)>0){
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' style='cursor:not-allowed;' > Qustion Added </button>";
					}
					else{
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id' data-toast='Question Added'> Answer This Later </button>";
					}

					//Check Upvoted or not
					$check_already_upvoted = mysqli_query($this->con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id' AND answer_upvoted_by='$thisUser'");
					$check_already_upvoted_num = mysqli_num_rows($check_already_upvoted);

					if($check_already_upvoted_num>0){
						$upvoted_or_not = "Upvoted";
					}
					else {
						$upvoted_or_not = "Upvote";
					}

					//Check downvoted or not
					$check_already_downvoted = mysqli_query($this->con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id' AND answer_downvoted_by='$thisUser'");
					$check_already_downvoted_num = mysqli_num_rows($check_already_downvoted);

					if($check_already_upvoted_num>0){
						$downvoted_or_not = "Downvoted";
					}
					else {
						$downvoted_or_not = "Downvote";
					}

					while(strpos($answer_body,'style="')!=NULL){

						$var1='style="';
						$var2='"';
						$pool=$answer_body;
							$temp1 = strpos($pool,$var1)+strlen($var1);
							$result = substr($pool,$temp1,strlen($pool));
							$dd=strpos($result,$var2);
							if($dd == 0){
								$dd = strlen($result);
							}
						$font_tag_to_remove = substr($result,0,$dd);
						$answer_body = str_replace($font_tag_to_remove,"",$answer_body);
						$answer_body = str_replace('style=""',"",$answer_body);
					}

						$var1='<';
						$var2='>';
						$pool=$answer_body;
							$temp1 = strpos($pool,$var1)+strlen($var1);
							$result = substr($pool,$temp1,strlen($pool));
							$dd=strpos($result,$var2);
							if($dd == 0){
								$dd = strlen($result);
							}
						$font_tag_to_remove = substr($result,0,$dd);
						$answer_body = str_replace($font_tag_to_remove,"",$answer_body);
						$answer_body = str_replace('<>',"",$answer_body);

					//$answer_first_letter = substr($answer_body,0,1);
					//$answer_first_letter = "<div style='font-family: Georgia,serif;font-size: 33px;line-height: 23px;font-weight:bolder;float:left;'>".
								  // "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$answer_first_letter."</div>";

					//$answer_body = substr($answer_body,1);
					//$answer_body = "<" . $font_tag_to_remove . ">" . $answer_first_letter . $answer_body;

					$answer_body_hidden = "<div class='newsfeed_edited_text_hidden' style='font-family: Georgia,serif;font-size: 17px;line-height: 30px; height:43px;'>".$answer_body."</div>";
					$answer_body = "<div class='newsfeed_edited_text' style='font-family: Georgia,serif;font-size: 17px;line-height: 30px;'>".
								   $answer_body."</div>";



					//Timeframe
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
							$days = " ago";
						}
						else if($interval->d == 1) {
							$days = $interval->d . " day ago";
						}
						else {
							$days = $interval->d . " days ago";
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

					$question_body = 'Q:-&nbsp;&nbsp;' . $question_body;
					$time_message = "Posted ". $time_message;
					$str .= "
								<div class='question_post_series'>

									<div class='question_post_series_question_and_deletebutton newsfeed_question'>
										<div class='question_post_series_question'>
											<a href='search.php?q=$question_body_unedited&qid=$id'>$question_body</a>
										</div>

										<div class='question_post_series_deletebutton questionExtraMenu'>

											<div uk-icon='menu' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>

											<div uk-drop='mode: click'>
												<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
													<ul class='questionUlDesign'>
														<a href='#'><li type='button' uk-toggle='target: #question_add_topic$id'>Add topics</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #report_question$id'>Report</li></a>
													</ul>
												</div>
											</div>

										</div>


										<div id='question_add_topic$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Add some topic</h2>

												<h5>Q:-".$question_body_unedited."</h5>

												<div class='questionAddedTopicsInModal$id$userLoggedIn'>
													".$topics_to_show."
												</div>

												<div class='load_topic_form'>
													<form action='ajax_load_topic_box.php' method='POST' class='load_topic_form_form'>	
														<input type='text' name='topix_text' placeholder='Search a Topic to add.. (Ex:- Science, Technology, etc) ' onkeyup='getLiveTopic(this.value, ".'"'.$userLoggedIn.'"'.",".'"add"'.", ".'"'.$id.'"'.")'
															   autocomplete='off' id='id_topix_text' style='width:100%; height: 30px;background-color: #e9eef3;' class='load_topic_form_form_input'>
													</form>



													<div class='search_topic_results'>
													</div>
												</div>

												<div class='uk-modal-footer uk-text-right'>
													<p class='uk-text-right'>
														<button class='uk-button uk-button-default uk-modal-close' type='button'>DONE</button>
													</p>
												</div>
											</div>
										</div>

										<div class='uk-alert-warning hide_question$id' uk-alert style='display:none'>
											<a class='uk-alert-close' uk-close></a>
											<p style='margin-left:10px;font-size:17px'>You will stop seeing question related to<br>Q:-".$question_body_unedited."</p>
										</div>

										<div class='changeQuestion' id='change_question$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Change Question</h2>
												<h4>From:-</h4>
												<h5>Q:-".$question_body_unedited."</h5>
												<h4>To:-</h4>

												<form action='question.php' method='post'>
													<input type='text' name='change_question_question' value='$question_body_unedited'>
													<input type='hidden' name='change_question_id' value='$id'>
													<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
													<div class='uk-modal-footer uk-text-right'>
													<p class='uk-text-right'>
														<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
														<input class='uk-button uk-button-primary' type='submit' name='question_change_button' id='change_question'>
													</p>
												</div>
												</form>


											</div>
										</div>

										<div class='report_question' id='report_question$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Report this Question</h2>
												<h5>Q:-".$question_body_unedited."</h5>


												<form class='reportquestion' method='post'>
													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;' checked>Inappropriate Question</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Spelling Mistake or Grammatical Error</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Questoin Typed In Language other than English</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Abusive Words</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Sexual Intimation</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Marketing Stuff From Unauthorized User</label>



													<input type='hidden' name='change_question_id' value='$id'>
													<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
													<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
															<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
														</p>
													</div>
												</form>


											</div>
										</div>

									</div>

									$question_link_line

									$user_profile_to_show

									<div id='hide_answer$answer_id'>
										<div class='question_post_series_answer_hidden'>
											$answer_body_hidden
										</div>
										<div class='question_post_series_answer_hidden_button'>
											<button type='button' class='ShowMore' id='show_more$thisUser$answer_id'>
												Show more...
											</button>
										</div>
									</div>


									<div id='show_answer$answer_id'  style='display:none;'>
										<div class='question_post_series_answer'>
											$answer_body
										</div>

										<div class='question_post_series_number'>
											<div class='question_post_series_number_views' id='question_post_series_number_views$thisUser$answer_id'>
												$num_views
											</div>
											<div class='question_post_series_number_upvote' id='question_post_series_number_upvote$thisUser$answer_id'>
												$num_upvote
											</div>
											<div class='question_post_series_number_downvote' id='question_post_series_number_downvote$thisUser$answer_id'>
												$num_downvote
											</div>
											<div class='question_post_series_number_comment' id='question_post_series_number_comment'>
												$num_comment
											</div>
										</div>

										<div class='question_post_series_button'>
											 <button type='button' class='UpvoteAnswer' style='background-color: #bb3030;color:#fff;height:30px;width:90px;margin-right: 8px;' id='upvote$thisUser$answer_id'>
												$upvoted_or_not
											</button>
											<button type='button' class='DownvoteAnswer' style='background-color: #b7b7b7;
											color: #827c7c;width: 102px; margin-right: 8px;height:30px;' id='downvote$thisUser$answer_id'>
												$downvoted_or_not
											</button>

											<div uk-icon='more' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>

											<div uk-drop='mode: click'>
												<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
													<ul class='questionUlDesign'>
														<a href='#'><li type='button' id='add_bookmark$thisUser$answer_id'>$bookmark_button_text</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #report_answer$id'>Report this Answer</li></a>
													</ul>
												</div>
											</div>
										</div>

										<div class='report_answer' id='report_answer$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Report this Answer</h2>

												<form class='reportquestion' method='post'>
													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;' checked>Inappropriate Answer not relevant to Question</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Spelling Mistake or Grammatical Error</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Answer Typed In Language other than English</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Sarcastic answer with no Sincererity</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Abusive Words</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Sexual Intimation</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Marketing Stuff From Unauthorized User</label>



													<input type='hidden' name='change_question_id' value='$id'>
													<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
													<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
															<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
														</p>
													</div>
												</form>


											</div>
										</div>

										<div class='question_post_series_comment'>
											<iframe src='comment_frame.php?answer_id=$answer_id' id='answer_comment_iframe' frameborder='0'></iframe>
										</div>

									</div>	
								</div>


							<hr>

							";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more question to load</h5>";
				
				
			}
            //$str = $str .'<h5>No more question to load</h5>';
			echo $str;
	}
	
	public function getTopicFeed($data,$limit,$topic){
		
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
		
		$str = ""; //String to return 
		/*$data_query = mysqli_query($this->con, "SELECT * FROM answer 
												WHERE views IN (SELECT MAX(views) FROM answer GROUP BY question_id) AND given_by <> '$userLoggedIn'		
												ORDER BY date_added
												LIKE '$topic'
												DESC 
												");*/
        $topic = ','.$topic.',';
        $data_query = mysqli_query($this->con, "SELECT * FROM question 
                                                WHERE topic LIKE '%$topic%' AND answered = 'yes'
												ORDER BY date_added
												 
												");
		//$topic_query = mysqli_query($this->con,"SELECT * FROM topic WHERE name='$topic'");
		//$topic_row = mysqli_fetch_array($topic_query);
		//SELECT * FROM answer WHERE answer.date_added > DATE_SUB(CURDATE(), INTERVAL 90 DAY)");

        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
		  </script>
         <?php

			if(mysqli_num_rows($data_query) > 0) {
				
				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;
				
				while($row = mysqli_fetch_array($data_query)) {
					
					//$answer_id = $row['id'];
					$question_id = $row['id'];

					$data_query_answer = mysqli_query($this->con, "SELECT * FROM answer WHERE question_id='$question_id'");
					$row_answer = mysqli_fetch_array($data_query_answer);

					$id = $row['id'];
					$question_body = $row['question_body'];
					$question_body_unedited = $question_body;
					$question_link = $row['question_link'];
					$posted_by = $row['posted_by'];
					$date_time = $row['date_added'];
					$question_topic = $row['topic'];
                    
                    $answer_id = $row_answer['id'];
					$answer_body = $row_answer['answer_body'];
					$answer_date_time = $row_answer['date_added'];
					$answer_given_by = $row_answer['given_by'];
					$answer_upvote = $row_answer['upvote'];
					$thisUser = $this->user_from;

					$question_body_unedited = str_replace("'","",$question_body_unedited);
					$question_body_unedited = str_replace('"',"",$question_body_unedited);
					
					//echo $question_topic."<br>".$topic."<br>".$id."<br><br><br>";
					if(strstr($question_topic,$topic)){
						

						?>
							<script> 


								$(document).ready(function() {
										$('#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

										  //var question_id = $('#question_id_name').attr("value");
										  //var user = $('#userLoggedin').attr("value");
										  var thisUser = '<?php echo $thisUser; ?>';

										  var data = 'userUpvoted='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
										  //console.log(data);
										  $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_answer_upvote.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												var result = $.parseJSON(msg);
												var upvotes = result[0] + ' Upvotes';
												$("#question_post_series_number_upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text(upvotes);
												if(result[1]==0){
												   $("#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Upvote");
												   }
												else{
													$("#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Upvoted");
													toast("Upvoted");

												}
											},
											error: function() {
												alert('Answer submission Failure');
											}
										  });

										});

										$('#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

										  //var question_id = $('#question_id_name').attr("value");
										  //var user = $('#userLoggedin').attr("value");
										  var thisUser = '<?php echo $thisUser; ?>';

										  var data = 'userDownvoted='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
										  //console.log(data);
										  $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_answer_downvote.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												var result = $.parseJSON(msg);
												var downvotes = result[0] + ' Downvotes';
												$("#question_post_series_number_downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text(downvotes);
												if(result[1]==0){
												   $("#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Downvote");
												   }
												else{
													$("#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Downvoted");
												}
											},
											error: function() {
												alert('Submission Failure');
											}
										  });

										});

										$('#show_more<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

										  //var question_id = $('#question_id_name').attr("value");
										  //var user = $('#userLoggedin').attr("value");
										  var thisUser = '<?php echo $thisUser; ?>';
										  var data = 'userLogin='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
										  //console.log(data);
										  $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_answer_show.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
													$("#hide_answer<?php echo $answer_id; ?>").hide();
														$("#show_answer<?php echo $answer_id; ?>").show();

													console.log(msg);

											},
											error: function() {
												alert('Submission Failure');
											}

										  });
										});
								});

							</script>

						<?php

						if($num_iterations++ < $start)
							continue; 

						//Once 10 posts have been loaded, break
						if($count > $limit) {
							break;
						}
						else {
							$count++;
						}

						//Display number of views
						$check_num_views = mysqli_query($this->con, "SELECT * FROM answer WHERE id='$answer_id'");
						$num_views_row = mysqli_fetch_array($check_num_views);
						$num_views = $num_views_row['views'];
						$num_views =  $num_views . " Views";

						//Display numver of upvote
						$check_num_upvote = mysqli_query($this->con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id'");
						$num_upvote = mysqli_num_rows($check_num_upvote);
						$num_upvote = $num_upvote . " Upvotes";

						//Display numver of downvote
						$check_num_downvote = mysqli_query($this->con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id'");
						$num_downvote = mysqli_num_rows($check_num_downvote);
						$num_downvote = $num_downvote . " Downvotes";

						//Display numver of Comments
						$check_num_comment = mysqli_query($this->con, "SELECT * FROM comments WHERE answer_id='$answer_id'");
						$num_comment = mysqli_num_rows($check_num_comment);
						$num_comment = $num_comment . " Comments";

						//Display the like button
						$check_num_likes_button = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id' 
																			AND question_liked_by='$this->user_from'");
						if(mysqli_num_rows($check_num_likes_button)>0){
							$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_unlike$thisUser$id'>Unlike this question </button>";
						}
						else{
							$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_like$thisUser$id'>Like this question </button>";
						}


						$check_answer_later_button = mysqli_query($this->con, "SELECT * FROM answer_later WHERE question_id='$id' 
																			AND user_added='$this->user_from'");
						if(mysqli_num_rows($check_answer_later_button)>0){
							$answer_later_button_to_show = "<button type='button' class='AnswerLater' style='cursor:not-allowed;' > Qustion Added </button>";
						}
						else{
							$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id' data-toast='Question Added'> Answer This Later </button>";
						}

						//Check Upvoted or not
						$check_already_upvoted = mysqli_query($this->con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id' AND answer_upvoted_by='$thisUser'");
						$check_already_upvoted_num = mysqli_num_rows($check_already_upvoted);

						if($check_already_upvoted_num>0){
							$upvoted_or_not = "Upvoted";
						}
						else {
							$upvoted_or_not = "Upvote";
						}

						//Check downvoted or not
						$check_already_downvoted = mysqli_query($this->con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id' AND answer_downvoted_by='$thisUser'");
						$check_already_downvoted_num = mysqli_num_rows($check_already_downvoted);

						if($check_already_upvoted_num>0){
							$downvoted_or_not = "Downvoted";
						}
						else {
							$downvoted_or_not = "Downvote";
						}

						while(strpos($answer_body,'style="')!=NULL){

							$var1='style="';
							$var2='"';
							$pool=$answer_body;
								$temp1 = strpos($pool,$var1)+strlen($var1);
								$result = substr($pool,$temp1,strlen($pool));
								$dd=strpos($result,$var2);
								if($dd == 0){
									$dd = strlen($result);
								}
							$font_tag_to_remove = substr($result,0,$dd);
							$answer_body = str_replace($font_tag_to_remove,"",$answer_body);
							$answer_body = str_replace('style=""',"",$answer_body);
						}

							$var1='<';
							$var2='>';
							$pool=$answer_body;
								$temp1 = strpos($pool,$var1)+strlen($var1);
								$result = substr($pool,$temp1,strlen($pool));
								$dd=strpos($result,$var2);
								if($dd == 0){
									$dd = strlen($result);
								}
							$font_tag_to_remove = substr($result,0,$dd);
							$answer_body = str_replace($font_tag_to_remove,"",$answer_body);
							$answer_body = str_replace('<>',"",$answer_body);

						//$answer_first_letter = substr($answer_body,0,1);
						//$answer_first_letter = "<div style='font-family: Georgia,serif;font-size: 33px;line-height: 23px;font-weight:bolder;float:left;'>".
									  // "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$answer_first_letter."</div>";

						//$answer_body = substr($answer_body,1);
						//$answer_body = "<" . $font_tag_to_remove . ">" . $answer_first_letter . $answer_body;

						$answer_body_hidden = "<div class='newsfeed_edited_text_hidden' style='font-family: Georgia,serif;font-size: 17px;line-height: 30px; height:43px;'>".$answer_body."</div>";
						$answer_body = "<div class='newsfeed_edited_text' style='font-family: Georgia,serif;font-size: 17px;line-height: 30px;'>".
									   $answer_body."</div>";



							//Timeframe
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
									$days = " ago";
								}
								else if($interval->d == 1) {
									$days = $interval->d . " day ago";
								}
								else {
									$days = $interval->d . " days ago";
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

							$question_body = 'Q:-&nbsp;&nbsp;' . $question_body;
							$time_message = "Posted ". $time_message;
							$str .= "
										<div class='question_post_series'>

											<div class='question_post_series_question newsfeed_question'>
												<a href='search.php?q=$question_body_unedited&qid=$id'>$question_body</a>
											</div>

											<div id='hide_answer$answer_id'>
												<div class='question_post_series_answer_hidden'>
													$answer_body_hidden
												</div>
												<div class='question_post_series_answer_hidden_button'>
													<button type='button' class='ShowMore' id='show_more$thisUser$answer_id'>
														Show more...
													</button>
												</div>
											</div>


											<div id='show_answer$answer_id'  style='display:none;'>
												<div class='question_post_series_answer'>
													$answer_body
												</div>

												<div class='question_post_series_number'>
													<div class='question_post_series_number_views' id='question_post_series_number_views$thisUser$answer_id'>
														$num_views
													</div>
													<div class='question_post_series_number_upvote' id='question_post_series_number_upvote$thisUser$answer_id'>
														$num_upvote
													</div>
													<div class='question_post_series_number_downvote' id='question_post_series_number_downvote$thisUser$answer_id'>
														$num_downvote
													</div>
													<div class='question_post_series_number_comment' id='question_post_series_number_comment'>
														$num_comment
													</div>
												</div>

												<div class='question_post_series_button'>
													 <button type='button' class='UpvoteAnswer' style='background-color: #bb3030;color:#fff;height:30px;width:90px;margin-right: 8px;' id='upvote$thisUser$answer_id'>
														$upvoted_or_not
													</button>
													<button type='button' class='DownvoteAnswer' style='background-color: #b7b7b7;
													color: #827c7c;width: 102px; margin-right: 8px;height:30px;' id='downvote$thisUser$answer_id'>
														$downvoted_or_not
													</button>
												</div>
												<div class='question_post_series_comment'>
													<iframe src='comment_frame.php?answer_id=$answer_id' id='answer_comment_iframe' frameborder='0'></iframe>
												</div>

											</div>	
										</div>


									<hr>

									";


					}
				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more question to load</h5>";
				
			
			}
            //$str = $str .'<h5>No more question to load</h5>';
            if($str == "" && $page == 0){
                echo "There is no question to show";
            }
            else{
				echo $str;
			}
	}
	
	public function getProfileAnswer($data,$limit){
		
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
		
		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM answer 
												WHERE given_by = '$userLoggedIn' AND anonymous <> 'yes'
												ORDER BY date_added 
												DESC 
												");
		//SELECT * FROM answer WHERE answer.date_added > DATE_SUB(CURDATE(), INTERVAL 90 DAY)");

        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
		  </script>
         <?php

			if(mysqli_num_rows($data_query) > 0) {
				
				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;
				
				while($row = mysqli_fetch_array($data_query)) {
					$answer_id = $row['id'];
					$question_id = $row['question_id'];

					$data_query_question = mysqli_query($this->con, "SELECT * FROM question WHERE id='$question_id'");
					$row_question = mysqli_fetch_array($data_query_question);

					$id = $row_question['id'];
					$question_body = $row_question['question_body'];
					$question_body_unedited = $question_body;
					$question_link = $row_question['question_link'];
					$posted_by = $row_question['posted_by'];
					$date_time = $row_question['date_added'];
					$question_topic = $row_question['topic'];

					$answer_body = $row['answer_body'];
					$answer_date_time = $row['date_added'];
					$answer_given_by = $row['given_by'];
					$answer_upvote = $row['upvote'];
					$thisUser = $this->user_from;
                    
                    $data_query_user = mysqli_query($this->con, "SELECT * FROM users WHERE username='$answer_given_by'");
					$row_user = mysqli_fetch_array($data_query_user);
                    
                    $user_first_name = $row_user['first_name'];
                    $user_last_name = $row_user['last_name'];
                    $user_profile_pic = $row_user['profile_pic'];
                    $user_follower = $row_user['follower'];
 
                    $data_query_user_details = mysqli_query($this->con, "SELECT description FROM user_details WHERE username='$answer_given_by'");
					$row_user_details = mysqli_fetch_array($data_query_user_details);
                    
                    $user_description = $row_user_details['description'];

					$question_body_unedited = str_replace("'","",$question_body_unedited);
					$question_body_unedited = str_replace('"',"",$question_body_unedited);
					
					$topics_to_show ="";
					$seperator = "spz";

					?>
						<script> 
							function toggle<?php echo $id; ?><?php echo $seperator; ?><?php echo $answer_id; ?>() {

								var target = $(event.target);
									if (!target.is("a")) {
										var element = document.getElementById("toggleAnswer<?php echo $id; ?><?php echo $answer_id; ?>");
										var showAnswer = document.getElementById("show_answer<?php echo $answer_id; ?>");

										if(element.style.display == "block") {									
											element.style.display = "none";
											showAnswer.style.display = "block";
										}
										else {
											element.style.display = "block";
											showAnswer.style.display = "none";
										}
								}
							}

							$(document).ready(function() {
							
									
								$('#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

								  //var question_id = $('#question_id_name').attr("value");
								  //var user = $('#userLoggedin').attr("value");
								  var thisUser = '<?php echo $thisUser; ?>';

								  var data = 'userUpvoted='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
								  //console.log(data);
								  $.ajax({
									type:"POST",
									cache:false,
									url:"ajaxfile/ajax_answer_upvote.php",
									data:data,    // multiple data sent using ajax
									success: function (msg) {
										var result = $.parseJSON(msg);
										var upvotes = result[0] + ' Upvotes';
										$("#question_post_series_number_upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text(upvotes);
										if(result[1]==0){
										   $("#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Upvote");
										   }
										else{
											$("#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Upvoted");
											toast("Upvoted");

										}
									},
									error: function() {
										alert('Answer submission Failure');
									}
								  });

								});

								$('#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

								  //var question_id = $('#question_id_name').attr("value");
								  //var user = $('#userLoggedin').attr("value");
								  var thisUser = '<?php echo $thisUser; ?>';

								  var data = 'userDownvoted='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
								  //console.log(data);
								  $.ajax({
									type:"POST",
									cache:false,
									url:"ajaxfile/ajax_answer_downvote.php",
									data:data,    // multiple data sent using ajax
									success: function (msg) {
										var result = $.parseJSON(msg);
										var downvotes = result[0] + ' Downvotes';
										$("#question_post_series_number_downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text(downvotes);
										if(result[1]==0){
										   $("#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Downvote");
										   }
										else{
											$("#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Downvoted");
										}
									},
									error: function() {
										alert('Submission Failure');
									}
								  });

								});

								$('#show_more<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

									  var thisUser = '<?php echo $thisUser; ?>';
									  var data = 'userLogin='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
									 
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_answer_show.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											$("#hide_answer<?php echo $answer_id; ?>").hide();
											$("#show_answer<?php echo $answer_id; ?>").show();
										},
										error: function() {
											alert('Submission Failure');
										}

									  });
								});
								
								$('#bookmark<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

									  var thisUser = '<?php echo $thisUser; ?>';
									  var data = 'userLogin='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
					
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_answer_bookmark.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											
										},
										error: function() {
											alert('Submission Failure');
										}

									  });
									});
							});

						</script>

					<?php
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
                    //Display link line
                    if($question_link != ""){
                        $question_link_line = "<div class='question_post_series_link'>
                                                    <a href='$question_link' target='_blank'>
                                                        <span uk-icon='link'></span>
                                                        $question_link
                                                    </a>
                                                </div>";
                    }
                    else{
                        $question_link_line = "";
                    }
					
					$question_topics = explode(",", $question_topic);
					foreach($question_topics as $i){
						if($i!=''){
							$topics_to_show .= "<div class='topicsAlreadyAddedInQuestion' id='topicsAlreadyAddedInQuestionId$id$i$userLoggedIn'>
													<button class='topicsAlreadyAddedInQuestionButton' id='topicsAlreadyAddedInQuestionButton$id$i$userLoggedIn'>x</button>
													".$i."													
												</div>";
							?>
								<script>
									 $(document).on('click', '#topicsAlreadyAddedInQuestionButton<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>', function() { 

										var userLoggedIn = '<?php echo $userLoggedIn; ?>';
										var topicDeleted = '<?php echo $i; ?>';
										var data = 'userDeleted='+ userLoggedIn + '&topicDeleted=' + topicDeleted + '&qid=' + <?php echo $id; ?>;

										 $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_topic_deleted_to_question.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												console.log(msg);
												$('#topicsAlreadyAddedInQuestionId<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>').hide();

											},
											error: function() {
												alert('Deleting Failure');
											}
										 });

									   });
									
								</script>
							<?php
						}
						
					}
                    
                    
					//Display number of views
					$check_num_views = mysqli_query($this->con, "SELECT * FROM answer WHERE id='$answer_id'");
					$num_views_row = mysqli_fetch_array($check_num_views);
					$num_views = $num_views_row['views'];
					$num_views =  $num_views . " Views";

					//Display numver of upvote
					$check_num_upvote = mysqli_query($this->con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id'");
					$num_upvote = mysqli_num_rows($check_num_upvote);
					$num_upvote = $num_upvote . " Upvotes";

					//Display numver of downvote
					$check_num_downvote = mysqli_query($this->con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id'");
					$num_downvote = mysqli_num_rows($check_num_downvote);
					$num_downvote = $num_downvote . " Downvotes";

					//Display numver of Comments
					$check_num_comment = mysqli_query($this->con, "SELECT * FROM comments WHERE answer_id='$answer_id'");
					$num_comment = mysqli_num_rows($check_num_comment);
					$num_comment = $num_comment . " Comments";

					//Display the like button
					$check_num_likes_button = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id' 
																		AND question_liked_by='$this->user_from'");
					if(mysqli_num_rows($check_num_likes_button)>0){
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_unlike$thisUser$id'>Unlike this question </button>";
					}
					else{
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_like$thisUser$id'>Like this question </button>";
					}
					
					//Display edit answer button
					if($answer_given_by == $userLoggedIn){
						$edit_or_report_answer_button = "<hr>
														   <a href='#'>
															   <li type='button' onClick='javascript:toggle$id$seperator$answer_id()'>Edit this answer</li>
														   </a>";
					}
					else{
						$edit_or_report_answer_button = "<hr>
														   <a href='#'>
															   <li type='button' uk-toggle='target: #report_answer$id'>Report this answer</li>
														   </a>";
					}


					$check_answer_later_button = mysqli_query($this->con, "SELECT * FROM answer_later WHERE question_id='$id' 
																		AND user_added='$this->user_from'");
					if(mysqli_num_rows($check_answer_later_button)>0){
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' style='cursor:not-allowed;' > Qustion Added </button>";
					}
					else{
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id' data-toast='Question Added'> Answer This Later </button>";
					}

					//Check Upvoted or not
					$check_already_upvoted = mysqli_query($this->con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id' AND answer_upvoted_by='$thisUser'");
					$check_already_upvoted_num = mysqli_num_rows($check_already_upvoted);

					if($check_already_upvoted_num>0){
						$upvoted_or_not = "Upvoted";
					}
					else {
						$upvoted_or_not = "Upvote";
					}

					//Check downvoted or not
					$check_already_downvoted = mysqli_query($this->con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id' AND answer_downvoted_by='$thisUser'");
					$check_already_downvoted_num = mysqli_num_rows($check_already_downvoted);

					if($check_already_upvoted_num>0){
						$downvoted_or_not = "Downvoted";
					}
					else {
						$downvoted_or_not = "Downvote";
					}

					while(strpos($answer_body,'style="')!=NULL){

						$var1='style="';
						$var2='"';
						$pool=$answer_body;
							$temp1 = strpos($pool,$var1)+strlen($var1);
							$result = substr($pool,$temp1,strlen($pool));
							$dd=strpos($result,$var2);
							if($dd == 0){
								$dd = strlen($result);
							}
						$font_tag_to_remove = substr($result,0,$dd);
						$answer_body = str_replace($font_tag_to_remove,"",$answer_body);
						$answer_body = str_replace('style=""',"",$answer_body);
					}

						$var1='<';
						$var2='>';
						$pool=$answer_body;
							$temp1 = strpos($pool,$var1)+strlen($var1);
							$result = substr($pool,$temp1,strlen($pool));
							$dd=strpos($result,$var2);
							if($dd == 0){
								$dd = strlen($result);
							}
						$font_tag_to_remove = substr($result,0,$dd);
						$answer_body = str_replace($font_tag_to_remove,"",$answer_body);
						$answer_body = str_replace('<>',"",$answer_body);

					//$answer_first_letter = substr($answer_body,0,1);
					//$answer_first_letter = "<div style='font-family: Georgia,serif;font-size: 33px;line-height: 23px;font-weight:bolder;float:left;'>".
								  // "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$answer_first_letter."</div>";

					//$answer_body = substr($answer_body,1);
					//$answer_body = "<" . $font_tag_to_remove . ">" . $answer_first_letter . $answer_body;

					$answer_body_hidden = "<div class='newsfeed_edited_text_hidden' style='font-family: Georgia,serif;font-size: 17px;line-height: 30px; height:43px;'>".$answer_body."</div>";
					$answer_body = "<div class='newsfeed_edited_text' style='font-family: Georgia,serif;font-size: 17px;line-height: 30px;'>".
								   $answer_body."</div>";



						//Timeframe
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
								$days = " ago";
							}
							else if($interval->d == 1) {
								$days = $interval->d . " day ago";
							}
							else {
								$days = $interval->d . " days ago";
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

						$question_body = 'Q:-&nbsp;&nbsp;' . $question_body;
						$time_message = "Posted ". $time_message;
						$str .= "
									<div class='question_post_series'>

										<div class='question_post_series_question_and_deletebutton newsfeed_question'>
											<div class='question_post_series_question'>
												<a href='search.php?q=$question_body_unedited&qid=$id'>$question_body</a>
											</div>
											
											<div class='question_post_series_deletebutton questionExtraMenu'>
											
												<div uk-icon='menu' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>
												
												<div uk-drop='mode: click'>
													<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
														<ul class='questionUlDesign'>
															<a href='#'><li type='button' uk-toggle='target: #question_add_topic$id'>Add topics</li></a>
															<hr>
															<a href='#'><li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
															<hr>
															<a href='#'><li type='button' uk-toggle='target: #report_question$id'>Report</li></a>
														</ul>
													</div>
												</div>
												
											</div>
											
											
											<div id='question_add_topic$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Add some topic</h2>

													<h5>Q:-".$question_body_unedited."</h5>

													<div class='questionAddedTopicsInModal$id$userLoggedIn'>
														".$topics_to_show."
													</div>

													<div class='load_topic_form'>
														<form action='ajax_load_topic_box.php' method='POST' class='load_topic_form_form'>	
															<input type='text' name='topix_text' placeholder='Search a Topic to add.. (Ex:- Science, Technology, etc) ' onkeyup='getLiveTopic(this.value, ".'"'.$userLoggedIn.'"'.",".'"add"'.", ".'"'.$id.'"'.")'
																   autocomplete='off' id='id_topix_text' style='width:100%; height: 30px;background-color: #e9eef3;' class='load_topic_form_form_input'>
														</form>



														<div class='search_topic_results'>
														</div>
													</div>

													<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>DONE</button>
														</p>
													</div>
												</div>
											</div>

											<div class='uk-alert-warning hide_question$id' uk-alert style='display:none'>
												<a class='uk-alert-close' uk-close></a>
												<p style='margin-left:10px;font-size:17px'>You will stop seeing question related to<br>Q:-".$question_body_unedited."</p>
											</div>

											<div class='changeQuestion' id='change_question$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Change Question</h2>
													<h4>From:-</h4>
													<h5>Q:-".$question_body_unedited."</h5>
													<h4>To:-</h4>

													<form action='question.php' method='post'>
														<input type='text' name='change_question_question' value='$question_body_unedited'>
														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
															<input class='uk-button uk-button-primary' type='submit' name='question_change_button' id='change_question'>
														</p>
													</div>
													</form>


												</div>
											</div>

											<div class='report_question' id='report_question$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Report this Question</h2>
													<h5>Q:-".$question_body_unedited."</h5>


													<form class='reportquestion' method='post'>
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;' checked>Inappropriate Question</label>

														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Spelling Mistake or Grammatical Error</label>

														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Questoin Typed In Language other than English</label>

														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Abusive Words</label>

														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Sexual Intimation</label>

														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Marketing Stuff From Unauthorized User</label>



														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
															<p class='uk-text-right'>
																<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
																<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
															</p>
														</div>
													</form>


												</div>
											</div>
											
										</div>
                                        
                                        $question_link_line
										
										<div class='question_post_series_link_a_tag'>
											<a href='profile.php?profile_username=$answer_given_by'>
												<div class='question_post_series_user_added'>

													<div class='question_post_series_user_added_profile_pic'>
														<img src='$user_profile_pic'>
													</div>
													<div class='question_post_series_user_added_details'>
														<div class='question_post_series_user_added_details_label'>
															Answered By
														</div>
														<div class='question_post_series_user_added_details_name'>
															$user_first_name $user_last_name, <span id='username'>(@$answer_given_by)</span>
														</div>
														<div class='question_post_series_user_added_details_description'>
															$user_description
														</div>
													</div>				
													<div class='question_post_series_user_added_follow_button'>
													</div>

												</div>
											</a>
										</div>

										<div id='show_answer$answer_id'>
											<div class='question_post_series_answer'>
												$answer_body
											</div>

											<div class='question_post_series_number'>
												<div class='question_post_series_number_views' id='question_post_series_number_views$thisUser$answer_id'>
													$num_views
												</div>
												<div class='question_post_series_number_upvote' id='question_post_series_number_upvote$thisUser$answer_id'>
													$num_upvote
												</div>
												<div class='question_post_series_number_downvote' id='question_post_series_number_downvote$thisUser$answer_id'>
													$num_downvote
												</div>
												<div class='question_post_series_number_comment' id='question_post_series_number_comment'>
													$num_comment
												</div>
											</div>

											<div class='question_post_series_button'>
												 <button type='button' class='UpvoteAnswer' style='background-color: #bb3030;color:#fff;height:30px;width:90px;margin-right: 8px;' id='upvote$thisUser$answer_id'>
													$upvoted_or_not
												</button>
												<button type='button' class='DownvoteAnswer' style='background-color: #b7b7b7;
												color: #827c7c;width: 102px; margin-right: 8px;height:30px;' id='downvote$thisUser$answer_id'>
													$downvoted_or_not
												</button>
												
												<div uk-icon='more' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>
												
												<div uk-drop='mode: click'>
													<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
														<ul class='questionUlDesign'>
															<a href='#'><li type='button' uk-toggle='target: #add_bookmark$id'>Bookmark</li></a>
															<hr>
															<a href='#'><li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
															$edit_or_report_answer_button
														</ul>
													</div>
												</div>
											</div>
											
											<div class='report_answer' id='report_answer$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Report this Answer</h2>

													<form class='reportquestion' method='post'>
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;' checked>Inappropriate Answer not relevant to Question</label>

														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Spelling Mistake or Grammatical Error</label>

														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Answer Typed In Language other than English</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Sarcastic answer with no Sincererity</label>

														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Abusive Words</label>

														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Sexual Intimation</label>

														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Marketing Stuff From Unauthorized User</label>



														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
															<p class='uk-text-right'>
																<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
																<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
															</p>
														</div>
													</form>


												</div>
											</div>
											
											<div class='question_post_series_comment'>
												<iframe src='comment_frame.php?answer_id=$answer_id' id='answer_comment_iframe' frameborder='0'></iframe>
											</div>

										</div>	
									</div>
									
									<div class='question_post_answer' id='toggleAnswer$id$answer_id' style='display:none'>
										<iframe src='answer_frame.php?question_id=$id&answer_id=$answer_id' id='question_answer_iframe' frameborder='0'></iframe>
									</div>


								<hr>

								";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more question to load</h5>";
				
				
			}
			else{
				$str = "<h5>You didnt answer any question till now</h5>";
			}
            //$str = $str .'<h5>No more question to load</h5>';
			echo $str;
	}
	
	public function getProfileQuestion($data,$limit){
		
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM question WHERE posted_by='$this->user_from' AND deleted<>'yes' ORDER BY id DESC");

        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
			  
			   $(document).on('click', '.hide_similar_question', function() { 
					
						UIkit.alert(element, options);
						alert("fe");
					});
			  
			  
		  </script>
         <?php
			if(mysqli_num_rows($data_query) > 0) {

				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;

				while($row = mysqli_fetch_array($data_query)) {
					
					$topics_to_show = "";
					$id = $row['id'];
					$question_body = $row['question_body'];
					$question_body_unedited = $question_body;
					$question_link = $row['question_link'];
					$posted_by = $row['posted_by'];
					$date_time = $row['date_added'];
					$question_topic = $row['topic'];
					$thisUser = $this->user_from;

					$question_body_unedited = str_replace("'","",$question_body_unedited);
					$question_body_unedited = str_replace('"',"",$question_body_unedited);


					?>
						<script> 
							function toggle<?php echo $id; ?>() {

							var target = $(event.target);
								if (!target.is("a")) {
									var element = document.getElementById("toggleAnswer<?php echo $id; ?>");

									if(element.style.display == "block") 									
										element.style.display = "none";
									else 
										element.style.display = "block";
								}
							}
							
							/* When the user clicks on the button, 
							toggle between hiding and showing the dropdown content */
							function showQuestionExtraMenu() {
								document.getElementById("profile_icon_myDropdown").classList.toggle("profile_icon_show");
							}

							// Close the dropdown if the user clicks outside of it
							window.onclick = function(event) {
							  if (!event.target.matches('.profile_icon_dropbtn')) {

								var dropdowns = document.getElementsByClassName("profile_icon_dropdown-content");
								var i;
								for (i = 0; i < dropdowns.length; i++) {
								  var openDropdown = dropdowns[i];
								  if (openDropdown.classList.contains('profile_icon_show')) {
									openDropdown.classList.remove('profile_icon_show');
								  }
								}
							  }
							}

								$(document).ready(function() {
									$('#question_like<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_like.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var likes = msg + ' Likes';
											$("#question_like_shows<?php echo $thisUser; ?><?php echo $id; ?>").text(likes);
											location.reload();
											//$("#question_like<?php echo $thisUser; ?><?php echo $id; ?>").text("Unlike this question");

											//$("#question_like<?php echo $thisUser; ?><?php echo $id; ?>").attr("id","question_unlike<?php echo $thisUser; ?><?php //echo $id; ?>");

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});


									$('#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_unlike.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var likes = msg + ' Likes';
											$("#question_like_shows<?php echo $thisUser; ?><?php echo $id; ?>").text(likes);
											location.reload();
											//$("#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>").text("Like this question");

											//$("#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>").attr("id","question_like<?php echo $thisUser; ?><?php ///echo $id; ?>");

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});

									$('#answer_later<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userClicked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_answer_later.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {

											if(msg == '0'){
											   $("#answer_later<?php echo $thisUser; ?><?php echo $id; ?>").text("Answer this Later");
												toast('Question Removed');
											   }
											else{
												$("#answer_later<?php echo $thisUser; ?><?php echo $id; ?>").text("Question Added");
												toast('Question Added');
											}

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});
									
									$('#delete_question<?php echo $id; ?>').click(function () {

									  var data = 'id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_delete.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											location.reload();
										},
										error: function() {
											alert('Question Deletion Failure');
										}
									  });

									});
									
									$('#question_add_topic<?php echo $id; ?>').on('hidden', function () {
										$(".search_topic_results").html("");
										$('.search_topic_results').css({"height": "0px"});
									})
									
									$('#hide_question<?php echo $id; ?>').click(function () {
										$('.hide_question<?php echo $id; ?>').css("display","block");
									});
	
								});
							
							


						</script>

					<?php
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
					//Topic List
                    //Display link line
                    if($question_link != ""){
                        $question_link_line = "<div class='question_post_series_link'>
                                                    <a href='$question_link' target='_blank'>
                                                        <span uk-icon='link'></span>
                                                        $question_link
                                                    </a>
                                                </div>";
                    }
                    else{
                        $question_link_line = "";
                    }
					
					$question_topics = explode(",", $question_topic);
					foreach($question_topics as $i){
						if($i!=''){
							$topics_to_show .= "<div class='topicsAlreadyAddedInQuestion' id='topicsAlreadyAddedInQuestionId$id$i$userLoggedIn'>
													<button class='topicsAlreadyAddedInQuestionButton' id='topicsAlreadyAddedInQuestionButton$id$i$userLoggedIn'>x</button>
													".$i."													
												</div>";
							?>
								<script>
									 $(document).on('click', '#topicsAlreadyAddedInQuestionButton<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>', function() { 

										var userLoggedIn = '<?php echo $userLoggedIn; ?>';
										var topicDeleted = '<?php echo $i; ?>';
										var data = 'userDeleted='+ userLoggedIn + '&topicDeleted=' + topicDeleted + '&qid=' + <?php echo $id; ?>;

										 $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_topic_deleted_to_question.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												console.log(msg);
												$('#topicsAlreadyAddedInQuestionId<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>').hide();

											},
											error: function() {
												alert('Deleting Failure');
											}
										 });

									   });
									
								</script>
							<?php
						}
						
					}
					
					//Display number of likes
					$check_num_likes = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id'");
					$num_likes = mysqli_num_rows($check_num_likes);
					$num_like_string =  $num_likes . " Likes";

					//Display the like button
					$check_num_likes_button = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id' 
																		AND question_liked_by='$this->user_from'");
					if(mysqli_num_rows($check_num_likes_button)>0){
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_unlike$thisUser$id'>Unlike this question </button>";
					}
					else{
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_like$thisUser$id'>Like this question </button>";
					}

					//Check to Display answer later button
					$check_answer_later_button = mysqli_query($this->con, "SELECT * FROM answer_later WHERE question_id='$id' 
																		AND user_added='$this->user_from'");
					if(mysqli_num_rows($check_answer_later_button)>0){
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id' > Qustion Added </button>";
					}
					else{
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id'> Answer This Later </button>";
					}
					
					//Check to display delete button
					if(($thisUser == $posted_by) || $thisUser == 'akash_dutta'){
						$close_button_to_show = "<div class='question_post_series_deletebutton questionExtraMenu' id='questionCloseButton$id'>
													<div uk-icon='close' uk-toggle='target: #close_button$id'></div>
												</div>
												";
						
						$close_button_modal_to_show = "<div class='delete_button' id='close_button$id' uk-modal>
														<div class='uk-modal-dialog uk-modal-body'>
															<h3>This Question will be deleted permanently.</h3>
															<hr>
															<h5>Do you want to delete this following question?</h5>
															<h5>Q:-'.$question_body_unedited.'</h5>
															<div class='uk-modal-footer uk-text-right'>
																<p class='uk-text-right'>
																	<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
																	<button class='uk-button uk-button-primary' type='button' id='delete_question$id'>Yes, Delete</button>
																</p>
															</div>
														</div>
													</div>
													";
					}
					else{
						$close_button_to_show = "";
						$close_button_modal_to_show = "";
					}

						//Timeframe
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

						$time_message = "Posted ". $time_message;
						$str .= "<div class='question_post_series'>

									<div class='question_post_series_question_and_deletebutton'>
										<div class='question_post_series_question'>
											<a href='search.php?q=$question_body_unedited&qid=$id'>$question_body</a>
										</div>
										$close_button_to_show
										
									</div>
								   
									$close_button_modal_to_show
									$question_link_line			
												
									<div class='question_post_series_info'>
										<div class='question_post_series_time'>
											$time_message
										</div>
										<div class='question_post_series_likes' id='question_like_shows$thisUser$id'>
										  $num_like_string
										</div>
									</div>	
									<div class='question_post_series_button'>

										<button type='button' class='AddAnswer' style='background-color: #bb3030;color: #fff;'  onClick='javascript:toggle$id()'>
											Add a answer 
										</button>

										$like_button_to_show
										$answer_later_button_to_show
										
										<div uk-icon='menu' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>
					
											
											<div uk-drop='mode: click'>
												<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
													<ul class='questionUlDesign'>
														<a href='#'><li type='button' uk-toggle='target: #question_add_topic$id'>Add topics</li></a>
														<hr>
														<a href='#'><li type='button' id='hide_question$id'>Hide similar question</li></a>
														<hr>
														<a href='#' <li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #report_question$id'>Report</li></a>
													</ul>
												</div>
											</div>
											
											<div id='question_add_topic$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Add some topic</h2>
													
													<h5>Q:-".$question_body_unedited."</h5>
													
													<div class='questionAddedTopicsInModal$id$userLoggedIn'>
														".$topics_to_show."
													</div>
													
													<div class='load_topic_form'>
														<form action='ajax_load_topic_box.php' method='POST' class='load_topic_form_form'>	
															<input type='text' name='topix_text' placeholder='Search a Topic to add.. (Ex:- Science, Technology, etc) ' onkeyup='getLiveTopic(this.value, ".'"'.$userLoggedIn.'"'.",".'"add"'.", ".'"'.$id.'"'.")'
																   autocomplete='off' id='id_topix_text' style='width:100%; height: 30px;background-color: #e9eef3;' class='load_topic_form_form_input'>
														</form>



														<div class='search_topic_results'>
														</div>
													</div>
													
													<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>DONE</button>
														</p>
													</div>
												</div>
											</div>
											
											<div class='uk-alert-warning hide_question$id' uk-alert style='display:none'>
												<a class='uk-alert-close' uk-close></a>
												<p style='margin-left:10px;font-size:17px'>You will stop seeing question related to<br>Q:-".$question_body_unedited."</p>
											</div>
											
											<div class='changeQuestion' id='change_question$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Change Question</h2>
													<h4>From:-</h4>
													<h5>Q:-".$question_body_unedited."</h5>
													<h4>To:-</h4>
													
													<form action='question.php' method='post'>
														<input type='text' name='change_question_question' value='$question_body_unedited'>
														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
															<input class='uk-button uk-button-primary' type='submit' name='question_change_button' id='change_question'>
														</p>
													</div>
													</form>
													
													
												</div>
											</div>
											
											<div class='report_question' id='report_question$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Report this Question</h2>
													<h5>Q:-".$question_body_unedited."</h5>
													
													
													<form action='question.php' method='post'>
													 	<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;' checked>Inappropriate Question</label>
														
            										 	<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Spelling Mistake or Grammatical Error</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Questoin Typed In Language other than English</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Abusive Words</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Sexual Intimation</label>
														
														<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Marketing Stuff From Unauthorized User</label>

														
														
														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
															<p class='uk-text-right'>
																<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
																<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
															</p>
														</div>
													</form>
													
													
												</div>
											</div>
										

									</div> 
								</div>

								<div class='question_post_answer' id='toggleAnswer$id' style='display:none;'>
									<iframe src='answer_frame.php?question_id=$id' id='question_answer_iframe' frameborder='0'></iframe>
								</div>

								<hr>";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more question to load</h5>";
				
			}
			else{
				$str = "<h5>You didnt ask any question till now <br>Start asking <a data-toggle='modal' data-target='#post_question'>now</a></h5>";
			}
            //$str = $str .'<h5>No more question to load</h5>';
			echo $str;

        
    }
	
	public function getSearchAnswer($data,$limit,$qid){
		
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
		
		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM answer WHERE question_id='$qid' ORDER BY upvote DESC");

        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				
			})
		  </script>
         <?php

			if(mysqli_num_rows($data_query) > 0) {

				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;
				
				while($row = mysqli_fetch_array($data_query)) {
					$answer_id = $row['id'];
					$question_id = $row['question_id'];

					$data_query_question = mysqli_query($this->con, "SELECT * FROM question WHERE id='$question_id'");
					$row_question = mysqli_fetch_array($data_query_question);

					$id = $row_question['id'];
					$question_body = $row_question['question_body'];
					$question_body_unedited = $question_body;
					$question_link = $row_question['question_link'];
					$posted_by = $row_question['posted_by'];
					$date_time = $row_question['date_added'];

					$answer_body = $row['answer_body'];
					$answer_date_time = $row['date_added'];
					$answer_given_by = $row['given_by'];
					$answer_upvote = $row['upvote'];
					$thisUser = $this->user_from;

					$question_body_unedited = str_replace("'","",$question_body_unedited);
					$question_body_unedited = str_replace('"',"",$question_body_unedited);

					?>
						<script> 
							$(document).ready(function() {
									$('#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userUpvoted='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_answer_upvote.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var result = $.parseJSON(msg);
											var upvotes = result[0] + ' Upvotes';
											$("#question_post_series_number_upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text(upvotes);
											if(result[1]==0){
											   $("#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Upvote");
											   }
											else{
												$("#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Upvoted");
												var content = $('[data-toast]').data("toast");
												toast(content);
											}
										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});

									$('#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userDownvoted='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_answer_downvote.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var result = $.parseJSON(msg);
											var downvotes = result[0] + ' Downvotes';
											$("#question_post_series_number_downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text(downvotes);
											if(result[1]==0){
											   $("#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Downvote");
											   }
											else{
												$("#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Downvoted");
											}
										},
										error: function() {
											alert('Submission Failure');
										}
									  });

									});

									$('#show_more<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';
									  var data = 'userLogin='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_answer_show.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
												$("#hide_answer<?php echo $answer_id; ?>").hide();
													$("#show_answer<?php echo $answer_id; ?>").show();



										},
										error: function() {
											alert('Submission Failure');
										}

									  });
									});
							});

						</script>

					<?php
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
					//Display number of likes
					$check_num_views = mysqli_query($this->con, "SELECT * FROM answer WHERE id='$answer_id'");
					$num_views_row = mysqli_fetch_array($check_num_views);
					$num_views = $num_views_row['views'];
					$num_views =  $num_views . " Views";

					//Display numver of upvote
					$check_num_upvote = mysqli_query($this->con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id'");
					$num_upvote = mysqli_num_rows($check_num_upvote);
					$num_upvote = $num_upvote . " Upvotes";

					//Display numver of downvote
					$check_num_downvote = mysqli_query($this->con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id'");
					$num_downvote = mysqli_num_rows($check_num_downvote);
					$num_downvote = $num_downvote . " Downvotes";

					//Display numver of Comments
					$check_num_comment = mysqli_query($this->con, "SELECT * FROM comments WHERE answer_id='$answer_id'");
					$num_comment = mysqli_num_rows($check_num_comment);
					$num_comment = $num_comment . " Comments";

					//Display the like button
					$check_num_likes_button = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id' 
																		AND question_liked_by='$this->user_from'");
					if(mysqli_num_rows($check_num_likes_button)>0){
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_unlike$thisUser$id'>Unlike this question </button>";
					}
					else{
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_like$thisUser$id'>Like this question </button>";
					}


					$check_answer_later_button = mysqli_query($this->con, "SELECT * FROM answer_later WHERE question_id='$id' 
																		AND user_added='$this->user_from'");
					if(mysqli_num_rows($check_answer_later_button)>0){
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' style='cursor:not-allowed;' > Qustion Added </button>";
					}
					else{
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id' data-toast='Question Added'> Answer This Later </button>";
					}

					//Check Upvoted or not
					$check_already_upvoted = mysqli_query($this->con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id' AND answer_upvoted_by='$thisUser'");
					$check_already_upvoted_num = mysqli_num_rows($check_already_upvoted);

					if($check_already_upvoted_num>0){
						$upvoted_or_not = "Upvoted";
					}
					else {
						$upvoted_or_not = "Upvote";
					}

					//Check downvoted or not
					$check_already_downvoted = mysqli_query($this->con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id' AND answer_downvoted_by='$thisUser'");
					$check_already_downvoted_num = mysqli_num_rows($check_already_downvoted);

					if($check_already_upvoted_num>0){
						$downvoted_or_not = "Downvoted";
					}
					else {
						$downvoted_or_not = "Downvote";
					}

					while(strpos($answer_body,'style="')!=NULL){

						$var1='style="';
						$var2='"';
						$pool=$answer_body;
							$temp1 = strpos($pool,$var1)+strlen($var1);
							$result = substr($pool,$temp1,strlen($pool));
							$dd=strpos($result,$var2);
							if($dd == 0){
								$dd = strlen($result);
							}
						$font_tag_to_remove = substr($result,0,$dd);
						$answer_body = str_replace($font_tag_to_remove,"",$answer_body);
						$answer_body = str_replace('style=""',"",$answer_body);
					}

					//	$var1='<';
					//	$var2='>';
					//	$pool=$answer_body;
					//		$temp1 = strpos($pool,$var1)+strlen($var1);
					//		$result = substr($pool,$temp1,strlen($pool));
					//		$dd=strpos($result,$var2);
					//		if($dd == 0){
					//			$dd = strlen($result);
					//		}
					//	$font_tag_to_remove = substr($result,0,$dd);
					//	$answer_body = str_replace($font_tag_to_remove,"",$answer_body);
					//	$answer_body = str_replace('<>',"",$answer_body);

					//$answer_first_letter = substr($answer_body,0,1);
					//$answer_first_letter = "<div style='font-family: Georgia,serif;font-size: 33px;line-height: 23px;font-weight:bolder;float:left;'>".
					//			   "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$answer_first_letter."</div>";
					//			   "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$answer_first_letter."</div>";

					//$answer_body = substr($answer_body,1);
					//$answer_body = "<" . $font_tag_to_remove . ">" . $answer_first_letter . $answer_body;

					$answer_body_hidden = "<div class='newsfeed_edited_text_hidden' style='font-family: Georgia,serif;font-size: 17px;line-height: 30px; height:43px;'>".$answer_body."</div>";
					$answer_body = "<div class='newsfeed_edited_text' style='font-family: Georgia,serif;font-size: 17px;line-height: 30px;'>".
								   $answer_body."</div>";



						//Timeframe
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

						$question_body = 'Q:-&nbsp;&nbsp;' . $question_body;
						$time_message = "Posted ". $time_message;
						$str .= "
									<div class='question_post_series_search'>

											<div class='question_post_series_answer'>
												$answer_body
											</div>

											<div class='question_post_series_number'>
												<div class='question_post_series_number_views' id='question_post_series_number_views$thisUser$answer_id'>
													$num_views
												</div>
												<div class='question_post_series_number_upvote' id='question_post_series_number_upvote$thisUser$answer_id'>
													$num_upvote
												</div>
												<div class='question_post_series_number_downvote' id='question_post_series_number_downvote$thisUser$answer_id'>
													$num_downvote
												</div>
												<div class='question_post_series_number_comment' id='question_post_series_number_comment'>
													$num_comment
												</div>
											</div>

											<div class='question_post_series_button'>
												 <button type='button' class='UpvoteAnswer' style='background-color: #bb3030;color:#fff;height:30px;width:90px;margin-right: 8px;' id='upvote$thisUser$answer_id' data-toast='Upvoted'>
													$upvoted_or_not
												</button>
												<button type='button' class='DownvoteAnswer' style='background-color: #b7b7b7;
												color: #827c7c;width: 102px; margin-right: 8px;height:30px;' id='downvote$thisUser$answer_id'>
													$downvoted_or_not
												</button>
											</div>
											<div class='question_post_series_comment'>
												<iframe src='comment_frame.php?answer_id=$answer_id' id='answer_comment_iframe' frameborder='0'></iframe>
											</div>


									</div>


								<hr>

								";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more question to load</h5>";
			}
            //$str = $str .'<h5>No more answers to load</h5>';
			echo $str;
	}
	
	public function submitAnswer($body,$qid,$anonymous){
		
	    $body = mysqli_escape_string($this->con, $body);
		$check_empty = preg_replace('/\s+/', '', $body); //Deltes all spaces 
        $thisUser = $this->user_from;
		
		if($check_empty != "") 
		{
			if(substr($body,0,1)=='<'){
				
			}
			else{
			$body = "<span>" . $body . "</span>";
			}
			//Update answer count for user
            $user_details_query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$thisUser'");
		    $user_row = mysqli_fetch_array($user_details_query);
            $num_question = $user_row['answer'];
			$num_question++;
			$insert = mysqli_query($this->con, "UPDATE users SET answer = '$num_question' WHERE username ='$thisUser'");
			
			$date_time_now = date("Y-m-d H:i:s");
			$insert_post = mysqli_query($this->con, "INSERT INTO answer VALUES ('', '$body', '$date_time_now', '$this->user_from','$anonymous', '$qid','0' ,'no','1')");
			
			//Get question body
			$question_query = mysqli_query($this->con, "SELECT * FROM question WHERE id='$qid'");
			$question_query_row = mysqli_fetch_array($question_query);
			$question_body = $question_query_row['question_body'];
			
			//Insert notification
			$notification_date_added = date("Y-m-d H:i:s");
			$notification_body = "<a href='search.php?qid=".$qid."'>Your answer to question <b>Q:-'".$question_body."'</b> submitted successfully</a>";
			$notification_body = str_replace("'",'"',$notification_body);
			$notification_insert = mysqli_query($this->con, "INSERT INTO notification VALUES('', '$notification_body', 
			'$this->user_from','$notification_date_added','no')");
			
			$remove_from_draft = mysqli_query($this->con,"DELETE FROM draft WHERE given_by = '$thisUser' AND question_id = '$qid'");
            $update_answered_column = mysqli_query($this->con,"UPDATE question SET answered = 'yes' WHERE id='$qid'");
            
		}
	  
	}
	
	public function getDraft($data,$limit){
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM draft WHERE given_by='$userLoggedIn' ORDER BY id DESC");

        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
			  
			   $(document).on('click', '.hide_similar_question', function() { 
					
						UIkit.alert(element, options);
						alert("fe");
					});
			  
			  
		  </script>
         <?php
			if(mysqli_num_rows($data_query) > 0) {

				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;

				while($row = mysqli_fetch_array($data_query)) {
					
					$topics_to_show = "";
					$draft_id = $row['id'];
					$id = $row['question_id'];
					$question_draft_query = mysqli_query($this->con, "SELECT * FROM question WHERE id='$id'");
					$question_draft_row = mysqli_fetch_array($question_draft_query);
					
						
					$question_body = $question_draft_row['question_body'];
					$question_body_unedited = $question_body;
					$question_link = $question_draft_row['question_link'];
					$posted_by = $question_draft_row['posted_by'];
					$date_time = $question_draft_row['date_added'];
					$question_topic = $question_draft_row['topic'];
					$thisUser = $this->user_from;

					$question_body_unedited = str_replace("'","",$question_body_unedited);
					$question_body_unedited = str_replace('"',"",$question_body_unedited)


					?>
						<script> 
							function toggle<?php echo $id; ?>() {

							var target = $(event.target);
								if (!target.is("a")) {
									var element = document.getElementById("toggleAnswer<?php echo $id; ?>");

									if(element.style.display == "block") 									
										element.style.display = "none";
									else 
										element.style.display = "block";
								}
							}
							
							/* When the user clicks on the button, 
							toggle between hiding and showing the dropdown content */
							function showQuestionExtraMenu() {
								document.getElementById("profile_icon_myDropdown").classList.toggle("profile_icon_show");
							}

							// Close the dropdown if the user clicks outside of it
							window.onclick = function(event) {
							  if (!event.target.matches('.profile_icon_dropbtn')) {

								var dropdowns = document.getElementsByClassName("profile_icon_dropdown-content");
								var i;
								for (i = 0; i < dropdowns.length; i++) {
								  var openDropdown = dropdowns[i];
								  if (openDropdown.classList.contains('profile_icon_show')) {
									openDropdown.classList.remove('profile_icon_show');
								  }
								}
							  }
							}

								$(document).ready(function() {
									$('#question_like<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_like.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var likes = msg + ' Likes';
											$("#question_like_shows<?php echo $thisUser; ?><?php echo $id; ?>").text(likes);
											location.reload();
											//$("#question_like<?php echo $thisUser; ?><?php echo $id; ?>").text("Unlike this question");

											//$("#question_like<?php echo $thisUser; ?><?php echo $id; ?>").attr("id","question_unlike<?php echo $thisUser; ?><?php //echo $id; ?>");

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});


									$('#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_unlike.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var likes = msg + ' Likes';
											$("#question_like_shows<?php echo $thisUser; ?><?php echo $id; ?>").text(likes);
											location.reload();
											//$("#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>").text("Like this question");

											//$("#question_unlike<?php echo $thisUser; ?><?php echo $id; ?>").attr("id","question_like<?php echo $thisUser; ?><?php ///echo $id; ?>");

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});

									$('#answer_later<?php echo $thisUser; ?><?php echo $id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userClicked='+ thisUser + '&id=' + <?php echo $id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_question_answer_later.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {

											if(msg == '0'){
											   $("#answer_later<?php echo $thisUser; ?><?php echo $id; ?>").text("Answer this Later");
												toast('Question Removed');
											   }
											else{
												$("#answer_later<?php echo $thisUser; ?><?php echo $id; ?>").text("Question Added");
												toast('Question Added');
											}

										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});
									
									$('#question_add_topic<?php echo $id; ?>').on('hidden', function () {
										$(".search_topic_results").html("");
										$('.search_topic_results').css({"height": "0px"});
									})
									
									$('#hide_question<?php echo $id; ?>').click(function () {
										$('.hide_question<?php echo $id; ?>').css("display","block");
									});
	
								});
							
							


						</script>

					<?php
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
					//Topic List
					
					$question_topics = explode(",", $question_topic);
					foreach($question_topics as $i){
						if($i!=''){
							$topics_to_show .= "<div class='topicsAlreadyAddedInQuestion' id='topicsAlreadyAddedInQuestionId$id$i$userLoggedIn'>
													<button class='topicsAlreadyAddedInQuestionButton' id='topicsAlreadyAddedInQuestionButton$id$i$userLoggedIn'>x</button>
													".$i."													
												</div>";
							?>
								<script>
									 $(document).on('click', '#topicsAlreadyAddedInQuestionButton<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>', function() { 

										var userLoggedIn = '<?php echo $userLoggedIn; ?>';
										var topicDeleted = '<?php echo $i; ?>';
										var data = 'userDeleted='+ userLoggedIn + '&topicDeleted=' + topicDeleted + '&qid=' + <?php echo $id; ?>;

										 $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_topic_deleted_to_question.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												console.log(msg);
												$('#topicsAlreadyAddedInQuestionId<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>').hide();

											},
											error: function() {
												alert('Deleting Failure');
											}
										 });

									   });
									
								</script>
							<?php
						}
						
					}
					
					//Display number of likes
					$check_num_likes = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id'");
					$num_likes = mysqli_num_rows($check_num_likes);
					$num_like_string =  $num_likes . " Likes";

					//Display the like button
					$check_num_likes_button = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id' 
																		AND question_liked_by='$this->user_from'");
					if(mysqli_num_rows($check_num_likes_button)>0){
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_unlike$thisUser$id'>Unlike this question </button>";
					}
					else{
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_like$thisUser$id'>Like this question </button>";
					}


					$check_answer_later_button = mysqli_query($this->con, "SELECT * FROM answer_later WHERE question_id='$id' 
																		AND user_added='$this->user_from'");
					if(mysqli_num_rows($check_answer_later_button)>0){
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id' > Qustion Added </button>";
					}
					else{
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id'> Answer This Later </button>";
					}

						//Timeframe
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

						$time_message = "Posted ". $time_message;
						$str .= "<div class='question_post_series'>

									<div class='question_post_series_question'>
										<a href='search.php?q=$question_body_unedited&qid=$id'>$question_body</a>
									</div>
									<div class='question_post_series_info'>
										<div class='question_post_series_time'>
											$time_message
										</div>
										<div class='question_post_series_likes' id='question_like_shows$thisUser$id'>
										  $num_like_string
										</div>
									</div>	
									<div class='question_post_series_button'>

										<button type='button' class='AddAnswer' style='background-color: #bb3030;color: #fff;'  onClick='javascript:toggle$id()'>
											Add a answer 
										</button>

										$like_button_to_show
										$answer_later_button_to_show
										
										<div uk-icon='menu' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>
					
											
											<div uk-drop='mode: click'>
												<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
													<ul class='questionUlDesign'>
														<a href='#'><li type='button' uk-toggle='target: #question_add_topic$id'>Add topics</li></a>
														<hr>
														<a href='#'><li type='button' id='hide_question$id'>Hide similar question</li></a>
														<hr>
														<a href='#' <li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #report_question$id'>Report</li></a>
													</ul>
												</div>
											</div>
											
											<div id='question_add_topic$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Add some topic</h2>
													
													<h5>Q:-".$question_body_unedited."</h5>
													
													<div class='questionAddedTopicsInModal'>
														".$topics_to_show."
													</div>
													
													<div class='load_topic_form'>
														<form action='ajax_load_topic_box.php' method='POST' class='load_topic_form_form'>	
															<input type='text' name='topix_text' placeholder='Search a Topic to add.. (Ex:- Science, Technology, etc) ' onkeyup='getLiveTopic(this.value, ".'"'.$userLoggedIn.'"'.",".'"add"'.", ".'"'.$id.'"'.")'
																   autocomplete='off' id='id_topix_text' style='width:100%; height: 30px;background-color: #e9eef3;' class='load_topic_form_form_input'>
														</form>



														<div class='search_topic_results'>
														</div>
													</div>
													
													<div class='uk-modal-footer uk-text-righ'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>DONE</button>
														</p>
													</div>
												</div>
											</div>
											
											<div class='uk-alert-warning hide_question$id' uk-alert style='display:none'>
												<a class='uk-alert-close' uk-close></a>
												<p style='margin-left:10px;font-size:17px'>You will stop seeing question related to<br>Q:-".$question_body_unedited."</p>
											</div>
											
											<div class='changeQuestion' id='change_question$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Change Question</h2>
													<h4>From:-</h4>
													<h5>Q:-".$question_body_unedited."</h5>
													<h4>To:-</h4>
													
													<form action='question.php' method='post'>
														<input type='text' name='change_question_question' value='$question_body_unedited'>
														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
															<input class='uk-button uk-button-primary' type='submit' name='question_change_button' id='change_question'>
														</p>
													</div>
													</form>
													
													
												</div>
											</div>
											
											<div class='report_question' id='report_question$id' uk-modal>
												<div class='uk-modal-dialog uk-modal-body'>
													<h2 class='uk-modal-title'>Report this Question</h2>
													<h5>Q:-".$question_body_unedited."</h5>
													
													
													<form action='question.php' method='post'>
														<input type='radio' name='reportQuestionRadio' value='a' checked>Inappropriate Question<br>
														<input type='radio' name='reportQuestionRadio' value='b' checked>Spelling Mistake or Grammatical Error<br>
														<input type='radio' name='reportQuestionRadio' value='c' checked>Questoin Typed In Language other than English<br>
														<input type='radio' name='reportQuestionRadio' value='d' checked>Contain Abusive Words<br>							
														<input type='radio' name='reportQuestionRadio' value='e' checked>Contain Sexual Intimation<br>
														<input type='radio' name='reportQuestionRadio' value='f' checked>Contain Marketing Stuff From Unauthorized User<br>

														
														<input type='hidden' name='change_question_id' value='$id'>
														<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
														<div class='uk-modal-footer uk-text-right'>
															<p class='uk-text-right'>
																<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
																<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
															</p>
														</div>
													</form>
													
													
												</div>
											</div>
										

									</div> 
								</div>

								<div class='question_post_answer' id='toggleAnswer$id'>
									<iframe src='draft_frame.php?question_id=$id&draft_id=$draft_id' id='question_answer_iframe' frameborder='0'></iframe>
								</div>

								<hr>";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more question to load</h5>";
				
			}
			else{
				$str = "<h5 style='text-align: centre;'>You haven't saved any draft.</h5>";
			}
            //$str = $str .'<h5>No more question to load</h5>';
			echo $str;

	}
	
	public function submitDraft($body,$qid){
		
	    $body = mysqli_escape_string($this->con, $body);
		$check_empty = preg_replace('/\s+/', '', $body); //Deltes all spaces 
        $thisUser = $this->user_from;
		if($check_empty != "") 
		{

			//Update answer count for user
            $user_details_query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$thisUser'");
		    $user_row = mysqli_fetch_array($user_details_query);
            $num_question = $user_row['answer'];
			$num_question++;
			
			$date_time_now = date("Y-m-d H:i:s");
			$insert_post = mysqli_query($this->con, "INSERT INTO draft VALUES ('', '$body', '$date_time_now', '$this->user_from', '$qid')");
			
			//Get question body
			$question_query = mysqli_query($this->con, "SELECT * FROM question WHERE id='$qid'");
			$question_query_row = mysqli_fetch_array($question_query);
			$question_body = $question_query_row['question_body'];
			
			//Insert notification
			$notification_date_added = date("Y-m-d H:i:s");
			$notification_body = "<a href='search.php?qid=".$qid."'>Your draft to question <b>Q:-'".$question_body."'</b> saved successfully</a>";
			$notification_body = str_replace("'",'"',$notification_body);
			$notification_insert = mysqli_query($this->con, "INSERT INTO notification VALUES('', '$notification_body', 
			'$this->user_from','$notification_date_added','no')");
		}
	  
	}
    
    public function getSearchedQuestion($data,$limit,$search){
        $page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
        
        if($page == 1){
		  $str = "<div class='search_page_question_header'><h4>Showing Questions :- <br></h4></div>"; //String to return 
        }
        else{
            $str="";
        }
		$data_query = mysqli_query($this->con, "SELECT * FROM question WHERE question_body LIKE '%$search%' ORDER BY date_added DESC");

        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
			  
			   $(document).on('click', '.hide_similar_question', function() { 
					
						UIkit.alert(element, options);
						alert("fe");
					});
			  
			  
		  </script>

         <?php
			if(mysqli_num_rows($data_query) > 0) {

				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;

				while($row = mysqli_fetch_array($data_query)) {
                    
                    $qid = $row['id'];
                    $question_body = $row['question_body'];
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
					
                    $str .= "
                            <div class='search_page_query_question'>
                                <a href='search.php?q=".$question_body."&qid=".$qid."'>".$question_body."</a>
                            </div>
                            <hr>
                            ";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more results on '".$search."'</h5>";
				
			}
			else{
				$str = "<h5 style='text-align: centre;'>No searched result for '".$search."'</h5>";
			}
            //$str = $str .'<h5>No more question to load</h5>';
			echo $str;

    }
    
    public function getSearchedProfiles($data,$limit,$search){
        $page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
        
        if($page == 1){
		    $str = "<div class='search_page_question_header'><h4>Showing Profiles :- <br></h4></div>"; //String to return 
        }
        else{
            $str="";
        }
		$data_query = mysqli_query($this->con, "SELECT * FROM users 
                                                WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' 
                                                ORDER BY follower 
                                                DESC");

        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
			  
			   $(document).on('click', '.hide_similar_question', function() { 
					
						UIkit.alert(element, options);
						alert("fe");
					});
			  
			  
		  </script>

         <?php
			if(mysqli_num_rows($data_query) > 0) {

				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;

				while($row = mysqli_fetch_array($data_query)) {
                    
                    $user_id = $row['id'];
                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $username = $row['username'];
                    $profile_pic = $row['profile_pic'];
                    $follower = $row['follower'];
                    
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
					
                    $str .= "
                            <div class='search_page_query_profile'>
                                <a href='profile.php?profile_username=".$username."'>
                                    <div class='search_page_query_profile_pic'> 
                                        <img src='".$profile_pic."'>
                                    </div>

                                    <div class='search_page_query_profile_name'>
                                        ".$first_name." ".$last_name."
                                    </div>

                                    <div class='search_page_query_profile_username'>
                                        ".$username."
                                    </div>

                                    <div class='search_page_query_profile_follower'>
                                        ".$follower." Followers
                                    </div>
                                </a>
                            </div>
                            <hr>
                            ";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more results on '".$search."'</h5>";
				
			}
			else{
				$str = "<h5 style='text-align: centre;'>No searched result for '".$search."'</h5>";
			}
            //$str = $str .'<h5>No more question to load</h5>';
			echo $str;
    }
	
	public function getProfileFollower($data,$limit,$user_to_show){
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
		
		$str = ""; //String to return 
		
        $data_query = mysqli_query($this->con, "SELECT follower_list FROM users 
                                                WHERE username='$user_to_show'
												");

			
		$num_iterations = 0; //Number of results checked (not necasserily posted)
		$count = 1;
		
		
		$user_query_row = mysqli_fetch_array($data_query);
		$user_follower_list = $user_query_row['follower_list'];

		$user_follower_list_explode = explode(",", $user_follower_list);
		
		$no_of_followers = sizeof($user_follower_list_explode) - 2;
		$header = "
					<div class='user_profile_top_header'>
						<h2>
							$no_of_followers Follower
						</h2>
				    </div>
					<hr>
				  ";
		
		$str.= $header;

		foreach($user_follower_list_explode as $i) {

			if($num_iterations++ < $start)
					continue; 

				//Once 10 posts have been loaded, break
				if($count > $limit) {
					break;
				}
				else {
					$count++;
				}

			
			if($i != ""){
				
				$user_details_i_query = mysqli_query($this->con, "SELECT first_name,last_name,follower,profile_pic,follower_list FROM users 
                                                WHERE username='$i'
												");
				$user_details_i_row = mysqli_fetch_array($user_details_i_query);
				$first_name = $user_details_i_row['first_name'];
				$last_name = $user_details_i_row['last_name'];
				$follower = $user_details_i_row['follower'];
				$profile_pic = $user_details_i_row['profile_pic'];
				$user_follower_list = $user_details_i_row['follower_list'];
				
				if($i != $userLoggedIn){
					if(strstr($user_follower_list,$userLoggedIn)){
						$follow_button = "<div class='profile_follow_button_div'>
											<button type='button' class='profile_follow_button' id='follow_button$userLoggedIn$i' style='margin-left:30px'>
												Unfollow
											</button>
											<div class='profile_follower_display' id='profile_follower_display_id'>
												".$follower."
											</div>
										  </div>";
					}
					else{
						$follow_button = "<div class='profile_follow_button_div'>
											<button type='button' class='profile_follow_button' id='follow_button$userLoggedIn$i' style='margin-left:30px'>
												Follow
											</button>
											<div class='profile_follower_display' id='profile_follower_display_id'>
												".$follower."
											</div>
										  </div>";
					}
				}
				else{
					$follow_button="";
				}
				
				?>
				<script>
					$(document).ready(function() {
							$('#follow_button<?php echo $userLoggedIn; ?><?php echo $i; ?>').click(function () {

							  var userToShow = '<?php echo $i; ?>';
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
										$("#follow_button<?php echo $userLoggedIn; ?><?php echo $i; ?>").html("Follow");
										$("#profile_follower_display_id").text(follower);
										//toast('Question Removed');
									 }
									else{
										$("#follow_button<?php echo $userLoggedIn; ?><?php echo $i; ?>").html("Unfollow");
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
				<?php

				$str .= " 	<div class='box'>
                                <a href='profile.php?profile_username=".$i."'>
                                    <div class='search_page_query_profile_pic'> 
                                        <img src='".$profile_pic."'>
                                    </div>

                                    <div class='profile_name'>
                                        ".$first_name." ".$last_name."
                                    </div>

                                    <div class='profile_username'>
                                        ".$i."
                                    </div>

                                    <div class='profile_follower'>
                                        ".$follower." Followers
                                    </div>
									
                                </a>
								<div class='profile_follower_button_cover'>
									$follow_button
								</div>
                            </div>
						";
			}

		}

		if($count > $limit) 
			$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
					 <input type='hidden' class='noMorePosts' value='false'>";
		else 
			$str .= "<input type='hidden' class='noMorePosts' value='true'>
						
					";
		
		echo $str;

	}
	
	public function getProfileFollowing($data,$limit,$user_to_show){
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
		
		$str = ""; //String to return 
		
        $data_query = mysqli_query($this->con, "SELECT following_list FROM users 
                                                WHERE username='$user_to_show'
												");

			
		$num_iterations = 0; //Number of results checked (not necasserily posted)
		$count = 1;
		
		
		$user_query_row = mysqli_fetch_array($data_query);
		$user_following_list = $user_query_row['following_list'];

		$user_following_list_explode = explode(",", $user_following_list);
		
		$no_of_followings = sizeof($user_following_list_explode) - 2;
		$header = "
					<div class='user_profile_top_header'>
						<h2>
							$no_of_followings Following
						</h2>
				    </div>
					<hr>
				  ";
		
		$str.= $header;

		foreach($user_following_list_explode as $i) {

			if($num_iterations++ < $start)
					continue; 

				//Once 10 posts have been loaded, break
				if($count > $limit) {
					break;
				}
				else {
					$count++;
				}

			
			if($i != ""){
				
				$user_details_i_query = mysqli_query($this->con, "SELECT first_name,last_name,follower,profile_pic,follower_list FROM users 
                                                WHERE username='$i'
												");
				$user_details_i_row = mysqli_fetch_array($user_details_i_query);
				$first_name = $user_details_i_row['first_name'];
				$last_name = $user_details_i_row['last_name'];
				$follower = $user_details_i_row['follower'];
				$profile_pic = $user_details_i_row['profile_pic'];
				$user_follower_list = $user_details_i_row['follower_list'];
				
				if($i != $userLoggedIn){
					if(strstr($user_follower_list,$userLoggedIn)){
						$follow_button = "<div class='profile_follow_button_div'>
											<button type='button' class='profile_follow_button' id='follow_button$userLoggedIn$i' style='margin-left:30px'>
												Unfollow
											</button>
											<div class='profile_follower_display' id='profile_follower_display_id'>
												".$follower."
											</div>
										  </div>";
					}
					else{
						$follow_button = "<div class='profile_follow_button_div'>
											<button type='button' class='profile_follow_button' id='follow_button$userLoggedIn$i' style='margin-left:30px'>
												Follow
											</button>
											<div class='profile_follower_display' id='profile_follower_display_id'>
												".$follower."
											</div>
										  </div>";
					}
				}
				else{
					$follow_button="";
				}
				
				?>
				<script>
					$(document).ready(function() {
							$('#follow_button<?php echo $userLoggedIn; ?><?php echo $i; ?>').click(function () {

							  var userToShow = '<?php echo $i; ?>';
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
										$("#follow_button<?php echo $userLoggedIn; ?><?php echo $i; ?>").html("Follow");
										$("#profile_follower_display_id").text(follower);
										//toast('Question Removed');
									 }
									else{
										$("#follow_button<?php echo $userLoggedIn; ?><?php echo $i; ?>").html("Unfollow");
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
				<?php

				$str .= " 	<div class='box'>
                                <a href='profile.php?profile_username=".$i."'>
                                    <div class='search_page_query_profile_pic'> 
                                        <img src='".$profile_pic."'>
                                    </div>

                                    <div class='profile_name'>
                                        ".$first_name." ".$last_name."
                                    </div>

                                    <div class='profile_username'>
                                        ".$i."
                                    </div>

                                    <div class='profile_follower'>
                                        ".$follower." Followers
                                    </div>
									
                                </a>
								<div class='profile_follower_button_cover'>
									$follow_button
								</div>
                            </div>
						";
			}

		}

		if($count > $limit) 
			$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
					 <input type='hidden' class='noMorePosts' value='false'>";
		else 
			$str .= "<input type='hidden' class='noMorePosts' value='true'>
						
					";
		
		echo $str;

	}
	
	public function getProfileTopic($data,$limit,$user_to_show){
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
		
		$str = ""; //String to return 
		
        $data_query = mysqli_query($this->con, "SELECT topic FROM users 
                                                WHERE username='$user_to_show'
												");
		$data_query_userLoggedIn = mysqli_query($this->con, "SELECT topic FROM users 
                                                WHERE username='$userLoggedIn'
												");
		
		$user_query_userLoggedIn_row = mysqli_fetch_array($data_query_userLoggedIn);
		$user_loggedin_topic_list = $user_query_userLoggedIn_row['topic'];

			
		$num_iterations = 0; //Number of results checked (not necasserily posted)
		$count = 1;
		
		
		$user_query_row = mysqli_fetch_array($data_query);
		$user_topic_list = $user_query_row['topic'];

		$user_topic_list_explode = explode(",", $user_topic_list);
		
		$no_of_topic = sizeof($user_topic_list_explode) - 2;
		$header = "
					<div class='user_profile_top_header'>
						<h2>
							$no_of_topic Topic
						</h2>
				    </div>
					<hr>
				  ";
		
		$str.= $header;

		foreach($user_topic_list_explode as $i) {
			
			$original_i = $i;
			
			if($num_iterations++ < $start)
					continue; 

				//Once 10 posts have been loaded, break
				if($count > $limit) {
					break;
				}
				else {
					$count++;
				}

			
			if($i != ""){
				
				$i = str_replace(' ','_',$i);
				
				$topic_details_i_query = mysqli_query($this->con, "SELECT * FROM topic 
                                                WHERE name='$i'
												");
				$topic_details_i_row = mysqli_fetch_array($topic_details_i_query);
				$topic_id = $topic_details_i_row['id'];
				$topic_pic = $topic_details_i_row['topic_pic'];
				$followers = $topic_details_i_row['followers'];
				$question = $topic_details_i_row['question'];
				
				if(strstr($user_loggedin_topic_list,','.$i.',')){
					$follow_button = "<button class='followButton' name='followButton' id='followButtonOnProfile$topic_id$userLoggedIn'>Unfollow</button>";
				}
				else{
					$follow_button = "<button class='followButton' name='followButton' id='followButtonOnProfile$topic_id$userLoggedIn'>Follow</button>";
				}

			
				?>
				<script>
					$(document).ready(function() {
						
						$(document).on('click', '#followButtonOnProfile<?php echo $topic_id; ?><?php echo $userLoggedIn; ?>', function() { 
													
						var userLoggedIn = '<?php echo $userLoggedIn; ?>';
						
						var data = 'userFollowed='+ userLoggedIn + '&id=' + <?php echo $topic_id; ?>;

						 $.ajax({
								type:"POST",
								cache:false,
								url:"ajaxfile/ajax_topic_followed_onprofile.php",
								data:data,    // multiple data sent using ajax
								success: function (msg) {
									console.log("d");
									if(msg==0){
										$("#followButtonOnProfile<?php echo $topic_id; ?><?php echo $userLoggedIn; ?>").text("Follow");
									}
									else{
										$("#followButtonOnProfile<?php echo $topic_id; ?><?php echo $userLoggedIn; ?>").text("Unfollow");
									}
								},
								error: function() {
									alert('follow Failure');
								}
							 });

						});
	
					});
				</script>
				<?php

				$str .= " 	<div class='box'>
                                <a href='topic.php?topic=".$i."'>
                                    <div class='search_page_query_profile_pic'> 
                                        <img src='".$topic_pic."'>
                                    </div>

                                    <div class='profile_name'>
                                        ".$original_i."
                                    </div>
									
                                </a>
								<div class='profile_follower_button_cover'>
									$follow_button
								</div>
                            </div>
						";
			}

		}

		if($count > $limit) 
			$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
					 <input type='hidden' class='noMorePosts' value='false'>";
		else 
			$str .= "<input type='hidden' class='noMorePosts' value='true'>
						
					";
		
		echo $str;
	}
	
	public function getBookmark($data,$limit){
		$page = $data['page']; 
		$userLoggedIn =  $this->user_from;

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
		
		$str = ""; //String to return 
		$data_query_bookmark = mysqli_query($this->con, "SELECT * FROM bookmarks
												WHERE username = '$userLoggedIn'
												");
		//SELECT * FROM answer WHERE answer.date_added > DATE_SUB(CURDATE(), INTERVAL 90 DAY)");

        ?>
          <script>
			  var toasts = 0;

			function toast(content){
				toasts += 1;
				$("body").append(
					'<div class="toast" id="toast-'+toasts+'"></div>'
				);

				var toast = $("#toast-" + toasts);

				/* apply options */
				toast.html(content);

				toast.addClass("toast-opening");
				toast.css('bottom','20px');

				setTimeout(function(){
					toast.removeClass("toast-opening").addClass("toast-closing");
					setTimeout(function(){
						toast.remove();   
					},1000);

				},3000);
			}
			  $('[data-toast]').click(function() {
				var content = $(this).data("toast");
				toast(content);
			})
		  </script>
         <?php

			if(mysqli_num_rows($data_query_bookmark) > 0) {
				
				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;
				
				while($row_bookmark = mysqli_fetch_array($data_query_bookmark)) {
					
					$answer_id = $row_bookmark['answer_id'];
					
					$data_query_question = mysqli_query($this->con, "SELECT * FROM answer WHERE id='$answer_id'");				
					$row = mysqli_fetch_array($data_query_question);
					
					$question_id = $row['question_id'];

					$data_query_question = mysqli_query($this->con, "SELECT * FROM question WHERE id='$question_id'");
					$row_question = mysqli_fetch_array($data_query_question);

					$id = $row_question['id'];
					$question_body = $row_question['question_body'];
					$question_body_unedited = $question_body;
					$question_link = $row_question['question_link'];
					$posted_by = $row_question['posted_by'];
					$date_time = $row_question['date_added'];
					$question_topic = $row_question['topic'];

					$answer_body = $row['answer_body'];
					$answer_date_time = $row['date_added'];
					$answer_given_by = $row['given_by'];
					$answer_upvote = $row['upvote'];
					$answer_anonymous = $row['anonymous'];
					$thisUser = $this->user_from;
                    
                    $data_query_user = mysqli_query($this->con, "SELECT * FROM users WHERE username='$answer_given_by'");
					$row_user = mysqli_fetch_array($data_query_user);
                    
                    $user_first_name = $row_user['first_name'];
                    $user_last_name = $row_user['last_name'];
                    $user_profile_pic = $row_user['profile_pic'];
                    $user_follower = $row_user['follower'];
 
                    $data_query_user_details = mysqli_query($this->con, "SELECT description FROM user_details WHERE username='$answer_given_by'");
					$row_user_details = mysqli_fetch_array($data_query_user_details);
                    
                    $user_description = $row_user_details['description'];

					$question_body_unedited = str_replace("'","",$question_body_unedited);
					$question_body_unedited = str_replace('"',"",$question_body_unedited);
					
					$topics_to_show ="";

					?>
						<script> 
							
							
							$(document).ready(function() {
									$('#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userUpvoted='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_answer_upvote.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var result = $.parseJSON(msg);
											var upvotes = result[0] + ' Upvotes';
											$("#question_post_series_number_upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text(upvotes);
											if(result[1]==0){
											   $("#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Upvote");
											   }
											else{
												$("#upvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Upvoted");
												toast("Upvoted");
												
											}
										},
										error: function() {
											alert('Answer submission Failure');
										}
									  });

									});

									$('#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

									  //var question_id = $('#question_id_name').attr("value");
									  //var user = $('#userLoggedin').attr("value");
									  var thisUser = '<?php echo $thisUser; ?>';

									  var data = 'userDownvoted='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;
									  //console.log(data);
									  $.ajax({
										type:"POST",
										cache:false,
										url:"ajaxfile/ajax_answer_downvote.php",
										data:data,    // multiple data sent using ajax
										success: function (msg) {
											var result = $.parseJSON(msg);
											var downvotes = result[0] + ' Downvotes';
											$("#question_post_series_number_downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text(downvotes);
											if(result[1]==0){
											   $("#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Downvote");
											   }
											else{
												$("#downvote<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Downvoted");
											}
										},
										error: function() {
											alert('Submission Failure');
										}
									  });

									});

									$('#show_more<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

										  var thisUser = '<?php echo $thisUser; ?>';
										  var data = 'userLogin='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;

										  $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_answer_show.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												$("#hide_answer<?php echo $answer_id; ?>").hide();
												$("#show_answer<?php echo $answer_id; ?>").show();

											},
											error: function() {
												alert('Submission Failure');
											}

										  });
									});
									
									$('#add_bookmark<?php echo $thisUser; ?><?php echo $answer_id; ?>').click(function () {

										  var thisUser = '<?php echo $thisUser; ?>';
										  var data = 'userLoggedIn='+ thisUser + '&answer_id=' + <?php echo $answer_id; ?>;

										  $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_answer_bookmark.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												if(msg=='1'){
													toast("Bookmark Removed");
													$("#add_bookmark<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Add Bookmark");
												}
												else{
													toast("Bookmark Added");
													$("#add_bookmark<?php echo $thisUser; ?><?php echo $answer_id; ?>").text("Remove Bookmark");
												}
											},
											error: function() {
												alert('Bookmark Failure');
											}

										  });
									});
							});

						</script>

					<?php
					
					if($num_iterations++ < $start)
						continue; 
				
				    //Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}
					
					
					$question_topics = explode(",", $question_topic);
					foreach($question_topics as $i){
						if($i!=''){
							$topics_to_show .= "<div class='topicsAlreadyAddedInQuestion' id='topicsAlreadyAddedInQuestionId$id$i$userLoggedIn'>
													<button class='topicsAlreadyAddedInQuestionButton' id='topicsAlreadyAddedInQuestionButton$id$i$userLoggedIn'>x</button>
													".$i."													
												</div>";
							?>
								<script>
									 $(document).on('click', '#topicsAlreadyAddedInQuestionButton<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>', function() { 

										var userLoggedIn = '<?php echo $userLoggedIn; ?>';
										var topicDeleted = '<?php echo $i; ?>';
										var data = 'userDeleted='+ userLoggedIn + '&topicDeleted=' + topicDeleted + '&qid=' + <?php echo $id; ?>;

										 $.ajax({
											type:"POST",
											cache:false,
											url:"ajaxfile/ajax_topic_deleted_to_question.php",
											data:data,    // multiple data sent using ajax
											success: function (msg) {
												console.log(msg);
												$('#topicsAlreadyAddedInQuestionId<?php echo $id; ?><?php echo $i; ?><?php echo $userLoggedIn; ?>').hide();

											},
											error: function() {
												alert('Deleting Failure');
											}
										 });

									   });
									
								</script>
							<?php
						}
						
					}
                    
                    
					//Display number of views
					$check_num_views = mysqli_query($this->con, "SELECT * FROM answer WHERE id='$answer_id'");
					$num_views_row = mysqli_fetch_array($check_num_views);
					$num_views = $num_views_row['views'];
					$num_views =  $num_views . " Views";

					//Display numver of upvote
					$check_num_upvote = mysqli_query($this->con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id'");
					$num_upvote = mysqli_num_rows($check_num_upvote);
					$num_upvote = $num_upvote . " Upvotes";

					//Display numver of downvote
					$check_num_downvote = mysqli_query($this->con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id'");
					$num_downvote = mysqli_num_rows($check_num_downvote);
					$num_downvote = $num_downvote . " Downvotes";

					//Display numver of Comments
					$check_num_comment = mysqli_query($this->con, "SELECT * FROM comments WHERE answer_id='$answer_id'");
					$num_comment = mysqli_num_rows($check_num_comment);
					$num_comment = $num_comment . " Comments";
					
					
                    //Display link line
                    if($question_link != ""){
                        $question_link_line = "<div class='question_post_series_link'>
                                                    <a href='$question_link' target='_blank'>
                                                        <span uk-icon='link'></span>
                                                        $question_link
                                                    </a>
                                                </div>";
                    }
                    else{
                        $question_link_line = "";
                    }
					
					//Display who answered the question (user or anonymous)
					
					if($answer_anonymous == 'yes'){
						$user_profile_to_show = "<div class='question_post_series_link_a_tag'>
													<div class='question_post_series_user_added'>

														<div class='question_post_series_user_added_profile_pic'>
															<img src='assets/images/anonymous.jpg'>
														</div>
														<div class='question_post_series_user_added_details'>
															<div class='question_post_series_user_added_details_label'>
																Answered By
															</div>
															<div class='question_post_series_user_added_details_name'>
																Anonymous, <span id='username'>Hidden User</span>
															</div>
															<div class='question_post_series_user_added_details_description'>
																Details have been hidden according to <a href='policy.php' class='hidden_answer_policy'>privacy policy</a> but you can report this answer.
															</div>
														</div>				
														<div class='question_post_series_user_added_follow_button'>
														</div>

													</div>
												</div>";
					}
					else{
						$user_profile_to_show = "<div class='question_post_series_link_a_tag'>
													<a href='profile.php?profile_username=$answer_given_by'>
														<div class='question_post_series_user_added'>

															<div class='question_post_series_user_added_profile_pic'>
																<img src='$user_profile_pic'>
															</div>
															<div class='question_post_series_user_added_details'>
																<div class='question_post_series_user_added_details_label'>
																	Answered By
																</div>
																<div class='question_post_series_user_added_details_name'>
																	$user_first_name $user_last_name, <span id='username'>(@$answer_given_by)</span>
																	
																</div>
																<div class='question_post_series_user_added_details_description'>
																	$user_description
																</div>
															</div>				
															<div class='question_post_series_user_added_follow_button'>
															</div>

														</div>
													</a>
												</div>";
					}

					
					//Display bookmark button
					$bookmark_check_query = mysqli_query($this->con,"SELECT * FROM bookmarks WHERE answer_id='$answer_id' AND username='$userLoggedIn'");
					
					if(mysqli_num_rows($bookmark_check_query) == 0){
						$bookmark_button_text = "Add Bookmark";
					}
					else{
						$bookmark_button_text = "Remove Bookmark";
					}
					
					//Display the like button
					$check_num_likes_button = mysqli_query($this->con, "SELECT * FROM question_like WHERE question_id='$id' 
																		AND question_liked_by='$this->user_from'");
					if(mysqli_num_rows($check_num_likes_button)>0){
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_unlike$thisUser$id'>Unlike this question </button>";
					}
					else{
						$like_button_to_show = "<button type='button' class='LikeThisQuestion' id='question_like$thisUser$id'>Like this question </button>";
					}


					$check_answer_later_button = mysqli_query($this->con, "SELECT * FROM answer_later WHERE question_id='$id' 
																		AND user_added='$this->user_from'");
					if(mysqli_num_rows($check_answer_later_button)>0){
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' style='cursor:not-allowed;' > Qustion Added </button>";
					}
					else{
						$answer_later_button_to_show = "<button type='button' class='AnswerLater' id='answer_later$thisUser$id' data-toast='Question Added'> Answer This Later </button>";
					}

					//Check Upvoted or not
					$check_already_upvoted = mysqli_query($this->con, "SELECT * FROM answer_upvote WHERE answer_id='$answer_id' AND answer_upvoted_by='$thisUser'");
					$check_already_upvoted_num = mysqli_num_rows($check_already_upvoted);

					if($check_already_upvoted_num>0){
						$upvoted_or_not = "Upvoted";
					}
					else {
						$upvoted_or_not = "Upvote";
					}

					//Check downvoted or not
					$check_already_downvoted = mysqli_query($this->con, "SELECT * FROM answer_downvote WHERE answer_id='$answer_id' AND answer_downvoted_by='$thisUser'");
					$check_already_downvoted_num = mysqli_num_rows($check_already_downvoted);

					if($check_already_upvoted_num>0){
						$downvoted_or_not = "Downvoted";
					}
					else {
						$downvoted_or_not = "Downvote";
					}

					while(strpos($answer_body,'style="')!=NULL){

						$var1='style="';
						$var2='"';
						$pool=$answer_body;
							$temp1 = strpos($pool,$var1)+strlen($var1);
							$result = substr($pool,$temp1,strlen($pool));
							$dd=strpos($result,$var2);
							if($dd == 0){
								$dd = strlen($result);
							}
						$font_tag_to_remove = substr($result,0,$dd);
						$answer_body = str_replace($font_tag_to_remove,"",$answer_body);
						$answer_body = str_replace('style=""',"",$answer_body);
					}

						$var1='<';
						$var2='>';
						$pool=$answer_body;
							$temp1 = strpos($pool,$var1)+strlen($var1);
							$result = substr($pool,$temp1,strlen($pool));
							$dd=strpos($result,$var2);
							if($dd == 0){
								$dd = strlen($result);
							}
						$font_tag_to_remove = substr($result,0,$dd);
						$answer_body = str_replace($font_tag_to_remove,"",$answer_body);
						$answer_body = str_replace('<>',"",$answer_body);

					//$answer_first_letter = substr($answer_body,0,1);
					//$answer_first_letter = "<div style='font-family: Georgia,serif;font-size: 33px;line-height: 23px;font-weight:bolder;float:left;'>".
								  // "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$answer_first_letter."</div>";

					//$answer_body = substr($answer_body,1);
					//$answer_body = "<" . $font_tag_to_remove . ">" . $answer_first_letter . $answer_body;

					$answer_body_hidden = "<div class='newsfeed_edited_text_hidden' style='font-family: Georgia,serif;font-size: 17px;line-height: 30px; height:43px;'>".$answer_body."</div>";
					$answer_body = "<div class='newsfeed_edited_text' style='font-family: Georgia,serif;font-size: 17px;line-height: 30px;'>".
								   $answer_body."</div>";



					//Timeframe
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
							$days = " ago";
						}
						else if($interval->d == 1) {
							$days = $interval->d . " day ago";
						}
						else {
							$days = $interval->d . " days ago";
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

					$question_body = 'Q:-&nbsp;&nbsp;' . $question_body;
					$time_message = "Posted ". $time_message;
					$str .= "
								<div class='question_post_series'>

									<div class='question_post_series_question_and_deletebutton newsfeed_question'>
										<div class='question_post_series_question'>
											<a href='search.php?q=$question_body_unedited&qid=$id'>$question_body</a>
										</div>

										<div class='question_post_series_deletebutton questionExtraMenu'>

											<div uk-icon='menu' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>

											<div uk-drop='mode: click'>
												<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
													<ul class='questionUlDesign'>
														<a href='#'><li type='button' uk-toggle='target: #question_add_topic$id'>Add topics</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #report_question$id'>Report</li></a>
													</ul>
												</div>
											</div>

										</div>


										<div id='question_add_topic$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Add some topic</h2>

												<h5>Q:-".$question_body_unedited."</h5>

												<div class='questionAddedTopicsInModal$id$userLoggedIn'>
													".$topics_to_show."
												</div>

												<div class='load_topic_form'>
													<form action='ajax_load_topic_box.php' method='POST' class='load_topic_form_form'>	
														<input type='text' name='topix_text' placeholder='Search a Topic to add.. (Ex:- Science, Technology, etc) ' onkeyup='getLiveTopic(this.value, ".'"'.$userLoggedIn.'"'.",".'"add"'.", ".'"'.$id.'"'.")'
															   autocomplete='off' id='id_topix_text' style='width:100%; height: 30px;background-color: #e9eef3;' class='load_topic_form_form_input'>
													</form>



													<div class='search_topic_results'>
													</div>
												</div>

												<div class='uk-modal-footer uk-text-right'>
													<p class='uk-text-right'>
														<button class='uk-button uk-button-default uk-modal-close' type='button'>DONE</button>
													</p>
												</div>
											</div>
										</div>

										<div class='uk-alert-warning hide_question$id' uk-alert style='display:none'>
											<a class='uk-alert-close' uk-close></a>
											<p style='margin-left:10px;font-size:17px'>You will stop seeing question related to<br>Q:-".$question_body_unedited."</p>
										</div>

										<div class='changeQuestion' id='change_question$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Change Question</h2>
												<h4>From:-</h4>
												<h5>Q:-".$question_body_unedited."</h5>
												<h4>To:-</h4>

												<form action='question.php' method='post'>
													<input type='text' name='change_question_question' value='$question_body_unedited'>
													<input type='hidden' name='change_question_id' value='$id'>
													<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
													<div class='uk-modal-footer uk-text-right'>
													<p class='uk-text-right'>
														<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
														<input class='uk-button uk-button-primary' type='submit' name='question_change_button' id='change_question'>
													</p>
												</div>
												</form>


											</div>
										</div>

										<div class='report_question' id='report_question$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Report this Question</h2>
												<h5>Q:-".$question_body_unedited."</h5>


												<form class='reportquestion' method='post'>
													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;' checked>Inappropriate Question</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Spelling Mistake or Grammatical Error</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Questoin Typed In Language other than English</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Abusive Words</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Sexual Intimation</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Marketing Stuff From Unauthorized User</label>



													<input type='hidden' name='change_question_id' value='$id'>
													<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
													<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
															<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
														</p>
													</div>
												</form>


											</div>
										</div>

									</div>

									$question_link_line

									$user_profile_to_show

									<div id='hide_answer$answer_id'>
										<div class='question_post_series_answer_hidden'>
											$answer_body_hidden
										</div>
										<div class='question_post_series_answer_hidden_button'>
											<button type='button' class='ShowMore' id='show_more$thisUser$answer_id'>
												Show more...
											</button>
										</div>
									</div>


									<div id='show_answer$answer_id'  style='display:none;'>
										<div class='question_post_series_answer'>
											$answer_body
										</div>

										<div class='question_post_series_number'>
											<div class='question_post_series_number_views' id='question_post_series_number_views$thisUser$answer_id'>
												$num_views
											</div>
											<div class='question_post_series_number_upvote' id='question_post_series_number_upvote$thisUser$answer_id'>
												$num_upvote
											</div>
											<div class='question_post_series_number_downvote' id='question_post_series_number_downvote$thisUser$answer_id'>
												$num_downvote
											</div>
											<div class='question_post_series_number_comment' id='question_post_series_number_comment'>
												$num_comment
											</div>
										</div>

										<div class='question_post_series_button'>
											 <button type='button' class='UpvoteAnswer' style='background-color: #bb3030;color:#fff;height:30px;width:90px;margin-right: 8px;' id='upvote$thisUser$answer_id'>
												$upvoted_or_not
											</button>
											<button type='button' class='DownvoteAnswer' style='background-color: #b7b7b7;
											color: #827c7c;width: 102px; margin-right: 8px;height:30px;' id='downvote$thisUser$answer_id'>
												$downvoted_or_not
											</button>

											<div uk-icon='more' class='questionExtraMenu' id='questionExtraMenu$id' onclick='showQuestionExtraMenu()' style='margin-top:10px' class='profile_icon_dropbtn uk-button uk-button-default'></div>

											<div uk-drop='mode: click'>
												<div class='uk-card uk-card-body uk-card-default questionUlDesignDiv'>
													<ul class='questionUlDesign'>
														<a href='#'><li type='button' id='add_bookmark$thisUser$answer_id'>$bookmark_button_text</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #change_question$id'>Edit this Question</li></a>
														<hr>
														<a href='#'><li type='button' uk-toggle='target: #report_answer$id'>Report this Answer</li></a>
													</ul>
												</div>
											</div>
										</div>

										<div class='report_answer' id='report_answer$id' uk-modal>
											<div class='uk-modal-dialog uk-modal-body'>
												<h2 class='uk-modal-title'>Report this Answer</h2>

												<form class='reportquestion' method='post'>
													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;' checked>Inappropriate Answer not relevant to Question</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Spelling Mistake or Grammatical Error</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Answer Typed In Language other than English</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Sarcastic answer with no Sincererity</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Abusive Words</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Sexual Intimation</label>

													<label><input class='uk-radio' type='radio' name='radio2' style='outline:0;margin-bottom: 6px;margin-right: 10px;'>Contain Marketing Stuff From Unauthorized User</label>



													<input type='hidden' name='change_question_id' value='$id'>
													<input type='hidden' name='userLoggedIn' value='$userLoggedIn'>
													<div class='uk-modal-footer uk-text-right'>
														<p class='uk-text-right'>
															<button class='uk-button uk-button-default uk-modal-close' type='button'>Close</button>
															<input class='uk-button uk-button-primary' type='submit' name='question_report_button' id='report_question'>
														</p>
													</div>
												</form>


											</div>
										</div>

										<div class='question_post_series_comment'>
											<iframe src='comment_frame.php?answer_id=$answer_id' id='answer_comment_iframe' frameborder='0'></iframe>
										</div>

									</div>	
								</div>


							<hr>

							";



				} //End while loop
				
				if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more question to load</h5>";
				
				
			}
		else{
			$str = "<h5 style='text-align: centre;'>You havn't saved any bookmarks</h5>";
		}
            //$str = $str .'<h5>No more question to load</h5>';
			echo $str;
	
	}
}
?>
