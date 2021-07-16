<?php

$toEmail="";
$toUsername="";


if(isset($_POST['verihfy_button'])){
	
	//use PHPMailer\PHPMailer\PHPMailer;
	//use PHPMailer\PHPMailer\Exception;

	require 'vendor/autoload.php';

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
		$mail->addAddress('firstmineacc@gmail.com','Tousere');

		//$mail->addReplyTo('info@example.com', 'Information');
		//$mail->addCC('cc12@example.com');
		//$mail->addBCC('bcc12@example.com');

		//Content
		$mail->isHTML(true);                                 
		$mail->Subject = 'Password Verification';
		$mail->Body    = 'This is a one time message to provide a secure login 
						  <br> Your <b>OTP</b>&nbsp;&nbsp;&nbsp;<b>432123</b>';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
		//echo 'Message has been sent';
		$query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', 'no', '0', '0', '0', '0')");
	} catch (Exception $e) {
		//echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	}
}

?>