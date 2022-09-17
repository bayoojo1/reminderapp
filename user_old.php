<?php
include_once("php_includes/check_login_status.php");
// Initialize any variables that the page might echo
$u = "";
$firstname = "";
$lastname = "";
$mobile = "";
$profile_pic = "";
$profile_pic_btn = "";
$avatar_form = "";
$audio_upload_pic = "";
$audio_upload_btn = "";
$audio_upload_form = "";
$joindate = "";
$lastsession = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
    //$e = mysqli_real_escape_string($db_connect, $_GET['e']);
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_GET['u']);
} else {
    header("location: location: http://localhost:8080/reminderapp/login.php");
    exit();
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
$user_query = mysqli_query($db_connect, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
    echo "That user does not exist or is not yet activated, press back";
    exit();
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
    $isOwner = "yes";
    $profile_pic_btn = '<a href="#" onclick="return false;" onmousedown="toggleElement(\'avatar_form\')">Toggle Avatar Form</a>';
    $avatar_form  = '<form id="avatar_form" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">';
    $avatar_form .=   '<h4>Change your avatar</h4>';
    $avatar_form .=   '<input type="file" name="avatar" required>';
    $avatar_form .=   '<p><input type="submit" value="Upload"></p>';
    $avatar_form .= '</form>';
}

// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
    $profile_id = $row["id"];
    $username = $row["username"];
    //$email = $row["email"];
    $firstname = $row["firstname"];
    $lastname = $row["lastname"];
    $countrycode = $row['countrycode'];
    $mobile = $row["mobile"];
    $avatar = $row["avatar"];
    $signup = $row["signup"];
    $lastlogin = $row["lastlogin"];
    $joindate = strftime("%b %d, %Y", strtotime($signup));
    $lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
}
$profile_pic = '<img src="user/'.$u.'/'.$avatar.'" alt="'.$u.'">';
?><?php
$following = false;
//$unfollow = false;
if($u != $log_username && $user_ok == true){
    $following_check = "SELECT id FROM follows WHERE user1='$log_username' AND user2='$u' LIMIT 1";
    if(mysqli_num_rows(mysqli_query($db_connect, $following_check)) > 0){
        $following = true;
    }
}
?><?php
$follow_button = '<button disabled>Follow</button>';
//$unfollow_button = '<button hidden>Unfollow</button>';
//LOGIC FOR FOLLOW BUTTON
if($following == true){
    $follow_button = '<button onclick="followToggle(\'unfollow\',\''.$u.'\',\'followBtn\')">Unfollow</button>';
} else if($user_ok == true && $u != $log_username && $following == false) {
    $follow_button = '<button onclick="followToggle(\'follow\',\''.$u.'\',\'followBtn\')">Follow</button>';
}
?><?php
//EVALUATE FOLLOWERS
$followersHTML = '';
if($u == $log_username){
  $sql = "SELECT COUNT(id) FROM follows WHERE user2='$u'";
  $query = mysqli_query($db_connect, $sql);
  $query_count = mysqli_fetch_row($query);
  $follower_count = $query_count[0];
  if($follower_count < 1){
    $followersHTML = "0";
  } else {
    $followersHTML = $follower_count;
 }
} else {
  $sql = "SELECT COUNT(id) FROM follows WHERE user2='$u'";
  $query = mysqli_query($db_connect, $sql);
  $query_count = mysqli_fetch_row($query);
  $follower_count = $query_count[0];
  if($follower_count < 1){
       $followersHTML = "0";
   } else {
     $followersHTML = $follower_count;
 }
}

//EVALUATE FOLLOWING
$followingHTML = '';
if($u == $log_username){
$sql = "SELECT COUNT(id) FROM follows WHERE user1='$u'";
$query = mysqli_query($db_connect, $sql);
$query_count = mysqli_fetch_row($query);
$following_count = $query_count[0];
if($following_count < 1){
$followingHTML = "0";
} else {
    $followingHTML = $following_count;
}
} else {
  $sql = "SELECT COUNT(id) FROM follows WHERE user1='$u'";
  $query = mysqli_query($db_connect, $sql);
  $query_count = mysqli_fetch_row($query);
  $following_count = $query_count[0];
  if($following_count < 1){
    $followingHTML = "0";
  } else {
    $followingHTML = $following_count;
  }
}
?><?php
$coverpic = "";
$sql = "SELECT filename FROM photos WHERE user='$u' ORDER BY RAND() LIMIT 1";
$query = mysqli_query($db_connect, $sql);
if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_row($query);
    $filename = $row[0];
    $coverpic = '<img src="user/'.$u.'/'.$filename.'" alt="pic">';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $u; ?></title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
div#profile_pic_box{float:left; border:#999 2px solid; width:200px; height:200px; margin:20px 30px 0px 0px; overflow-y:hidden; border-radius: 20%}
div#profile_pic_box > img{z-index:2000; width:200px;}
div#profile_pic_box > a {
    display: none;
    position:absolute;
    margin:140px 0px 0px 120px;
    z-index:4000;
    background:#D8F08E;
    border:#81A332 1px solid;
    border-radius:3px;
    padding:5px;
    font-size:12px;
    text-decoration:none;
    color:#60750B;
}
div#profile_pic_box > form{
    display:none;
    position:absolute;
    z-index:3000;
    padding:10px;
    opacity:.8;
    background:#F0FEC2;
    width:180px;
    height:180px;
    border-radius:20%;
}
div#profile_pic_box:hover a {
    display: block;
}

</style>


<script src="js/ajax.original.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/header_scroll.js"></script>
<script type="text/javascript">
function followToggle(type, user, elem) {
    var conf = confirm("Press OK to confirm the '" + type + "' action for user <?php echo $firstname; ?>.");
    if (conf != true) {
        return false;
    }
    _(elem).innerHTML = 'please wait ...';
    var ajax = ajaxObj("POST", "php_parsers/follow_system.php");
    ajax.onreadystatechange = function() {
        if (ajaxReturn(ajax) == true) {
            if (ajax.responseText == "follow_ok") {
                _(elem).innerHTML = '<button onclick="followToggle(\'unfollow\',\'<?php echo $u; ?>\',\'followBtn\')">Unfollow</button>';
            } else if (ajax.responseText == "unfollow_ok") {
                _(elem).innerHTML = '<button onclick="followToggle(\'follow\',\'<?php echo $u; ?>\',\'followBtn\')">Follow</button>';
            } else {
                alert(ajax.responseText);
                _(elem).innerHTML = 'Try again later';
            }
        }
    }
    ajax.send("type="+type+"&user="+user);
}

function restrict(elem){
    var tf = _(elem);
    var rx = new RegExp;
    if(elem == "date"){
        rx = /[^0-9-]/gi;
    } else if(elem == "time"){
        rx = /[^0-9:]/gi;
    } else if(elem == "mobile"){
        rx = /[^0-9,]/gi;
    }
    tf.value = tf.value.replace(rx, "");
}

function emptyElement(x){
    _(x).innerHTML = "";
}

function sendCall() {
    var t = _("message").value;
    var m = _("mobile").value;
    var f = _("followers").value;
    var d = _("date").value;
    var k = _("time").value;
    var r = _("recurrent").value;
    var sh = _("shared").value;
    var status = _("status");
    if (t == "" || d == "" || k == "") {
        status.innerHTML = "Fill out all of the form data";
    } else {
        _("signupbtn").style.display = "none";
        status.innerHTML = 'please wait ...';
        var ajax = ajaxObj("POST", "functions/app_checkbox.php");
        ajax.onreadystatechange = function() {
            if (ajaxReturn(ajax) == true) {
                if (ajax.responseText != "OK") {
                    status.innerHTML = ajax.responseText;
                    _("signupbtn").style.display = "block";
                } else {
                    window.scrollTo(0, 0);
                }
            }
        }
        ajax.send("t=" + t + "&m=" + m + "&f=" + f + "&d=" + d + "&k=" + k + "&r=" + r + "&sh=" + sh);
    }
}

function feed() {
  var xhttp;
  if (window.XMLHttpRequest) {
    // code for modern browsers
    xhttp = new XMLHttpRequest();
    } else {
    // code for IE6, IE5
    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("homeFeed").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "functions/feedUpdate_New.php", true);
  xhttp.send();
}
//ajax for deleting post

function deletePost(id) {
  var xhttp;
  if (window.XMLHttpRequest) {
    // code for modern browsers
    xhttp = new XMLHttpRequest();
    } else {
    // code for IE6, IE5
    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("homeFeed").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "functions/deletePost.php?id="+id+"&status=delete", true);
  xhttp.send(null);
}

$(document).ready(function() {
    $("#hide").hide();

    $("#message").click(function() {
      $("#hide").toggle(1000);
    });

});

$(document).ready(function() {
    $("#mobile").click(function() {
      $("#follower").hide();
    });

});

$(document).ready(function() {
    $("#follower").change(function() {
        if($(this).find("option:selected").val() == "Yes") {
            $("#mobile").hide();
        }
    });
});

</script>
</head>
<body class="insidepage">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div><br /><br />
<div id="PageLeft">
    <div id="userContent">
        <div id="profile_pic_box">
            <?php
                echo $profile_pic_btn;
            ?><?php
                echo $avatar_form;
            ?><?php
                echo $profile_pic; ?>
        </div>
        <h2><?php echo $firstname.' '.$lastname; ?></h2>
        <p>Is the viewer the page owner, logged in and verified? <b><?php echo $isOwner; ?></b></p>
        <p>Mobile: <?php echo $mobile; ?></p>
        <p>Join Date: <?php echo $joindate; ?></p>
        <p>Last Session: <?php echo $lastsession; ?></p>
        <hr />
        <p><span id="followBtn"><?php echo $follow_button; ?></p>
        <hr />
        <p>Following: <?php echo $followingHTML; ?></p>
        <p>Follower: <?php echo $followersHTML; ?></p>
            <hr />
    </div>
</div>
<div id="PageMiddle"> <?php if( $u != $log_username ){ ?>
        <style type="text/css">div#PageMiddle{
            display:none;
}       </style>
<?php
} ?>
  <?php //include_once("functions/app.php") ?>
    <div id="pageMiddleForm">
        <form name="signupform" id="signupform" onsubmit="return false;">
            <textarea rows="3" id="message" placeholder="Write your message here <?php echo $firstname ?>"  onfocus="emptyElement('status')"></textarea>
            <br />
            <div id="hide">
                <textarea rows="2" id="mobile" placeholder="Enter recepient mobile(s) here, separated by comma..." form="signupform" onfocus="emptyElement('status')" onkeyup="restrict('mobile')"></textarea>
                <br /><br /><br />
                <div id="follower">Broadcast to Followers mobile:
                <select id="followers" name="followers" style="width: 110px;"/>
                        <option selected="selected" value="No">No</option>
                        <option value="Yes">Yes</option>
                </select></div><br /><br >
                <div>Schedule:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input id="date"
                type="text"
                placeholder="yyyy-mm-dd"
                pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                title="You need to enter the correct pattern YYYY-MM-DD for your schedule to work as expected"
                size="11"
                onfocus="emptyElement('status')" onkeyup="restrict('date')">
                <input id="time"
                type="text"
                placeholder="hh:mm:ss"
                pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}"
                title="You need to enter the correct pattern HH:MM:SS for your schedule to work as expected"
                size="11"
                onfocus="emptyElement('status')" onkeyup="restrict('time')">
                </div><br /><br />
                <div>Recurrent(Optional):&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <select id="recurrent" name="recurrent" style="width: 110px;"/>
                        <option selected="selected" value="Once">Once</option>
                        <option value="Daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                </div><br /><br />
                <div>Who can see this post?:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <select id="shared" name="shared" style="width: 110px;"/>
                        <option selected="selected" value="OnlyMe">Only me</option>
                        <option value="Followers">Followers</option>
                        <option value="SpecificFollower">Specific Follower</option>
                    </select>
                </div><br /><br />
                <button id="signupbtn" onclick="sendCall();feed();">Submit</button>
                <span id="status"></span>
            </div>
        </form>
    </div>
<br />
<div id="homeFeed">
            <?php include_once("functions/feedUpdate_New.php"); ?>
        </div> </div>
<div id="PageRight"></div>
<?php //include_once("template_pageBottom.php"); ?>
<script src="js/main.js"></script>
</body>
</html>
