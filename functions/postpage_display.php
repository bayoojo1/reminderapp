<?php
//usleep(500000);
include("./php_includes/a2billing_db.php");
include("./php_includes/mysqli_connect.php");
include("./php_includes/wombat_db.php");

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

// Check if logusername is following the owner of this post
$follow = false;

$sql = "SELECT users.username, users.firstname, users.avatar, users.alias, useroptions.aliascheck FROM users INNER JOIN useroptions ON users.username = useroptions.username WHERE users.username=:logusername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $Theusername = $row['username'];
    $Thefirstname = rtrim($row['firstname']);
    $Theavatar = $row['avatar'];
    $Thealias = $row['alias'];
}



$Followers = 'Followers';
$Subscribers = 'Subscribers';
$viewResult = '';
$commentView = '';
//
$sql = "SELECT $posttag.id, message, audio, audio_desc, accepted, postdate, tag, broadcast, $posttag.username1, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, useroptions.aliascheck, content_provider.content_type FROM $posttag INNER JOIN users ON $posttag.username1 = users.username INNER JOIN useroptions ON $posttag.username1 = useroptions.username LEFT OUTER JOIN content_provider ON $posttag.username1 = content_provider.provider WHERE $posttag.username1=:user AND $posttag.id=:postid LIMIT 1";

$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $remindOn = $row['accepted'];
    $postOn = $row['postdate'];
    $audio = $row['audio'];
    $tag = $row['tag'];
    $feed_id = $row['id'];
    $content_type = $row['content_type'];

    if($row['audio'] != "" && $row['broadcast'] == 'Subscribers') {
        $filename = pathinfo($audio, PATHINFO_BASENAME);
        $terminated = "TERMINATED";

        $sql_stats = "SELECT id FROM audio_stats WHERE audio=:audio AND statusCode=:terminated";
        $stmt = $db_connect->prepare($sql_stats);
        $stmt->bindParam(':audio', $filename, PDO::PARAM_STR);
        $stmt->bindParam(':terminated', $terminated, PDO::PARAM_STR);
        $stmt->execute();

        $callcount = $stmt->rowCount();

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

        //$row = $stmt4->fetch();
        //$commentCounts = $row[0];
        $commentCounts = $stmt4->rowCount();
    }
    $viewResult .= '<div id="parentContainer" class="parentContainer">';
    $viewResult .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['username1'].'"><img src="user/'.$row['username1'].'/'.$row['avatar'].'" alt="'.$row['username1'].'">'.'</a><br />';
    if($row['aliascheck'] == '1' && $row['username1'] == $log_username) {
        $viewResult .= '<div id="ProfileName" style="color:white; font-weight:bold">'.$row['fullname'].'</div>';
    } else if($row['aliascheck'] == '0' && $row['username1'] == $log_username) {
        $viewResult .= '<div id="ProfileName" style="color:white; font-weight:bold">'.$row['alias'].'</div>';
    } else if($row['username1'] != $log_username && $row['aliascheck'] == '1') {
        $viewResult .= '<div id="ProfileName" style="color:white; font-weight:bold">'.$row['fullname'].'</div>';
    } else if($row['username1'] != $log_username && $row['aliascheck'] == '0') {
        $viewResult .= '<div id="ProfileName" style="color:white; font-weight:bold">'.$row['alias'].'</div>';
    }
    $viewResult .= '<img src="./images/posted-white.png" alt="posted" title="Post Date">'.'<span class="posted" data-livestamp="'.$postOn.'" style="color:white; font-size:10px; font-family:Arial, Helvetica, sans-serif;">'.'</span>';
    if($row['audio'] != "" && $row['broadcast'] == 'Subscribers') {
    $viewResult .= '<div id="content_type" style="margin-top:-12px;">'.'<b>'."$content_type".'</b>'.'</div>';
    }
    $viewResult .= '<img src="./images/chat.png" alt="comment" title="Comment">'.'<div class="comment" style="color:white; font-size:10px; font-family:Arial, Helvetica, sans-serif;">'.$commentCounts.'</div>';
    if($row['username1'] == $log_username){
    //$viewResult .= '<div id="delete">'.'<input id='.$tag.'_'.$feed_id.' type="button" title="Delete" value="x" onclick="deletePost(this);" />'.'</div>';
    $viewResult .= '<img class="deleteimg" id="'.$tag.'_'.$feed_id.'" style="cursor:pointer;" onclick="deletePost(this.id)"; src="./images/deleteimg.png" title="Delete">';
}
$viewResult .= '</div>';
if($row['message'] != "") {
    $viewResult .= '<div id="childdiv1">'.'<div style="background-color:gainsboro;">'.nl2br($row['message']).'</div>'.'</div>'.'<br />';
} else if($row['audio'] != "" && $row['broadcast'] == 'Subscribers') {
    $viewResult .= '<div id="childdiv1">'.'<div style="background-color:gainsboro;">'.nl2br($row['audio_desc']).'</div>'.'</div>';
    $viewResult .= '<div id="lowerbtm" style="background-color:lightslategray; margin-top:-2px; height:25px;">';
    $viewResult .= '<input id='.$tag.'.'.$feed_id.' type="button" style="float:right; color:white; background-color:#004080; font-weight:600; margin-top:4px;" value="Request Audio" onclick="requestAudio(this);" />';
    $viewResult .= '<span class="callcount" style="float:right; min-width:20px; text-align:center; margin-right:1px; margin-top:2.5px; background-color:#ccc; color:#004080; border-radius:10px;">'.$callcount.'</span>';
    if($row['username1'] == $log_username && $isProvider) {
        $viewResult .= '<a href="http://localhost:8080/reminderapp/callstats.php?u='.$row['username1'].'&id='.$row['id'].'&tag='.$row['tag'].'">'.'<img src="./images/statistics-icon-white.png" alt="statistics" title="Call Statistics" onclick="callstats(this)" style="height:20px; width:30px; float:right; margin-top:2px; cursor:pointer;">'.'</a>';
    }

    $viewResult .= '<div id="popbtn'.$feed_id.''.$tag.'" class="popup">';
    $viewResult .= '<span id="closepop'.$feed_id.''.$tag.'" class="closepop" title="Close" onclick="deletePopup(this)">&times</span>';

    $viewResult .= '<form id="bcastothers" onsubmit="return false;">';
      $viewResult .= '<div class="baudio" style="color:white;">Broadcast this audio to others.</div>';
      $viewResult .= '<br /><br />';
      $viewResult .= '<textarea id="mobilepop'.$feed_id.'.'.$tag.'" name="mobilepop" class="mobilepop" rows="4" placeholder="Enter recipient mobile(s) here in the format: 2348020000000, without leading zero, separated by comma."></textarea>';
      $viewResult .= '<br /><br />';
      $viewResult .= '<br />';
      $viewResult .= '<br />';
      $viewResult .= '<br />';
      $viewResult .= '<br />';
      $viewResult .= '<button id="btnCast" class="btnCast" onclick="sharebcast(this)">Broadcast</button>';
      $viewResult .= '<br />';
      $viewResult .= '<p id="broadcaststatus" style="color:white; font-style:italic;"></p>';
    $viewResult .= '</form>';

    $viewResult .= '</div>';
    $viewResult .= '<img id="broadcastbtn'.$feed_id.''.$tag.'" src="./images/note_still.png" alt="broadcast" title="Broadcast" onclick="broadcast(this)" style="height:18px; width:25px; float:right; margin-top:3.5px; cursor:pointer;">';

if($likepost === 'nill') {
    $viewResult .= '<span id="likecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$like_counts.'</span>';
    $viewResult .= '<img id="likebtn'.$feed_id.''.$tag.'" src="./images/likebtn-white.png" alt="like" title="Like" onclick="like(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';

    $viewResult .= '<span id="unlikecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$unlike_counts.'</span>';
    $viewResult .= '<img id="unlikebtn'.$feed_id.''.$tag.'" src="./images/unlikebtn-white.png" alt="unlike" title="Unlike" onclick="unlike(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';
} else if($likepost === 'yes') {
    $viewResult .= '<span id="likecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$like_counts.'</span>';
    $viewResult .= '<img id="likebtn'.$feed_id.''.$tag.'" src="./images/likebtn-blue.png" alt="like" title="Like" onclick="like(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';

    $viewResult .= '<span id="unlikecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$unlike_counts.'</span>';
    $viewResult .= '<img id="unlikebtn'.$feed_id.''.$tag.'" src="./images/unlikebtn-white.png" alt="unlike" title="Unlike" onclick="unlike(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';
} else if($likepost === 'no') {
    $viewResult .= '<span id="likecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2px;">'.$like_counts.'</span>';
    $viewResult .= '<img id="likebtn'.$feed_id.''.$tag.'" src="./images/likebtn-white.png" alt="like" title="Like" onclick="like(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';

    $viewResult .= '<span id="unlikecnt'.$feed_id.''.$tag.'" style="color:#004080; margin-top:2.5px;">'.$unlike_counts.'</span>';
    $viewResult .= '<img id="unlikebtn'.$feed_id.''.$tag.'" src="./images/unlikebtn-blue.png" alt="unlike" title="Unlike" onclick="unlike(this)" style="height:20px; width:30px; margin-top:2.5px; cursor:pointer;">';
}
$viewResult .= '</div>';
$viewResult .= '<div style="border: 1px solid gray; text-align:center; background-color:white;" id="share"></div>';
echo '<br />';
}else {
    $viewResult .= '<audio controls controlsList="nodownload">';
    $viewResult .= '<source src='.$row['audio'].' type="audio/mpeg">';
    $viewResult .= '<source src='.$row['audio'].' type="audio/wav">';
    $viewResult .= '<source src='.$row['audio'].' type="audio/ogg">';
    $viewResult .= 'Your browser does not support the audio element.';
    $viewResult .= '</audio>';
    $viewResult .= '<br />';
}
$commentView .= '<div>';
$commentView .= '<div style="height:90px; margin-bottom:-35px;">';
$commentView .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$Theusername.'">'.'<img id="comment-img" src="user/'.$Theusername.'/'.$Theavatar.'" alt="'.$Theusername.'"></a>'.'<textarea rows="2" name="comments" id="comments" onfocus="emptyElement("status")" placeholder="Write your comment here '.$Thefirstname.'" style="display:inline-block; float:right;"></textarea>'.'</div>';
$commentView .= '<div style="margin-top:-15px;">'.'<input id="'.$feed_id.'_'.$tag.'" class="commentClass" type="button" value="COMMENT" name="commentPost" onclick="comment(this); commentCnt(this);" style="float:right;  font-weight:800;"/>'.'</div>';
$commentView .= '</div>';
$commentView .= '<br />';
$commentView .= '<br />';
$commentView .= '<div id="comment-id"></div>';

}
echo $viewResult;
echo '<br />';
if(($row['broadcast'] == 'Subscribers') || ($row['broadcast'] == 'Followers') || ($row['username1'] == 'naatcast')) {
echo $commentView;
}
?>
