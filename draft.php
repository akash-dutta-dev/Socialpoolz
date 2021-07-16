<?php
include("header.php");
?>
        <div class="header_cover" style="width:100%;height:50px;">
        </div>
        
        <div class="question_left_panel">
            <h5>Question Options</h5>
            <hr align="center" width="50%">
            <ul class="question_left_panel_ul">
				<a href="question.php"> <li class="question_left_panel_li question_tablinks question_activ">All Question</li></a>
                <a href="answerLater.php"><li class="question_left_panel_li question_tablinks" >Answer Later</li></a>
                <a href="likedQuestion.php"><li class="question_left_panel_li question_tablinks" >Liked Question</li></a>
                <a href="yourQuestion.php"><li class="question_left_panel_li question_tablinks">Your Question</li></a>
                <a href="questionAnsweredByYou.php"><li class="question_left_panel_li question_tablinks">Question answered by you</li></a>
                <a href="draft.php"><li class="question_left_panel_li question_tablinks" style="color:#6da5ff;">Draft</li></a>
            </ul>
            
        </div>

        <div class="question_main main_colum">
            
            
            <div id="Draft" class="question_tabcontent">
				<div class="posts_area"></div>
				<img id="loading" src="assets/images/loading.gif" style="width:100px;">
            </div>
            
        </div>

		<script>
			//<div class="posts_area"></div>
			//		<img id="loading" src="assets/images/loading.gif" style="width:100px;">
			var userLoggedIn = '<?php echo $userLoggedIn; ?>';

			$(document).ready(function() {

				$('#loading').show();

				//Original ajax request for loading first posts 
				$.ajax({
					url: "ajaxfile/ajax_if_getDraft.php",
					type: "POST",
					data: "page=1&userLoggedIn=" + userLoggedIn,
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
							url: "ajaxfile/ajax_if_getDraft.php",
							type: "POST",
							data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
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
  
    
     
