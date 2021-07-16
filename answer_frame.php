<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title></title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/bootbox.min.js"></script>
	<script src="assets/js/socialpoolz.js"></script>
    <script type="text/javascript">
    var oDoc, sDefTxt;


    function initDoc() {
		
      oDoc = document.getElementById("textBox");
      sDefTxt = oDoc.innerHTML;
     if (document.compForm.switchMode.checked) { setDocMode(true); }
    }

    function formatDoc(sCmd, sValue) {
      if (validateMode()) { document.execCommand(sCmd, false, sValue); oDoc.focus(); }
    }

    function validateMode() {
      if (!document.compForm.switchMode.checked) { return true ; }
      alert("Uncheck \"Show HTML\".");
      oDoc.focus();
      return false;
    }

    function setDocMode(bToSource) {
      var oContent;
      if (bToSource) {
        oContent = document.createTextNode(oDoc.innerHTML);
        oDoc.innerHTML = "";
        var oPre = document.createElement("pre");
        oDoc.contentEditable = false;
        oPre.id = "sourceText";
        oPre.contentEditable = true;
        oPre.appendChild(oContent);
        oDoc.appendChild(oPre);
		  console.log(oContent);
		  
      } else {
        if (document.all) {
          oDoc.innerHTML = oDoc.innerText;
        } else {
          oContent = document.createRange();
          oContent.selectNodeContents(oDoc.firstChild);
          oDoc.innerHTML = oContent.toString();
        }
        oDoc.contentEditable = true;
      }
      oDoc.focus();
    }
		
	

    function printDoc() {
      if (!validateMode()) { return; }
      var oPrntWin = window.open("","_blank","width=450,height=470,left=400,top=100,menubar=yes,toolbar=no,location=no,scrollbars=yes");
      oPrntWin.document.open();
      oPrntWin.document.write("<!doctype html><html><head><title>Print<\/title><\/head><body onload=\"print();\">" + oDoc.innerHTML + "<\/body><\/html>");
      oPrntWin.document.close();
    }
    </script>
    <style type="text/css">
    .intLink { cursor: pointer; }
    img.intLink { border: 0; }
    #toolBar1 select { font-size:10px; }
    #textBox {
      width: 96%;
      height: 182px;
      border: none;
      padding: 12px;
      overflow:auto;
	  font-size: 20px;
	  outline:0px !important;
      -webkit-appearance:none;
    }
    #textBox #sourceText {
      padding: 0;
      margin: 0;
      min-width: 498px;
      min-height: 200px;
    }
    #editMode label { cursor: pointer; }
		
	[contenteditable=true]:empty:before{
    content: attr(placeholder);
    display: block; /* For Firefox */
	opacity:0.9;
	color:#a7a2a2;
		font:30px;
    }	
	#toolBar2{
		height: 35px;
		background-color: #e4dbdb;
	}
	#toolBar2 img{
		width: 30px;
		height: 30px;
		}
    </style>

</head>

<body onload="initDoc();">
  <?php  
	require 'config/config.php';
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

   ?>
	
	<script>
		function toggle() {
			var element = document.getElementById("comment_section");

			if(element.style.display == "block") 
				element.style.display = "none";
			else 
				element.style.display = "block";
		}
	</script>

	<?php  
	//Get id of post
	if(isset($_GET['question_id'])) {
		$question_id = $_GET['question_id'];
	}
	if(isset($_GET['answer_id'])) {
		$answer_id = $_GET['answer_id'];
		$answer_body_query = mysqli_query($con, "SELECT answer_body FROM answer WHERE id='$answer_id'");
		$answer_body_row = mysqli_fetch_array($answer_body_query);
		$answer_body = $answer_body_row['answer_body'];
	}
	else{
		$answer_body = "";
	}
/*
	if(isset($_POST['postAnswer'])) {
        $answer_body = $_POST['answer_body'];
	 	$question_id =$_POST['question_id'];
	    $answer_body = mysqli_escape_string($con, $answer_body);
		$date_time_now = date("Y-m-d H:i:s");
		$insert_post = mysqli_query($con, "INSERT INTO answer VALUES ('', '$answer_body', '$date_time_now', '$userLoggedIn', '$question_id','0' ,'no')");
		?>
	    <script>
			alert("Your answer posted successfully ! You can view you answered question in 'Question Answered By You' tab");
		</script>
        <?php
		
	}   */
	?>
	
	<script>
	$(document).ready(function() {
		$('#postAnswer').click(function () {
		

		  var answer_body = encodeURIComponent(oDoc.innerHTML);
		  var question_id = $('#question_id_name').attr("value");
		  var user = $('#userLoggedin').attr("value");
		  if(document.getElementById('anonymous_checkbox').checked){
			  var anonymous = 'yes';
		  }
		  else{
			  var anonymous = 'no';
		  }
		  console.log(anonymous);
		  var data = 'question_id='+ question_id   + '&answer_body='+ answer_body  + '&user='+ user  + '&anonymous='+ anonymous; // this where i add multiple data using  ' & '
           //alert(data);
		  $.ajax({
			type:"POST",
			cache:false,
			url:"ajaxfile/ajax_submit_answer.php",
			data:data,    // multiple data sent using ajax
			success: function (msg) {
              
				parent.location.reload();
			 
			},
			error: function() {
				alert('Answer submission Failure');
			}
		  });
			
			
			$.ajax({
			type:"POST",
			cache:false,
			url:"ajaxfile_mail/ajax_on_answering.php",
			data:data,    // multiple data sent using ajax
			success: function (msg) {

			},
			error: function() {
				
			}
		  });

		});
		
		
		$('#draftAnswer').click(function () {
		

		  var answer_body = encodeURIComponent(oDoc.innerHTML);
			//var answer_body_string = answer_body.toString()
          //var answer_body="\"hello<br>jello\"";
		  var question_id = $('#question_id_name').attr("value");
		  var user = $('#userLoggedin').attr("value");
          console.log(question_id);
			console.log(user);
			console.log(answer_body);
		  var data = 'question_id='+ question_id   + '&answer_body='+ answer_body  + '&user='+ user; // this where i add multiple data using  ' & '
           //alert(data);
		  $.ajax({
			type:"POST",
			cache:false,
			url:"ajaxfile/ajax_submit_draft.php",
			data:data,    // multiple data sent using ajax
			success: function (msg) {
              //alert("Your answer posted successfully to draft");
				//location.reload();
			  //console.log(msg);
			},
			error: function() {
				alert('Answer submission Failure');
			}
		  });
			

		});
		
		
	});
	</script>
	<div class="answer_frame_form">
	
		<form class="answer_post" name="compForm" method="post" action="answer_frame.php?question_id=<?php echo $question_id; ?>" onsubmit="if(validateMode()){this.myDoc.value=oDoc.innerHTML;return true;}return false;">

			
            <div class="text_area">
				<input type="hidden" name="myDoc">
				<!--
				<div id="toolBar1">
				<select onchange="formatDoc('formatblock',this[this.selectedIndex].value);this.selectedIndex=0;">
				<option selected>- formatting -</option>
				<option value="h1">Title 1 &lt;h1&gt;</option>
				<option value="h2">Title 2 &lt;h2&gt;</option>
				<option value="h3">Title 3 &lt;h3&gt;</option>
				<option value="h4">Title 4 &lt;h4&gt;</option>
				<option value="h5">Title 5 &lt;h5&gt;</option>
				<option value="h6">Subtitle &lt;h6&gt;</option>
				<option value="p">Paragraph &lt;p&gt;</option>
				<option value="pre">Preformatted &lt;pre&gt;</option>
				</select>
				<select onchange="formatDoc('fontname',this[this.selectedIndex].value);this.selectedIndex=0;">
				<option class="heading" selected>- font -</option>
				<option>Arial</option>
				<option>Arial Black</option>
				<option>Courier New</option>
				<option>Times New Roman</option>
				</select>
				<select onchange="formatDoc('fontsize',this[this.selectedIndex].value);this.selectedIndex=0;">
				<option class="heading" selected>- size -</option>
				<option value="1">Very small</option>
				<option value="2">A bit small</option>
				<option value="3">Normal</option>
				<option value="4">Medium-large</option>
				<option value="5">Big</option>
				<option value="6">Very big</option>
				<option value="7">Maximum</option>
				</select>
				<select onchange="formatDoc('forecolor',this[this.selectedIndex].value);this.selectedIndex=0;">
				<option class="heading" selected>- color -</option>
				<option value="red">Red</option>
				<option value="blue">Blue</option>
				<option value="green">Green</option>
				<option value="black">Black</option>
				</select>
				<select onchange="formatDoc('backcolor',this[this.selectedIndex].value);this.selectedIndex=0;">
				<option class="heading" selected>- background -</option>
				<option value="red">Red</option>
				<option value="green">Green</option>
				<option value="black">Black</option>
				</select>
				</div>
-->
				<div id="toolBar2">

				<img class="intLink" title="Bold" onclick="formatDoc('bold');" src="data:image/gif;base64,R0lGODlhFgAWAID/AMDAwAAAACH5BAEAAAAALAAAAAAWABYAQAInhI+pa+H9mJy0LhdgtrxzDG5WGFVk6aXqyk6Y9kXvKKNuLbb6zgMFADs=" />
				<img class="intLink" title="Italic" onclick="formatDoc('italic');" src="data:image/gif;base64,R0lGODlhFgAWAKEDAAAAAF9vj5WIbf///yH5BAEAAAMALAAAAAAWABYAAAIjnI+py+0Po5x0gXvruEKHrF2BB1YiCWgbMFIYpsbyTNd2UwAAOw==" />
				<img class="intLink" title="Underline" onclick="formatDoc('underline');" src="data:image/gif;base64,R0lGODlhFgAWAKECAAAAAF9vj////////yH5BAEAAAIALAAAAAAWABYAAAIrlI+py+0Po5zUgAsEzvEeL4Ea15EiJJ5PSqJmuwKBEKgxVuXWtun+DwxCCgA7" />
				<img class="intLink" title="Left align" onclick="formatDoc('justifyleft');" src="data:image/gif;base64,R0lGODlhFgAWAID/AMDAwAAAACH5BAEAAAAALAAAAAAWABYAQAIghI+py+0Po5y02ouz3jL4D4JMGELkGYxo+qzl4nKyXAAAOw==" />
				<img class="intLink" title="Center align" onclick="formatDoc('justifycenter');" src="data:image/gif;base64,R0lGODlhFgAWAID/AMDAwAAAACH5BAEAAAAALAAAAAAWABYAQAIfhI+py+0Po5y02ouz3jL4D4JOGI7kaZ5Bqn4sycVbAQA7" />
				<img class="intLink" title="Right align" onclick="formatDoc('justifyright');" src="data:image/gif;base64,R0lGODlhFgAWAID/AMDAwAAAACH5BAEAAAAALAAAAAAWABYAQAIghI+py+0Po5y02ouz3jL4D4JQGDLkGYxouqzl43JyVgAAOw==" />
				<img class="intLink" title="Numbered list" onclick="formatDoc('insertorderedlist');" src="data:image/gif;base64,R0lGODlhFgAWAMIGAAAAADljwliE35GjuaezxtHa7P///////yH5BAEAAAcALAAAAAAWABYAAAM2eLrc/jDKSespwjoRFvggCBUBoTFBeq6QIAysQnRHaEOzyaZ07Lu9lUBnC0UGQU1K52s6n5oEADs=" />
				<img class="intLink" title="Dotted list" onclick="formatDoc('insertunorderedlist');" src="data:image/gif;base64,R0lGODlhFgAWAMIGAAAAAB1ChF9vj1iE33mOrqezxv///////yH5BAEAAAcALAAAAAAWABYAAAMyeLrc/jDKSesppNhGRlBAKIZRERBbqm6YtnbfMY7lud64UwiuKnigGQliQuWOyKQykgAAOw==" />
				
				</div>
				<div id="textBox" contenteditable="true" placeholder="Enter your text here"><?php echo $answer_body; ?></div>
				
				<p id="editMode"><input type="checkbox" name="switchMode" id="switchBox" onchange="setDocMode(this.checked);" style="display:none;"/> 
					<label for="switchBox" style="display:none;">Show HTML</label>
				</p>
                
            </div>
			<input type="hidden" name="question_id_name" id="question_id_name" value="<?php echo $question_id; ?>">
			<input type="hidden" name="userLoggedin" id="userLoggedin" value="<?php echo $userLoggedIn; ?>">
			<input type="submit" id="postAnswer" value="Submit Answer">
			<input type="submit" id="draftAnswer" value="Save as Draft">
			<div class='answer_frame_form_checkbox'>
				<input type="checkbox" id="anonymous_checkbox" name="anonymous" value="yes"> <label>Answer Anonymously</label>
			</div>
            <script>
            function openBoldTab(evt) {
                var element = document.getElementById("answer_body");
                if(document.getElementById("boldTab").className == " answer_frame_form_tabs_img"){
                    evt.currentTarget.className += " answer_frame_form_tabs_active_img_active";
                    element.style.fontWeight = "bold";
                }
                else{
                    document.getElementById("boldTab").className  = " answer_frame_form_tabs_img";
                    element.style.fontWeight = "normal";
                }
            }
                
                function openItalicTab(evt) {
                var element = document.getElementById("answer_body");
                if(document.getElementById("italicTab").className == " answer_frame_form_tabs_img"){
                    evt.currentTarget.className += " answer_frame_form_tabs_active_img_active";
                    element.style.fontStyle = "italic";
                }
                else{
                    document.getElementById("italicTab").className  = " answer_frame_form_tabs_img";
                    element.style.fontStyle= "normal";
                }
            }
            </script>
		</form>
	</div>

	<!-- Load comments -->
	         


	
</body>
</html>