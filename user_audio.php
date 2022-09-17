<?php
include_once("template_pageLeft.php");
//include("theCollector.php");
?>
<!DOCTYPE html>
<html>
<head>
<!--<base href="/">-->
<meta charset="UTF-8">
<title>Home - <?php echo $fullName; ?></title>
<link rel="stylesheet" href="style/normalize.css">
<link href="https://fonts.googleapis.com/css?family=Changa+One:400,400i|Open+Sans:400,400i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="insidepage">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div><div style="height:40px;"></div>
<div id="wrapper">
    <div class="accordion" style="color:#0066ff; cursor:pointer; width:30px; margin-top:-20px;"><i class="fas fa-th fa-2x"></i></div>
<?php echo $pageleft; ?>
    <div id="PageMiddle"> <?php if($user_ok == true && $u == $log_username){ ?>
    <br class="b1"><br class="b2">
        <div id="pageMiddleForm">
            <form id="signupform">
                <?php if($showAlias == '1'){ ?>
                <textarea rows="4" name="message" id="message" placeholder="Write your message here <?php echo rtrim($firstname) ?>, OR click on the icon below to upload an audio file..."  onfocus="emptyElement('status')"></textarea>
                <?php } else if($showAlias == '0') { ?>
                    <textarea rows="4" name="message" id="message"  placeholder="Write your message here <?php echo rtrim($theAlias) ?>, OR click on the icon below to upload an audio file..."  onfocus="emptyElement('status')"></textarea>
                    <?php } ?>
                <div id="audio_upload" style="margin: 0 auto; text-align:center; background-color:#3d6b99; color:white;"><img src="images/upload.png" id="upload_img" title="Upload Audio File" style="cursor:pointer;" /><span id="audioStatus"></span><input name="audio" id="audio" type="file" style="display:none;"/></div>

                <div id="hide" style="display:none;">
                    <textarea rows="3" name="mobile" id="mobile" placeholder="Enter recepient mobile(s) here, separated by comma..." onfocus="emptyElement('status')" onkeyup="restrict('mobile')"></textarea>
                    <br /><br />
        <table style="border-collapse:separate; border-spacing:0 5px;">
              <?php if($isProvider) { ?>

          <?php } ?>
            <tr>
                <td>
                    <div id="follower" style="float: right; font-style:italic;">Broadcast to Followers mobile:
                </td>
                <td><select id="followers" name="followers"/>
                            <option selected="selected" value="No">No</option>
                            <option value="Yes" title="By selecting this option, you will be billed for every call answered by your follower(s)">Yes</option>
                    </select></div>
                </td>
            </tr>
            <tr>
              <td>
                  <div id="subscriber" style="float: right; font-style:italic;">Show my number to recipient?:
              </td>
              <td><select id="subscribers" name="subscribers"/>
                          <option selected="selected" value="No">No</option>
                          <option value="Yes" title="By selecting YES, your per minute broadcast cost is higher. Please check our FAQ for current premium rate">Yes</option>
                  </select></div>
              </td>
          </tr>
            <tr>
                <td>
                    <div style="float: right; font-style:italic;">Recurrent:
                </td>
                <td>
                    <select id="recurrent" name="recurrent"/>
                            <option selected="selected" value="Once" title="This option will run the broadcast only once starting at the the date/time selected above. It will place  calls to all the recepient(s), follower(s) or subscriber(s) until the list is exhausted.">Once</option>
                            <option value="Daily" title="This option will start the same broadcast AFRESH every day, and place calls to recepient(s), follower(s) or subscriber(s) until the list is exhausted. The option is good for broadcasting daily recurrent messages.">Daily</option>
                            <option value="DailyRound" title="This option will run a broadcast 24/7 until the list of recepient(s), follower(s) or subscriber(s) is exhausted.">Round The Clock</option>
                            <option value="DailyDaytime" title="This option will run a broadcast from Sunday to Saturday, 7AM to 6PM every day until the list of recepient(s), follower(s) or subscriber(s) is exhausted.">Daytime</option>
                            <option value="DailyNight" title="This option will run a broadcast from Sunday to Saturday, 7PM to 6AM every day until the list of recepient(s), follower(s) or subscriber(s) is exhausted.">Night</option>
                            <option value="DailyWeekDaytime" title="This option will run a broadcast from Monday to Friday, 7AM to 6PM until the list of recepient(s), follower(s) or subscriber(s) is exhausted.">Weekdays(Daytime)</option>
                            <option value="DailyWeekDayNight" title="This option will run a broadcast from Monday to Friday, 7PM to 6AM until the list of recepient(s), followers or subscribers is exhausted.">Weekdays(Night)</option>
                            <option value="DailyWeekendDay" title="This option will run a broadcast from Saturday to Sunday, 7AM to 6PM until the list of recepient(s), follower(s) or subscriber(s) is exhausted.">Weekend(Daytime)</option>
                            <option value="DailyWeekendNight" title="This option will run a broadcast from Saturday to Sunday, 7PM to 6AM until the list of recepient(s), follower(s) or subscriber(s) is exhausted.">Weekend(Night)</option>
                            <option value="Weekly" title="This option will start the same broadcast AFRESH every week, and place calls to recepient(s), follower(s) or subscriber(s) until the list is exhausted. The option is good for broadcasting weekly recurrent messages.">Weekly</option>
                            <option value="Monthly" title="This option will start the same broadcast AFRESH every month, and place calls to recepient(s), follower(s) or subscriber(s) until the list is exhausted. The option is good for broadcasting monthly recurrent messages.">Monthly</option>
                            <option value="Yearly" title="This option will start the same broadcast AFRESH every year, and place calls to recepient(s), follower(s) or subscriber(s) until the list is exhausted. The option is good for broadcasting yearly recurrent messages.">Yearly</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="float: right; font-style:italic;">Who can see this post?:
                </td>
                <td>
                    <select id="shared" name="shared"/>
                            <option selected="selected" value="OnlyMe">Only me</option>
                            <option value="Followers">Followers</option>
                            <?php if($isProvider) { ?>
                                <option value="Subscribers">Subscribers</option>
                            <?php } ?>
                            <!--<option value="SpecificFollower">Specific Follower</option> -->
                        </select>
                    </div>
                </td>
            </tr>
            <tr><td>
                    <div class="datetime" style="float: right; font-style:italic;">Start Time:</div>
                </td>
                <td><input type="text" name="date" id="thisdate"  style="float:left; background-color:#F3F9DD;">
                    <img src="sample/images/cal.gif" onclick="javascript:NewCssCal('thisdate','yyyyMMdd','dropdown',true,'24',true,'future')" style="cursor:pointer; height:20px;"/>
                </td>
            </tr>
        </table>
                    <br />
                    <textarea rows="2" name="sub_description" id="sub_description" style="display:none;" placeholder="Give a detail description of your uploaded audio here..." onfocus="emptyElement('status')" onkeyup="restrict('sub_description')"></textarea>
                    <input type="submit" value="Post" id="btnSubmit" style="font-face:bold; font-size:16px;">
                    <div id="status" style="color:white; font-style:italic;"></div><br />
                </div>
            </form>
        </div>
        <div id="homeFeed"></div>
        </div>
<?php } else { ?>
   <div id="homeFeed1">
   <?php include_once("functions/feedVisitorUpdate.php"); ?>
    </div></div>
<?php } ?>
<?php include_once("template_pageRight.php"); ?>
</div>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="sample/datetimepicker_css.js"></script>
<script src="js/header_scroll.js"></script>
<script src="js/main.js"></script>
<script src="js/sticky-master/jquery.sticky.js"></script>
<script src="js/moment.js"></script>
<script src="js/livestamp.js"></script>
<script src="js/functions.js"></script>

</body>
</html>
