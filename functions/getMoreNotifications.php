<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
$rpp = 10;
$notify_result = '';
$name = "";

if(isset($_POST['row'])){
    $rows = preg_replace('#[^0-9]#i', '', $_POST['row']);
    $naatcast = preg_replace('#[^a-z]#i', '', $_POST['naatcast']);
    $click = preg_replace('#[^0-9]#i', '', $_POST['count']);
}else{
    exit();
}

$sql_statement = "SELECT notifications.id, notifications.initiator, target, action, post_id, post_tag, detail, postdate, follows.user2, subscription.provider, users.fullname, users.alias, users.avatar, useroptions.aliascheck FROM notifications LEFT OUTER JOIN follows ON follows.user2=notifications.initiator LEFT OUTER JOIN subscription ON subscription.provider=notifications.initiator LEFT OUTER JOIN users ON users.username=notifications.initiator LEFT OUTER JOIN useroptions ON useroptions.username=notifications.initiator WHERE follows.user1=:logusername OR subscription.subscriber=:logusername OR initiator LIKE :naatcast OR target LIKE :naatcast GROUP BY notifications.id ORDER BY COALESCE(notifications.id, post_id) DESC";

$stmt = $db_connect->prepare($sql_statement);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindValue(':naatcast', '%'.$naatcast.'%', PDO::PARAM_STR);
$stmt->execute();
$numrows = $stmt->rowCount();

// Check if it's the last page
$last = ceil($numrows/$rpp);

// This sets the range of rows to query for the chosen $pn
$limit = 'LIMIT ' . $rows .',' .$rpp;

// This is the query again, it is for grabbing just one page worth of rows by applying $limit
$sql = "$sql_statement"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindValue(':naatcast', '%'.$naatcast.'%', PDO::PARAM_STR);
$stmt->execute();


foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $postdate = $row['postdate'];
    $b = date_create($postdate);
    $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
    if($row['aliascheck'] == '1') {
      $name = $row['fullname'];
    } elseif($row['aliascheck'] == '0') {
      $name = $row['alias'];
    }
    if($row['action'] == 'query' && $row['target'] == strpos($row['target'], $naatcast) && $row['target'] == $log_username) {
      $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
      $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
      $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just sent a '.'<a href="http://localhost:8080/reminderapp/ticketpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'">query</a> to NaatCast'.'</div>';
      $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
      $notify_result .= '</div>';
    } elseif($row['action'] == 'ticket_comment' && $row['initiator'] == strpos($row['initiator'], $naatcast) && $row['target'] == $log_username) {
      $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
      $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
      $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just commented on your '.'<a href="http://localhost:8080/reminderapp/ticketpage.php?u='.$row['target'].'&id='.$row['post_id'].'">ticket'.'</a></div>';
      $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
      $notify_result .= '</div>';
    } elseif($row['action'] == 'broadcast') {
      $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
      $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
      $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just broadcast an '.' <a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">audio</a>'.'</div>';
        $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
      $notify_result .= '</div>';
    } else if($row['action'] == 'comment') {
      $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
      $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
      $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just comment on a'.' <a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">post</a>'.'</div>';
        $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
      $notify_result .= '</div>';
    } elseif($row['action'] == 'like') {
      $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
      $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
      $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just liked a'.' <a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">post</a>'.'</div>';
        $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
      $notify_result .= '</div>';
    } elseif($row['action'] == 'follow') {
      $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
      $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
      $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just followed '.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['target'].'">'.$row['target'].''.'</a></div>';
      $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
      $notify_result .= '</div>';
    } elseif($row['action'] == 'post') {
      $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
      $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
      $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just shared a '.'<a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">post</a>'.'</div>';
      $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
      $notify_result .= '</div>';
    } elseif($row['action'] == 'rebroadcast') {
      $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
      $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
      $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just rebroadcast a'.' <a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">post</a>'.'</div>';
      $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
      $notify_result .= '</div>';
    } elseif($row['action'] == 'request') {
      $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
      $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
      $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just requested a'.' <a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">broadcast</a>'.'</div>';
      $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
      $notify_result .= '</div>';
    } elseif($row['action'] == 'subscribed') {
      $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; border:solid 1px white;">';
      $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
      $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just subscribed to a'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['target'].'"> provider</a>'.'</div>';
      $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
      $notify_result .= '</div>';
    }
    $notify_result .= '<p></p>';
}


$notify_result .= '<p id="loader" style="display:none; text-align:center;">'.'<img src="images/loading2.gif" height="30", width="30">'.'</p>';

if($click < $last) {
$notify_result .= '<div class="load-notifications" style="text-align:center; width:90px; margin:0 auto;">Load More</div>';
$notify_result .= '<input type="hidden" id="rowNotification" value="0">';
$notify_result .= '<input type="hidden" id="allNotification" value="'.$numrows.'">';
$notify_result .= '<input type="hidden" id="naatcast" value="'.$naatcast.'">';
$notify_result .= '<input type="hidden" id="inc" value="">';
}
$notify_result .= '</p>';

echo $notify_result;
?>
