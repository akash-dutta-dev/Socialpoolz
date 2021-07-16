<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'vendor/autoload.php';
require 'config/config.php';


			$fname = $_POST['reg_fname'];
			$lname = $_POST['reg_lname'];
	        $em = $_POST['reg_email'];
			$em2 = $_POST['reg_email2']; 
			$rand_otp = $_POST['user_id'];
			$username = $_POST['reg_username'];
			$password = $_POST['reg_password']; //Remove html tags
			$password2 = $_POST['reg_password2']; //Remove html tags
			$error="";
	
			$fullname = $fname." ".$lname;
			
			
	
	
			if($em == $em2) {
				//Check if email is in valid format 
				if(filter_var($em, FILTER_VALIDATE_EMAIL)) {

					$em = filter_var($em, FILTER_VALIDATE_EMAIL);

					//Check if email already exists 
					$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

					//Count the number of rows returned
					$num_rows = mysqli_num_rows($e_check);

					if($num_rows > 0) {
						$error="thereiserror";
					}

				}
				else {
					$error="thereiserror";
				}


			}
	
			else {
					$error="thereiserror";
					
				}

			$u_check = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
			$num_rows = mysqli_num_rows($u_check);

					if($num_rows > 0) {
						$error="thereiserror";
					}

			if(strlen($fname) > 25 || strlen($fname) < 2) {
				$error="thereiserror";
			}

			if(strlen($lname) > 25 || strlen($lname) < 2) {
				$error="thereiserror";
			}

			if($password != $password2) {
				$error="thereiserror";
			}
			
			if(strlen($password > 30 || strlen($password) < 5)) {
				$error="thereiserror";
			}

			if(preg_match('/[^A-Za-z0-9_*]/', $username)) {
			$error="thereiserror";
			}

	
			
			if($error=="") {
				
				$mail = new PHPMailer(true);  
				try {
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
					$mail->Subject = 'Password Verification';
					$mail->Body    = '<h3>Hello '.$fname.',</h3><br>
									  Welcome to <b>SocialPoolz</b><br><br>
									  Your <b>OTP</b>&nbsp;&nbsp;&nbsp;<b>'.$rand_otp.'</b>';

					$mail->AltBody = 'Hello '.$fname.',
									  Welcome to SocialPoolz.
									  Your OTP'.$rand_otp;

					$mail->send();
					//echo 'Message has been sent';
				
				}catch (Exception $e) {
			//echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
				}
			
			
			//array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>");
			} 
?>