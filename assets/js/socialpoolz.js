$(document).ready(function() {
    
    //$("#post_question").modal('hide');
    $('#search_text_input').focus(function() {
		//if(window.matchMedia( "(min-width: 800px)" ).matches) {
		//	$(this).animate({width: '250px'}, 500);
		//}
	});

	$('.button_holder_button').on('click', function() {
		
		if(document.search_form.r.value != ""){
			document.search_form.submit();
		}

	});
    
	//Button for question post
	$('#question_button').click(function(){
		
		$.ajax({
			type: "POST",
			url: "ajaxfile/submitquestion.php",
			data: $('form.question_post').serialize(),
			success: function(msg) {
				$("#post_question").modal('hide');
				location.reload();
				
                //console.log(msg);
                
			},
			error: function() {
				alert('Failure');
			}
		});

	});
	
	//Button for answer post



});


function getLiveSearch(value, user) {

	$.post("ajaxfile/ajax_search.php", {query:value, userLoggedIn: user}, function(data) {

		if($(".search_results_footer_empty")[0]) {
			$(".search_results_footer_empty").toggleClass("search_results_footer");
			$(".search_results_footer_empty").toggleClass("search_results_footer_empty");
		}

		$('.search_results').html(data);
        $('.search_results').css({"display": "block"});
		$('.search_results_footer').html("<a href='search.php?r=" + value + "'>See All Results</a>");

		if(data == "") {
			$('.search_results_footer').html("");
			$('.search_results_footer').toggleClass("search_results_footer_empty");
			$('.search_results_footer').toggleClass("search_results_footer");
            $('.search_results').css({"display": "none"});
		}

	});

}


function getNotification(user) {

	if($(".notification_dropdown_window").css("height") == "0px") {
		
		$('#unread_notification').hide();
		
		var ajaxreq = $.ajax({
			url: "ajaxfile/ajax_load_notifications.php",
			type: "POST",
			data: "page=1&userLoggedIn=" + user,
			cache: false,

			success: function(response) {
				
				$(".notification_dropdown_window").html(response);
				$(".notification_dropdown_window").css({"width":"170%",
														"padding" : "0px",
														"height": "auto",
														"border" : "1px solid #DADADA",
													    "background-color":"#fff",
													    "margin-top":"12px"});
				
			}

		});

	}
	else {
		$(".notification_dropdown_window").html("");
		$(".notification_dropdown_window").css({"padding" : "0px", "height": "0px", "border" : "none"});
	}

}

function getTopicBox(user) {

	if($(".topic_dropdown_window").css("height") == "0px") {
		
		var ajaxreq = $.ajax({
			url: "ajaxfile/ajax_load_topic_box.php",
			type: "POST",
			data: "userLoggedIn=" + user,
			cache: false,

			success: function(response) {
				
				$(".topic_dropdown_window").html(response);
				$(".topic_dropdown_window").css({       "position":"absolute",
												        "height":"290px",
					                                    "width":"680px",
												 		"top":"20%",
												 		"left":"13%",
														"padding" : "10px",
														"border" : "1px solid #DADADA",
													    "background-color":"#fff"});
				
			}

		});

	}
	else{
		$(".topic_dropdown_window").html("");
		$(".topic_dropdown_window").css({"padding" : "0px", "height": "0px", "border" : "none"});
	}
	

}



function getLiveTopic(value, user,action,qid) {

	$.post("ajaxfile/ajax_search_topic.php", {query:value, userLoggedIn: user,action:action,questionId:qid}, function(data) {

		if($(".search_topic_results_footer_empty")[0]) {
			$(".search_topic_results_footer_empty").toggleClass("search_topic_results");
			$(".search_topic_results_footer_empty").toggleClass("search_topic_results_footer_empty");
		}

		$('.search_topic_results').html(data);
        $('.search_topic_results').css({"display": "block"});
		$('.search_topic_results').css({"height": "250px"});
		$('.search_topic_results_footer').html("<a href='#'>See All Results</a>");

		if(data == "") {
			$('.search_topic_results_footer').html("");
			$('.search_topic_results_footer').toggleClass("search_topic_results_footer_empty");
			$('.search_topic_results_footer').toggleClass("search_topic_results_footer");
            $('.search_topic_results').css({"display": "none"});
		}

	});

}

function getPopUpValues(value, user,table,input,box) {

	$.post("ajaxfile/ajax_search_other_info.php", {query:value, userLoggedIn: user,table:table,input:input,box:box}, function(data) {


		
        $('.searched_info_results'+input).css({"display": "block",
												"position": "absolute",
												"width": "55%",
												"left": "65px",
												"height": "auto",
												"border": "solid 1px #d6d6d6",
												"background-color": "#fff"});
		$('.searched_info_results'+input).html(data);

		if(data == "") {
			$('.searched_info_results'+input).html("");
            $('.searched_info_results'+input).css({"display": "none"});
		}

	});

}
function collapseHide(type,itemNo,totalItem) {
	var i;
	for (i = 1; i <= totalItem; i++) { 
		if(i !== itemNo){
			//$('.third'+i).collapse('hide');
			$('.third'+i).slideUp('slow');
		}else{
			$('.third'+i).slideDown('show');
			
		}
	}
}

$(document).ready(function() {

	$('#survey2_submit').click(function(){
		
		$.ajax({
			type: "POST",
			url: "ajaxfile/ajax_submit_survey2.php",
			data: $('form.form_survey2').serialize(),
			success: function(msg) {
				
                //console.log(msg);
                
			},
			error: function() {
				alert('Failure');
			}
		});

	});
	
	$('#survey3_submit').click(function(){
		
		$.ajax({
			type: "POST",
			url: "ajaxfile/ajax_submit_survey3.php",
			data: $('form.form_survey3').serialize(),
			success: function(msg) {
				
               // console.log(msg);
                
			},
			error: function() {
				alert('Failure');
			}
		});

	});
	
	$('#survey4_submit').click(function(){
		
		$.ajax({
			type: "POST",
			url: "ajaxfile/ajax_submit_survey4.php",
			data: $('form.form_survey4').serialize(),
			success: function(msg) {
				
                //console.log(msg);
                
			},
			error: function() {
				alert('Failure');
			}
		});

	});
	$('#survey5_submit').click(function(){
		
		$.ajax({
			type: "POST",
			url: "ajaxfile/ajax_submit_survey5.php",
			data: $('form.form_survey5').serialize(),
			success: function(msg) {
				
                //console.log(msg);
                
			},
			error: function() {
				alert('Failure');
			}
		});

	});
	
	$('#submit_profile_description').click(function(){

		$.ajax({
			type: "POST",
			url: "ajaxfile/ajax_submit_profile_description.php",
			data: $('form.profile_submit_description').serialize(),
			success: function(msg) {
			
				$("#profile_user_description_shown").text(msg);
				$("#profile_user_description_hidden").hide();
				$("#profile_user_description_shown").show();
				
			},
			error: function() {
				alert('Description submission failure.');
			}
		});

	});
	
	$("#changeDescriptionLink").click(function() {
		$("#profile_user_description_shown").hide(function(){
			$("#profile_user_description_hidden").show();
		});
	});
	
	$("#cancel_profile_description").click(function() {
		$("#profile_user_description_hidden").hide(function(){
			$("#profile_user_description_shown").show();
		});
	});

});

