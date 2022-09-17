<?php
include("./php_includes/mysqli_connect.php");

$sql = "SELECT * FROM users WHERE username=:username AND activated='1' LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $u, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();
if($count < 1) {
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
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $profile_id = $row["id"];
    $username = $row["username"];
    $password = $row['password'];
    $email = $row["email"];
    $firstname = $row["firstname"];
    $lastname = $row["lastname"];
    $fullName = $row['fullname'];
    $countrycode = $row['countrycode'];
    $mobile = $row["mobile"];
    $isdn = $row['isdn'];
    $avatar = $row["avatar"];
    $website = $row['website'];
    $alias = $row['alias'];
    $about = $row['about'];
    $signup = $row["signup"];
    $lastlogin = $row["lastlogin"];
    $joindate = strftime("%b %d, %Y", strtotime($signup));
    $lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
    $naatcast = 'naatcast';
    $naatcastbilling = 'naatcast.billing';
    $naatcastsupport = 'naatcast.support';
    $naatcastreport = 'naatcast.report';
    $naatcastothers = 'naatcast.others';
}
$profile_pic = '<img src="user/'.$u.'/'.$avatar.'" alt="'.$u.'" style="border: solid 3px white">';
?><?php
$following = false;
//$unfollow = false;
if($u != $log_username && $user_ok == true){
    $following_check = "SELECT id FROM follows WHERE user1=:logusername AND user2=:user LIMIT 1";
    $stmt = $db_connect->prepare($following_check);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->bindParam(':user', $u, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count > 0){
        $following = true;
    }
    //$db_connect = null;
}
?><?php
//$follow_button = '<button style="display:none;">Follow</button>';
$follow_button = "";
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
include("./php_includes/mysqli_connect.php");
$sql = "SELECT COUNT(id) FROM follows WHERE user2=:user";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$follower_count = $row[0];
if($follower_count < 1){
    $followersHTML = "0";
} else {
    $followersHTML = $follower_count;
}
} else {
$sql = "SELECT COUNT(id) FROM follows WHERE user2=:user";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$follower_count = $row[0];
if($follower_count < 1){
    $followersHTML = "0";
} else {
    $followersHTML = $follower_count;
}
}
$db_connect = null;
//EVALUATE FOLLOWING
$followingHTML = '';
if($u == $log_username){
include("./php_includes/mysqli_connect.php");
$sql = "SELECT COUNT(id) FROM follows WHERE user1=:user";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$following_count = $row[0];
if($following_count < 1){
$followingHTML = "0";
} else {
    $followingHTML = $following_count;
}
} else {
include("./php_includes/mysqli_connect.php");
$sql = "SELECT COUNT(id) FROM follows WHERE user1=:user";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$following_count = $row[0];
if($following_count < 1){
    $followingHTML = "0";
} else {
    $followingHTML = $following_count;
}
}
$db_connect = null;
?><?php
// Get the alias of the user
$theAlias = "";
if($user_ok == true && $u == $log_username) {
include("./php_includes/mysqli_connect.php");
$sql = "SELECT alias FROM users WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $theAlias = $row['alias'];
    }
} else if($user_ok == true && $u != $log_username) {
    // Get the alias of other
include("./php_includes/mysqli_connect.php");
$sql = "SELECT alias FROM users WHERE username=:user";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $theAlias = $row['alias'];
    }
}
$db_connect = null;
?><?php
// Get alias status from useroptions table
$showAlias = "";
if($user_ok == true && $u == $log_username) {
include("./php_includes/mysqli_connect.php");
$sql = "SELECT aliascheck FROM useroptions WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $showAlias = $row['aliascheck'];
    }
} else if($user_ok == true && $u != $log_username) {
    include("./php_includes/mysqli_connect.php");
    $sql = "SELECT aliascheck FROM useroptions WHERE username=:user";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':user', $u, PDO::PARAM_STR);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $showAlias = $row['aliascheck'];
        }
}
$db_connect = null;
?><?php
$visited_mcheck = "";
if($user_ok == true && $u != $log_username) {
    include("./php_includes/mysqli_connect.php");
    $sql = "SELECT mobilecheck FROM useroptions WHERE username=:user";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':user', $u, PDO::PARAM_STR);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $visited_mcheck = $row['mobilecheck'];
    }
} else if($user_ok == true && $u == $log_username) {
    include("./php_includes/mysqli_connect.php");
    $sql = "SELECT mobilecheck FROM useroptions WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $visited_mcheck = $row['mobilecheck'];
    }
}
$db_connect = null;
?><?php
$visited_wcheck = "";
if($user_ok == true && $u == $log_username) {
    include("./php_includes/mysqli_connect.php");
    $sql = "SELECT websitecheck FROM useroptions WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $visited_wcheck = $row['websitecheck'];
    }
} else if($user_ok == true && $u != $log_username) {
    include("./php_includes/mysqli_connect.php");
    $sql = "SELECT websitecheck FROM useroptions WHERE username=:user";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':user', $u, PDO::PARAM_STR);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $visited_wcheck = $row['websitecheck'];
    }
}
$db_connect = null;
?><?php
$visited_acheck = "";
if($user_ok == true && $u == $log_username) {
    include("./php_includes/mysqli_connect.php");
    $sql = "SELECT aboutcheck FROM useroptions WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $visited_acheck = $row['aboutcheck'];
    }
} else if($user_ok == true && $u != $log_username) {
    include("./php_includes/mysqli_connect.php");
    $sql = "SELECT aboutcheck FROM useroptions WHERE username=:user";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':user', $u, PDO::PARAM_STR);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $visited_acheck = $row['aboutcheck'];
    }
}
$db_connect = null;
?><?php
$coverpic = "";
include("./php_includes/mysqli_connect.php");
$sql = "SELECT filename FROM photos WHERE user=:user ORDER BY RAND() LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$filename = $row[0];
if($filename > 0){
    $coverpic = '<img src="user/'.$u.'/'.$filename.'" alt="pic">';
}
$db_connect = null;
?><?php
if($user_ok == true && $u != $log_username) {
include("./php_includes/mysqli_connect.php");
$sql = "SELECT noaudiocheck FROM follows WHERE user1=:logusername AND user2=:user";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
$no_audio_check = $row['noaudiocheck'];
    }
}
$db_connect = null;
?><?php
// Check if a user is a provider or not
$isProvider = false;
$approved = '1';
if($user_ok == true && $u == $log_username) {
include("./php_includes/mysqli_connect.php");
$sql = "SELECT id FROM content_provider WHERE provider=:provider AND approved=:approved LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':provider', $log_username, PDO::PARAM_STR);
$stmt->bindParam(':approved', $approved, PDO::PARAM_STR);
$stmt->execute();
$numrows = $stmt->rowCount();
if($numrows == 1) {
    $isProvider = true;
}
} else if ($user_ok == true && $u != $log_username) {
    include("./php_includes/mysqli_connect.php");
    $sql = "SELECT id FROM content_provider WHERE provider=:provider AND approved=:approved LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':provider', $u, PDO::PARAM_STR);
    $stmt->bindParam('approved', $approved, PDO::PARAM_STR);
    $stmt->execute();
    $numrows = $stmt->rowCount();
    if($numrows == 1) {
        $isProvider = true;
    }
}
$db_connect = null;
?><?php
// Check the number of subscriber a user has
$subscriberHTML = '';
if($user_ok == true && $u == $log_username) {
include("./php_includes/mysqli_connect.php");
$sql = "SELECT COUNT(id) FROM subscription WHERE provider=:provider";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':provider', $log_username, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$subscriber_count = $row[0];
if($subscriber_count < 1){
    $subscriberHTML = "0";
} else {
    $subscriberHTML = $subscriber_count;
}
}  else if($user_ok == true && $u != $log_username) {
    include("./php_includes/mysqli_connect.php");
    $sql = "SELECT COUNT(id) FROM subscription WHERE provider=:provider";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':provider', $u, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $subscriber_count = $row[0];
    if($subscriber_count < 1){
        $subscriberHTML = "0";
    } else {
        $subscriberHTML = $subscriber_count;
    }
}
?><?php
// EVALUATE SUBSCRIBED
$subscribed = false;

if($u != $log_username && $user_ok == true){
    $subscribed_check = "SELECT id FROM subscription WHERE subscriber=:subscriber AND provider=:provider LIMIT 1";
    $stmt = $db_connect->prepare($subscribed_check);
    $stmt->bindParam(':subscriber', $log_username, PDO::PARAM_STR);
    $stmt->bindParam(':provider', $u, PDO::PARAM_STR);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        $subscribed = true;
    }
}
if($subscribed == true){
    $subscription_Btn = '<button onclick="subscriptionToggle(\'unsubscribe\',\''.$u.'\',\'subscribeBtn\')">Unsubscribe</button>';
    } elseif($u != $log_username && $subscribed == false && $user_ok == true) {
        $subscription_Btn = '<button onclick="subscriptionToggle(\'subscribe\',\''.$u.'\',\'subscribeBtn\')">Subscribe</button>';
    } else {
        $subscription_Btn = '<button style="display:none;">Me</button>';
    }
?><?php
$pageleft = '<div id="PageLeft">';
        $pageleft .= '<div id="pix">';
        $pageleft .= '<div id="profile_pic_box" style="border: solid 1px white; background-color:#bdbdbd;">'.$profile_pic;
        if($isProvider == true) {
            $pageleft .= '<img style="width:40px; height:40px; border:none;" src="./images/emblem-pro.svg" id="profileimage">';
        }
        $pageleft .= '</div>';
        if($showAlias == '1') {
        $pageleft .= '<div id="profileName" style="background-color:#004080; color:white; margin-bottom:-10px;"><h3>'.$fullName.'</h3></div>'.'</div>';
        } else if($showAlias == '0') {
            $pageleft .= '<div id="profileName" style="background-color:#004080; color:white; margin-bottom:-10px;"><h3>'.$theAlias.'</h3></div>'.'</div>';
        }
        if($visited_acheck == '1'){
        $pageleft .= '<div class="About" id="aboutNone" style="border:solid 1px #bdbdbd; text-align:center; display:none; background-color:white;">';
        $pageleft .= '<p style="background-color:#bdbdbd; color:white; font-weight:600; margin-top:0; margin-bottom:0;">'.'About Me'.'</p>';
        $pageleft .= '<div style="font-size:90%;">'.$about.'</div>'.'</div>';
        } else {
            $pageleft .= '<div class="About" id="aboutBlock" style="border:solid 1px #bdbdbd; text-align:center; display:block; background-color:white;">';
            $pageleft .= '<p style="background-color:#bdbdbd; color:white; font-weight:600; margin-top:0;margin-bottom:0;">'.'About Me'.'</p>';
            $pageleft .= '<div style="font-size:90%;">'.$about.'</div>'.'</div>'.'<br />';
        }
        if($u != $naatcast && $u != $naatcastbilling && $u != $naatcastsupport && $u != $naatcastreport && $u != $naatcastothers) {
            $pageleft .= '<div id="followCnt" style="border:solid 1px #bdbdbd; background-color:white;">';
            $pageleft .= '<a id="followerscnt" href="http://localhost:8080/reminderapp/follow_page.php?u='.$u.'" style="color:#004080;"><b>Followers</b></a>';
            $pageleft .= '<span class="followerspan"><b>' .$followersHTML.'</b></span>';
            $pageleft .= '<a id="followingcnt" href="http://localhost:8080/reminderapp/following_page.php?u='.$u.'" style="color:#004080;"><b>Following</b></a>';
            $pageleft .= '<span class="followingspan">'.'<b>' .$followingHTML.'</b></span>';
            if($u == $log_username) {
            $pageleft .= '<span id="followBtn" style="display:none;">'.$follow_button.'</span>';
            } else {
                $pageleft .= '<span id="followBtn">'.$follow_button.'</span>';
            }
            $pageleft .= '</div>'.'<br />';
        }
        if($isProvider == true) {
            $pageleft .= '<div class="subscription" style="border:solid 1px #bdbdbd; background-color:white; margin-top:-15px; margin-bottom:20px; color:#004080; text-align:center;"><b>Subscribers</b>';
            $pageleft .= '<span style="font-size:20px; color:gray; display:block; margin-top:-5px;">'.$subscriberHTML.'</span>';
            $pageleft .= '<span id="subscribeBtn">'.$subscription_Btn.'</span>';
            $pageleft .= '</div>';
        }
        if($visited_mcheck == '0' && ($u != $naatcast && $u != $naatcastbilling && $u != $naatcastsupport && $u != $naatcastreport && $u != $naatcastothers)){
        $pageleft .= '<div id="userProfile" style="border:solid 1px #bdbdbd; background-color:white; margin-top:-15px;">';
        $pageleft .= '<div class="MobileIcon" title="Mobile" style="margin: 0 auto; color:#0066ff; text-align:center;">+'.''.$isdn.'</div>';
        $pageleft .= '</div>'.'<br />';
      } else if($visited_mcheck == '0' && ($u == $naatcast || $u == $naatcastbilling || $u == $naatcastsupport || $u == $naatcastreport || $u == $naatcastothers)) {
        $pageleft .= '<div id="userProfile" style="border:solid 1px #bdbdbd; background-color:white; margin-top:10px;">';
        $pageleft .= '<div class="MobileIcon" title="Mobile" style="margin: 0 auto; color:#0066ff; text-align:center;">+'.''.$isdn.'</div>';
        $pageleft .= '</div>'.'<br />';
      }
        if($user_ok == true && $u == $log_username && ($u != $naatcast && $u != $naatcastbilling && $u != $naatcastsupport && $u != $naatcastreport && $u != $naatcastothers)) {
        $pageleft .= '<div class="subscribe" style="border:solid 1px #bdbdbd; text-align:center; color:gray; background-color:white; margin-top:-15px;"><i class="fas fa-portrait" id="subicon"><a href="http://localhost:8080/reminderapp/subscribe.php?u='.$u.'"></i>My Subscription</a>'.'</div>'.'<br />';
        }
        if($user_ok == true && $u == $log_username && $u != $isProvider && ($u != $naatcast && $u != $naatcastbilling && $u != $naatcastsupport && $u != $naatcastreport && $u != $naatcastothers)) {
        $pageleft .= '<div class="provide" style="border:solid 1px #bdbdbd; text-align:center; font-weight:800; background-color:white; margin-top:-15px; margin-bottom: 20px;"><a href="http://localhost:8080/reminderapp/subscription_registration.php?u='.$u.'">Provide Content</a>'.'</div>';
        }
        if($visited_wcheck == '1'){
        $pageleft .= '<div class="Website" style="border:solid 3px white; text-align:center; display:none; background-color:white; word-wrap: break-word;"><a href="http://'.$website.'">http://'.$website.'</a>'.'</div>'.'<br />';
        } else {
            $pageleft .= '<div class="Website" style="border:solid 1px #bdbdbd; text-align:center; display:block; background-color:white; margin-top:-15px; word-wrap: break-word;"><a href="http://'.$website.'">http://'.$website.'</a>'.'</div>'.'<br />';
        }
        if($user_ok == true && $u != $log_username && $following == true) {
            $pageleft .= '<div class="noaudio" style="border:solid 1px #bdbdbd; text-align:center; background-color:white; font-weight:800; color:gray; margin-top:-15px;">';
            if($no_audio_check == "1") {
            $pageleft .= '<span style="color:#0066ff;">'.'No audio from this user'.' <input type="checkbox" id="noaudio1" onchange="checkaudio()" title="Check this if you don\'t want to receive any audio broadcast from this user anymore." checked>'.'</span>';
            } else {
                $pageleft .= '<span style="color:#0066ff;">'.'No audio from this user'.' <input type="checkbox" id="noaudio1" title="Check this if you don\'t want to receive any audio broadcast from this user anymore." onchange="checkaudio()">'.'</span>';
            }
            $pageleft .= '</div>';
        }
        if($u == $log_username && $u == $naatcast ) {
            $pageleft .= '<div class="subscribe" style="border:solid 1px #bdbdbd; text-align:center; font-weight:800; color:gray; background-color:white; margin-top:-5px;"><a href="http://localhost:8080/reminderapp/provider_mgt_page.php?u='.$u.'">Provider Management</a>'.'</div>'.'<br />';
            $pageleft .= '<div class="subscribe" style="border:solid 1px #bdbdbd; text-align:center; font-weight:800; color:gray; background-color:white; margin-top:-15px;"><a href="http://localhost:8080/reminderapp/manage_users.php?u='.$u.'">User Management</a>'.'</div>'.'<br />';
            $pageleft .= '<div class="subscribe" style="border:solid 1px #bdbdbd; text-align:center; font-weight:800; color:gray; background-color:white; margin-top:-15px;"><a href="http://localhost:8080/reminderapp/upload_pricing.php?u='.$u.'">Rate Management</a>'.'</div>'.'<br />';
        } elseif ($u == $log_username && ($u == $naatcastbilling || $u == $naatcastsupport || $u == $naatcastreport || $u == $naatcastothers)) {
          $pageleft .= '<div class="subscribe" style="border:solid 1px #bdbdbd; text-align:center; font-weight:800; color:gray; background-color:white; margin-top:-15px;"><a href="http://localhost:8080/reminderapp/manage_users.php?u='.$u.'">User Management</a>'.'</div>'.'<br />';
    }
    $pageleft .= '</div>';
?>
