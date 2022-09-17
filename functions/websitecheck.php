<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away
//include_once("page_functions.php");

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}
?><?php
$sql = "UPDATE useroptions SET websitecheck=:websitecheck WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':websitecheck', $_POST['checked'], PDO::PARAM_STR);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
$db_connect = null;
?>