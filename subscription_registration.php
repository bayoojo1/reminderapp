<?php
// This is registration page for content provider //
include("php_includes/check_login_status.php");
include_once("template_pageLeft.php");

if(isset($_SESSION['username'])) {
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_SESSION['username']);;
} else {
    exit();
}
if(isset($_GET['u'])){
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_GET['u']);
}else{
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Subscription Registration - <?php echo $fullName; ?></title>
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
<script src="js/sticky-master/jquery.sticky.js"></script>
<script src="js/functions.js"></script>
</head>
<body class="insidepage">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div>
<br /><br />
<div id="wrapper">
<?php echo $pageleft; ?>
    <div id="PageMiddle"> <?php if($u != $log_username){ ?>

        <?php echo '<br /><br />' ?>
        <?php echo '<div id="followerList" style="height:auto; text-align:center; vertical-align: middle; font-size:20px; color:white;">'; ?>
        <?php echo "Sorry, you cannot view other users profile page! This page is privately owned by this user and only veiwable to the owner."; ?>
        <?php echo '</div>' ?>
    </div>
    <?php } else { ?>
        <?php echo '<form id="sendform">'; ?>
        <?php echo '<br /><br />'; ?>
        <?php echo '<div style="display:inline-block; font-weight:800; color:gray;">Content Type:</div><span style="font-size:12px;"> (Select the content type from the drop down)</span>'; ?>
        <?php echo '<select id="type" name="type" style="max-width:200px; float:right;"/>
                                <option selected="selected" value="one">Select one</option>
                                <option value="Comedy">Comedy</option>
                                <option value="Sport">Sport</option>
                                <option value="Breaking News">Breaking News</option>
                                <option value="Weather Report">Weather Report</option>
                                <option value="Market Survey">Market Survey</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Hot Gist">Hot Gist</option>
                                <option value="Religion">Religion</option>
                                <option value="Educational">Educational</option>
                                <option value="Health and Fitness">Health and Fitness</option>
                                <option value="Food and Nutrition">Food and Nutrition</option>
                                <option value="Fashion and Beauty">Fashion and Beauty</option>
                                <option value="Finance">Finance</option>
                                <option value="Security">Security</option>
                                <option value="Traffic Report">Traffic Report</option>
                                <option value="Auto Care">Auto Care</option>
                                <option value="Pet Care">Pet Care</option>
                            </select>'; ?>
                            <?php echo '<br /><br /><br/><br />'; ?>
        <?php echo '<div style="display:inline-block; font-weight:800; color:gray;">Content Sample:</div><span style="font-size:12px;"> (Sample of your contents, not more than 5MB in size)</span>'; ?>
        <?php echo '<input name="sample" id="sample" type="file" style="float:right;"/>'; ?>
        <?php echo '<br /><br /><br /><br />'; ?>
        <?php echo '<div style="display:inline-block; font-weight:800; color:gray;">Means of Identification:</div><span style="font-size:12px;"> (e.g. International passport bio page or driver\'s license. If an organization, certificate of incorporation)</span>'; ?>
        <?php echo '<input name="identity" id="identity" type="file" style="float:right;"/>'; ?>
        <?php echo '<br /><br /><br /><br />'; ?>
        <?php echo '<div style="display:inline-block; font-weight:800; color:gray;">Brief Description:</div><span style="font-size:12px;"> (Give a brief but detail description of the type of service your contents would be providing to your subscribers in not more than 500 characters)</span>'; ?>
        <?php echo "<textarea rows='5' name='description' id='description' onfocus='emptyElement('status')' onkeyup='charLimit(this, 500);'></textarea>"; ?>
        <?php echo '<br /><br />'; ?>
        <?php echo '<input type="submit" value="Send" id="btnSend" style="font-weight:800; float:right;">'; ?>
        <?php echo '<span id="status" style="background-color:#004080; color:white;"></span>'; ?>
        <?php echo '</form>'; ?>
    </div>
<?php } ?>

<?php include_once("template_pageRight.php"); ?>
</div>
</body>
</html>
