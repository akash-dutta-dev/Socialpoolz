<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'vendor/autoload.php';
require '../config/config.php';

$mail = new PHPMailer(true);  
try {
			$question_id = $_POST['question_id'];
			$answer_body = $_POST['answer_body'];
	        $user = $_POST['user'];
	
			$question_query = mysqli_query($con,"SELECT * FROM question WHERE id='$question_id'");
			$question_query_row = mysqli_fetch_array($question_query);
			$username = $question_query_row['posted_by'];
			$qbody = $question_query_row['question_body'];
	
			$user_query =  mysqli_query($con,"SELECT * FROM users WHERE username='$username'");
			$user_query_row = mysqli_fetch_array($user_query);
			$fname = $user_query_row['first_name'];
			$lname = $user_query_row['last_name'];
			$em = $user_query_row['email'];
	
			$fullname = $fname." ".$lname;
			
			$answer_body = strip_tags($answer_body);
			
			if(strlen($answer_body)>60){
				$answer_body = substr($answer_body,0,60);
				$answer_body = $answer_body . "...(<a href='http://www.socialpoolz.com/search.php?qid=".$question_id."'>Show More</a>)";
			}
			else{
				$answer_body = $answer_body . "...(<a href='http://www.socialpoolz.com/search.php?qid=".$question_id."'>Show More</a>)";
			}
			
			$mail->SMTPDebug = 2;                                
			$mail->Host = 'relay-hosting.secureserver.net'; 
			$mail->SMTPSecure = "none";
			$mail->SMTPAuth = false;                               
			$mail->Username = 'admin@socialpoolz.com';
			$mail->Password = 'AkashCse@1997'; 
			$mail->Port = 25;

			$mail->setFrom('admin@socialpoolz.com','Socialpoolz');
			$mail->addAddress($em,$fullname);

			//$mail->addReplyTo('info@example.com', 'Information');
			//$mail->addCC('cc12@example.com');
			//$mail->addBCC('bcc12@example.com');

			//Content
			$mail->isHTML(true);                                 
			$mail->Subject = 'Q:-'.$qbody;
			$mail->Body    = 'Hello <h3>'.$fullname.',</h3><br>
							  Your question has been answered.<br><br>
							  <b>Q:-'.$qbody.' </b><br>
							  '.$answer_body.'<br><br>
							  Hope you like the answer.';
			
			$mail->AltBody = 'Hello '.$fullname.',
							  Your question has been answered.
							  Q:-'.$qbody.' 
							  '.$answer_body.'
							  Hope you like the answer.';

			$mail->send();
			//echo 'Message has been sent';
			
			
			//array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>");
		} catch (Exception $e) {
			//echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
?>