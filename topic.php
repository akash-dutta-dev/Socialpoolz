<?php
include("header.php");

if(isset($_GET['topic'])){
	$topic_name = $_GET['topic'];
	
	$query = mysqli_query($con,"SELECT * FROM topic WHERE name='$topic_name'");
	$row = mysqli_fetch_array($query);
	$topic_pic = $row['topic_pic']; 
	
}

?>


        <div class="header_cover" style="width:100%;height:50px;">
        </div>

        <div class="main_body">

			<div class="question_left_panel">
				<h5>Your Feeds</h5>
				<hr align="center" width="50%">
				<ul class="question_left_panel_ul">
					<a href="index.php"> <li class="question_left_panel_li question_tablinks question_activ">Top Answers</li></a>
					<a href="bookmark.php"> <li class="question_left_panel_li question_tablinks question_activ">Bookmark</li></a>
					<a href="javascript:void(0);" onclick="getTopicBox('<?php echo $userLoggedIn; ?>')">
						<li class="question_left_panel_li question_tablinks" >Topics</li>
					</a>

				</ul>

				<div class="topic_dropdown_window" style="height:0px; border:none;">
				</div>
				
				<script>
					
				
					$(document).on("click",function(e){
						
						var div1 = $(".topic_dropdown_window");
						var div2 = $(".load_topic_form_form_input");
						var div3 = $(".followButton");
						
						if(!div1.is(e.target)  && !div2.is(e.target) && !div3.is(e.target)){							
								$(".topic_dropdown_window").css("padding", "0px");
							$(".topic_dropdown_window").css("height", "0px");
							$(".topic_dropdown_window").css("border", "none");
							$(".topic_dropdown_window").html("");					
						}
						
					});
				
				</script>
				
				<div class="topic_user" style="border:none;">
					<?php
						
						$user_query = mysqli_query($con,"SELECT topic from users WHERE username='$userLoggedIn'");
						$user_query_row = mysqli_fetch_array($user_query);
						$user_topic_array = $user_query_row['topic'];
					
						$user_topic_array_explode = explode(",", $user_topic_array);

						//$query = mysqli_query($con, "SELECT friend_array FROM users WHERE username='$user_to_check'");
						//$row = mysqli_fetch_array($query);
						//$user_to_check_array = $row['friend_array'];
						//$user_to_check_array_explode = explode(",", $user_to_check_array);
						
						
					
						foreach($user_topic_array_explode as $i) {
							
							$i_to_show = $i;
							$i = str_replace(" ","_",$i);
							
							$close_button = "<button class='topic_close_btn' id='topic_close$i$userLoggedIn'>x</button>";
							if($i != ""){
							
							?>
							<script>
								$(document).ready(function() {
									$('#topic_close<?php echo $i; ?><?php echo $userLoggedIn; ?>').click(function () {

										var user = '<?php echo $userLoggedIn; ?>';
								   	    var topic_name_before = '<?php echo $i; ?>';
										var topic_name = topic_name_before.replace(/_/g, " ");
										var data = 'user='+ user + '&topic_name=' + topic_name;
											  
										$.ajax({
										   type:"POST",
										   cache:false,
										   url:"ajaxfile/ajax_remove_topic.php",
										   data:data,    // multiple data sent using ajax
										   success: function (msg) {
											  $("#topic_list<?php echo $i; ?>").remove();
										   },
										   error: function() {
											  alert('topic removal Failure');
										   }
										  });

										});
									});
							</script>
								<?php
								
							echo "<div class='topic_user_list' id='topic_list$i'>
							     ".$close_button."<a href='topic.php?topic=".$i."'>".$i_to_show."</a>
								 <br>
								 <br>
								 </div>
								 ";
							}
								
						}
					?>
				</div>
				
			</div>

			<div class="question_main main_colum">   
				<div  class="question_tabcontent">
					<div class='topic_header'>
						<div class='topic_header_image'>
							<img src='<?php echo $topic_pic ?>' class='topic_header_image_img'>
						</div>
						<div class='topic_header_text'>
							<h2><?php echo $topic_name; ?></h2>
						</div>
					</div>
					<div class="posts_area"></div>
					<img id="loading" src="assets/images/loading.gif" style="width:100px;">
				</div>
			</div>
            
		</div>

		<script>
			var userLoggedIn = '<?php echo $userLoggedIn; ?>';
			var topic = '<?php echo $topic_name; ?>';

			$(document).ready(function() {

				$('#loading').show();

				//Original ajax request for loading first posts 
				$.ajax({
					url: "ajaxfile/ajax_if_getTopicFeed.php",
					type: "POST",
					data: "page=1&userLoggedIn=" + userLoggedIn + "&topic=" + topic,
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
							url: "ajaxfile/ajax_if_getTopicFeed.php",
							type: "POST",
							data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&topic=" + topic,
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


    </body>
</html>