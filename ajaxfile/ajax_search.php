<?php
include("../config/config.php");

error_reporting(E_PARSE | E_ERROR);

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

$username = "0";
//If user is seraching a username
if(strpos($query, "@") !== false) {
	
	$query = substr($query,1);
	$usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' LIMIT 4");
	$username = "1";
}
//If query contains one word
else if(count($names) == 1) {
	$usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE first_name LIKE '%$query%' OR
                                                                           last_name LIKE '%$query%' LIMIT 4");
	$username = "1";
}
//If there are two words
else if(count($names) == 2){
	$usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE first_name LIKE '$names[0]' AND
                                                                           last_name LIKE '%$names[1]%'
                                                                            
                                                                            LIMIT 4");
	$username = "1";
}
//If query has three words
else if(count($names) == 3)
	$usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
    
                                             (question_body LIKE '$query%' OR
                                              question_body LIKE '%$query%' OR
                                              question_body LIKE '$names[0]%' AND  question_body LIKE '%$names[1]%' OR
                                              question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' OR
                                              question_body LIKE '$names[1]%' AND  question_body LIKE '%$names[0]%' OR
                                              question_body LIKE '%$names[1]%' AND  question_body LIKE '%$names[0]%' OR
                                              
                                              question_body LIKE '$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' OR
                                              question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' OR
                                              question_body LIKE '$names[0]%' AND  question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[1]%' OR
                                              question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[1]%' OR
                                              question_body LIKE '$names[1]%' AND  question_body LIKE '%$names[0]%' AND question_body LIKE '%$names[2]%' OR
                                              question_body LIKE '%$names[1]%' AND  question_body LIKE '%$names[0]%' AND question_body LIKE '%$names[2]%' OR
                                              question_body LIKE '$names[1]%' AND  question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[0]%' OR
                                              question_body LIKE '%$names[1]%' AND  question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[0]%' OR
                                              question_body LIKE '$names[2]%' AND  question_body LIKE '%$names[0]%' AND question_body LIKE '%$names[1]%' OR
                                              question_body LIKE '%$names[2]%' AND  question_body LIKE '%$names[0]%' AND question_body LIKE '%$names[1]%' OR
                                              question_body LIKE '$names[2]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[0]%' OR
                                              question_body LIKE '%$names[2]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[0]%')
                                              LIMIT 8");

else if(count($names)==4)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' LIMIT 6");

else if(count($names)==5)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' LIMIT 6");

else if(count($names)==6)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' LIMIT 6");

else if(count($names)==7)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' LIMIT 6");

else if(count($names)==8)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%'AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' LIMIT 6");

else if(count($names)==9)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' LIMIT 8");

else if(count($names)==10)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' LIMIT 8");

else if(count($names)==11)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' LIMIT 8");


else if(count($names)==12)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND  question_body LIKE '%$names[11]%' LIMIT 8");

else if(count($names)==13)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
   question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' LIMIT 8");

else if(count($names)==14)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
   question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' LIMIT 8");

else if(count($names)==15)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' LIMIT 8");

else if(count($names)==16)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' AND question_body LIKE '%$names[15]%' LIMIT 8");

else if(count($names)==17)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' AND question_body LIKE '%$names[15]%' AND question_body LIKE '%$names[16]%' LIMIT 8");

else if(count($names)==18)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' AND question_body LIKE '%$names[15]%' AND question_body LIKE '%$names[16]%' AND question_body LIKE '%$names[17]%' LIMIT 8");

else if(count($names)==19)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' AND question_body LIKE '%$names[15]%' AND question_body LIKE '%$names[16]%' AND question_body LIKE '%$names[17]%' AND question_body LIKE '%$names[18]%' LIMIT 8");

else if(count($names)==20)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' AND question_body LIKE '%$names[15]%' AND question_body LIKE '%$names[16]%' AND question_body LIKE '%$names[17]%' AND question_body LIKE '%$names[18]%' AND question_body LIKE '%$names[19]%' LIMIT 8");

else if(count($names)==21)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' AND question_body LIKE '%$names[15]%' AND question_body LIKE '%$names[16]%' AND question_body LIKE '%$names[17]%' AND question_body LIKE '%$names[18]%' AND question_body LIKE '%$names[19]%' AND question_body LIKE '%$names[20]%' LIMIT 8");

else if(count($names)==22)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' AND question_body LIKE '%$names[15]%' AND question_body LIKE '%$names[16]%' AND question_body LIKE '%$names[17]%' AND question_body LIKE '%$names[18]%' AND question_body LIKE '%$names[19]%' AND question_body LIKE '%$names[20]%' AND question_body LIKE '%$names[21]%' LIMIT 8");

else if(count($names)==23)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' AND question_body LIKE '%$names[15]%' AND question_body LIKE '%$names[16]%' AND question_body LIKE '%$names[17]%' AND question_body LIKE '%$names[18]%' AND question_body LIKE '%$names[19]%' AND question_body LIKE '%$names[20]%' AND question_body LIKE '%$names[21]%' AND question_body LIKE '%$names[22]%' LIMIT 8");

else if(count($names)==24)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' AND question_body LIKE '%$names[15]%' AND question_body LIKE '%$names[16]%' AND question_body LIKE '%$names[17]%' AND question_body LIKE '%$names[18]%' AND question_body LIKE '%$names[19]%' AND question_body LIKE '%$names[20]%' AND question_body LIKE '%$names[21]%' AND question_body LIKE '%$names[22]%' AND question_body LIKE '%$names[23]%' LIMIT 8");

else if(count($names)==25)
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM question WHERE 
                                            
    question_body LIKE '%$names[0]%' AND  question_body LIKE '%$names[1]%' AND question_body LIKE '%$names[2]%' AND question_body LIKE '%$names[3]%' AND  question_body LIKE '%$names[4]%' AND  question_body LIKE '%$names[5]%' AND  question_body LIKE '%$names[6]%' AND  question_body LIKE '%$names[7]%' AND  question_body LIKE '%$names[8]%' AND  question_body LIKE '%$names[9]%' AND  question_body LIKE '%$names[10]%' AND question_body LIKE '%$names[11]%' AND question_body LIKE '%$names[12]%' AND question_body LIKE '%$names[13]%' AND question_body LIKE '%$names[14]%' AND question_body LIKE '%$names[15]%' AND question_body LIKE '%$names[16]%' AND question_body LIKE '%$names[17]%' AND question_body LIKE '%$names[18]%' AND question_body LIKE '%$names[19]%' AND question_body LIKE '%$names[20]%' AND question_body LIKE '%$names[21]%' AND question_body LIKE '%$names[22]%' AND question_body LIKE '%$names[23]%' AND question_body LIKE '%$names[24]%' LIMIT 8");

else
    $usersReturnedQuery = '0';


if($usersReturnedQuery != '0')
{
    if($query != ""){
		
		if($username == "1"){
			
			while($row = mysqli_fetch_array($usersReturnedQuery)) {
				
				$fname = $row['first_name'];
				$lname = $row['last_name'];
				$username = $row['username'];
				$profile_pic = $row['profile_pic'];
				$follower = $row['follower'] . " Followers";
				
				if($row['is_online'] == 'yes'){
					
					$online_class = "online_dot_search";
				}
				else{
					$online_class = "";
				}
				
				$fullname = $fname . " " . $lname;
				
				echo "<div class='resultDisplay'>
						<a href='profile.php?profile_username=". $username . "' style='color: #000;text-decoration:none;'>
							<div class='liveSearchProfilePic'>
								<img src='" . $profile_pic . "'>
								<div class='".$online_class."'>
								</div>
							</div>
							<div class='liveSearchUsersDetails'>
								<div class='liveSearchFullName'>
									" . $fullname . "
								</div>
								<div class='liveSearchUsername'>
									" . $username . "
								</div>
								<div class='liveSearchFollower'>
									" . $follower . "
								</div>
							</div>
						</a>
						</div>
						<hr>";
			}
		}
		
		else{
			while($row = mysqli_fetch_array($usersReturnedQuery)) {
				$newstr = $row['question_body'];

				//for ($x = 0; $x < count($names); $x++) {
				   // if($names[$x]!='')
					//{
						//$oldstr = $newstr;
						//$start_pos = strpos($oldstr,$names[$x]);
						//$end_pos = strlen($names[$x])+$start_pos;

						//if(substr($oldstr,$start_pos,3) != '>')
						//$newstr = substr($oldstr, 0, $start_pos) . '<b>' . substr($oldstr, $start_pos,strlen($names[$x])) . '</b>' . substr($oldstr, $end_pos);
					//}
					//What are some amazing job in India under salary 40,000INR ??

				//}


				echo "<div class='resultDisplay'>
						<a href='search.php?q=". $row['question_body'] ."&qid=" . $row['id'] . "' style='color: #000;text-decoration:none;'>
							<div class='liveSearchText'>
								" . $newstr . "
							</div>
						</a>
						</div>
						<hr>";
			}
        }

    }
	
}
else{
    echo "<div class='resultDisplay'>
            No result found
        </div>";

}
?>