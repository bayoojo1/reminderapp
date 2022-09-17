<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}

$sql = "SELECT id, username, countrycode, mobile FROM users WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $api_id = $row['id'];
    $cc = $row['countrycode'];
    $m = $row['mobile'];
    $username = $row['username'];
}

if(isset($_POST['apiname'])) {
    $apiname = preg_replace('#[^a-z0-9.@_]#i', '', $_POST['apiname']);
    $api_description = preg_replace('#[^a-z0-9.@_ ]#i', '', $_POST['apidescription']);
}

// GATHER OTHER REQUIRED VARIABLES
$api_key = rand(0, 100000000);
$string = trim("$api_id"."$cc"."$m"."$username"."$apiname"."$api_description"."$api_key");
// Generate md5 hash with the collected variables
$api_token = md5($string);

// Update the DB
$stmt = $db_connect->prepare("INSERT INTO api (api_id, countrycode, mobile, username, api_name, api_description, api_key, api_token, date_created) VALUES(:api_id, :countrycode, :mobile, :username, :api_name, :api_description, :api_key, :api_token, now())");
$stmt->execute(array(':api_id' => $api_id, ':countrycode' => $cc, ':mobile' => $m, ':username' => $username, ':api_name' => $apiname, ':api_description' => $api_description, ':api_key' => $api_key, ':api_token' => $api_token));

// Echo back the api_token
echo $api_token;
?>
