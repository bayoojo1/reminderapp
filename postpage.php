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
    $postid = preg_replace('#[^0-9]#i', '', $_GET['id']);
    $posttag = lcfirst(preg_replace('#[^a-z]#i', '', $_GET['tag']));
}else{
    exit();
}

include_once("functions/page_functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Post - <?php echo $fullName; ?></title>
<link rel="stylesheet" href="style/normalize.css">
<link href="https://fonts.googleapis.com/css?family=Changa+One:400,400i|Open+Sans:400,400i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="jssocials-1.4.0/dist/jssocials.css" />
<link rel="stylesheet" type="text/css" href="jssocials-1.4.0/dist/jssocials-theme-minima.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="insidepage">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div><br /><br />
<div id="wrapper">
    <?php echo $pageleft; ?>

    <div id="PageMiddle"> <?php if($u != $log_username || $u == $log_username){ ?>


        <?php include_once("functions/postpage_display.php"); ?><br />

    </div>
    <?php } else if(!isset($posttag) || !isset($postid) ) { ?>
   <?php echo '<br /><br />' ?>
   <?php echo '<div id="followerList" style="height:auto; text-align:center; vertical-align: middle; font-size:20px; color:white;">'; ?>
   <?php echo "Sorry, you got yourself in a wrong page. How did you get here?"; ?>
   <?php echo '</div>' ?>
    </div>
<?php } ?>

    <?php include_once("template_pageRight.php"); ?>

</div>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="jssocials-1.4.0/dist/jssocials.min.js"></script>
<script src="js/header_scroll.js"></script>
<script src="js/main.js"></script>
<script src="js/moment.js"></script>
<script src="js/livestamp.js"></script>
<script src="js/functions.js"></script>
</body>
</html>
