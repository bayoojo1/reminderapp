<?php
include_once("php_includes/check_login_status.php");
include("theCollector.php");
// If the page requestor is not logged in, usher them away

if($user_ok != true || $log_username == ""){
    header("location: http://www.naatcast.com");
    exit();
}

if(isset($_GET['u'])){
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_GET['u']);
}else{
    exit();
}

if(isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
}
// Send request to VoguePay
$data = file_get_contents('https://voguepay.com/?v_transaction_id='.$transaction_id.'&type=json&demo=true');
$arr = json_decode($data, true);

// Get the needed parameter
$status = $arr['status'];

include_once("functions/profile_page_left_functions.php");

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Payment Failed - <?php echo $fullName; ?></title>
<link rel="stylesheet" href="style/normalize.css">
<link href="https://fonts.googleapis.com/css?family=Changa+One:400,400i|Open+Sans:400,400i,700,700i" rel="stylesheet">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src='https://assets.fortumo.com/fmp/fortumopay.js' type='text/javascript'></script>
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

    <div id="PageMiddle">
    <br /><br />

       <?php echo '<br /><br />' ?>
   <?php echo '<div id="followerList" style="height:auto; text-align:center; vertical-align: middle; font-size:20px; color:white;">'; ?>
   <?php echo "Sorry, your payment was not successful and has been $status. Please <b><a href='www.naatcast.com/billing/$log_username'>go back</a></b> to the payment page to try again."; ?>
   <?php echo '</div>' ?>

    </div>

    </div>


    <?php include_once("template_pageRight.php"); ?>

</div>
</body>
</html>
