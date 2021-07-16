<?php  
require '../config/config.php';

$profession = $_POST['form_input_profession'];
$user = $_POST['userLoggedInSurvey3'];
//echo $profession;

if($profession == 'school'){
	
	$school_name = $_POST['form_input_school_name'];
	$school_std = $_POST['form_input_school_standard'];
	
	$college_name = NULL;
	$college_course = NULL;
	$college_year = NULL;
	$college_location = NULL;
	
	$working_company_name = NULL;
	$working_company_post = NULL;
	$working_company_location = NULL;
	$working_company_time = NULL;
	
	$business_name = NULL;
	$business_location = NULL;
	$business_type = NULL;
	$business_started = NULL;
	
	$other_specification = NULL;
	
	if($school_name != "" && $school_std != ""){
		$query = mysqli_query($con,"UPDATE user_details SET school='yes',school_name='$school_name',school_std='$school_std' WHERE username='$user'");
	}
}

if($profession == 'college'){
	$school_name = NULL;
	$school_std = NULL;
	
	$college_name = $_POST['form_input_college_name'];
	$college_course = $_POST['form_input_college_course'];
	$college_year = $_POST['form_input_college_standard'];
	$college_location = $_POST['form_input_college_location'];
	
	$working_company_name = NULL;
	$working_company_post = NULL;
	$working_company_location = NULL;
	$working_company_time = NULL;
	
	$business_name = NULL;
	$business_location = NULL;
	$business_type = NULL;
	$business_started = NULL;
	
	$other_specification = NULL;
	
	if($college_name != "" && $college_course != "" && $college_year != "" && $college_location != ""){
		$query = mysqli_query($con,"UPDATE user_details SET college='yes',college_name='$college_name',college_course='$college_course',college_year='$college_year',college_location='$college_location' WHERE username='$user'");
	}
}

if($profession == 'working'){
	$school_name = NULL;
	$school_std = NULL;
	
	$college_name = NULL;
	$college_course = NULL;
	$college_year = NULL;
	$college_location = NULL;
	
	$working_company_name = $_POST['form_input_working_company_name'];
	$working_company_post = $_POST['form_input_working_company_post'];
	$working_company_location = $_POST['form_input_working_company_location'];
	$working_company_time = $_POST['form_input_working_company_time'];
	
	$business_name = NULL;
	$business_location = NULL;
	$business_type = NULL;
	$business_started = NULL;
	
	$other_specification = NULL;
	
	if($working_company_name != "" && $working_company_post != "" && $working_company_location != "" && $working_company_time != ""){
		$query = mysqli_query($con,"UPDATE user_details SET working='yes',working_name='$working_company_name',working_post='$working_company_post',working_location='$working_company_location',working_time='$working_company_time' WHERE username='$user'");
	}
}

if($profession == 'business'){
	$school_name = NULL;
	$school_std = NULL;
	
	$college_name = NULL;
	$college_course = NULL;
	$college_year = NULL;
	$college_location = NULL;
	
	$working_company_name = NULL;
	$working_company_post = NULL;
	$working_company_location = NULL;
	$working_company_time = NULL;
	
	$business_name = $_POST['form_input_business_name'];
	$business_location = $_POST['form_input_business_location'];
	$business_type = $_POST['form_input_business_type'];
	$business_started = $_POST['form_input_business_started'];
	
	$other_specification = NULL;
	
	if($business_name != "" && $business_location != "" && $business_type != "" && $business_started != ""){
		$query = mysqli_query($con,"UPDATE user_details SET business='yes',business_name='$business_name',business_location='$business_location',business_type='$business_type',business_started='$business_started' WHERE username='$user'");
	}
}

if($profession == 'other'){
	$school_name = NULL;
	$school_std = NULL;
	
	$college_name = NULL;
	$college_course = NULL;
	$college_year = NULL;
	$college_location = NULL;
	
	$working_company_name = NULL;
	$working_company_post = NULL;
	$working_company_location = NULL;
	$working_company_time = NULL;
	
	$business_name = NULL;
	$business_location = NULL;
	$business_type = NULL;
	$business_started = NULL;
	
	$other_specification = $_POST['form_input_other_specification'];
	
	if($other_specification != ""){
		$query = mysqli_query($con,"UPDATE user_details SET other='yes',other_specification='$other_specification' WHERE username='$user'");
	}
}

?>