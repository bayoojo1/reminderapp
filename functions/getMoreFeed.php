<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
include("../php_includes/wombat_db.php");

if(isset($_POST['row'])){
    $row = preg_replace('#[^0-9]#i', '', $_POST['row']);
    $naatcast = preg_replace('#[^a-z]#i', '', $_POST['naatcast']);
    $Subscribers = preg_replace('#[^a-z]#i', '', $_POST['Subscribers']);
    $Followers = preg_replace('#[^a-z]#i', '', $_POST['Followers']);
    $Onlyme = preg_replace('#[^a-z]#i', '', $_POST['OnlyMe']);
    $click = preg_replace('#[^0-9]#i', '', $_POST['count']);
}else{
    exit();
}


// Check if a user is a provider or not
$isProvider = false;
$approved = '1';
if($log_username) {
$sql = "SELECT id FROM content_provider WHERE provider=:provider AND approved=:approved LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':provider', $log_username, PDO::PARAM_STR);
$stmt->bindParam(':approved', $approved, PDO::PARAM_STR);
$stmt->execute();
$numrows = $stmt->rowCount();
if($numrows == 1) {
    $isProvider = true;
  }
}

$perPage = 10;
$feedResult = '';

$sql_statement = "SELECT once.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, once.mobile, once.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM once LEFT OUTER JOIN users ON once.username1 = users.username INNER JOIN useroptions ON once.username1 = useroptions.username LEFT OUTER JOIN content_provider ON once.username1 = content_provider.provider WHERE once.username1=:logusername OR once.username1=:naatcast
UNION
SELECT daily.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, daily.mobile, daily.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM daily LEFT OUTER JOIN users ON daily.username1 = users.username INNER JOIN useroptions ON daily.username1 = useroptions.username LEFT OUTER JOIN content_provider ON daily.username1 = content_provider.provider WHERE daily.username1=:logusername OR daily.username1=:naatcast
UNION
SELECT weekly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, weekly.mobile, weekly.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM weekly LEFT OUTER JOIN users ON weekly.username1 = users.username INNER JOIN useroptions ON weekly.username1 = useroptions.username LEFT OUTER JOIN content_provider ON weekly.username1 = content_provider.provider WHERE weekly.username1=:logusername OR weekly.username1=:naatcast
UNION
SELECT monthly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, monthly.mobile, monthly.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM monthly LEFT OUTER JOIN users ON monthly.username1 = users.username INNER JOIN useroptions ON monthly.username1 = useroptions.username LEFT OUTER JOIN content_provider ON monthly.username1 = content_provider.provider  WHERE monthly.username1=:logusername OR monthly.username1=:naatcast
UNION
SELECT yearly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, yearly.mobile, yearly.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM yearly LEFT OUTER JOIN users ON yearly.username1 = users.username INNER JOIN useroptions ON yearly.username1 = useroptions.username LEFT OUTER JOIN content_provider ON yearly.username1 = content_provider.provider WHERE yearly.username1=:logusername OR yearly.username1=:naatcast
UNION
/* For dailyround */
SELECT dailyround.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyround.mobile, dailyround.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyround LEFT OUTER JOIN users ON dailyround.username1 = users.username INNER JOIN useroptions ON dailyround.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyround.username1 = content_provider.provider WHERE dailyround.username1=:logusername OR dailyround.username1=:naatcast
UNION
/* For dailydaytime */
SELECT dailydaytime.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailydaytime.mobile, dailydaytime.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailydaytime LEFT OUTER JOIN users ON dailydaytime.username1 = users.username INNER JOIN useroptions ON dailydaytime.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailydaytime.username1 = content_provider.provider WHERE dailydaytime.username1=:logusername OR dailydaytime.username1=:naatcast
UNION
/* For dailynight */
SELECT dailynight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailynight.mobile, dailynight.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailynight LEFT OUTER JOIN users ON dailynight.username1 = users.username INNER JOIN useroptions ON dailynight.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailynight.username1 = content_provider.provider WHERE dailynight.username1=:logusername OR dailynight.username1=:naatcast
UNION
/* For dailyweekdaytime */
SELECT dailyweekdaytime.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekdaytime.mobile, dailyweekdaytime.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekdaytime LEFT OUTER JOIN users ON dailyweekdaytime.username1 = users.username INNER JOIN useroptions ON dailyweekdaytime.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekdaytime.username1 = content_provider.provider WHERE dailyweekdaytime.username1=:logusername OR dailyweekdaytime.username1=:naatcast
UNION
/* For dailyweekdaynight */
SELECT dailyweekdaynight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekdaynight.mobile, dailyweekdaynight.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekdaynight LEFT OUTER JOIN users ON dailyweekdaynight.username1 = users.username INNER JOIN useroptions ON dailyweekdaynight.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekdaynight.username1 = content_provider.provider WHERE dailyweekdaynight.username1=:logusername OR dailyweekdaynight.username1=:naatcast
UNION
/* For dailyweekendday */
SELECT dailyweekendday.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekendday.mobile, dailyweekendday.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekendday LEFT OUTER JOIN users ON dailyweekendday.username1 = users.username INNER JOIN useroptions ON dailyweekendday.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekendday.username1 = content_provider.provider WHERE dailyweekendday.username1=:logusername OR dailyweekendday.username1=:naatcast
UNION
/* For dailyweekendnight */
SELECT dailyweekendnight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekendnight.mobile, dailyweekendnight.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekendnight LEFT OUTER JOIN users ON dailyweekendnight.username1 = users.username INNER JOIN useroptions ON dailyweekendnight.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekendnight.username1 = content_provider.provider WHERE dailyweekendnight.username1=:logusername OR dailyweekendnight.username1=:naatcast
UNION
/* Followers */
SELECT once.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, once.mobile, once.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM once LEFT OUTER JOIN follows ON once.username1 = follows.user2 INNER JOIN users ON once.username1 = users.username INNER JOIN useroptions ON once.username1 = useroptions.username LEFT OUTER JOIN content_provider ON once.username1 = content_provider.provider WHERE follows.user1=:logusername AND once.broadcast=:followers
UNION
SELECT daily.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, daily.mobile, daily.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM daily LEFT OUTER JOIN follows ON daily.username1 = follows.user2 INNER JOIN users ON daily.username1 = users.username INNER JOIN useroptions ON daily.username1 = useroptions.username LEFT OUTER JOIN content_provider ON daily.username1 = content_provider.provider WHERE follows.user1=:logusername AND daily.broadcast=:followers
UNION
SELECT weekly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, weekly.mobile, weekly.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM weekly LEFT OUTER JOIN follows ON weekly.username1 = follows.user2 INNER JOIN users ON weekly.username1 = users.username INNER JOIN useroptions ON weekly.username1 = useroptions.username LEFT OUTER JOIN content_provider ON weekly.username1 = content_provider.provider WHERE follows.user1=:logusername AND weekly.broadcast=:followers
UNION
SELECT monthly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, monthly.mobile, monthly.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM monthly LEFT OUTER JOIN follows ON monthly.username1 = follows.user2 INNER JOIN users ON monthly.username1 = users.username INNER JOIN useroptions ON monthly.username1 = useroptions.username LEFT OUTER JOIN content_provider ON monthly.username1 = content_provider.provider WHERE follows.user1=:logusername AND monthly.broadcast=:followers
UNION
SELECT yearly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, yearly.mobile, yearly.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM yearly LEFT OUTER JOIN follows ON yearly.username1 = follows.user2 INNER JOIN users ON yearly.username1 = users.username INNER JOIN useroptions ON yearly.username1 = useroptions.username LEFT OUTER JOIN content_provider ON yearly.username1 = content_provider.provider WHERE follows.user1=:logusername AND yearly.broadcast=:followers
UNION
/* For  dailyround */
SELECT dailyround.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyround.mobile, dailyround.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyround LEFT OUTER JOIN follows ON dailyround.username1 = follows.user2 INNER JOIN users ON dailyround.username1 = users.username INNER JOIN useroptions ON dailyround.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyround.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailyround.broadcast=:followers
UNION
/* For dailydaytime */
SELECT dailydaytime.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailydaytime.mobile, dailydaytime.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailydaytime LEFT OUTER JOIN follows ON dailydaytime.username1 = follows.user2 INNER JOIN users ON dailydaytime.username1 = users.username INNER JOIN useroptions ON dailydaytime.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailydaytime.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailydaytime.broadcast=:followers
UNION
/* For dailynight */
SELECT dailynight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailynight.mobile, dailynight.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailynight LEFT OUTER JOIN follows ON dailynight.username1 = follows.user2 INNER JOIN users ON dailynight.username1 = users.username INNER JOIN useroptions ON dailynight.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailynight.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailynight.broadcast=:followers
UNION
/* For dailyweekdaytime */
SELECT dailyweekdaytime.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekdaytime.mobile, dailyweekdaytime.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekdaytime LEFT OUTER JOIN follows ON dailyweekdaytime.username1 = follows.user2 INNER JOIN users ON dailyweekdaytime.username1 = users.username INNER JOIN useroptions ON dailyweekdaytime.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekdaytime.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailyweekdaytime.broadcast=:followers
UNION
/* For dailyweekdaynight */
SELECT dailyweekdaynight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekdaynight.mobile, dailyweekdaynight.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekdaynight LEFT OUTER JOIN follows ON dailyweekdaynight.username1 = follows.user2 INNER JOIN users ON dailyweekdaynight.username1 = users.username INNER JOIN useroptions ON dailyweekdaynight.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekdaynight.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailyweekdaynight.broadcast=:followers
UNION
/* For dailyweekendday */
SELECT dailyweekendday.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekendday.mobile, dailyweekendday.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekendday LEFT OUTER JOIN follows ON dailyweekendday.username1 = follows.user2 INNER JOIN users ON dailyweekendday.username1 = users.username INNER JOIN useroptions ON dailyweekendday.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekendday.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailyweekendday.broadcast=:followers
UNION
/* For dailyweekendnight */
SELECT dailyweekendnight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekendnight.mobile, dailyweekendnight.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekendnight LEFT OUTER JOIN follows ON dailyweekendnight.username1 = follows.user2 INNER JOIN users ON dailyweekendnight.username1 = users.username INNER JOIN useroptions ON dailyweekendnight.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekendnight.username1 = content_provider.provider WHERE follows.user1=:logusername AND dailyweekendnight.broadcast=:followers
UNION
/* Subscribers */
SELECT once.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, once.mobile, once.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM once LEFT OUTER JOIN subscription ON once.username1 = subscription.provider INNER JOIN users ON once.username1 = users.username INNER JOIN useroptions ON once.username1 = useroptions.username LEFT OUTER JOIN content_provider ON once.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND once.broadcast=:subscribers
UNION
SELECT daily.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, daily.mobile, daily.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM daily LEFT OUTER JOIN subscription ON daily.username1 = subscription.provider INNER JOIN users ON daily.username1 = users.username INNER JOIN useroptions ON daily.username1 = useroptions.username LEFT OUTER JOIN content_provider ON daily.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND daily.broadcast=:subscribers
UNION
SELECT weekly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, weekly.mobile, weekly.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM weekly LEFT OUTER JOIN subscription ON weekly.username1 = subscription.provider INNER JOIN users ON weekly.username1 = users.username INNER JOIN useroptions ON weekly.username1 = useroptions.username LEFT OUTER JOIN content_provider ON weekly.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND weekly.broadcast=:subscribers
UNION
SELECT monthly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, monthly.mobile, monthly.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM monthly LEFT OUTER JOIN subscription ON monthly.username1 = subscription.provider INNER JOIN users ON monthly.username1 = users.username INNER JOIN useroptions ON monthly.username1 = useroptions.username LEFT OUTER JOIN content_provider ON monthly.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND monthly.broadcast=:subscribers
UNION
SELECT yearly.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, yearly.mobile, yearly.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM yearly LEFT OUTER JOIN subscription ON yearly.username1 = subscription.provider INNER JOIN users ON yearly.username1 = users.username INNER JOIN useroptions ON yearly.username1 = useroptions.username LEFT OUTER JOIN content_provider ON yearly.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND yearly.broadcast=:subscribers
UNION
/* For dailyround */
SELECT dailyround.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyround.mobile, dailyround.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyround LEFT OUTER JOIN subscription ON dailyround.username1 = subscription.provider INNER JOIN users ON dailyround.username1 = users.username INNER JOIN useroptions ON dailyround.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyround.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailyround.broadcast=:subscribers
UNION
/* For dailydaytime */
SELECT dailydaytime.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailydaytime.mobile, dailydaytime.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailydaytime LEFT OUTER JOIN subscription ON dailydaytime.username1 = subscription.provider INNER JOIN users ON dailydaytime.username1 = users.username INNER JOIN useroptions ON dailydaytime.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailydaytime.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailydaytime.broadcast=:subscribers
UNION
/* For dailynight */
SELECT dailynight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailynight.mobile, dailynight.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailynight LEFT OUTER JOIN subscription ON dailynight.username1 = subscription.provider INNER JOIN users ON dailynight.username1 = users.username INNER JOIN useroptions ON dailynight.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailynight.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailynight.broadcast=:subscribers
UNION
/* For dailyweekdaytime */
SELECT dailyweekdaytime.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekdaytime.mobile, dailyweekdaytime.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekdaytime LEFT OUTER JOIN subscription ON dailyweekdaytime.username1 = subscription.provider INNER JOIN users ON dailyweekdaytime.username1 = users.username INNER JOIN useroptions ON dailyweekdaytime.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekdaytime.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailyweekdaytime.broadcast=:subscribers
UNION
/* For dailyweekdaynight */
SELECT dailyweekdaynight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekdaynight.mobile, dailyweekdaynight.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekdaynight LEFT OUTER JOIN subscription ON dailyweekdaynight.username1 = subscription.provider INNER JOIN users ON dailyweekdaynight.username1 = users.username INNER JOIN useroptions ON dailyweekdaynight.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekdaynight.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailyweekdaynight.broadcast=:subscribers
UNION
/* For dailyweekendday */
SELECT dailyweekendday.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekendday.mobile, dailyweekendday.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekendday LEFT OUTER JOIN subscription ON dailyweekendday.username1 = subscription.provider INNER JOIN users ON dailyweekendday.username1 = users.username INNER JOIN useroptions ON dailyweekendday.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekendday.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailyweekendday.broadcast=:subscribers
UNION
/* For dailyweekendnight */
SELECT dailyweekendnight.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, dailyweekendnight.mobile, dailyweekendnight.username1, state, users.username, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM dailyweekendnight LEFT OUTER JOIN subscription ON dailyweekendnight.username1 = subscription.provider INNER JOIN users ON dailyweekendnight.username1 = users.username INNER JOIN useroptions ON dailyweekendnight.username1 = useroptions.username LEFT OUTER JOIN content_provider ON dailyweekendnight.username1 = content_provider.provider WHERE subscription.subscriber=:logusername AND dailyweekendnight.broadcast=:subscribers ORDER BY postdate DESC";


$stmt = $db_connect->prepare($sql_statement);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindParam(':followers', $Followers, PDO::PARAM_STR);
$stmt->bindParam(':subscribers', $Subscribers, PDO::PARAM_STR);
$stmt->bindParam(':naatcast', $naatcast, PDO::PARAM_STR);
$stmt->execute();

$numrows = $stmt->rowCount();

// Check if it's the last page
$last = ceil($numrows/$perPage);

// Select the first 10 posts
$limit = 'LIMIT ' . $row .',' .$perPage;
$sql = "$sql_statement"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindParam(':followers', $Followers, PDO::PARAM_STR);
$stmt->bindParam(':subscribers', $Subscribers, PDO::PARAM_STR);
$stmt->bindParam(':naatcast', $naatcast, PDO::PARAM_STR);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $remindOn = $row['accepted'];
                $postOn = $row['postdate'];
                $tag = $row['tag'];
                $feed_id = $row['id'];
                $audio = $row['audio'];
                $content_type = $row['content_type'];


    if($row['audio'] != "" && $row['broadcast'] == 'Subscribers') {
            $filename = pathinfo($audio, PATHINFO_BASENAME);
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
    if(($row['broadcast'] == 'Subscribers') || ($row['broadcast'] == 'Followers') || ($row['username1'] == 'naatcast')) {
            // Count the number of comment per post
            $commentCounts = '0';
            $sql_comment = "SELECT id FROM comment WHERE post_id=:post_id AND post_tag=:post_tag";
            $stmt4 = $db_connect->prepare($sql_comment);
            $stmt4->bindParam(':post_id', $feed_id, PDO::PARAM_INT);
            $stmt4->bindParam(':post_tag', $tag, PDO::PARAM_STR);
            $stmt4->execute();

            $commentCounts = $stmt4->rowCount();
        }
                $feedResult .= '<div id="parentContainer" class="parentContainer">';
                    $feedResult .= '<a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['username1'].'&id='.$row['id'].'&tag='.$row['tag'].'"><img src="user/'.$row['username1'].'/'.$row['avatar'].'" alt="'.$row['username1'].'">'.'</a><br />';
                    if($row['aliascheck'] == '1' && $row['username1'] == $log_username) {
                        $feedResult .= '<div id="ProfileName" style="color:white; font-weight:bold">'.$row['fullname'].'</div>';
                    } else if($row['aliascheck'] == '0' && $row['username1'] == $log_username) {
                        $feedResult .= '<div id="ProfileName" style="color:white; font-weight:bold">'.$row['alias'].'</div>';
                    } else if($row['username1'] != $log_username && $row['aliascheck'] == '1') {
                        $feedResult .= '<div id="ProfileName" style="color:white; font-weight:bold">'.$row['fullname'].'</div>';
                    } else if($row['username1'] != $log_username && $row['aliascheck'] == '0') {
                        $feedResult .= '<div id="ProfileName" style="color:white; font-weight:bold">'.$row['alias'].'</div>';
                    }
                    $feedResult .= '<img src="./images/posted-white.png" alt="posted" title="Post Date">'.'<span class="posted" data-livestamp="'.$postOn.'" style="color:white; font-size:10px; font-family:Arial, Helvetica, sans-serif; font-weight:bold;">'.'</span>';
                    if($row['audio'] != "" && $row['broadcast'] == 'Subscribers') {
                    $feedResult .= '<div id="content_type" style="margin-top:-14px;">'.'<b>'."$content_type".'</b>'.'</div>';
                    }
                    if(($row['broadcast'] == 'Subscribers') || ($row['broadcast'] == 'Followers') || ($row['username1'] == 'naatcast')) {
                    $feedResult .= '<a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['username1'].'&id='.$row['id'].'&tag='.$row['tag'].'">'.'<img src="./images/chat.png" alt="comment" title="Comment">'.'<div class="comment" style="color:white; font-size:10px; font-family:Arial, Helvetica, sans-serif; margin-left:-5px;">'.$commentCounts.'</div>'.'</a>';
                    }
                    if($row['username1'] == $log_username && $row['mobile'] == ""){
                    $feedResult .= '<img class="deleteimg" id="'.$tag.'_'.$feed_id.'" style="cursor:pointer;" onclick="deletePost(this.id)"; src="./images/deleteimg.png" title="Delete">';
                } else if($row['username1'] == $log_username && $row['mobile'] != "" && $row['state'] == 'pause') {
                    $feedResult .= '<img class="pauseimg" id="'.$tag.'_'.$feed_id.'" style="cursor:pointer;" onclick="pausePost(this.id)"; src="./images/pauseimg.png" title="Pause">';
                } else if($row['username1'] == $log_username && $row['mobile'] != "" && $row['state'] == 'play') {
                  $feedResult .= '<img class="playimg" id="'.$tag.'_'.$feed_id.'" style="cursor:pointer;" onclick="playPost(this.id)"; src="./images/playimg.png" title="Resume">';
                }
                $feedResult .= '</div>';
                if($row['message'] != "") {
                    $feedResult .= '<div id="childdiv1" text-align:center>'.'<div style="background-color:#bdbdbd;">'.nl2br($row['message']).'</div>'.'</div>'.'<br />';
                } else if($row['audio'] != "" && $row['broadcast'] == 'Subscribers') {
                    $feedResult .= '<div id="childdiv1" text-align:center>'.'<div style="background-color:#bdbdbd;">'.nl2br($row['audio_desc']).'</div>'.'</div>';
                    $feedResult .= '<div id="lowerbtm" style="background-color:lightslategray; margin-top:-2px; height:25px;">';
                    $feedResult .= '<input id='.$tag.'.'.$feed_id.' type="button" style="float:right; color:white; background-color:#004080; font-weight:600; margin-top:4px;" value="Request Audio" onclick="requestAudio(this);" />';
                    $feedResult .= '<span id="callcount" style="float:right; min-width:20px; text-align:center; margin-top:3px; margin-right:1px; background-color:#ccc; color:#004080; border-radius:10px;">'.$count.'</span>';
                    if($row['username1'] == $log_username && $isProvider) {
                        $feedResult .= '<a href="http://localhost:8080/reminderapp/callstats.php?u='.$row['username1'].'&id='.$row['id'].'&tag='.$row['tag'].'">'.'<i class="fas fa-chart-pie" title="Call Statistics" onclick="callstats(this)" style="height:20px; width:30px; float:right; margin-top:2px; cursor:pointer; color:white; padding-top:2px;"></i>'.'</a>';
                    }
                    $feedResult .= '<div id="popbtn'.$feed_id.''.$tag.'" class="popup">';
                    $feedResult .= '<span id="closepop'.$feed_id.''.$tag.'" class="closepop" title="Close" onclick="deletePopup(this)">&times</span>';

                    $feedResult .= '<form id="bcastothers" onsubmit="return false;">';
                      $feedResult .= '<div class="baudio" style="color:white;">Broadcast this audio to others.</div>';
                      $feedResult .= '<br /><br />';
                      $feedResult .= '<textarea id="mobilepop'.$feed_id.'.'.$tag.'" name="mobilepop" class="mobilepop" rows="4" placeholder="Enter recipient mobile(s) here in the format: 2348020000000, without leading zero, separated by comma."></textarea>';
                      $feedResult .= '<br /><br />';
                      $feedResult .= '<br />';
                      $feedResult .= '<br />';
                      $feedResult .= '<br />';
                      $feedResult .= '<br />';
                      $feedResult .= '<button id="btnCast" class="btnCast" onclick="sharebcast(this)">Broadcast</button>';
                      $feedResult .= '<br />';
                      $feedResult .= '<p id="broadcaststatus" style="color:white; font-style:italic;"></p>';
                    $feedResult .= '</form>';

                    $feedResult .= '</div>';
                    $feedResult .= '<i id="broadcastbtn'.$feed_id.''.$tag.'" class="fas fa-bullhorn" title="Broadcast" onclick="broadcast(this)" style="height:18px; width:25px; float:right; padding-top:4.5px; margin-right:6px; cursor:pointer; color:white;"></i>';

                    if($likepost === 'nill') {
                        $feedResult .= '<span id="likecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$like_counts.'</span>';
                        $feedResult .= '<i id="likebtn'.$feed_id.''.$tag.'" class="fas fa-thumbs-up" title="Like" onclick="like(this)" style="height:20px; width:30px; padding-top:4.5px; cursor:pointer; color:white;"></i>';

                        $feedResult .= '<span id="unlikecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$unlike_counts.'</span>';
                        $feedResult .= '<i id="unlikebtn'.$feed_id.''.$tag.'" class="fas fa-thumbs-down" title="Unlike" onclick="unlike(this)" style="height:20px; width:30px; padding-top:4.5px; cursor:pointer; color:white;"></i>';
                    } else if($likepost === 'yes') {
                        $feedResult .= '<span id="likecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$like_counts.'</span>';
                        $feedResult .= '<i id="likebtn'.$feed_id.''.$tag.'" class="fas fa-thumbs-up" title="Like" onclick="like(this)" style="height:20px; width:30px; padding-top:4.5px; cursor:pointer; color:#004080;"></i>';

                        $feedResult .= '<span id="unlikecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2px;">'.$unlike_counts.'</span>';
                        $feedResult .= '<i id="unlikebtn'.$feed_id.''.$tag.'" class="fas fa-thumbs-down" title="Unlike" onclick="unlike(this)" style="height:20px; width:30px; padding-top:4.5px; cursor:pointer; color:white;"></i>';
                    } else if($likepost === 'no') {
                        $feedResult .= '<span id="likecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2px;">'.$like_counts.'</span>';
                        $feedResult .= '<i id="likebtn'.$feed_id.''.$tag.'" class="fas fa-thumbs-up" title="Like" onclick="like(this)" style="height:20px; width:30px; padding-top:4.5px; cursor:pointer; color:white;"></i>';

                        $feedResult .= '<span id="unlikecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$unlike_counts.'</span>';
                        $feedResult .= '<i id="unlikebtn'.$feed_id.''.$tag.'" class="fas fa-thumbs-down" title="Unlike" onclick="unlike(this)" style="height:20px; width:30px; padding-top:4.5px; cursor:pointer; color:#004080;"></i>';
                    }
                    $feedResult .= '</div>';
                    $feedResult .= '<br />';
                } else {
                    $feedResult .= '<audio controls controlsList="nodownload">';
                    $feedResult .= '<source src='.$row['audio'].' type="audio/mpeg">';
                    $feedResult .= '<source src='.$row['audio'].' type="audio/wav">';
                    $feedResult .= '<source src='.$row['audio'].' type="audio/ogg">';
                    $feedResult .= 'Your browser does not support the audio element.';
                    $feedResult .= '</audio>';
                    $feedResult .= '</div>';
                }
}
$feedResult .= '<p id="loader" style="display:none; text-align:center;">'.'<img src="images/loading2.gif" height="30", width="30">'.'</p>';

if($click < $last) {
$feedResult .= '<div class="load-feed" style="text-align:center; width:90px; margin:0 auto;">Load More</div>';
$feedResult .= '<input type="hidden" id="row" value="0">';
$feedResult .= '<input type="hidden" id="allfeeds" value="'.$numrows.'">';
$feedResult .= '<input type="hidden" id="naatcast" value="'.$naatcast.'">';
$feedResult .= '<input type="hidden" id="Followers" value="'.$Followers.'">';
$feedResult .= '<input type="hidden" id="Subscribers" value="'.$Subscribers.'">';
$feedResult .= '<input type="hidden" id="OnlyMe" value="'.$Onlyme.'">';
$feedResult .= '<input type="hidden" id="inc" value="">';
}
$feedResult .= '</p>';

echo $feedResult;
?>
