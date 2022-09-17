<?php
include_once("php_includes/check_login_status.php");
// Initialize any variables that the page might echo
$u = "";
$firstname = "";
$lastname = "";
$fullName = "";
$mobile = "";
$profile_pic = "";
$profile_pic_btn = "";
$avatar_form = "";
$audio_upload_pic = "";
$audio_upload_btn = "";
$audio_upload_form = "";
$joindate = "";
$lastsession = "";


// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
    //$e = mysqli_real_escape_string($db_connect, $_GET['e']);
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_GET['u']);
} else {
    header("location: http://localhost:8080/reminderapp/logout.php");
    exit();
} 
include_once("functions/page_functions.php");
?>