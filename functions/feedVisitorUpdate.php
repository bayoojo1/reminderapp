<?php
//include("../php_includes/check_login_status.php");
@include("./php_includes/mysqli_connect.php");
include("./php_includes/wombat_db.php");
if(isset($_GET['u'])) {
    $u = $_GET['u'];

// Check if I'm following this user
$sql = "SELECT COUNT(id) FROM follows WHERE user2=:user AND user1=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
$query_count = $stmt->fetch();
$f_check = $query_count[0]; // This will always be either 0 or 1

// Check if I'm subscribed to this user
$sql = "SELECT COUNT(id) FROM subscription WHERE provider=:user AND subscriber=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
$query_count = $stmt->fetch();
$sub_check = $query_count[0]; // This will always be either 0 or 1

$naatcast = 'naatcast';
$naatcastbilling = 'naatcast.billing';
$naatcastsupport = 'naatcast.support';
$naatcastreport = 'naatcast.report';
$naatcastothers = 'naatcast.others';
$Followers = 'Followers';
$Subscribers = 'Subscribers';

// Check if the person I'm veiwing and following has shared any post
$sql = "SELECT COUNT(id) FROM once WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM daily WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM weekly WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM monthly WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM yearly WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM dailyround WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM dailydaytime WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM dailynight WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM dailyweekdaytime WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM dailyweekdaynight WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM dailyweekendday WHERE username1=:user AND broadcast=:followers UNION
        SELECT COUNT(id) FROM dailyweekendnight WHERE username1=:user AND broadcast=:followers";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->bindParam(':followers', $Followers, PDO::PARAM_STR);
//$stmt->bindParam(':subscribers', $Subscribers, PDO::PARAM_STR);
$stmt->execute();
$query_count = $stmt->fetch();
$b_check = $query_count[0];

// Check if the person I'm veiwing and a provider has shared any post
 $sql = "SELECT COUNT(id) FROM once WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM daily WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM weekly WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM monthly WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM yearly WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM dailyround WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM dailydaytime WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM dailynight WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM dailyweekdaytime WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM dailyweekdaynight WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM dailyweekendday WHERE username1=:user AND broadcast=:subscribers UNION
         SELECT COUNT(id) FROM dailyweekendnight WHERE username1=:user AND broadcast=:subscribers";
 $stmt = $db_connect->prepare($sql);
 $stmt->bindParam(':user', $u, PDO::PARAM_STR);
 $stmt->bindParam(':subscribers', $Subscribers, PDO::PARAM_STR);
 $stmt->execute();
 $query_count = $stmt->fetch();
 $b_sub_check = $query_count[0];
?><?php
$sql_statement = "SELECT once.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM once INNER JOIN follows ON once.username1 = follows.user2 INNER JOIN users ON once.username1 = users.username LEFT OUTER JOIN content_provider ON once.username1 = content_provider.provider WHERE follows.user1=:logusername AND once.broadcast=:followers AND follows.user2=:user
UNION
SELECT daily.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM daily INNER JOIN follows ON daily.username1 = follows.user2 INNER JOIN users ON daily.username1 = users.username  LEFT OUTER JOIN content_provider ON daily.username1 = content_provider.provider WHERE follows.user1=:logusername AND daily.broadcast=:followers AND follows.user2=:user
UNION
SELECT weekly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM weekly INNER JOIN follows ON weekly.username1 = follows.user2 INNER JOIN users ON weekly.username1 = users.username LEFT OUTER JOIN content_provider ON weekly.username1 = content_provider.provider WHERE follows.user1=:logusername AND weekly.broadcast=:followers AND follows.user2=:user
UNION
SELECT monthly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM monthly INNER JOIN follows ON monthly.username1 = follows.user2 INNER JOIN users ON monthly.username1 = users.username LEFT OUTER JOIN content_provider ON monthly.username1 = content_provider.provider WHERE follows.user1=:logusername AND monthly.broadcast=:followers AND follows.user2=:user
UNION
SELECT yearly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM yearly INNER JOIN follows ON yearly.username1 = follows.user2 INNER JOIN users ON yearly.username1 = users.username LEFT OUTER JOIN content_provider ON yearly.username1 = content_provider.provider WHERE follows.user1=:logusername AND yearly.broadcast=:followers AND follows.user2=:user
UNION
SELECT dailyround.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailyround INNER JOIN follows ON dailyround.username1 = follows.user2 INNER JOIN users ON dailyround.username1 = users.username LEFT OUTER JOIN content_provider ON dailyround.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailyround.broadcast=:followers AND follows.user2=:user
UNION
SELECT dailydaytime.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailydaytime INNER JOIN follows ON dailydaytime.username1 = follows.user2 INNER JOIN users ON dailydaytime.username1 = users.username LEFT OUTER JOIN content_provider ON dailydaytime.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailydaytime.broadcast=:followers AND follows.user2=:user
UNION
SELECT dailynight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailynight INNER JOIN follows ON dailynight.username1 = follows.user2 INNER JOIN users ON dailynight.username1 = users.username LEFT OUTER JOIN content_provider ON dailynight.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailynight.broadcast=:followers AND follows.user2=:user
UNION
SELECT dailyweekdaytime.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailyweekdaytime INNER JOIN follows ON dailyweekdaytime.username1 = follows.user2 INNER JOIN users ON dailyweekdaytime.username1 = users.username LEFT OUTER JOIN content_provider ON dailyweekdaytime.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailyweekdaytime.broadcast=:followers AND follows.user2=:user
UNION
SELECT dailyweekdaynight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailyweekdaynight INNER JOIN follows ON dailyweekdaynight.username1 = follows.user2 INNER JOIN users ON dailyweekdaynight.username1 = users.username LEFT OUTER JOIN content_provider ON dailyweekdaynight.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailyweekdaynight.broadcast=:followers AND follows.user2=:user
UNION
SELECT dailyweekendday.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailyweekendday INNER JOIN follows ON dailyweekendday.username1 = follows.user2 INNER JOIN users ON dailyweekendday.username1 = users.username LEFT OUTER JOIN content_provider ON dailyweekendday.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailyweekendday.broadcast=:followers AND follows.user2=:user
UNION
SELECT dailyweekendnight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailyweekendnight INNER JOIN follows ON dailyweekendnight.username1 = follows.user2 INNER JOIN users ON dailyweekendnight.username1 = users.username LEFT OUTER JOIN content_provider ON dailyweekendnight.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailyweekendnight.broadcast=:followers AND follows.user2=:user
UNION
/* For Subscribers */
SELECT once.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM once INNER JOIN subscription ON once.username1 = subscription.provider INNER JOIN users ON once.username1 = users.username LEFT OUTER JOIN content_provider ON once.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND once.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT daily.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM daily INNER JOIN subscription ON daily.username1 = subscription.provider INNER JOIN users ON daily.username1 = users.username LEFT OUTER JOIN content_provider ON daily.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND daily.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT weekly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM weekly INNER JOIN subscription ON weekly.username1 = subscription.provider INNER JOIN users ON weekly.username1 = users.username LEFT OUTER JOIN content_provider ON weekly.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND weekly.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT monthly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM monthly INNER JOIN subscription ON monthly.username1 = subscription.provider INNER JOIN users ON monthly.username1 = users.username LEFT OUTER JOIN content_provider ON monthly.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND monthly.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT yearly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM yearly INNER JOIN subscription ON yearly.username1 = subscription.provider INNER JOIN users ON yearly.username1 = users.username LEFT OUTER JOIN content_provider ON yearly.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND yearly.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT dailyround.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailyround INNER JOIN subscription ON dailyround.username1 = subscription.provider INNER JOIN users ON dailyround.username1 = users.username LEFT OUTER JOIN content_provider ON dailyround.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailyround.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT dailydaytime.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailydaytime INNER JOIN subscription ON dailydaytime.username1 = subscription.provider INNER JOIN users ON dailydaytime.username1 = users.username LEFT OUTER JOIN content_provider ON dailydaytime.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailydaytime.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT dailynight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailynight INNER JOIN subscription ON dailynight.username1 = subscription.provider INNER JOIN users ON dailynight.username1 = users.username LEFT OUTER JOIN content_provider ON dailynight.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailynight.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT dailyweekdaytime.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailyweekdaytime INNER JOIN subscription ON dailyweekdaytime.username1 = subscription.provider INNER JOIN users ON dailyweekdaytime.username1 = users.username LEFT OUTER JOIN content_provider ON dailyweekdaytime.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailyweekdaytime.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT dailyweekdaynight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailyweekdaynight INNER JOIN subscription ON dailyweekdaynight.username1 = subscription.provider INNER JOIN users ON dailyweekdaynight.username1 = users.username LEFT OUTER JOIN content_provider ON dailyweekdaynight.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailyweekdaynight.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT dailyweekendday.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailyweekendday INNER JOIN subscription ON dailyweekendday.username1 = subscription.provider INNER JOIN users ON dailyweekendday.username1 = users.username LEFT OUTER JOIN content_provider ON dailyweekendday.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailyweekendday.broadcast=:subscribers AND subscription.provider=:user
UNION
SELECT dailyweekendnight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, users.username, users.firstname, users.lastname, users.fullname, users.avatar, content_provider.content_type FROM dailyweekendnight INNER JOIN subscription ON dailyweekendnight.username1 = subscription.provider INNER JOIN users ON dailyweekendnight.username1 = users.username LEFT OUTER JOIN content_provider ON dailyweekendnight.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailyweekendnight.broadcast=:subscribers AND subscription.provider=:user ORDER BY postdate DESC";

$stmt = $db_connect->prepare($sql_statement);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindParam(':followers', $Followers, PDO::PARAM_STR);
$stmt->bindParam(':subscribers', $Subscribers, PDO::PARAM_STR);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->execute();

$numrows = $stmt->rowCount();

// Specify how many result per page
$rpp = '5';
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
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->execute();

    if(($f_check || $sub_check) == 1 && ($b_check || $b_sub_check) > 0) {
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
    echo $paginationCtrls;

        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $remindOn = $row['accepted'];
            $postOn = $row['postdate'];
            $tag = $row['tag'];
            $feed_id = $row['id'];
            $audio = $row['audio'];
            $content_type = $row['content_type'];

            if($row['audio'] != "" && $row['broadcast'] == 'Subscribers') {
                $filename = basename($audio);
                $terminated = "TERMINATED";

                $sql_stats = "SELECT id FROM audio_stats WHERE audio=:audio AND statusCode=:terminated";
                $stmt = $db_connect->prepare($sql_stats);
                $stmt->bindParam(':audio', $filename, PDO::PARAM_STR);
                $stmt->bindParam(':terminated', $terminated, PDO::PARAM_STR);
                $stmt->execute();

                $count = $stmt->rowCount();

                // Check if user like or unlike a post
                $sql_statement = "SELECT status FROM like_unlike WHERE username=:logusername AND post_id=:post_id AND recurrent=:recurrent LIMIT 1";
                $stmt1 = $db_connect->prepare($sql_statement);
                $stmt1->bindParam(':logusername', $log_username, PDO::PARAM_STR);
                $stmt1->bindParam(':post_id', $feed_id, PDO::PARAM_STR);
                $stmt1->bindParam(':recurrent', $tag, PDO::PARAM_STR);
                $stmt1->execute();

                $query_count = $stmt1->fetch();
                $like_unlikerows = $query_count[0];

                    if($like_unlikerows == "") {
                        $likepost = 'nill';
                    } else if($like_unlikerows == '1') {
                        $likepost ='yes';
                    } else if($like_unlikerows == '0') {
                        $likepost = 'no';
                    }

                // Count the number of likes in the like_unlike table
                $like_status = '1';
                $sql_like = "SELECT id FROM like_unlike WHERE post_id=:post_id AND status=:status AND recurrent=:recurrent";
                $stmt2 = $db_connect->prepare($sql_like);
                $stmt2->bindParam(':post_id', $feed_id, PDO::PARAM_STR);
                $stmt2->bindParam(':status', $like_status, PDO::PARAM_STR);
                $stmt2->bindParam(':recurrent', $tag, PDO::PARAM_STR);
                $stmt2->execute();

                $like_counts = $stmt2->rowCount();

                // Count the number of unlikes in the like_unlike table
                $unlike_status = '0';
                $sql_unlike = "SELECT id FROM like_unlike WHERE post_id=:post_id AND status=:status AND recurrent=:recurrent";
                $stmt3 = $db_connect->prepare($sql_like);
                $stmt3->bindParam(':post_id', $feed_id, PDO::PARAM_STR);
                $stmt3->bindParam(':status', $unlike_status, PDO::PARAM_STR);
                $stmt3->bindParam(':recurrent', $tag, PDO::PARAM_STR);
                $stmt3->execute();

                $unlike_counts = $stmt3->rowCount();
        }
        if(($row['broadcast'] == 'Subscribers') || ($row['broadcast'] == 'Followers') || ($row['username1'] == 'naatcast') || ($row['username1'] == 'naatcast.billing') || ($row['username1'] == 'naatcast.support') || ($row['username1'] == 'naatcast.report') || ($row['username1'] == 'naatcast.others')) {
            // Count the number of comment per post
            $commentCounts = '0';
            $sql_comment = "SELECT id FROM comment WHERE post_id=:post_id AND post_tag=:post_tag";
            $stmt4 = $db_connect->prepare($sql_comment);
            $stmt4->bindParam(':post_id', $feed_id, PDO::PARAM_INT);
            $stmt4->bindParam(':post_tag', $tag, PDO::PARAM_STR);
            $stmt4->execute();

            //$row = $stmt4->fetch();
            //$commentCounts = $row[0];
            $commentCounts = $stmt4->rowCount();
        }
                //echo '<br />';
                echo '<div id="parentContainer" class="parentContainer">';
                        echo '<a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['username'].'&id='.$row['id'].'&tag='.$row['tag'].'"><img src="user/'.$row['username'].'/'.$row['avatar'].'" alt="'.$row['username'].'">'.'<br />';
                        //echo '<div id="ProfileName" style="color:white; font-weight:bold">'.$row['fullname'].'</div>';
                        if($showAlias == '1') {
                            echo '<div id="ProfileName" style="color:white; font-weight:bold">'.$row['fullname'].'</div></a>';
                        } else if($showAlias == '0') {
                            echo '<div id="ProfileName" style="color:white; font-weight:bold">'.$theAlias.'</div></a>';
                        }
                        echo '<img src="./images/posted-white.png" alt="posted" title="Post Date">'.'<span class="posted" data-livestamp="'.$postOn.'" style="color:white; font-size:10px; font-family:Arial, Helvetica, sans-serif; font-weight:bold;">'.'</span>';
                        if($row['audio'] != "" && $row['broadcast'] == 'Subscribers') {
                        echo '<div id="content_type" style="margin-top:-12px;">'.'<b>'."$content_type".'</b>'.'</div>';
                        }
                        echo '<a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['username'].'&id='.$row['id'].'&tag='.$row['tag'].'">'.'<img src="./images/chat.png" alt="comment" title="Comment">'.'<div class="comment" style="color:white; font-size:10px; font-family:Arial, Helvetica, sans-serif; margin-left:-5px;">'.$commentCounts.'</div>'.'</a>';
                        if($row['username'] == $log_username){
                        echo '<div id="delete">'.'<input id='.$tag.'_'.$feed_id.' type="button" title="Delete" value="x" onclick="deletePost(this);" />'.'</div>';
                    }
                    echo '</div>';
                    if($row['message'] != "") {
                        echo '<div id="childdiv1" style="background-color:gainsboro;">'.$row['message'].'</div>'.'<br />';
                    } else if($row['audio'] != "" && $row['broadcast'] == 'Subscribers') {
                        echo '<div id="childdiv1" style="background-color:gainsboro;">'.$row['audio_desc'].'</div>';
                        echo '<div id="lowerbtm" style="background-color:lightslategray; margin-top:-2px; height:25px;">';
                        echo '<input id='.$tag.'.'.$feed_id.' type="button" style="float:right; color:white; background-color:#004080; font-weight:600; margin-top:4px;" value="Request Audio" onclick="requestAudio(this);" />';
                        echo '<span class="callcount" style="float:right; min-width:20px; text-align:center; margin-top:2.5px; margin-right:1px; background-color:#ccc; color:#004080; border-radius:10px;">'.$count.'</span>';
                        if($u == $log_username && $isProvider) {
                        echo '<a href="http://localhost:8080/reminderapp/callstats.php?u='.$row['username1'].'&id='.$row['id'].'&tag='.$row['tag'].'">'.'<img src="./images/statistics-icon-white.png" alt="statistics" title="Call Statistics" onclick="callstats(this)" style="height:20px; width:30px; float:right; margin-top:2px; cursor:pointer;">'.'</a>';
                        }
                        echo  '<div id="popbtn'.$feed_id.''.$tag.'" class="popup">';
                        echo  '<span id="closepop'.$feed_id.''.$tag.'" class="closepop" title="Close" onclick="deletePopup(this)">&times</span>';

                        echo  '<form id="bcastothers" onsubmit="return false;">';
                          echo '<div class="baudio" style="color:white;">Broadcast this audio to others.</div>';
                          echo '<br /><br />';
                          echo '<textarea id="mobilepop'.$feed_id.'.'.$tag.'" name="mobilepop" class="mobilepop" rows="4" placeholder="Enter recipient mobile(s) here in the format: 2348020000000, without leading zero, separated by comma."></textarea>';
                          echo '<br /><br />';
                          echo '<br />';
                          echo '<br />';
                          echo '<br />';
                          echo '<br />';
                          echo '<button id="btnCast" class="btnCast" onclick="sharebcast(this)">Broadcast</button>';
                          echo '<br />';
                          echo '<p id="broadcaststatus" style="color:white; font-style:italic;"></p>';
                        echo '</form>';

                        echo '</div>';
                        echo '<img id="broadcastbtn'.$feed_id.''.$tag.'" src="./images/note_still.png" alt="broadcast" title="Broadcast" onclick="broadcast(this)" style="height:18px; width:25px; float:right; margin-top:3.5px; cursor:pointer;">';

                        if($likepost === 'nill') {
                            echo '<span id="likecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$like_counts.'</span>';
                            echo '<img id="likebtn'.$feed_id.''.$tag.'" src="./images/likebtn-white.png" alt="like" title="Like" onclick="like(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';

                            echo '<span id="unlikecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$unlike_counts.'</span>';
                            echo '<img id="unlikebtn'.$feed_id.''.$tag.'" src="./images/unlikebtn-white.png" alt="unlike" title="Unlike" onclick="unlike(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';
                        } else if($likepost === 'yes') {
                            echo '<span id="likecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$like_counts.'</span>';
                            echo '<img id="likebtn'.$feed_id.''.$tag.'" src="./images/likebtn-blue.png" alt="like" title="Like" onclick="like(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';

                            echo '<span id="unlikecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$unlike_counts.'</span>';
                            echo '<img id="unlikebtn'.$feed_id.''.$tag.'" src="./images/unlikebtn-white.png" alt="unlike" title="Unlike" onclick="unlike(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';
                        } else if($likepost === 'no') {
                            echo '<span id="likecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2px;">'.$like_counts.'</span>';
                            echo '<img id="likebtn'.$feed_id.''.$tag.'" src="./images/likebtn-white.png" alt="like" title="Like" onclick="like(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';

                            echo '<span id="unlikecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$unlike_counts.'</span>';
                            echo '<img id="unlikebtn'.$feed_id.''.$tag.'" src="./images/unlikebtn-blue.png" alt="unlike" title="Unlike" onclick="unlike(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';
                        }
                        echo '</div>';
                        //echo '<br />';
                    } else {
                          echo '<audio controls controlsList="nodownload">';
                            echo '<source src='.$row['audio'].' type="audio/mpeg">';
                            echo '<source src='.$row['audio'].' type="audio/wav">';
                            echo '<source src='.$row['audio'].' type="audio/ogg">';
                            echo 'Your browser does not support the audio element.';
                          echo '</audio>';
                    //echo '</div>';
                    }
    }

} elseif(($f_check || $sub_check) == 1 && ($b_check && $b_sub_check) == 0) {
                    echo '<br />';
                    echo '<div id="parentContainer" class="parentContainer" style="height:100px; text-align:center;">';
                    echo '<h1 style="color:white;">'.'Notice!'.'<h1>';
                    echo '<h1 style="color:white;">'.'This user is yet to share any broadcast.'.'<h1>';
                echo '</div>';
                } elseif($u == $naatcast) {
                    echo '<br />';
                    echo '<div id="parentContainer" class="parentContainer" style="height:100px; text-align:center;">';
                    echo '<h1 style="color:white;">'.'Notice!'.'<h1>';
                    echo '<h1 style="color:white;">'.'Welcome to NaatCast Admin homepage.'.'<h1>';
                echo '</div>';
              } elseif($u == $naatcastbilling) {
                  echo '<br />';
                  echo '<div id="parentContainer" class="parentContainer" style="height:100px; text-align:center;">';
                  echo '<h1 style="color:white;">'.'Notice!'.'<h1>';
                  echo '<h1 style="color:white;">'.'Welcome to NaatCast Billing homepage.'.'<h1>';
              echo '</div>';
              } elseif($u == $naatcastsupport) {
                  echo '<br />';
                  echo '<div id="parentContainer" class="parentContainer" style="height:100px; text-align:center;">';
                  echo '<h1 style="color:white;">'.'Notice!'.'<h1>';
                  echo '<h1 style="color:white;">'.'Welcome to NaatCast Support homepage.'.'<h1>';
              echo '</div>';
              } elseif($u == $naatcastreport) {
                  echo '<br />';
                  echo '<div id="parentContainer" class="parentContainer" style="height:100px; text-align:center;">';
                  echo '<h1 style="color:white;">'.'Notice!'.'<h1>';
                  echo '<h1 style="color:white;">'.'Welcome to NaatCast Broadcast Report homepage.'.'<h1>';
              echo '</div>';
              } elseif($u == $naatcastothers) {
                echo '<br />';
                echo '<div id="parentContainer" class="parentContainer" style="height:100px; text-align:center;">';
                echo '<h1 style="color:white;">'.'Notice!'.'<h1>';
                echo '<h1 style="color:white;">'.'Welcome to NaatCast homepage.'.'<h1>';
            echo '</div>';
              } else {
                    echo '<br />';
                    echo '<div id="parentContainer" class="parentContainer" style="height:130px; text-align:center;">';
                    echo '<h1 style="color:white;">'.'Notice!'.'<h1>';
                    echo '<h1 style="color:white;">'.'You are neither following nor subscribe to this user.'.'<h1>';
                echo '</div>';
                }

}
?>
