<?php
include_once("php_includes/check_login_status.php");
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
<meta charset="UTF-8">
<title>Call Records - <?php echo $fullName; ?></title>
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
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
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

    <div id="PageMiddle"> <?php if($u == $log_username && $u == $naatcast){ ?>
    <br class="b1"><br class="b2">

<div style="text-align:center; color:darkslategrey;">Upload the CSV file for the latest pricing here. Please note that you also have to update a2billing with the new price list.:</div>
<br>
<form id="sendcsv">
    <table border="1">
    <tr >
    <td colspan="2" align="center"><strong style="color:black;">Import CSV file</strong></td>
    </tr>
    <tr>
    <td align="center">CSV File:</td><td><input type="file" name="csv" id="csv"></td></tr>
    <tr >
    <td colspan="2" align="center"><input type="submit" value="Post" id="csvSend"></td>
    </tr>
    </table>
</form>
<div style="text-align:center;">
<span id="status" style="background-color:#004080; color:white;"></span>
</div>
    </div>
    <?php } else { ?>
   <?php echo '<br /><br />' ?>
   <?php echo '<div id="followerList" style="height:auto; text-align:center; vertical-align: middle; font-size:20px; color:white;">'; ?>
   <?php echo "Sorry, you cannot view other users call record page! This page is privately owned by this user and only veiwable to the owner."; ?>
   <?php echo '</div>' ?>
    </div>
<?php } ?>

    <?php include_once("template_pageRight.php"); ?>

</div>
</body>
</html>
