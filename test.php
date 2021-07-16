<?php
include("header.php");
?>
<div class='test'></div>
<script>
	$(document).ready(function() {

		$.ajax({
		   type:"POST",
		   cache:false,
		   url:"ajaxfile/ajax_test.php",
		   success: function (msg) {
			   console.log(msg);
			  $(".test").append(msg);
		   },
		   error: function() {
			  alert('Failure');
		   }
		  });

	});
</script>