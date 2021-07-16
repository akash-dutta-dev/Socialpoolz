<?php  

$otp="";
$username="";


if(isset($_POST['verify_button'])) {

	$otp = $_POST['otp_number']; //Get OTP
	$username = $_SESSION['user_to_verify']; //Get username
	
	$check_database_query = mysqli_query($con, "SELECT * FROM toverify WHERE username='$username' AND otp='$otp'");
	$check_login_query = mysqli_num_rows($check_database_query);

	if($check_login_query == 1) {
		$row = mysqli_fetch_array($check_database_query);
		$fname = $row['first_name'];
		$lname = $row['last_name'];
		$em = $row['email'];
		$password = $row['password'];
		$date = $row['signup_date'];
		$profile_pic = $row['profile_pic'];

		$query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', 'no', '0', ',','0', ',','0', '0',',','1','yes')");	
		$query = mysqli_query($con, "INSERT INTO users_details VALUES ('$username','','','','','','','','','','','','','','','','','','','','','','','','','','')");	
		$delete_database_query = mysqli_query($con, "DELETE FROM toverify WHERE username = '$username' AND otp = '$otp'");
		
		$_SESSION['user_to_verify'] ="";
		$_SESSION['username'] = $username;
		
		setcookie('username', $username, time() + (86400 * 30 * 365), "/"); // 86400 = 1 day

		header("Location: index.php");
		exit();
	}
	else {
		array_push($error_array, "Incorrect OTP entered.<br>");
	}


}

?>