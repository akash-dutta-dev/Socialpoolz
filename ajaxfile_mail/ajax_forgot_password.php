<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require '../vendor/autoload.php';
require '../config/config.php';

$mail = new PHPMailer(true);  
try {
			$email = $_POST['fp_link_email'];
			$email = strip_tags($email);
	
			$email_query = mysqli_query($con,"SELECT first_name,last_name,profile_pic,username FROM users WHERE email='$email'");
		
			if(mysqli_num_rows($email_query) > 0){
				
				$email_row = mysqli_fetch_array($email_query);
				$first_name = $email_row['first_name'];
				$last_name = $email_row['last_name'];
				$profile_pic = $email_row['profile_pic'];
				$username = $email_row['username'];
				
				$fullname = $first_name." ".$last_name;
                                $mail->AddEmbeddedImage("../".$profile_pic , "profile_pic", "../".$profile_pic);
                                $mail->AddEmbeddedImage("../assets/images/logo.jpg", "logo", "../assets/images/logo.jpg");

				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$randstring = '';
				for ($i = 0; $i < 128; $i++) {
					$randstring .= $characters[rand(0, strlen($characters)-1)];
				}

				$encrypt_mail = md5($email);
				$resetLink = 'https:www.socialpoolz.com/forgotPassword.php?en='.$randstring.'&encrypt_mail='.$encrypt_mail.'&uname='.$username.'&v_code=asdfqwer123';
				
				$query = mysqli_query($con,"INSERT INTO forgot_password VALUES('','$randstring','$email','$username')");

				$mail->SMTPDebug = 2;                                
				$mail->Host = 'relay-hosting.secureserver.net'; 
				$mail->SMTPSecure = "none";
				$mail->SMTPAuth = false;                               
				$mail->Username = 'admin@socialpoolz.com';
				$mail->Password = 'AkashCse@1997'; 
				$mail->Port = 25;

				$mail->setFrom('admin@socialpoolz.com','Socialpoolz');
				$mail->addAddress($email,$fullname);

				//$mail->addReplyTo('info@example.com', 'Information');
				//$mail->addCC('cc12@example.com');
				//$mail->addBCC('bcc12@example.com');

				//Content
				$mail->isHTML(true);                                 
				$mail->Subject = 'SocialPoolz Reset Password';
				$mail->Body    = "
							<html>
								<head>
								<style>
								.email_body{
									background-color:#dae8ee;
								}
								.email_forgot_password_container p{
									font-family: Arial,sans-serif;
									font-size: 20px;
								}
								.email_forgot_password_container h4{
									font-family: Arial,sans-serif;
									font-size: 20px;
								}
								.email_forgot_password_container hr {
								  	display: block;
									height: 1px;
									border: 0;
									border-top: 1px solid #ccc;
									margin: 1em 0;
									padding: 0; 
								}
								.email_forgot_password_container{
									margin: auto;
									width: 35%;
									background-color: #fff;
									min-width: 400px;
								}
								.email_forgot_password_container a{
									word-break: break-all;
								}
								.email_forgot_password_header{
									width: 80%;
									height: 90px;
									padding: 40px;
									padding-top: 20px;
									padding-bottom: 0px;
									background-color: #fffefe;
								}
								.email_forgot_password_header_left{
									height:75px;
								}
								.email_forgot_password_header_left img{
									height: 75px;
								}
								.email_forgot_password_header_left {
								   float: left;          
								}
								.email_forgot_password_header_right{
									float: right;
									display: inline-flex;
								}
								.email_forgot_password_header_right img{

									height: 70px;
									border-radius: 35px;
								}
								.email_forgot_password_header_right p{
									padding-top: 9px;
									margin-right: 15px;
									color: #707373;
									font-weight: bolder;
									font-size: 20px;
								}
								.email_forgot_password_main_body{
									padding: 45px;
									padding-top: 0px;
									padding-bottom: 10px;
								}
								</style>
								</head>
								<body class='email_body'>
									<div class='email_forgot_password_container'>
										<div class='email_forgot_password_header'>
											<div class='email_forgot_password_header_left'>
												<img src='cid:logo'>
											</div>
											<div class='email_forgot_password_header_right'>
												<p>$fullname</p>
												<img src='cid:profile_pic'>
											</div>
										</div>
										<hr>
										<div class='email_forgot_password_main_body'>
											<h4>Hi, $first_name</h4>
											<p>Reset your password, and we'll get you on your way.

											<p>To change your SocialPoolz password, click here or paste the following link into your browser:</p>

											<a href='".$resetLink."'>$resetLink</a>

											<p>Thank you for using SocialPoolz!</p>

											<p>The SocialPoolz Team</p>
										</div>
									</div>
								</body>
							</html>";

				$mail->AltBody = 'To activate click this <a href="'.$resetLink.'">link</a>';

				$mail->send();
				echo 'Email has been sent, check your mail.';
			
			}
			else{
				echo "<span style='color:red'>Email does not exist in our record.<span>";
			}
			//array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>");
		} catch (Exception $e) {
			//echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
?>