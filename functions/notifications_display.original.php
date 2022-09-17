<?php
include("./php_includes/mysqli_connect.php");

$naatcast = 'naatcast';
$Followers = 'Followers';
$Subscribers = 'Subscribers';

$sql_statement = "SELECT once.id, message, audio, audio_desc, postdate, tag, broadcast, once.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM once INNER JOIN users ON once.username1 = users.username INNER JOIN useroptions ON once.username1 = useroptions.username WHERE once.username1=:naatcast
UNION
SELECT daily.id, message, audio, audio_desc, postdate, tag, broadcast, daily.username1, users.username,users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM daily INNER JOIN users ON daily.username1 = users.username INNER JOIN useroptions ON daily.username1 = useroptions.username WHERE daily.username1=:naatcast
UNION
SELECT weekly.id, message, audio, audio_desc, postdate, tag, broadcast, weekly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM weekly INNER JOIN users ON weekly.username1 = users.username INNER JOIN useroptions ON weekly.username1 = useroptions.username WHERE weekly.username1=:naatcast
UNION
SELECT monthly.id, message, audio, audio_desc, postdate, tag, broadcast, monthly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM monthly INNER JOIN users ON monthly.username1 = users.username INNER JOIN useroptions ON monthly.username1 = useroptions.username WHERE monthly.username1=:naatcast
UNION
SELECT yearly.id, message, audio, audio_desc, postdate, tag, broadcast, yearly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM yearly INNER JOIN users ON yearly.username1 = users.username INNER JOIN useroptions ON yearly.username1 = useroptions.username WHERE yearly.username1=:naatcast
UNION
/* For dailyround */
SELECT dailyround.id, message, audio, audio_desc, postdate, tag, broadcast, dailyround.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyround INNER JOIN users ON dailyround.username1 = users.username INNER JOIN useroptions ON dailyround.username1 = useroptions.username WHERE dailyround.username1=:naatcast
UNION
/* For dailydaytime */
SELECT dailydaytime.id, message, audio, audio_desc, postdate, tag, broadcast, dailydaytime.username1, users.username,users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailydaytime INNER JOIN users ON dailydaytime.username1 = users.username INNER JOIN useroptions ON dailydaytime.username1 = useroptions.username WHERE dailydaytime.username1=:naatcast
UNION
/* For dailynight */
SELECT dailynight.id, message, audio, audio_desc, postdate, tag, broadcast, dailynight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailynight INNER JOIN users ON dailynight.username1 = users.username INNER JOIN useroptions ON dailynight.username1 = useroptions.username WHERE dailynight.username1=:naatcast
UNION
/* For dailyweekdaytime */
SELECT dailyweekdaytime.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekdaytime.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekdaytime INNER JOIN users ON dailyweekdaytime.username1 = users.username INNER JOIN useroptions ON dailyweekdaytime.username1 = useroptions.username WHERE dailyweekdaytime.username1=:naatcast
UNION
/* For dailyweekdaynight */
SELECT dailyweekdaynight.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekdaynight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekdaynight INNER JOIN users ON dailyweekdaynight.username1 = users.username INNER JOIN useroptions ON dailyweekdaynight.username1 = useroptions.username WHERE dailyweekdaynight.username1=:naatcast
UNION
/* For dailyweekendday */
SELECT dailyweekendday.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekendday.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekendday INNER JOIN users ON dailyweekendday.username1 = users.username INNER JOIN useroptions ON dailyweekendday.username1 = useroptions.username WHERE dailyweekendday.username1=:naatcast
UNION
/* For dailyweekendnight */
SELECT dailyweekendnight.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekendnight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekendnight INNER JOIN users ON dailyweekendnight.username1 = users.username INNER JOIN useroptions ON dailyweekendnight.username1 = useroptions.username WHERE dailyweekendnight.username1=:naatcast
UNION
SELECT once.id, message, audio, audio_desc, postdate, tag, broadcast, once.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM once INNER JOIN follows ON once.username1 = follows.user2 INNER JOIN users ON once.username1 = users.username INNER JOIN useroptions ON once.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND once.broadcast=:followers AND date_visit.latest_visit > once.postdate
UNION
SELECT daily.id, message, audio, audio_desc, postdate, tag, broadcast, daily.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM daily INNER JOIN follows ON daily.username1 = follows.user2 INNER JOIN users ON daily.username1 = users.username INNER JOIN useroptions ON daily.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND daily.broadcast=:followers AND date_visit.latest_visit > daily.postdate
UNION
SELECT weekly.id, message, audio, audio_desc, postdate, tag, broadcast, weekly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM weekly INNER JOIN follows ON weekly.username1 = follows.user2 INNER JOIN users ON weekly.username1 = users.username INNER JOIN useroptions ON weekly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND weekly.broadcast=:followers AND date_visit.latest_visit > weekly.postdate
UNION
SELECT monthly.id, message, audio, audio_desc, postdate, tag, broadcast, monthly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM monthly INNER JOIN follows ON monthly.username1 = follows.user2 INNER JOIN users ON monthly.username1 = users.username INNER JOIN useroptions ON monthly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND monthly.broadcast=:followers AND date_visit.latest_visit > monthly.postdate
UNION
SELECT yearly.id, message, audio, audio_desc, postdate, tag, broadcast, yearly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM yearly INNER JOIN follows ON yearly.username1 = follows.user2 INNER JOIN users ON yearly.username1 = users.username INNER JOIN useroptions ON yearly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND yearly.broadcast=:followers AND date_visit.latest_visit > yearly.postdate
UNION
/* For  dailyround */
SELECT dailyround.id, message, audio, audio_desc, postdate, tag, broadcast, dailyround.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyround INNER JOIN follows ON dailyround.username1 = follows.user2 INNER JOIN users ON dailyround.username1 = users.username INNER JOIN useroptions ON dailyround.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailyround.broadcast=:followers AND date_visit.latest_visit > dailyround.postdate
UNION
/* For dailydaytime */
SELECT dailydaytime.id, message, audio, audio_desc, postdate, tag, broadcast, dailydaytime.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailydaytime INNER JOIN follows ON dailydaytime.username1 = follows.user2 INNER JOIN users ON dailydaytime.username1 = users.username INNER JOIN useroptions ON dailydaytime.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailydaytime.broadcast=:followers AND date_visit.latest_visit > dailydaytime.postdate
UNION
/* For dailynight */
SELECT dailynight.id, message, audio, audio_desc, postdate, tag, broadcast, dailynight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailynight INNER JOIN follows ON dailynight.username1 = follows.user2 INNER JOIN users ON dailynight.username1 = users.username INNER JOIN useroptions ON dailynight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailynight.broadcast=:followers AND date_visit.latest_visit > dailynight.postdate
UNION
/* For dailyweekdaytime */
SELECT dailyweekdaytime.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekdaytime.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekdaytime INNER JOIN follows ON dailyweekdaytime.username1 = follows.user2 INNER JOIN users ON dailyweekdaytime.username1 = users.username INNER JOIN useroptions ON dailyweekdaytime.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailyweekdaytime.broadcast=:followers AND date_visit.latest_visit > dailyweekdaytime.postdate
UNION
/* For dailyweekdaynight */
SELECT dailyweekdaynight.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekdaynight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekdaynight INNER JOIN follows ON dailyweekdaynight.username1 = follows.user2 INNER JOIN users ON dailyweekdaynight.username1 = users.username INNER JOIN useroptions ON dailyweekdaynight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailyweekdaynight.broadcast=:followers AND date_visit.latest_visit > dailyweekdaynight.postdate
UNION
/* For dailyweekendday */
SELECT dailyweekendday.id, dailyweekendday.username1, message, audio, audio_desc, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekendday INNER JOIN follows ON dailyweekendday.username1 = follows.user2 INNER JOIN users ON dailyweekendday.username1 = users.username INNER JOIN useroptions ON dailyweekendday.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailyweekendday.broadcast=:followers AND date_visit.latest_visit > dailyweekendday.postdate
UNION
/* For dailyweekendnight */
SELECT dailyweekendnight.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekendnight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekendnight INNER JOIN follows ON dailyweekendnight.username1 = follows.user2 INNER JOIN users ON dailyweekendnight.username1 = users.username INNER JOIN useroptions ON dailyweekendnight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = follows.user1 WHERE follows.user1=:logusername AND dailyweekendnight.broadcast=:followers AND date_visit.latest_visit > dailyweekendnight.postdate
UNION
/* Subscribers */
SELECT once.id, message, audio, audio_desc, postdate, tag, broadcast, once.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM once INNER JOIN subscription ON once.username1 = subscription.provider INNER JOIN users ON once.username1 = users.username INNER JOIN useroptions ON once.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND once.broadcast=:subscribers AND date_visit.latest_visit > once.postdate
UNION
SELECT daily.id, message, audio, audio_desc, postdate, tag, broadcast, daily.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM daily INNER JOIN subscription ON daily.username1 = subscription.provider INNER JOIN users ON daily.username1 = users.username INNER JOIN useroptions ON daily.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND daily.broadcast=:subscribers AND date_visit.latest_visit > daily.postdate
UNION
SELECT weekly.id, message, audio, audio_desc, postdate, tag, broadcast, weekly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM weekly INNER JOIN subscription ON weekly.username1 = subscription.provider INNER JOIN users ON weekly.username1 = users.username INNER JOIN useroptions ON weekly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND weekly.broadcast=:subscribers AND date_visit.latest_visit > weekly.postdate
UNION
SELECT monthly.id, message, audio, audio_desc, postdate, tag, broadcast, monthly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM monthly INNER JOIN subscription ON monthly.username1 = subscription.provider INNER JOIN users ON monthly.username1 = users.username INNER JOIN useroptions ON monthly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND monthly.broadcast=:subscribers AND date_visit.latest_visit > monthly.postdate
UNION
SELECT yearly.id, message, audio, audio_desc, postdate, tag, broadcast, yearly.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM yearly INNER JOIN subscription ON yearly.username1 = subscription.provider INNER JOIN users ON yearly.username1 = users.username INNER JOIN useroptions ON yearly.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND yearly.broadcast=:subscribers AND date_visit.latest_visit > yearly.postdate
UNION
/* For dailyround */
SELECT dailyround.id, message, audio, audio_desc, postdate, tag, broadcast, dailyround.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyround INNER JOIN subscription ON dailyround.username1 = subscription.provider INNER JOIN users ON dailyround.username1 = users.username INNER JOIN useroptions ON dailyround.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailyround.broadcast=:subscribers AND date_visit.latest_visit > dailyround.postdate
UNION
/* For dailydaytime */
SELECT dailydaytime.id, message, audio, audio_desc, postdate, tag, broadcast, dailydaytime.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailydaytime INNER JOIN subscription ON dailydaytime.username1 = subscription.provider INNER JOIN users ON dailydaytime.username1 = users.username INNER JOIN useroptions ON dailydaytime.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailydaytime.broadcast=:subscribers AND date_visit.latest_visit > dailydaytime.postdate
UNION
/* For dailynight */
SELECT dailynight.id, message, audio, audio_desc, postdate, tag, broadcast, dailynight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailynight INNER JOIN subscription ON dailynight.username1 = subscription.provider INNER JOIN users ON dailynight.username1 = users.username INNER JOIN useroptions ON dailynight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailynight.broadcast=:subscribers AND date_visit.latest_visit > dailynight.postdate
UNION
/* For dailyweekdaytime */
SELECT dailyweekdaytime.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekdaytime.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekdaytime INNER JOIN subscription ON dailyweekdaytime.username1 = subscription.provider INNER JOIN users ON dailyweekdaytime.username1 = users.username INNER JOIN useroptions ON dailyweekdaytime.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailyweekdaytime.broadcast=:subscribers AND date_visit.latest_visit > dailyweekdaytime.postdate
UNION
/* For dailyweekdaynight */
SELECT dailyweekdaynight.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekdaynight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekdaynight INNER JOIN subscription ON dailyweekdaynight.username1 = subscription.provider INNER JOIN users ON dailyweekdaynight.username1 = users.username INNER JOIN useroptions ON dailyweekdaynight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailyweekdaynight.broadcast=:subscribers AND date_visit.latest_visit > dailyweekdaynight.postdate
UNION
/* For dailyweekendday */
SELECT dailyweekendday.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekendday.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekendday INNER JOIN subscription ON dailyweekendday.username1 = subscription.provider INNER JOIN users ON dailyweekendday.username1 = users.username INNER JOIN useroptions ON dailyweekendday.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailyweekendday.broadcast=:subscribers AND date_visit.latest_visit > dailyweekendday.postdate
UNION
/* For dailyweekendnight */
SELECT dailyweekendnight.id, message, audio, audio_desc, postdate, tag, broadcast, dailyweekendnight.username1, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck FROM dailyweekendnight INNER JOIN subscription ON dailyweekendnight.username1 = subscription.provider INNER JOIN users ON dailyweekendnight.username1 = users.username INNER JOIN useroptions ON dailyweekendnight.username1 = useroptions.username INNER JOIN date_visit ON date_visit.username = subscription.subscriber WHERE subscription.subscriber=:logusername AND dailyweekendnight.broadcast=:subscribers AND date_visit.latest_visit > dailyweekendnight.postdate ORDER BY postdate DESC";

$stmt = $db_connect->prepare($sql_statement);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindParam(':followers', $Followers, PDO::PARAM_STR);
$stmt->bindParam(':subscribers', $Subscribers, PDO::PARAM_STR);
$stmt->bindParam(':naatcast', $naatcast, PDO::PARAM_STR);
$stmt->execute();
$numrows = $stmt->rowCount();

// Specify how many result per page
$rpp = '10';
// This tells us the page number of the last page
$last = ceil($numrows/$rpp);
// This makes sure $last cannot be less than 1
if($last < 1){
    $last = 1;
}
// Define pagination control
//$paginationCtrls = "";
// Define page number
$pn = "1";

// Get pagenum from URL vars if it is present, else it is = 1
if(isset($_GET['pn'])){
$pn = preg_replace('#[^0-9]#', '', $_GET['pn']);
//$searchquery = $_POST['searchquery'];
}

// Make the script run only if there is a page number posted to this script

// This makes sure the page number isn't below 1, or more than our $last page
if ($pn < 1) {
    $pn = 1;
} else if ($pn > $last) {
$pn = $last;
}

// This sets the range of rows to query for the chosen $pn
$limit = 'LIMIT ' .($pn - 1) * $rpp .',' .$rpp;

// This is the query again, it is for grabbing just one page worth of rows by applying $limit
$sql = "$sql_statement"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindParam(':followers', $Followers, PDO::PARAM_STR);
$stmt->bindParam(':subscribers', $Subscribers, PDO::PARAM_STR);
$stmt->bindParam(':naatcast', $naatcast, PDO::PARAM_STR);
$stmt->execute();

// Check if user has followers
    if($numrows < 1){
    echo '<br />';
    echo '<div id="followerList" style="height:60px; text-align:center; vertical-align: middle; font-size:20px; color:white;">';
    echo "You don't have new notification";
    echo '</div>';
        include_once("template_pageRight.php");

        exit();
    } else if($numrows > 0) {
        // Establish the $paginationCtrls variable
$paginationCtrls = '';
$paginationCtrls .= '<div id="paginationctrls">';
if($last != 1){
    /* First we check if we are on page one. If we are then we don't need a link to
       the previous page or the first page so we do nothing. If we aren't then we
       generate links to the first page, and to the previous page. */
    if ($pn > 1) {
        $previous = $pn - 1;
        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
        // Render clickable number links that should appear on the left of the target page number
        for($i = $pn-4; $i < $pn; $i++){
            if($i > 0){
                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
            }
        }
    }
    // Render the target page number, but without it being a link
    $paginationCtrls .= ''.$pn.' &nbsp; ';
    // Render clickable number links that should appear on the right of the target page number
    for($i = $pn+1; $i <= $last; $i++){
        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
        if($i >= $pn+4){
            break;
        }
    }
    // This does the same as above, only checking if we are on the last page, and then generating the "Next"
    if ($pn != $last) {
        $next = $pn + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$next.'">Next</a> ';
    }
}
$paginationCtrls .= '</div>';
}
?><?php
$notify_result = "";
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $postdate = $row['postdate'];
    $b = date_create($postdate);
    $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
    $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
    $notify_result .= '<a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['username1'].'&id='.$row['id'].'&tag='.$row['tag'].'"><img src="user/'.$row['username1'].'/'.$row['avatar'].'" alt="'.$row['username1'].'" width="41" height="40">';
        if($row['aliascheck'] == '1') {
            $notify_result .= '<div class="notification-user" style="color:white; font-weight:bold">'.$row['fullname'].'</div>';
        } else if($row['aliascheck'] == '0') {
            $notify_result .= '<div class="notification-user" style="color:white; font-weight:bold">'.$row['alias'].'</div>';
        }
        if(!empty($row['message'])) {
            $notify_result .= '<div class="notification-message" style="color:white;">'.substr($row['message'],0,60).'...'.'</div>';
        } else if(!empty($row['audio']) && !empty($row['audio_desc'])) {
            $notify_result .= '<div class="notification-message">'.substr($row['audio_desc'],0,60).'...'.'</div>';
        } else {
            $notify_result .= '<div class="notification-message" style="color:white;">'.$row['fullname']. 'just broadcast a message...'.'</div>';
        }
        $notify_result .= '</a>';
        $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';

    $notify_result .= '</div>';
    $notify_result .= '<br />';
}
echo $paginationCtrls;
echo '<br />';
echo $notify_result;
echo '<br />';
echo $paginationCtrls;
?>
