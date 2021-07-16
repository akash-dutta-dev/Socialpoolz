<?php

			require '../config/config.php';

			$userLoggedIn = $_POST['userLoggedIn'];
			$page = $_POST['page']; 
			$limit = 10;

			if($page == 1) 
				$start = 0;
			else 
				$start = ($page - 1) * $limit;


			$query_viewed = mysqli_query($con, "UPDATE notification SET viewed = 'yes' WHERE user_to_show = '$userLoggedIn' ");

			$query_notification = mysqli_query($con,"SELECT * FROM notification WHERE user_to_show = '$userLoggedIn' ORDER BY id DESC");

			$str = ""; //String to return 

			if(mysqli_num_rows($query_notification) > 0) {
				
				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;

				while($row = mysqli_fetch_array($query_notification)) {

						$notification_body = $row['notification_body'];
						$date_time = $row['date_added'];


					if($num_iterations++ < $start)
							continue; 

						//Once 10 posts have been loaded, break
						if($count > $limit) {
							break;
						}
						else {
							$count++;
						}
						//Timeframe
						$date_time_now = date("Y-m-d H:i:s");
						$start_date = new DateTime($date_time); //Time of post
						$end_date = new DateTime($date_time_now); //Current time
						$interval = $start_date->diff($end_date); //Difference between dates 
						if($interval->y >= 1) {
							if($interval->y == 1)
								$time_message = $interval->y . " year ago"; //1 year ago
							else 
								$time_message = $interval->y . " years ago"; //1+ year ago
						}
						else if ($interval->m >= 1) {
							if($interval->d == 0) {
								$days = " ago";
							}
							else if($interval->d == 1) {
								$days = $interval->d . " day ago";
							}
							else {
								$days = $interval->d . " days ago";
							}


							if($interval->m == 1) {
								$time_message = $interval->m . " month ". $days;
							}
							else {
								$time_message = $interval->m . " months ". $days;
							}

						}
						else if($interval->d >= 1) {
							if($interval->d == 1) {
								$time_message = "Yesterday";
							}
							else {
								$time_message = $interval->d . " days ago";
							}
						}
						else if($interval->h >= 1) {
							if($interval->h == 1) {
								$time_message = $interval->h . " hour ago";
							}
							else {
								$time_message = $interval->h . " hours ago";
							}
						}
						else if($interval->i >= 1) {
							if($interval->i == 1) {
								$time_message = $interval->i . " minute ago";
							}
							else {
								$time_message = $interval->i . " minutes ago";
							}
						}
						else {
							if($interval->s < 30) {
								$time_message = "Just now";
							}
							else {
								$time_message = $interval->s . " seconds ago";
							}
						}

						$time_message = "Notify ". $time_message;
						$str .= "<div class='notification_container'>
									<div class='notification_body'>
										$notification_body
									</div>
									<div class='notification_time'>
										$time_message
									</div>
								</div>
								<hr>";



				} //End while loop
	            if($count > $limit) 
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							 <input type='hidden' class='noMorePosts' value='false'>";
				else 
					$str .= "<input type='hidden' class='noMorePosts' value='true'><h5 style='text-align: centre;'>No more notification to load</h5>";
				
				
			}
            
			echo $str;


?>