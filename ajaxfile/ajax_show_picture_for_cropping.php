<?php

include("../config/config.php");

$profile_id = $_POST['user'];
$imgSrc = "";
$result_path = "";
$msg = "";
$rand = rand(1, 25);
$profile_id = $profile_id . $rand;

$temppath = '../aa_profile_pic_upload/'.$profile_id.'_temp.jpeg';
if (file_exists ($temppath)){ 
	@unlink($temppath);
}
	    

if(isset($_FILES['file']['name'])){	
	
		
		$ImageName = $_FILES['file']['name'];
		$ImageSize = $_FILES['file']['size'];
		$ImageTempName = $_FILES['file']['tmp_name'];
 
		$ImageType = @explode('/', $_FILES['file']['type']);
		$type = $ImageType[1]; //file type	
	    
		$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/aa_profile_pic_upload';
	
		$file_temp_name = $profile_id.'_original.'.md5(time()).'n'.$type; //the temp file name
		$fullpath = $uploaddir."/".$file_temp_name; // the temp file path
		$file_name = $profile_id.'_temp.jpeg'; //$profile_id.'_temp.'.$type; // for the final resized image
		$fullpath_2 = $uploaddir."/".$file_name; //for the final resized image
	
		$move = move_uploaded_file($ImageTempName ,$fullpath) ; 
		chmod($fullpath, 0777);  
		
		if (!$move) { 
			die ('File didnt upload');
		} else { 
			$imgSrc= "aa_profile_pic_upload/".$file_name; // the image to display in crop area
			$msg= "Upload Complete!";  	//message to page
			$src = $file_name;	 		//the file name to post from cropping form to the resize		
		} 

	
			clearstatcache();				
			$original_size = getimagesize($fullpath);
			$original_width = $original_size[0];
			$original_height = $original_size[1];
	
			$original_width_2 = $original_width;
			$original_height_2 = $original_height;
			
			if($original_width > 500){
				$main_width = 500; // set the width of the image
				$main_height = $original_height / ($original_width / $main_width);	
				
				$original_width_2 = $main_width;
				$original_height_2 = $main_height;
			}
	
			if($original_height > 400){
				$main_height = 400; 
				$main_width = $original_width / ($original_height / $main_height);	// this sets the height in ratio		
				
				$original_width_2 = $main_width;
				$original_height_2 = $main_height;
			}
				
			if($_FILES["file"]["type"] == "image/gif"){
				$src2 = imagecreatefromgif($fullpath);
			}elseif($_FILES["file"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/pjpeg"){
				$src2 = imagecreatefromjpeg($fullpath);
			}elseif($_FILES["file"]["type"] == "image/png"){ 
				$src2 = imagecreatefrompng($fullpath);
			}else{ 
				$msg .= "There was an error uploading the file. Please upload a .jpg, .gif or .png file. <br />";
			}
	
			$main = imagecreatetruecolor($original_width_2,$original_height_2);
			imagecopyresampled($main,$src2,0, 0, 0, 0,$original_width_2,$original_height_2,$original_width,$original_height);
		
			$main_temp = $fullpath_2;
			imagejpeg($main, $main_temp, 90);
			chmod($main_temp,0777);
		
			imagedestroy($src2);
			imagedestroy($main);
			//imagedestroy($fullpath);
			@ unlink($fullpath); // delete the original upload					
									
}//ADD Image 	
//if($imgsrc != ""){
	$_SESSION['set_profile_pic'] = $imgSrc;
	$_SESSION['profile_pic_width'] = $original_width_2;
	$_SESSION['profile_pic_height'] = $original_height_2;
	
	//echo json_encode(array($imgSrc, $original_width_2,$original_height_2 ));
	echo $imgSrc . "<br><br>" . $type. "<br><br>" .$fullpath;
//}
?>