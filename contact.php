<?php
include_once("php_includes/check_login_status.php");
include("theCollector.php");
// Set the naatcast user
$naatcast = 'naatcast';
$naatcastbilling = 'naatcast.billing';
$naatcastsupport = 'naatcast.support';
$naatcastreport = 'naatcast.report';
$naatcastothers = 'naatcast.others';
// If the page requestor is not logged in, usher them away
if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}

if(isset($_GET['u']) && $_GET['u'] == $log_username){
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_GET['u']);
}else{
  header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}
include_once("functions/page_functions.php");

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Contact Us - <?php echo $fullName; ?></title>
<link rel="stylesheet" href="style/normalize.css">
<link href="https://fonts.googleapis.com/css?family=Changa+One:400,400i|Open+Sans:400,400i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="js/header_scroll.js"></script>
<script src="js/main.js"></script>
<script src="js/moment.js"></script>
<script src="js/livestamp.js"></script>
<script src="js/functions.js"></script>
</head>
<body class="insidepage">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div><br /><br />
<div id="wrapper">
    <?php echo $pageleft; ?>

    <div id="PageMiddle">
    <br class="b1"><br class="b2">
     <?php if($u == $naatcast || $u == $naatcastbilling || $u == $naatcastsupport || $u == $naatcastreport || $u == $naatcastothers){ ?>
       <span style="color:grey; font-weight:bold;">Categories:</span> <select id="query" style="width: 160px;">
                   <option selected="selected" value="">--Select an option--</option>
                   <option value="Billing">Billing issue</option>
                   <option value="Technical">Technical support</option>
                   <option value="Report">Report a broadcast</option>
                   <option value="Others">Other issues</option>
                 </select>
                 <br><br>
                 <?php include_once('functions/queryview.php'); ?>
    <?php } else  { ?>
      <span style="color:#004080; font-weight:bold;">Hello <?php echo $fullName;?> </span>
      <br><br>
      <span style="font-size:12px;">We cherish your feedback and we'll give it quick response. Please use the form below to submit your query by chosing from the the drop down selection.</span>
      <br><br>
      <span style="color:grey; font-weight:bold;">Categories:</span> <select id="query" style="width: 160px;">
                  <option selected="selected" value="">--Select an option--</option>
                  <option value="Billing">Billing issue</option>
                  <option value="Technical">Technical support</option>
                  <option value="Report">Report a broadcast</option>
                  <option value="Others">Other issues</option>
                </select>
                <br><br>
      <span style="color:grey; font-weight:bold;">Provide Detail:</span> <textarea rows="6" id="detail" style="border:2px solid #00ced1;"></textarea>
                      <input type="button" id="submitquery" style="float:right;" value="Submit" onclick="sendquery()"/>
                      <br><br><br>
                      <div id="querylist"></div>
      <?php } ?>
    </div>
    <?php include_once("template_pageRight.php"); ?>
</div>
</body>
</html>
