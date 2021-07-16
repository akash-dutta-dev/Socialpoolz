<!DOCTYPE html>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require 'config/config.php';
require 'handlers/register_handler.php';
require 'handlers/login_handler.php';
require 'handlers/verification_handler.php';
require 'handlers/fblogin_handler.php';
require 'handlers/googlelogin_handler.php';
$mail = new PHPMailer(true);  
?>

<html>
    
    <head>
        <title>
            SocialPoolz Login
        </title>
        <!--CSS-->
        <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
		<link rel="shortcut icon" href="assets/images/logo.jpg" />
        
         <!--JS-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="assets/js/register.js"></script>
		<script type="text/javascript">

		  if (navigator.userAgent.match(/Android/i) ||
			  navigator.userAgent.match(/webOS/i) ||
			  navigator.userAgent.match(/iPhone/i) ||
			  navigator.userAgent.match(/iPad/i) ||
			  navigator.userAgent.match(/iPod/i) ||
			  navigator.userAgent.match(/BlackBerry/i) ||
			  navigator.userAgent.match(/Windows Phone/i)
			) {} else {
			
		  }

		</script>
    
    </head>
    
    <body>
        <?php  
        if(isset($_POST['register_button'])) {
            if(in_array("<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>", $error_array) ){
                echo '
                <script>

                $(document).ready(function() {
                    $("#first").hide();
                    $("#second").hide();
					$("#third").show();
                });

                </script>

                ';
            }
            else
            {

                echo '
                <script>

                $(document).ready(function() {
                    $("#first").hide();
                    $("#second").show();
                });

                </script>

                ';
            }
        }
		
		if(isset($_SESSION['username'])){
			header("Location: index.php");
			exit();
		}
		
		if(isset($_POST['verify_button'])){
			if(in_array("Incorrect OTP entered.<br>", $error_array) ){
				 echo '
                <script>

                $(document).ready(function() {
                    $("#first").hide();
                    $("#second").hide();
					$("#third").show();
                });

                </script>

                ';
			}
		}
        ?>

		<script>
			$(document).ready(function() {
				$('#register_button').click(function () {
					
					
						$.ajax({
							type:'POST',
							cache:false,
							url:'ajax_send_otp.php',
							data:$('form.register_form').serialize(),    // multiple data sent using ajax
							success: function (msg) {
								$('#first').hide();
								$('#second').hide();
								$('#third').show();	
								alert('done');
							},
							error: function() {
								alert('otp cannot send');
							}
						});
					

				});
				$('#fp_link_send_button').click(function () {
					
					
						$.ajax({
							type:'POST',
							cache:false,
							url:'ajaxfile_mail/ajax_forgot_password.php',
							data:$('form.fp_form').serialize(),    // multiple data sent using ajax
							success: function (msg) {
								console.log(msg);
								$('.fp_form_rt').text(msg);	
							},
							error: function() {
								alert('fp link cannot send');
							}
						});
					

				});
			});
		</script>
		
		
		
        <div id="wrapper">
        
            <div id="bg">
                
            </div>

            <div class="login_box">
   
                <div class="login_header">
                    <h1>SocialPoolZ !</h1>
                    Login or sign up below!
                </div>
			    <br>
                <div id="first">

                    <form action="register.php" method="POST" style="min-width:462px;">
                        <input type="email" name="log_email" placeholder="Email Address" value="<?php 
                        if(isset($_SESSION['log_email'])) {
                            echo $_SESSION['log_email'];
                        } 
                        ?>" required>
                        <br>
                        <input type="password" name="log_password" placeholder="Password">
                        <br>
                        <?php if(in_array("Email or password was incorrect<br>", $error_array)) echo  "Email or password was incorrect<br>"; ?>
						<input type="submit" name="login_button" value="Login">
						<span class="forgot_password_link_button" id="forgot_password_link"><a href="#" id="forgot_password_link">Forgot Password[..?]</a></span>
                        <br>
						<div class='register_terms_and_policy'>By signing up you indicate that you have read and agree to Socialpoolz's <a href="tos.php" target="_blank">Terms of Service</a> and <a href='policy.php' target="_blank">Privacy Policy.</a></div>
                        <a href="#" id="signup" class="signup">Need an account? Register here!</a>
						<div class="fb_button_design">
							<img src="assets/fb_logo.png">
							<input type="button" onclick="fblogIn()" class="facebook_button" value="Log In With Facebook">
						</div>	
						
						
						<?php require ("vendor_google/autoload.php");
							//Step 1: Enter you google account credentials

							$g_client = new Google_Client();

							$g_client->setClientId("67892232898-9bdb4g4tli25lioq8kchukv9p3vbh7da.apps.googleusercontent.com");
							$g_client->setClientSecret("b4HklZMKqIoSh6UouMiVmRsB");
							$g_client->setRedirectUri("http://www.socialpoolz.com/register.php");
							$g_client->setScopes(array(
							"https://www.googleapis.com/auth/plus.login",
							"https://www.googleapis.com/auth/userinfo.email",
							"https://www.googleapis.com/auth/userinfo.profile",
							"https://www.googleapis.com/auth/plus.me"
							));

							//$g_client->setScopes("last_name");

							//Step 2 : Create the url
							$auth_url = $g_client->createAuthUrl();
							echo "<button class='google_button'><a href='".$auth_url."' ><img src='assets/google_logo.png'>Log In With Google </a></button>";



							//Step 3 : Get the authorization  code
							$code = isset($_GET['code']) ? $_GET['code'] : NULL;

							//Step 4: Get access token
							if(isset($code)) {

								try {

									$token = $g_client->fetchAccessTokenWithAuthCode($code);
									$g_client->setAccessToken($token);

								}catch (Exception $e){
									echo $e->getMessage();
								}




								try {
									$pay_load = $g_client->verifyIdToken();


								}catch (Exception $e) {
									echo $e->getMessage();
								}

							} else{
								$pay_load = null;
							}

							if(isset($pay_load)){

									$email = $pay_load["email"];
									$name = $pay_load["name"];
									$profile_pic = $pay_load['picture'];
									echo '
											 <script>
											 	console.log("hrll");
											 </script>
											 ';

									$name_result = explode(" ",$name);
									$fname = $name_result[0];
									$lname = $name_result[1];

									$check_username_existance = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
									if(mysqli_num_rows($check_username_existance)==1){

										$check_username_existance_row = mysqli_fetch_array($check_username_existance);
										$username = $check_username_existance_row['username'];

										$_SESSION['username'] = $username;
										header("Location: index.php");
										//exit();
										//echo 'success';
									}
									else{
										//Generate username by concatenating first name and last name
										$username = strtolower($fname . "_" . $lname);
										$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");


										$i = 0; 
										//if username exists add number to username
										while(mysqli_num_rows($check_username_query) != 0) {
											$extra = "_" . $i;
											$username = str_replace($extra,"",$username);
											$i++; //Add 1 to i
											$username = $username . "_" . $i;
											$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

										}

										$password = md5(rand(100000, 999999));

										$date = date("Y-m-d"); //Current date

										$query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_pic', 'no', '0', '0', '0', '0')");	
										
										echo '
											 <script>
											 	console.log("hrll");
											 </script>
											 ';
										
										$_SESSION['username'] = $username;
										header("Location: index.php");
										//echo 'success';
										//exit();
									}


							}
						?>

                    </form>
					<script>
						var person = { userID: "", name: "", accessToken: "", picture: "", email: "",first_name:"",last_name:""};

						function fblogIn() {
							FB.login(function (response) {
								if (response.status == "connected") {
									person.userID = response.authResponse.userID;
									person.accessToken = response.authResponse.accessToken;

									FB.api('/me?fields=id,name,email,picture.type(large),first_name,last_name', function (userData) {
										person.name = userData.name;
										person.email = userData.email;
										person.picture = userData.picture.data.url;
										person.first_name = userData.first_name;
										person.last_name = userData.last_name;

										$.ajax({
										   url: "register.php",
										   method: "POST",
										   data: person,
										   dataType: 'text',
										   success: function (serverResponse) {
											   console.log(person);
											   if (serverResponse == "success")
												   window.location = "index.php";
											   location.reload();
										   }
										});
									});
								}
							}, {scope: 'public_profile, email'})
						}

						window.fbAsyncInit = function() {
							FB.init({
								appId            : '336326253537517',
								autoLogAppEvents : true,
								xfbml            : true,
								version          : 'v2.11'
							});
						};

						(function(d, s, id){
							var js, fjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) {return;}
							js = d.createElement(s); js.id = id;
							js.src = "https://connect.facebook.net/en_US/sdk.js";
							fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));
					</script>

                </div>

                <div id="second" style="display:none;">

                    <form action="register.php" method="POST" class="register_form">
                        <input type="text" name="reg_fname" placeholder="First Name" value="<?php 
                        if(isset($_SESSION['reg_fname'])) {
                            echo $_SESSION['reg_fname'];
                        } 
                        ?>" required>
                        <br>
                        <?php if(in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) echo "Your first name must be between 2 and 25 characters<br>"; ?>




                        <input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
                        if(isset($_SESSION['reg_lname'])) {
                            echo $_SESSION['reg_lname'];
                        } 
                        ?>" required>
                        <br>
                        <?php if(in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) echo "Your last name must be between 2 and 25 characters<br>"; ?>

                        <input type="text" name="reg_username" placeholder="Username" value="<?php 
                        if(isset($_SESSION['reg_username'])) {
                            echo $_SESSION['reg_username'];
                        } 
                        ?>" required>
                        <br>
                        <?php if(in_array("Your username can only contain english characters or numbers or underscore<br>", $error_array)) echo "Your username can only contain english characters or numbers or underscore<br>"; 
                        else if(in_array("Username already in use<br>", $error_array)) echo "Username already in use<br>"; ?>

                        <input type="email" name="reg_email" placeholder="Email" value="<?php 
                        if(isset($_SESSION['reg_email'])) {
                            echo $_SESSION['reg_email'];
                        } 
                        ?>" required>
                        <br>

                        <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
                        if(isset($_SESSION['reg_email2'])) {
                            echo $_SESSION['reg_email2'];
                        } 
                        ?>" required>
                        <br>
                        <?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>"; 
                        else if(in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>";
                        else if(in_array("Emails don't match<br>", $error_array)) echo "Emails don't match<br>"; ?>


                        <input type="password" name="reg_password" placeholder="Password" required>
                        <br>
                        <input type="password" name="reg_password2" placeholder="Confirm Password" required>
                        <br>
                        <?php if(in_array("Your passwords do not match<br>", $error_array)) echo "Your passwords do not match<br>"; 
                        else if(in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
                        else if(in_array("Your password must be betwen 5 and 30 characters<br>", $error_array)) echo "Your password must be betwen 5 and 30 characters<br>"; ?>

						<input type="hidden" name="user_id" value="<?php echo rand(100000, 999999); ?>">
                        <input type="submit" name="register_button" value="Register" id="register_button">

                        <br>

                        <?php if(in_array("<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>", $error_array)) echo "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>"; ?>
                        <a href="#" id="signin" class="signin">Already have an account? Sign in here!</a>

                    </form>
                </div>
				<div id="third" style="display:none;">

                    <form action="register.php" method="POST">
						<div class="otp_verify_text">
							OTP has been sent to your email. <br>
							Check and enter it below for verification
						</div>
                        <input type="text" name="otp_number" placeholder="Enter OTP" required>
                        <br>
						<?php if(in_array("Incorrect OTP entered.<br>", $error_array)) echo  "Incorrect OTP entered.<br>"; ?>
                        <input type="submit" name="verify_button" value="Verify">
                        <br>
						
                        <a href="handlers/logout.php" id="verification_goback" class="verification_goback">Go Back..</a>

                    </form>

                </div>
				<div id="fourth" style="display:none;">

                    <form class='fp_form' method="POST">
						Enter your registered Email address.
                        <input type="email" name="fp_link_email" placeholder="Email" required>
                        <br>
                    </form>
					<span class="fp_form_rt"></span>
					<input type="submit" name="fp_link_send_button" id="fp_link_send_button" value="Send"><br>
					<a href="handlers/logout.php" id="verification_goback" class="verification_goback">Go Back..</a>
                </div>

            </div>

        
        </div>

        <script>
            window.onload = function() { document.body.className = ''; }
            window.ontouchmove = function() { return false; }
            window.onorientationchange = function() { document.body.scrollTop = 0; }
        </script>
        
    </body>
    
</html>