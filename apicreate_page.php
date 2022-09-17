<?php
include_once("php_includes/check_login_status.php");
include("theCollector.php");
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

include_once("functions/profile_page_left_functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>API - <?php echo $fullName; ?></title>
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
<div class="accordion" style="color:#0066ff; cursor:pointer; width:30px; margin-top:-30px;"><i class="fas fa-th fa-2x"></i></div>
    <?php echo $pageleft; ?>

    <div id="PageMiddle"> <?php if($u == $log_username){ ?>
    <br class="b1"><br class="b2">

        <?php include_once("functions/api_display.php"); ?><br />

    </div>
    <?php } else { ?>
   <?php echo '<br /><br />' ?>
   <?php echo '<div id="followerList" style="height:auto; text-align:center; vertical-align: middle; font-size:20px; color:white;">'; ?>
   <?php echo "Sorry, you cannot view other users API page! This page is privately owned by this user and only veiwable to the owner."; ?>
   <?php echo '</div>' ?>
    </div>
<?php } ?>

    <?php include_once("template_pageRight.php"); ?>

</div>
</body>
</html>
