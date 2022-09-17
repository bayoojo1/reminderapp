<?php
include_once("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}

if(isset($_GET['u'])){
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_GET['u']);
}else{
    exit();
}
// Get total users
$sql = "SELECT id FROM users WHERE activated=:activated";
$stmt = $db_connect->prepare($sql);
$stmt->bindValue(':activated', '1', PDO::PARAM_STR);
$stmt->execute();
$user_count = $stmt->rowCount();
// Get total providers
$sql = "SELECT id FROM content_provider WHERE approved=:approved";
$stmt = $db_connect->prepare($sql);
$stmt->bindValue(':approved', '1', PDO::PARAM_STR);
$stmt->execute();
$provider_count = $stmt->rowCount();
// Get total broadcast
$sql = "SELECT id FROM once WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM daily WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM weekly WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM monthly WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM yearly WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM dailyround WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM dailydaytime WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM dailynight WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM dailyweekdaytime WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM dailyweekdaynight WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM dailyweekendday WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > '' UNION
        SELECT id FROM dailyweekendnight WHERE TRIM(audio_desc) > '' OR TRIM(mobile) > ''";
$stmt = $db_connect->prepare($sql);
$stmt->execute();
$broadcast_count = $stmt->rowCount();
// Get total API
$sql = "SELECT id FROM api";
$stmt = $db_connect->prepare($sql);
$stmt->execute();
$api_count = $stmt->rowCount();
include_once("functions/page_functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>User Management - <?php echo $fullName; ?></title>
<link rel="stylesheet" href="style/normalize.css">
<link href="https://fonts.googleapis.com/css?family=Changa+One:400,400i|Open+Sans:400,400i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script src="js/jquery.js"></script>
<script src="js/header_scroll.js"></script>
<script src="js/main.js"></script>
<script src="js/functions.js"></script>
</head>
<body class="insidepage">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div><br /><br />
<div id="wrapper">
    <?php echo $pageleft; ?>

    <div id="PageMiddle"><br class="b1"><br class="b2">
      <div style="width:100%; text-align:center;">
        <span style="display:inline-block; width:20%; height:50px; border: 1px solid gray; background-color:white;">
          <span style="margin-top:7px; display:block; color:grey;"><?php echo $user_count; ?></span>
          <span style="display:block;">User</span>
        </span>
        <span style="display:inline-block; width:20%; height:50px; border: 1px solid gray; background-color:white;">
          <span style="margin-top:7px; display:block; color:grey;"><?php echo $provider_count; ?></span>
          <span style="display:block;">Provider</span>
        </span>
        <span style="display:inline-block; width:20%; height:50px; border: 1px solid gray; background-color:white;">
          <span style="margin-top:7px; display:block; color:grey;"><?php echo $broadcast_count; ?></span>
          <span style="display:block;">Broadcast</span>
        </span>
        <span style="display:inline-block; width:20%; height:50px; border: 1px solid gray; background-color:white;">
          <span style="margin-top:7px; display:block; color:grey;"><?php echo $api_count; ?></span>
          <span style="display:block;">API</span>
        </span>
      </div><br />
        <div id="searchuserWrap" style="max-width:100%;">
            <span><input type="text" id="searchusers" name="searchusers" placeholder="Search user by username..."></span>
            <i class="fas fa-search" id="search" style="color:white; float:right; margin-top:6.5px;"></i>
        </div>
    <br />
        <?php include_once("functions/user_management.php"); ?><br />
    </div>
    <?php include_once("template_pageRight.php"); ?>
</div>
</body>
</html>
