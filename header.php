<?php  
require 'config/config.php';
include ("handlers/register_handler.php");
include ("handlers/question_post.php");
include ("handlers/question_handler.php");

ini_set('max_execution_time', 300);

if(isset($_SESSION['username']))
{
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con,"SELECT * FROM users WHERE username = '$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
	
	$user_extra_details_query = mysqli_query($con,"SELECT * FROM user_details WHERE username = '$userLoggedIn'");
	$user_extra_details = mysqli_fetch_array($user_extra_details_query);
}
else
{
	header("Location: register.php");
}

$is_online = $user['is_online'];
$is_online = 'yes';
$is_online = mysqli_query($con,"UPDATE users SET is_online='$is_online' WHERE username = '$userLoggedIn'");

?>
<html>
    
    <head>
        <title>
            SocialPoolz
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!--CSS-->
        <link rel="stylesheet" href="assets/css/style.css" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="assets/css/uikit.css">
		<link rel="stylesheet" type="text/css" href="assets/css/uikit-rtl.css">
		<link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />
		<link rel="shortcut icon" href="assets/images/logo.jpg" />
            
        <!--JS-->
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>   
        <script src="assets/js/bootstrap.js"></script>
	    <script src="assets/js/bootbox.min.js"></script>
        <script src="assets/js/socialpoolz.js"></script>
		<script src="assets/js/uikit.min.js"></script>
		<script src="assets/js/uikit-icons.min.js"></script>
		<script src="assets/js/jquery.Jcrop.js"></script>
		<script src="assets/js/jcrop_bits.js"></script>
        
		
        <script>
			var userLoggedIn = '<?php echo $userLoggedIn; ?>'
			setInterval(function() {
				
				$.ajax({
                        type: "POST",
                        url: "ajaxfile/ajax_fetch_unread_noti.php",
						data: 'userLoggedIn=' + userLoggedIn,
                        success: function(msg) {
							if(msg>0){
								//$("#unread_notification").toggleClass("notification_badge");
								$("#unread_notification").show();
								$("#unread_notification").text(msg);
							}
                        },
                        error: function() {
                          //  alert('Notification Fetch Failure');
                        }
                      });
			}, 5 * 1000);
			
            function openCity(evt, cityName) {
                var i,tablinks;
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                evt.currentTarget.className += " active";
            }
			

			
			$(document).ready(function(){         

                var validNavigation = false;
				var user = '<?php echo $userLoggedIn; ?>';
				var data ='user='+user;


				 
                window.onhashchange = function() {
                    validNavigation = true;
                }
                // Attach the event keypress to exclude the F5 refresh (includes normal refresh)
                $(document).bind('keypress', function(e) {
                    if (e.keyCode == 116){
                        validNavigation = true;
                    }
                });

                // Attach the event click for all links in the page
                $("a").bind("click", function() {
                    validNavigation = true;
                });

                // Attach the event submit for all forms in the page
                $("form").bind("submit", function() {
                  validNavigation = true;
                });

                // Attach the event click for all inputs in the page
                $("input[type=submit]").bind("click", function() {
                  validNavigation = true;
                }); 

               window.addEventListener("beforeunload", function (e) { 

                    if (!validNavigation) {     
                        $.ajax({
                        type: "POST",
                        url: "ajaxfile/ajax_closing_window.php",
                        data: data,
                        success: function(msg) {

                            //alert('Closing Failure');

                        },
                        error: function() {
                            alert('Closing Failure');
                        }
                      });
                    }
                });
                $('.logout').click(function () {              
                    if (!validNavigation) {     
                        $.ajax({
                        type: "POST",
                        url: "ajaxfile/ajax_closing_window.php",
                        data: data,
                        success: function(msg) {

                            alert('Closing Failure');

                        },
                        error: function() {
                            alert('Closing Failure');
                        }
                      });
                    }
                });

          });
        </script>
        
    </head>
    
    <body>
		<?php
		if(strlen($user['topic']) < 10){
			echo "  <script>
						setTimeout(function() {
							$('#survey1').modal('show');
						}, 2000);
					</script>
				 ";
		}
		else{
			
		}

		?>
        
        <div class="header_bar">
            
            <div class="header_bar_logo">
				<a href="index.php"><h2>SocialPoolz</h2></a>
            </div>
  
            <?php
				//Unread notifications 
				$query = mysqli_query($con, "SELECT * FROM notification WHERE viewed='no' AND user_to_show='$userLoggedIn'");
				$num_notifications = mysqli_num_rows($query);
				
			?>
			   
            <div class="header_bar_tabs">
                <div class="tab">
                    <a href="index.php"><button class="tablinks" onclick="openCity(event)">Home</button></a>
                    <a href="question.php"><button class="tablinks" onclick="openCity(event)">Question</button></a>
					<a href="javascript:void(0);" onclick="getNotification('<?php echo $userLoggedIn; ?>')">
						<button class="tablinks">Notification</button>
						<?php
							if($num_notifications > 0)
							 echo '<span class="notification_badge" id="unread_notification">' . $num_notifications . '</span>';
							else
							 echo '<span class="notification_badge_hidden" id="unread_notification">' . $num_notifications . '</span>';
						?>
					</a>
				
                </div>
				<div class="notification_dropdown_window" style="height:0px; border:none;">
				</div>
				<script>
				
					$(document).on("click",function(e){
						var $div = $(".notification_dropdown_window");
						if(!$div.is(e.target)){
							$(".notification_dropdown_window").css("padding", "0px");
							$(".notification_dropdown_window").css("height", "0px");
							$(".notification_dropdown_window").css("border", "none");
							$(".notification_dropdown_window").html("");
						}
					});
				
				</script>

            </div>
			
			
            
            <div class="header_bar_search">
                <form action="search.php" method="get" name="search_form">
                    <input type="text" onkeyup="getLiveSearch(this.value, '<?php echo $userLoggedIn; ?>')" name="r" placeholder="Search a question here..." autocomplete="off" id="search_text_input" required>
                    
                    <div class="button_holder" >
                        <img class="button_holder_button" src="assets/images/search_button.jpg">
                    </div>
 
                </form>
                
                                    
                <div class="search_results" style="display:none;">
                </div>
                    
                <div class="search_results_footer_empty">
                </div>
				
				<script>
				
					$(document).on("click",function(e){
						var $div = $(".search_results_footer");
						if(!$div.is(e.target)){
							$('.search_results_footer').html("");
							$('.search_results_footer').toggleClass("search_results_footer_empty");
							$('.search_results_footer').toggleClass("search_results_footer");
							$('.search_results').css({"display": "none"});
						}
					});
				
				</script>
            </div>
            
            <div class="header_bar_question">
                <button type="button" class="ask_question" uk-toggle="target: #ask-question"> Ask Question </button>
            </div>

            
            <div class="header_bar_profile">
				<div class="profile_icon_dropdown">
				<img src="<?php echo $user['profile_pic']; ?>" onclick="myFunction()" class="profile_icon_dropbtn">
				  <div id="profile_icon_myDropdown" class="profile_icon_dropdown-content">
					<a href="profile.php?profile_username=<?php echo $userLoggedIn; ?>">Profile</a>
					
					<a href="about.php">About</a>
					<span class='logout'><a href="handlers/logout.php">Logout</a></span>
				  </div>
					
				  <div class='online_dot_header'>
				  </div>
					
				</div>
		
            </div>
				<script>
				/* When the user clicks on the button, 
				toggle between hiding and showing the dropdown content */
				function myFunction() {
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
				</script>


        </div>
            
            
            
         
		<!--Ask Question Modals-->
			<div id="ask-question" uk-modal>
				<div class="uk-modal-dialog uk-modal-body">
					<?php 
                      if($user['question']==0)
                      {
                           echo '<div class="first_time_user">
                                    <h4>Because this is your first question.</h4>
                                    <br>
                                    Remember these points:
                                    <br>
                                    &#9658; Keep your question short and simple
                                    <br>
                                    &#9658; Simple question get faster result
                                    <br>
                                    &#9658; Check for grammatical error
                                    <br>
                                    &#9658; Avoid spelling mistake
                                    
                                 </div>';
                      }
                      ?>
                      
                      <div class="question_user_details">
                         <img src="<?php echo $user['profile_pic']; ?>">
                          <h6><?php echo $user['first_name'].' added'; ?></h6>
                      </div>
                      
                      <form class="question_post" action="" method="post">
                          <div class="form-group">
                              <textarea class="question_box" name="question_body" placeholder="Ask Your Question Here ??"></textarea>
                              <p>Add a link(optional).</p>
                              <input type="text" name="question_link" placeholder="Add any external link.">
                              <p>Add some tags</p>
                              <input type="text" name="question_tags" placeholder="Seperate by comma(Example Sports,Game,Athlete)">
                              <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
                          </div>
                      </form>
					<p class="uk-text-right">
						<button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
						<button class="uk-button uk-button-primary" type="button" name="question_button" id="question_button">Ask this Question</button>
					</p>
				</div>
			</div>
		
		<!--Survey1-->
            <div class="modal fade" id="survey1" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true" style="border-radius: 			0px;width: 52%;padding: 10px;">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header">
                      <h3 class="modal-title" id="exampleModalLabel">Choose some topic ( atleast 5 to continue )</h3>
                    </div>
					
				    <div class="survey_modal_body">
						<div class="survey_modal_body_selected_topic">
						</div>
						<div class="survey_modal_body_search topic">
							
							<div class="load_topic_form">
								<form action="ajax_load_topic_box.php" method="POST" class="load_topic_form_form">	
									<input type="text" name="topix_text" placeholder="Search a Topic here.. (Ex:- Science, Technology, etc) " onkeyup="getLiveTopic(this.value, '<?php echo $userLoggedIn; ?>')"
										   autocomplete="off" id="id_topix_text" style="width:90%; height: 30px;background-color: #e9eef3;margin-left:15px" class="load_topic_form_form_input">
								</form>

								<div class="search_topic_results_footer_empty" style = "height:230px;width:101%;overflow: scroll;">
									<?php
						
										$usersReturnedQuery = mysqli_query($con, "SELECT * FROM topic ORDER BY id LIMIT 20");

												while($row = mysqli_fetch_array($usersReturnedQuery)) {
													$newstr = $row['name'];
													$id = $row['id'];
													$topic_pic = $row['topic_pic'];

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

													$user_query = mysqli_query($con,"SELECT * FROM users WHERE username = '$userLoggedIn'");
													$user_query_array = mysqli_fetch_array($user_query);
													$user_topic = $user_query_array['topic'];

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

									?>
								</div>
							</div>
							
						</div>
						
					</div>     
                    
						<p class="uk-text-right">
							<button class="uk-button uk-button-default uk-modal-close" data-dismiss="modal">Close</button>
							<button class="uk-button uk-button-primary" type="button" data-toggle="modal" data-dismiss="modal" data-target="#survey2">Next</button>
						</p>
					
                </div>
              </div>
            </div>  	
		
		<!--Survey2-->
            <div class="modal fade" id="survey2" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true" style="border-radius: 			0px;width: 50%;padding: 10px;">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header">
                      <h3 class="modal-title" id="exampleModalLabel">Select from the option</h3>
                    </div>
					
				    <div class="survey_modal_body">
						<form class='form_survey2' action="" method="POST">
							<div>
								<input type='text' class="survey_inputs" name='form_input_user_country' placeholder='Country'  id='user_country' onkeyup="getPopUpValues(this.value, '<?php echo $userLoggedIn; ?>','z_country',1,'user_country')" autocomplete='off' style='margin-top: 30px;margin-bottom: 5px;'>
							</div>

							<div class="searched_info_results1 popUpBox" style="height: 0;border:none;">
							</div>

							<div>
								<input type='text' class="survey_inputs" name='form_input_user_state' placeholder='State'  id='user_state' onkeyup="getPopUpValues(this.value, '<?php echo $userLoggedIn; ?>','z_state',2,'user_state')" autocomplete='off' style='margin-top: 30px;margin-bottom: 5px;'>
							</div>

							<div class="searched_info_results2 popUpBox" style="height: 0;border:none;">
							</div>

							<div>
								<input type='text' class="survey_inputs" name='form_input_user_city' placeholder='City'  id='user_cities' onkeyup="getPopUpValues(this.value, '<?php echo $userLoggedIn; ?>','z_cities',3,'user_cities')" autocomplete="off" style='margin-top: 30px;margin-bottom: 5px;'>
							</div>

							<div class="searched_info_results3 popUpBox" style="height: 0;border:none;">
							</div>
							<input type='hidden' name='userLoggedInSurvey2' value='<?php echo $userLoggedIn; ?>'>	
						</form>
					</div>     
                    
						<p class="uk-text-right">
							<button class="uk-button uk-button-default uk-modal-close" data-dismiss="modal">Close</button>
							<button class="uk-button uk-button-primary" type="button" id='survey2_submit' data-toggle="modal" data-dismiss="modal" data-target="#survey3">Next</button>
						</p>
					
                </div>
              </div>
            </div>  
		<!--Survey3-->
            <div class="modal fade" id="survey3" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true" style="border-radius: 			0px;width: 50%;padding: 10px;">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header">
                      <h3 class="modal-title" id="exampleModalLabel">Select your profession</h3>
                    </div>
					
				    <div class="survey_modal_body" style='padding:25px'>
						<form class='form_survey3' action="" method="POST">
						
							<!--School-->
							<label><input class='uk-radio ' id='thirdid1' type='radio' name='form_input_profession' style='outline:0;margin-bottom: 6px;margin-right: 10px;' onchange="collapseHide('third',1,5)" value="school">School</label>	

								<div class="third1" id="form_input_school_collapse"   style='display:none'>
									<div class="servey_input_box">
										<div>
											<input type='text' class="survey_inputs" name='form_input_school_name' placeholder='School Name'  id='school_name' onkeyup="getPopUpValues(this.value, '<?php echo $userLoggedIn; ?>','z_school',1,'school_name')">
										</div>
										
										<div class="searched_info_results1 popUpBox" style="height: 0;border:none;">
										</div>
										
										<div>
											<!--<input type='number' class="survey_inputs" name='form_input_school_standard' placeholder='Standard (Ex 4-12)'>-->
											<select class="survey_inputs" name="form_input_school_standard">
												<option value="sdefault">Current Studying Standard</option>
												<option value="sb4">Below 4th standard</option>
												<option value="s4">4th standard</option>
												<option value="s5">5th standard</option>
												<option value="s6">6th standard</option>
												<option value="s7">7th standard</option>
												<option value="s8">8th standard</option>
												<option value="s9">9th standard</option>
												<option value="s10">10th standard</option>
												<option value="s11">11th standard</option>
												<option value="s12">12th standard</option>
											</select>
										</div>
									</div>
								</div>

							<!--College-->
							<label><input class='uk-radio ' id='thirdid2' type='radio' name='form_input_profession' style='outline:0;margin-bottom: 6px;margin-right: 10px;' onchange="collapseHide('third',2,5)" value="college">College/University</label>

								<div class="third2" id="form_input_college_collapse"   style='display:none'>
								  <div class="servey_input_box">
										<div>
											<input type='text' class="survey_inputs" name='form_input_college_name' placeholder='University Name' onkeyup="getPopUpValues(this.value, '<?php echo $userLoggedIn; ?>','z_college','2','college_name')" id='college_name'>
										</div>
									  
									    <div class="searched_info_results2 popUpBox" style="height: 0;border:none;">
										</div>
									  
										<div>
											<input type='text' class="survey_inputs" name='form_input_college_course' placeholder='Your Course' onkeyup="getPopUpValues(this.value, '<?php echo $userLoggedIn; ?>','course','3')">
										</div>
									   
										<div>
											<!--<input type='number' class="survey_inputs" name='form_input_college_standard' placeholder='Year'>-->

										<select class="survey_inputs" name="form_input_college_standard">
												<option value="cdefault">Current Studying Year</option>
												<option value="c1">1st year</option>
												<option value="c2">2nd year</option>
												<option value="c3">3rd year</option>
												<option value="c4">4th year</option>
												<option value="c5">5th year (for B.Arch students)</option>
										</select>
										</div>
										<div>
											<input type='text' class="survey_inputs" name='form_input_college_location' placeholder='University Location' onkeyup="getPopUpValues(this.value, '<?php echo $userLoggedIn; ?>','z_cities','4','college_location')" id="college_location">
										</div>
									  
									    <div class="searched_info_results4 popUpBox" style="height: 0;border:none;">
										</div>
									  
									</div>
								</div>

							<!--Working-->
							<label><input class='uk-radio ' id='thirdid3' type='radio' name='form_input_profession' style='outline:0;margin-bottom: 6px;margin-right: 10px;'  onchange="collapseHide('third',3,5)" value="working">Working</label>
								<div class="third3" id="form_input_working_collapse"   style='display:none'>
								  <div class="servey_input_box">
										<div>
											<input type='text' class="survey_inputs" name='form_input_working_company_name' placeholder='Company Name' onkeyup="getPopUpValues(this.value, '<?php echo $userLoggedIn; ?>','z_company','5','company_name')" id="company_name">
										</div>
									    
									    <div class="searched_info_results5 popUpBox" style="height: 0;border:none;">
										</div>
									  
										<div>
											<input type='text' class="survey_inputs" name='form_input_working_company_post' placeholder='Your Post (Ex- Manager,Developer,Adviser)'>
										</div>
										<div>
											<input type='text' class="survey_inputs" name='form_input_working_company_location' placeholder='Working Place (Ex- Bangalore,Chennai,etc)' onkeyup="getPopUpValues(this.value, '<?php echo $userLoggedIn; ?>','z_cities','6','company_location')" id="company_location">
										</div>
									  
									    <div class="searched_info_results6 popUpBox" style="height: 0;border:none;">
										</div>
									  
										<div>
											<!--<input type='number' class="survey_inputs" name='form_input_working_company_time' placeholder='Working Since..'>-->
											 <select class="survey_inputs" name="form_input_working_company_time" placeholder='Working Since..'>
												<option value="default">Working Since</option>
												<option value="wl1">Less than 1 year</option>
												<option value="w1">1 year</option>
												<option value="w2">2 year</option>
												<option value="w3">3 year</option>
												<option value="w4">4 year</option>
												<option value="w5">5 year</option>
												<option value="wm5">More than 5 year</option>
											  </select>
										</div>
									</div>
								</div>
							<!--Business-->
							<label><input class='uk-radio ' id='thirdid4' type='radio' name='form_input_profession' style='outline:0;margin-bottom: 6px;margin-right: 10px;'  onchange="collapseHide('third',4,5)" value="business">Business</label>
								<div class="third4" id="form_input_business_collapse"   style='display:none'>
								  <div class="servey_input_box">
										<div>
											<input type='text' class="survey_inputs" name='form_input_business_name' placeholder='Organization Name'>
										</div>
										<div>
											<input type='text' class="survey_inputs" name='form_input_business_location' placeholder='Organization Headquarter (Ex- Bangalore,Chennai,etc)' onkeyup="getPopUpValues(this.value, '<?php echo $userLoggedIn; ?>','z_cities','7','business_city')" id='business_city'>
										</div>
									  
									  	<div class="searched_info_results7 popUpBox" style="height: 0;border:none;">
										</div>									  
									  
										<div>
											<input type='text' class="survey_inputs" name='form_input_business_type' placeholder='Business Type (Ex- Service,Consultancy,etc)'>
										</div>
										<div>
											<!--<input type='number' class="survey_inputs" name='form_input_business_started' placeholder='Business Started since'>-->
											<select class="survey_inputs" name="form_input_business_started">
												<option value="default">Business Started Since</option>
												<option value="bl1">Less than 1 year</option>
												<option value="b1">1 year</option>
												<option value="b2">2 year</option>
												<option value="b3">3 year</option>
												<option value="b4">4 year</option>
												<option value="b5">5 year</option>
												<option value="bm5">More than 5 year</option>
											</select>
										</div>
									</div>
								</div>
							<!--Others-->
							<label><input class='uk-radio ' id='thirdid5' type='radio' name='form_input_profession' style='outline:0;margin-bottom: 6px;margin-right: 10px;'  onchange="collapseHide('third',5,5)" value="other">Other</label>
								<div class="third5" id="form_input_other_collapse"   style='display:none'>
								  <div class="servey_input_box">
										<div>
											<input type='text' class="survey_inputs" name='form_input_other_specification' placeholder='Please Specify(Ex- Freelancer,Local Shop,Plumber,etc)'>
										</div>
									</div>
								</div>
							
							<input type='hidden' name='userLoggedInSurvey3' value='<?php echo $userLoggedIn; ?>'>			
						</form>
						<script>
				
							$(document).on("click",function(e){
								var $div = $(".popUpBox");
								if(!$div.is(e.target)){
									$(".popUpBox").css("padding", "0px");
									$(".popUpBox").css("height", "0px");
									$(".popUpBox").css("border", "none");
									$(".popUpBox").html("");
								}
							});

						</script>
					</div>     
                    
						<p class="uk-text-right">
							
							<button class="uk-button uk-button-default uk-modal-close" data-dismiss="modal">Close</button>
							<button class="uk-button uk-button-primary" type="button" id='survey3_submit' data-toggle="modal" data-dismiss="modal" data-target="#survey4">Next</button>
						</p>
					
                </div>
              </div>
            </div>  
		
		<!--Survey4-->
            <div class="modal fade" id="survey4" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true" style="border-radius: 			0px;width: 50%;padding: 10px;">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header">
                      <h3 class="modal-title" id="exampleModalLabel">How often do you read Books?</h3>
                    </div>
					
				    <div class="survey_modal_body" style="padding:30px">
						
						<form class="form_survey4" action="" method="post">
						
							<label><input class='uk-radio' type='radio' name='read_level' style='outline:0;margin-bottom: 6px;margin-right: 10px;' value = '1'>Level 1 - Never, its way too boring.</label>

							<label><input class='uk-radio' type='radio' name='read_level' style='outline:0;margin-bottom: 6px;margin-right: 10px;' value = '2'>Level 2 - Read only during exams.</label>

							<label><input class='uk-radio' type='radio' name='read_level' style='outline:0;margin-bottom: 6px;margin-right: 10px;' value = '3'>Level 3 - Read a Complete Book in a week.</label>

							<label><input class='uk-radio' type='radio' name='read_level' style='outline:0;margin-bottom: 6px;margin-right: 10px;' value = '4'>Level 4 - Read everyday at least for once.</label>

							<label><input class='uk-radio' type='radio' name='read_level' style='outline:0;margin-bottom: 6px;margin-right: 10px;' value = '5'>Level 5 - I know every author and what book they have written.</label>
						
							<input type='hidden' name='userLoggedInSurvey4' value='<?php echo $userLoggedIn; ?>'>
							
						</form>
							
					</div>     
                    
						<p class="uk-text-right">
							<button class="uk-button uk-button-default uk-modal-close" data-dismiss="modal">Close</button>
							<button class="uk-button uk-button-primary" type="button" id='survey4_submit' data-toggle="modal" data-dismiss="modal" data-target="#survey5">Next</button>
						</p>
					
                </div>
              </div>
            </div>  
		
		<!--Survey5-->
            <div class="modal fade" id="survey5" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true" style="border-radius: 			0px;width: 50%;padding: 10px;">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                    <div class="modal-header">
                      <h3 class="modal-title" id="exampleModalLabel">Would you like to be an author in SocialPoolz?</h3>
                    </div>
					
				    <div class="survey_modal_body" style="padding:30px">
						<form class="form_survey5" action="" method="post">
						
							<label><input class='uk-radio' type='radio' name='author_level' style='outline:0;margin-bottom: 6px;margin-right: 10px;' value = '1'>No never</label>

							<label><input class='uk-radio' type='radio' name='author_level' style='outline:0;margin-bottom: 6px;margin-right: 10px;' value = '2'>I would like to be, but I have other work.</label>

							<label><input class='uk-radio' type='radio' name='author_level' style='outline:0;margin-bottom: 6px;margin-right: 10px;' value = '3'>Maybe, for Sometime.</label>

							<label><input class='uk-radio' type='radio' name='author_level' style='outline:0;margin-bottom: 6px;margin-right: 10px;' value = '4'>Yes, I would Love to be an author here</label>

							<label><input class='uk-radio' type='radio' name='author_level' style='outline:0;margin-bottom: 6px;margin-right: 10px;' value = '5'>I would like to an Author as well as a Member.</label>
						
							<input type='hidden' name='userLoggedInSurvey5' value='<?php echo $userLoggedIn; ?>'>
							
						</form>
						
					</div>     
                    
						<p class="uk-text-right">
							<!--<button class="uk-button uk-button-default uk-modal-close" data-dismiss="modal">Close</button>-->
							<button class="uk-button uk-button-primary" type="button" id='survey5_submit' data-toggle="modal" data-dismiss="modal">Finish</button>
						</p>
					
                </div>
              </div>
            </div>  
		
		
    </body>
</html>
    