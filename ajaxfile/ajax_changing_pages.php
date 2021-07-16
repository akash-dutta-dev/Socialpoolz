<?php  
require '../config/config.php';

//$set_profile_pic_extra = $_POST['set_profile_pic_extra'];

//if(isset($_SESSION['set_profile_pic_extra']) )
//{}
//else

{
    
    $_SESSION['set_profile_pic'] = "";
    $_SESSION['profile_pic_width'] = "";
    $_SESSION['profile_pic_height'] = "";
    $_SESSION['set_profile_pic_extra'] = "";

    unset($_SESSION['set_profile_pic']);
    unset($_SESSION['profile_pic_width']);
    unset($_SESSION['profile_pic_height']);
    unset($_SESSION['set_profile_pic_extra']);

}

?>