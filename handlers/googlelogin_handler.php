<?php
	
								if(isset($pay_load)){
									//$_SESSION['username'] = $pay_load["email"];
									
									
									$email = $pay_load["email"];
									$name = $pay_load["name"];
									$profile_pic = $pay_load['picture'];
									echo '
											 <script>
											 	console.log("hrll");
											 </script>
											 ';

									$name_result = explode(" ",$name);
									$fname = $result[0];
									$lname = $result[1];

									$check_username_existance = mysqli_query($con, "SELECT * FROM users WHERE email='$em'");
									if(mysqli_num_rows($check_username_existance)==1){

										$check_username_existance_row = mysqli_fetch_array($check_username_existance);
										$username = $check_username_existance_row['username'];

										$_SESSION['username'] = $username;
										setcookie('username', $username, time() + (86400 * 30 * 365), "/");
										//header("Location: index.php");
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

										$query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', 'no', '0', ',','0', ',','0', '0',',','1','yes')");	
										$query = mysqli_query($con, "INSERT INTO users_details VALUES ('$username','','','','','','','','','','','','','','','','','','','','','','','','','','')");	
										
										echo '
											 <script>
											 	console.log("hrll");
											 </script>
											 ';
										
										$_SESSION['username'] = $username;
										setcookie('username', $username, time() + (86400 * 30 * 365), "/");
										//header("Location: index.php");
										//echo 'success';
										//exit();
									}
								}
?>