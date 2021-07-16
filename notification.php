<?php
include("header.php");
?>
        <div class="header_cover" style="width:100%;height:50px;">
        </div>

        <div class='notification_page_cover'>
			<div  class="question_tabcontent notification_page">
				<div class="posts_area"></div>
				<img id="loading" src="assets/images/loading.gif" style="width:100px;">
			</div>
		</div>

		<script>
			var userLoggedIn = '<?php echo $userLoggedIn; ?>';

			$(document).ready(function() {

				$('#loading').show();

				//Original ajax request for loading first posts 
				$.ajax({
					url: "ajaxfile/ajax_if_getNotificationPage.php",
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
							url: "ajaxfile/ajax_if_getNotificationPage.php",
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
  
    
     
