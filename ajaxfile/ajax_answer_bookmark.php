<?php
include("../config/config.php");

$answer_id = $_POST['answer_id'];
$userLoggedIn = $_POST['userLoggedIn'];

$bookmark_check_query = mysqli_query($con,"SELECT * FROM bookmarks WHERE answer_id='$answer_id' AND username='$userLoggedIn'");

if(mysqli_num_rows($bookmark_check_query) == 0){
    $bookmark_insert_query = mysqli_query($con,"INSERT INTO bookmarks VALUES('','$answer_id','$userLoggedIn')");
    echo '0';
}
else{
    $bookmark_delete_query = mysqli_query($con,"DELETE FROM bookmarks WHERE answer_id='$answer_id' AND username='$userLoggedIn'");
    echo '1';
}
?>