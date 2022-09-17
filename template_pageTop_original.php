<?php
// It is important for any file that includes this file, to have
// check_login_status.php included at its very top.
include_once("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");
$notify_result = "";
$notification = "";
$friend = "";
$home = "";
$rss = "";
$loginLink = '<a class="extlogin" href="login.php" style="color: white;"><b>Log In</b></a>&nbsp;&nbsp;<a class="extsignup" href="signup.php" style="color: white;"><b>Sign Up</b></a>';
if($user_ok == true) {
    $naatcast = 'naatcast';
    $Followers = 'Followers';
    $Subscribers = 'Subscribers';

    //$perPage = 10;

    $sql_statement = "SELECT once.id, message, audio, audio_desc, postdate, tag, broadcast, once.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM once INNER JOIN follows ON once.username1 = follows.user2 INNER JOIN users ON once.username1 = users.username INNER JOIN useroptions ON once.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND once.broadcast=:followers AND date_visit.latest_visit < once.postdate
    UNION
    SELECT daily.id, message, audio, audio_desc, postdate, tag, broadcast, daily.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM daily INNER JOIN follows ON daily.username1 = follows.user2 INNER JOIN users ON daily.username1 = users.username INNER JOIN useroptions ON daily.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND daily.broadcast=:followers AND date_visit.latest_visit < daily.postdate
    UNION
    SELECT weekly.id, message, audio, audio_desc, postdate, tag, broadcast, weekly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM weekly INNER JOIN follows ON weekly.username1 = follows.user2 INNER JOIN users ON weekly.username1 = users.username INNER JOIN useroptions ON weekly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND weekly.broadcast=:followers AND date_visit.latest_visit < weekly.postdate
    UNION
    SELECT monthly.id, message, audio, audio_desc, postdate, tag, broadcast, monthly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM monthly INNER JOIN follows ON monthly.username1 = follows.user2 INNER JOIN users ON monthly.username1 = users.username INNER JOIN useroptions ON monthly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND monthly.broadcast=:followers AND date_visit.latest_visit < monthly.postdate
    UNION
    SELECT yearly.id, message, audio, audio_desc, postdate, tag, broadcast, yearly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM yearly INNER JOIN follows ON yearly.username1 = follows.user2 INNER JOIN users ON yearly.username1 = users.username INNER JOIN useroptions ON yearly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND yearly.broadcast=:followers AND date_visit.latest_visit < yearly.postdate
    UNION
    /* For  dailyround */
    SELECT dailyround.id, message, audio, audio_desc, postdate, tag, broadcast, dailyround.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyround INNER JOIN follows ON dailyround.username1 = follows.user2 INNER JOIN users ON dailyround.username1 = users.username INNER JOIN useroptions ON dailyround.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailyround.broadcast=:followers AND date_visit.latest_visit < dailyround.postdate
    UNION
    /* For dailydaytime */
    SELECT dailydaytime.id, message, audio, audio_desc, postdate, tag, broadcast, dailydaytime.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailydaytime INNER JOIN follows ON dailydaytime.username1 = follows.user2 INNER JOIN users ON dailydaytime.username1 = users.username INNER JOIN useroptions ON dailydaytime.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailydaytime.broadcast=:followers AND date_visit.latest_visit < dailydaytime.postdate
    UNION
    /* For dailynight */
    SELECT dailynight.id, message, audio, audio_desc, postdate, tag, broadcast, dailynight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailynight INNER JOIN follows ON dailynight.username1 = follows.user2 INNER JOIN users ON dailynight.username1 = users.username INNER JOIN useroptions ON dailynight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailynight.broadcast=:followers AND date_visit.latest_visit < dailynight.postdate
    UNION
    /* For dailyweekdaytime */
    SELECT dailyweekdaytime.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekdaytime.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekdaytime INNER JOIN follows ON dailyweekdaytime.username1 = follows.user2 INNER JOIN users ON dailyweekdaytime.username1 = users.username INNER JOIN useroptions ON dailyweekdaytime.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailyweekdaytime.broadcast=:followers AND date_visit.latest_visit < dailyweekdaytime.postdate
    UNION
    /* For dailyweekdaynight */
    SELECT dailyweekdaynight.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekdaynight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekdaynight INNER JOIN follows ON dailyweekdaynight.username1 = follows.user2 INNER JOIN users ON dailyweekdaynight.username1 = users.username INNER JOIN useroptions ON dailyweekdaynight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailyweekdaynight.broadcast=:followers AND date_visit.latest_visit < dailyweekdaynight.postdate
    UNION
    /* For dailyweekendday */
    SELECT dailyweekendday.id, dailyweekendday.username1, message, audio, audio_desc, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekendday INNER JOIN follows ON dailyweekendday.username1 = follows.user2 INNER JOIN users ON dailyweekendday.username1 = users.username INNER JOIN useroptions ON dailyweekendday.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailyweekendday.broadcast=:followers AND date_visit.latest_visit < dailyweekendday.postdate
    UNION
    /* For dailyweekendnight */
    SELECT dailyweekendnight.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekendnight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekendnight INNER JOIN follows ON dailyweekendnight.username1 = follows.user2 INNER JOIN users ON dailyweekendnight.username1 = users.username INNER JOIN useroptions ON dailyweekendnight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailyweekendnight.broadcast=:followers AND date_visit.latest_visit < dailyweekendnight.postdate
    UNION
    /* Subscribers */
    SELECT once.id, message, audio, audio_desc, postdate, tag, broadcast, once.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM once INNER JOIN subscription ON once.username1 = subscription.provider INNER JOIN users ON once.username1 = users.username INNER JOIN useroptions ON once.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND once.broadcast=:subscribers AND date_visit.latest_visit < once.postdate
    UNION
    SELECT daily.id, message, audio, audio_desc, postdate, tag, broadcast, daily.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM daily INNER JOIN subscription ON daily.username1 = subscription.provider INNER JOIN users ON daily.username1 = users.username INNER JOIN useroptions ON daily.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND daily.broadcast=:subscribers AND date_visit.latest_visit < daily.postdate
    UNION
    SELECT weekly.id, message, audio, audio_desc, postdate, tag, broadcast, weekly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM weekly INNER JOIN subscription ON weekly.username1 = subscription.provider INNER JOIN users ON weekly.username1 = users.username INNER JOIN useroptions ON weekly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND weekly.broadcast=:subscribers AND date_visit.latest_visit < weekly.postdate
    UNION
    SELECT monthly.id, message, audio, audio_desc, postdate, tag, broadcast, monthly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM monthly INNER JOIN subscription ON monthly.username1 = subscription.provider INNER JOIN users ON monthly.username1 = users.username INNER JOIN useroptions ON monthly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND monthly.broadcast=:subscribers AND date_visit.latest_visit < monthly.postdate
    UNION
    SELECT yearly.id, message, audio, audio_desc, postdate, tag, broadcast, yearly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM yearly INNER JOIN subscription ON yearly.username1 = subscription.provider INNER JOIN users ON yearly.username1 = users.username INNER JOIN useroptions ON yearly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND yearly.broadcast=:subscribers AND date_visit.latest_visit < yearly.postdate
    UNION
    /* For dailyround */
    SELECT dailyround.id, message, audio, audio_desc, postdate, tag, broadcast, dailyround.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyround INNER JOIN subscription ON dailyround.username1 = subscription.provider INNER JOIN users ON dailyround.username1 = users.username INNER JOIN useroptions ON dailyround.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailyround.broadcast=:subscribers AND date_visit.latest_visit < dailyround.postdate
    UNION
    /* For dailydaytime */
    SELECT dailydaytime.id, message, audio, audio_desc, postdate, tag, broadcast, dailydaytime.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailydaytime INNER JOIN subscription ON dailydaytime.username1 = subscription.provider INNER JOIN users ON dailydaytime.username1 = users.username INNER JOIN useroptions ON dailydaytime.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailydaytime.broadcast=:subscribers AND date_visit.latest_visit < dailydaytime.postdate
    UNION
    /* For dailynight */
    SELECT dailynight.id, message, audio, audio_desc, postdate, tag, broadcast, dailynight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailynight INNER JOIN subscription ON dailynight.username1 = subscription.provider INNER JOIN users ON dailynight.username1 = users.username INNER JOIN useroptions ON dailynight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailynight.broadcast=:subscribers AND date_visit.latest_visit < dailynight.postdate
    UNION
    /* For dailyweekdaytime */
    SELECT dailyweekdaytime.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekdaytime.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekdaytime INNER JOIN subscription ON dailyweekdaytime.username1 = subscription.provider INNER JOIN users ON dailyweekdaytime.username1 = users.username INNER JOIN useroptions ON dailyweekdaytime.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailyweekdaytime.broadcast=:subscribers AND date_visit.latest_visit < dailyweekdaytime.postdate
    UNION
    /* For dailyweekdaynight */
    SELECT dailyweekdaynight.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekdaynight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekdaynight INNER JOIN subscription ON dailyweekdaynight.username1 = subscription.provider INNER JOIN users ON dailyweekdaynight.username1 = users.username INNER JOIN useroptions ON dailyweekdaynight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailyweekdaynight.broadcast=:subscribers AND date_visit.latest_visit < dailyweekdaynight.postdate
    UNION
    /* For dailyweekendday */
    SELECT dailyweekendday.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekendday.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekendday INNER JOIN subscription ON dailyweekendday.username1 = subscription.provider INNER JOIN users ON dailyweekendday.username1 = users.username INNER JOIN useroptions ON dailyweekendday.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailyweekendday.broadcast=:subscribers AND date_visit.latest_visit < dailyweekendday.postdate
    UNION
    /* For dailyweekendnight */
    SELECT dailyweekendnight.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekendnight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekendnight INNER JOIN subscription ON dailyweekendnight.username1 = subscription.provider INNER JOIN users ON dailyweekendnight.username1 = users.username INNER JOIN useroptions ON dailyweekendnight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailyweekendnight.broadcast=:subscribers AND date_visit.latest_visit < dailyweekendnight.postdate ORDER BY postdate DESC";

    // Get the total row count of all the db entries first
    $stmt1 = $db_connect->prepare($sql_statement);
    $stmt1->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt1->bindParam(':followers', $Followers, PDO::PARAM_STR);
    $stmt1->bindParam(':subscribers', $Subscribers, PDO::PARAM_STR);
    $stmt1->execute();

    $totalcount = $stmt1->rowCount();

    $notification_count = "";
    if($totalcount > 9) {
        $notification_count = "9+";
    } else {
        $notification_count = $totalcount;
    }

    $sql = $sql_statement . " LIMIT ". 100;
    $stmt = $db_connect->prepare($sql);
    //$stmt->bindParam(':one', $one, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->bindParam(':followers', $Followers, PDO::PARAM_STR);
    $stmt->bindParam(':subscribers', $Subscribers, PDO::PARAM_STR);
    //$stmt->bindParam(':naatcast', $naatcast, PDO::PARAM_STR);
    $stmt->execute();
    $numrows = $stmt->rowCount();

        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            // Write your code here
            $avatar = $row['avatar'];
            $fullname = $row['fullname'];
            $postdate = $row['postdate'];
            $b = date_create($postdate);
            $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
            $notify_result .= '<div id="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
            $notify_result .= '<a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['username1'].'&id='.$row['id'].'&tag='.$row['tag'].'"><img src="user/'.$row['username1'].'/'.$row['avatar'].'" alt="'.$row['username1'].'" width="41" height="40">';
                if($row['aliascheck'] == '1') {
                    $notify_result .= '<div class="notification-user" style="color:#004080; font-weight:bold; margin-left:8px;">'.$row['fullname'].'</div>';
                } else if($row['aliascheck'] == '0') {
                    $notify_result .= '<div class="notification-user" style="color:#004080; font-weight:bold; margin-left:8px;">'.$row['alias'].'</div>';
                }
                if(!empty($row['message'])) {
                    $notify_result .= '<div style="margin-left:8px;" class="notification-message">'.substr($row['message'],0,30).'...</div>';
                } else if(!empty($row['audio'])) {
                    $notify_result .= '<div style="margin-left:8px;" class="notification-message">'.substr($row['audio_desc'],0,30).'...</div>';
                }
                $notify_result .= '</a>';
                $notify_result .= '<div id="notification-date" style="color:#004080; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.$readabledate.'</div>';

            $notify_result .= '</div>';
            $notify_result .= '<hr>';
        }

    if ($notification_count == "0") {
        $notification .= '<div id="notify" style="display:inline-block;">'.'<a href="notification_page.php?u='.$log_username.'"><i class="fas fa-bell fa-2x" id="note_still" alt="Notes" title="Notifications" style="color:#3fa9f5; margin-top:0.8px;"></i>'.'</a></div>';
    } else {
        $notification .= '<div id="notify" style="display:inline-block;" onclick="zeroCounter()">'.'<i class="fas fa-bell fa-2x" id="note_still" alt="Notes" title="Notifications" style="color:white; cursor:pointer;margin-top:0.8px;"></i>'.'<span id="totalcount" style="background-color:red; color:white; width:50px; height:20px; font-size:12px; margin-top:5px; cursor:pointer;">'.$notification_count.'</span>';
        $notification .= '<div id="notecontainer" class="notecontainer" style="display:none; border:solid 1px lightslategray; margin-top:4px;">';
        $notification .= '<div style="text-align:center; color:#004080; font-weight:800; font-size:14px; z-index:9999; background-color:white; height:30px;">NOTIFICATIONS</div>';
        $notification .= '<div id="notedisplay" class="notedisplay">'.$notify_result.'</div>';
        $notification .= '<div id="spanBase" style="height:40px; width:320px; background-color:gray; text-align:center; margin-top:-2px;"><a style="color:white; font-weight:800;" href="notification_page.php?u='.$log_username.'">View all notifications</a></div>';
        $notification .= '</div>';
        $notification .= '</div>';
    }
    $loginLink = '<a href="logout.php"><i class="fas fa-sign-out-alt fa-2x" id="logoutid" alt="logout" title="Logout" style="color:#3fa9f5;"></i></a>';
        $friend = '<a href="follow_page.php?u='.$log_username.'"><i class="fas fa-user-plus fa-2x" id="friendid" alt="follow" title="Follow" style="color:#3fa9f5;"></i></a>';
        $rss = '<a href="profile_page.php?u='.$log_username.'"><i class="fas fa-cog fa-2x" id="settingsid" alt="rss" title="Profile Setting" style="color:#3fa9f5;"></i></a>';
        $home = '<a href="user_audio.php?u='.$log_username.'"><i class="fas fa-home fa-2x" id="homeid" alt="home" title="Home Feed" style="color:#3fa9f5;"></i></a>';
}
$db_connect = null;
?>
<div id="PageTop";>
    <div id="PageTopLogo">
        <?php if(!empty($log_username)) { ?>
      <a href="user_audio.php?u=<?php echo $log_username ?>">
        <img src="images/logo.svg" alt="logo" title="NaatCast">
      </a>
        <?php } else { ?>
            <a href="login.php">
            <img src="images/logo.svg" alt="logo" title="NaatCast">
            </a>
        <?php } ?>
    </div>
      <div id="menu1">
          <?php
          echo $loginLink;
          echo $notification;
          echo $rss;
          echo $friend;
          echo $home;
          ?>
      </div>
</div>
