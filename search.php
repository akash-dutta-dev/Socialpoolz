<?php

require 'config/config.php';
include ("handlers/register_handler.php");
include ("handlers/question_post.php");


if(isset($_SESSION['username']))
{
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con,"SELECT * FROM users WHERE username = '$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
}
else
{
	header("Location: register.php");
}

                
			if(isset($_GET['qid'])) {
				//$question = $_GET['q'];
                $qid = strip_tags($_GET['qid']);
				$question_query = mysqli_query($con,"SELECT * FROM question WHERE id=$qid");
				$question_row = mysqli_fetch_array($question_query);
				
				$user_fname = $user['first_name'];
				$thisUser = $userLoggedIn;

				$q_body = $question_row['question_body'];

				echo "<!DOCTYPE html>
					  <html>
						<head>
							<title>$q_body</title>
						";
				
			}
			else{
				//header("Location: index.php");
			}	

?>


        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!--CSS-->
        <link rel="stylesheet" href="assets/css/style.css" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
            
        <!--JS-->
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>   
        <script src="assets/js/bootstrap.js"></script>
	    <script src="assets/js/bootbox.min.js"></script>
        <script src="assets/js/socialpoolz.js"></script>
        
		
        <script>
        function openCity(evt, cityName) {
            var i,tablinks;
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            evt.currentTarget.className += " active";
        }
        </script>
				
				<script>
					document.getElementById("#search_text_input").style.height = "17px";
				</script>
				
        
		
    </head>
    
    <body>
        
        <div class="header_bar">
            
            <div class="header_bar_logo" style="padding-top:0px;font-weight:100;">
                <h2 style="font-weight:100;">SocialPoolz</h2>
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
					<?php if(isset($_GET['qid'])){ ?>
                    <input type="text" onkeyup="getLiveSearch(this.value, '<?php echo $userLoggedIn; ?>')" name="r" placeholder="Search a question here..." autocomplete="off" id="search_text_input" style='height:15px' required>
                    <?php } ?>
					
					<?php if(isset($_GET['r'])){ ?>
                    <input type="text" onkeyup="getLiveSearch(this.value, '<?php echo $userLoggedIn; ?>')" name="r" placeholder="Search a question here..." autocomplete="off" id="search_text_input" style='height:37px' required>
                    <?php } ?>
					
                    <div class="button_holder">
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
                <button type="button" class="ask_question" data-toggle="modal" data-target="#post_question"> Ask Question </button>
            </div>

            
            <div class="header_bar_profile">
				<div class="profile_icon_dropdown">
				<img src="<?php echo $user['profile_pic']; ?>" onclick="myFunction()" class="profile_icon_dropbtn">
				  <div id="profile_icon_myDropdown" class="profile_icon_dropdown-content">
					<a href="profile.php?profile_username=<?php echo $userLoggedIn; ?>">Profile</a>
					
					<a href="about.php">About</a>
					<a href="handlers/logout.php">Logout</a>
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
            
            
            
        <!-- Modal -->
        <div class="modal fade" id="post_question" tabindex="-1" role="dialog" aria-labelledby="questionModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    
                  <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Question</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                    
                  <div class="modal-body">
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
                      
                  </div>
                    
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" name="question_button" id="question_button">Ask this Question</button>
                  </div>
                </div>
              </div>
            </div>        

		
        <div class="header_cover" style="width:100%;height:50px;">
        </div>
		
		
		<?php
		if(isset($_GET['qid'])){
		?>
        <div class="search_main_feed">   
			<div class="search_main_feed_question">
				<?php
					echo 'Q:- ' . $question_row['question_body'];
					
					$total_answer_query = mysqli_query($con,"SELECT * FROM answer WHERE question_id = $qid");
					$total_answer_query_num = mysqli_num_rows($total_answer_query);
					$total_answer_query_num = 'Total ' . $total_answer_query_num . ' answers';
					echo "<div class='search_total_answer'>$total_answer_query_num</div>";
				?>
			</div>
			<hr>
			<script> 
						function toggle<?php echo $qid; ?>() {
							
						var target = $(event.target);
							if (!target.is("a")) {
								var element = document.getElementById("toggleAnswer<?php echo $qid; ?>");

								if(element.style.display == "block") 									
									element.style.display = "none";
								else 
									element.style.display = "block";
							}
						}
						
							$(document).ready(function() {
								$('#question_like<?php echo $thisUser; ?><?php echo $qid; ?>').click(function () {
									
								  //var question_id = $('#question_id_name').attr("value");
								  //var user = $('#userLoggedin').attr("value");
								  var thisUser = '<?php echo $thisUser; ?>';

								  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $qid; ?>;
								  //console.log(data);
								  $.ajax({
									type:"POST",
									cache:false,
									url:"ajaxfile/ajax_question_like.php",
									data:data,    // multiple data sent using ajax
									success: function (msg) {
										var likes = msg + ' Likes';
									    $("#question_like_shows<?php echo $thisUser; ?><?php echo $qid; ?>").text(likes);
										location.reload();
										//$("#question_like<?php echo $thisUser; ?><?php echo $qid; ?>").text("Unlike this question");
										
										//$("#question_like<?php echo $thisUser; ?><?php echo $qid; ?>").attr("id","question_unlike<?php echo $thisUser; ?><?php //echo $id; ?>");
										
									},
									error: function() {
										alert('Answer submission Failure');
									}
								  });

								});
								
								
								$('#question_unlike<?php echo $thisUser; ?><?php echo $qid; ?>').click(function () {
									
								  //var question_id = $('#question_id_name').attr("value");
								  //var user = $('#userLoggedin').attr("value");
								  var thisUser = '<?php echo $thisUser; ?>';

								  var data = 'userLiked='+ thisUser + '&id=' + <?php echo $qid; ?>;
								  //console.log(data);
								  $.ajax({
									type:"POST",
									cache:false,
									url:"ajaxfile/ajax_question_unlike.php",
									data:data,    // multiple data sent using ajax
									success: function (msg) {
										var likes = msg + ' Likes';
									    $("#question_like_shows<?php echo $thisUser; ?><?php echo $qid; ?>").text(likes);
										location.reload();
										
										
									},
									error: function() {
										alert('Answer submission Failure');
									}
								  });

								});
								
								$('#answer_later<?php echo $thisUser; ?><?php echo $qid; ?>').click(function () {
									
								  //var question_id = $('#question_id_name').attr("value");
								  //var user = $('#userLoggedin').attr("value");
								  var thisUser = '<?php echo $thisUser; ?>';

								  var data = 'userClicked='+ thisUser + '&id=' + <?php echo $qid; ?>;
								  //console.log(data);
								  $.ajax({
									type:"POST",
									cache:false,
									url:"ajaxfile/ajax_question_answer_later.php",
									data:data,    // multiple data sent using ajax
									success: function (msg) {
									    var content = $('[data-toast]').data("toast");
            							toast(content);
										$("#answer_later<?php echo $thisUser; ?><?php echo $qid; ?>").text('Question Added');
										$("#answer_later<?php echo $thisUser; ?><?php echo $qid; ?>").attr("disabled", "disabled");
										$("#answer_later<?php echo $thisUser; ?><?php echo $qid; ?>").css("cursor", "not-allowed");
										
									},
									error: function() {
										alert('Answer submission Failure');
									}
								  });

								});
							});
						

					</script>
			
			<?php
				$line_to_show = $user_fname . ', are you sure that you can answer this question.';
				$answer_query = mysqli_query($con,"SELECT * FROM answer WHERE question_id = $qid");
				$num_answer_query = mysqli_num_rows($answer_query);
				if($num_answer_query == 0){
					echo "
						<div class='search_main_feed_no_answer'>
							<div class='search_main_feed_text'>
								$line_to_show
							</div>
							
							<button type='button' class='AddAnswer' style='background-color: #bb3030;color: #fff;'  onClick='javascript:toggle$qid()'>
								Add a answer 
							</button>
									
							<div class='question_post_answer' id='toggleAnswer$qid' style='display:none;'>
								<iframe src='answer_frame.php?question_id=$qid' id='question_answer_iframe' frameborder='0'></iframe>
							</div>
						</div>
					";
				}
				
				else{
					   echo '<div class="posts_area"></div>
					         <img id="loading" src="assets/images/loading.gif" style="width:100px;">
					         ';
				}
			?>
        </div>
        <script>
			var userLoggedIn = '<?php echo $userLoggedIn; ?>';
			var qid = '<?php echo $qid; ?>';

			$(document).ready(function() {

				$('#loading').show();

				//Original ajax request for loading first posts 
				$.ajax({
					url: "ajaxfile/ajax_if_getSearchAnswer.php",
					type: "POST",
					data: "page=1&userLoggedIn=" + userLoggedIn + "&qid=" + qid,
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
							url: "ajaxfile/ajax_if_getSearchAnswer.php",
							type: "POST",
							data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&qid=" + qid,
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
        
		<?php
		}
		?>
		
		<?php
		if(isset($_GET['r'])){
            $search = strip_tags($_GET['r']);
		?>
            <div class='search_page_header'>
                <h2>Showing  results for '<?php echo $search; ?>'</h2>
            </div>
            <div class='notification_page_cover'>
                <div  class="question_tabcontent search_page_question" id="search_page_question" style="margin-top:0px">
                    <div class="search_page_question_area"></div>
                    <img id="loading" src="assets/images/loading.gif" style="width:100px;">
                </div>

                <div  class="question_tabcontent search_page_profile" style="margin-top:0px;">
                    <div class="search_page_profile_area"></div>
                    <img id="loading_profile" src="assets/images/loading.gif" style="width:100px;">
                </div>
            </div>
            <script>
                var userLoggedIn = '<?php echo $userLoggedIn; ?>';
                var search = '<?php echo $search; ?>';
				var scroll_question =500;
				var scroll_profile = 500;
                
                $(document).ready(function() {

                    $('#loading').show();

                    //Original ajax request for loading first posts 
                    $.ajax({
                        url: "ajaxfile/ajax_if_get_searched_question.php",
                        type: "POST",
                        data: "page=1&userLoggedIn=" + userLoggedIn + "&search=" + search,
                        cache:false,

                        success: function(data) {
                            $('#loading').hide();
                            $('.search_page_question_area').html(data);
                        }
                    });
                    

                    $(window).scroll(function() {
                        var height = $('.search_page_question_area').height(); //Div containing posts
                        var scroll_top = $(this).scrollTop();
                        var page = $('.search_page_question_area').find('.nextPage').val();
                        var noMorePosts = $('.search_page_question_area').find('.noMorePosts').val();
                        
                        if ((document.body.scrollTop>scroll_question) 
                            && noMorePosts == 'false') {
                            
							scroll_question = scroll_question+500;
							
                            $('#loading').show();

                            var ajaxReq = $.ajax({
                                url: "ajaxfile/ajax_if_get_searched_question.php",
                                type: "POST",
                                data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&search=" + search,
                                cache:false,

                                success: function(response) {
                                    $('.search_page_question_area').find('.nextPage').remove(); //Removes current .nextpage 
                                    $('.search_page_question_area').find('.noMorePosts').remove(); //Removes current .nextpage 

                                    $('#loading').hide();
                                    $('.search_page_question_area').append(response);
                                }
                            });

                        } //End if 
                        

                        return false;

                    }); //End (window).scroll(function())
                        
                    
                    $('#loading_profile').show();
                    //Original ajax request for loading first posts 
                    $.ajax({
                        url: "ajaxfile/ajax_if_get_searched_profile.php",
                        type: "POST",
                        data: "page=1&userLoggedIn=" + userLoggedIn + "&search=" + search,
                        cache:false,

                        success: function(data) {
                            $('#loading_profile').hide();
                            $('.search_page_profile_area').html(data);
                        }
                    });

                    $(window).scroll(function() {
                        var height = $('.search_page_profile_area').height(); //Div containing posts
                        var scroll_top = $(this).scrollTop();
                        var page = $('.search_page_profile_area').find('.nextPage').val();
                        var noMorePosts = $('.search_page_profile_area').find('.noMorePosts').val();

                        if ((document.body.scrollTop>scroll_profile) 
                            && noMorePosts == 'false') {
							
							scroll_profile = scroll_profile+500;
                            $('#loading_profile').show();

                            var ajaxReq = $.ajax({
                                url: "ajaxfile/ajax_if_get_searched_profile.php",
                                type: "POST",
                                data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&search=" + search,
                                cache:false,

                                success: function(response) {
                                    $('.search_page_profile_area').find('.nextPage').remove(); //Removes current .nextpage 
                                    $('.search_page_profile_area').find('.noMorePosts').remove(); //Removes current .nextpage 

                                    $('#loading_profile').hide();
                                    $('.search_page_profile_area').append(response);
                                }
                            });

                        } //End if 

                        return false;

                    }); //End (window).scroll(function())

                });

            </script>
		<?php
		}
		?>
		
		
      


      
    </body>
</html>
  
    
     
