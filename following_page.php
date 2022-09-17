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

include_once("functions/page_functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<!--<base href="/reminderapp">-->
<meta charset="UTF-8">
<title>Following - <?php echo $fullName; ?></title>
<link rel="stylesheet" href="style/normalize.css">
<link href="https://fonts.googleapis.com/css?family=Changa+One:400,400i|Open+Sans:400,400i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<style>
    #aboutBlock { margin-top: 10px; }
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/jquery.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
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
    <form action="" method="post"><div id="searchWrap" style="max-width:100%;">
        <span><input type="text" id="searchquery" name="searchquery" placeholder="Search users by name..." autocomplete="on" onkeypress="key_down(event)"></span>
        <i class="fas fa-search" id="search" onclick="searchUsers();" style="color:white; float:right; margin-top:6.5px;"></i>
    </div>
    </form><br /><br />

        <?php include_once("functions/following_display.php"); ?><br />

    </div>

    <?php include_once("template_pageRight.php"); ?>

</div>
</body>
</html>
