<?php
include_once("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
$notify_result = '';
$notification = '';
if($user_ok == true) {
    $naatcast = 'naatcast';

    $sql_statement = "SELECT notifications.id, notifications.initiator, target, action, post_id, post_tag, detail, postdate, follows.user2, subscription.provider, users.fullname, users.alias, users.avatar, useroptions.aliascheck, date_visit.last_visited FROM notifications LEFT JOIN follows ON follows.user2=notifications.initiator LEFT JOIN subscription ON subscription.provider=notifications.initiator INNER JOIN users ON users.username=notifications.initiator INNER JOIN useroptions ON useroptions.username=notifications.initiator INNER JOIN date_visit ON date_visit.username=follows.user1 WHERE notifications.postdate > date_visit.last_visited AND date_visit.username=:logusername AND (follows.user1=:logusername OR subscription.subscriber=:logusername OR initiator LIKE :naatcast OR target LIKE :naatcast) GROUP BY id ORDER BY notifications.id DESC";

    // Get the total row count of all the db entries first
    $stmt = $db_connect->prepare($sql_statement);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->bindValue(':naatcast', '%'.$naatcast.'%', PDO::PARAM_STR);
    $stmt->execute();

    $totalcount = $stmt->rowCount();

    $notification_count = "";
    if($totalcount > 9) {
        $notification_count = "9+";
    } else {
        $notification_count = $totalcount;
    }
    $sql = $sql_statement . " LIMIT ". 10;
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->bindValue(':naatcast', '%'.$naatcast.'%', PDO::PARAM_STR);
    $stmt->execute();
    $numrows = $stmt->rowCount();

        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            // Write your code here
            $avatar = $row['avatar'];
            $fullname = $row['fullname'];
            $postdate = $row['postdate'];
            $b = date_create($postdate);
            $readabledate = date_format($b, 'g:ia \o\n l jS F Y');
            if($row['aliascheck'] == '1') {
              $name = $row['fullname'];
            } elseif($row['aliascheck'] == '0') {
              $name = $row['alias'];
            }
            if($row['action'] == 'query' && $row['target'] == strpos($row['target'], $naatcast) && $row['target'] == $log_username) {
              $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
              $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
              $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just sent a '.'<a href="http://localhost:8080/reminderapp/ticketpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'">query</a> to NaatCast'.'</div>';
              $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
              $notify_result .= '</div>';
            } elseif($row['action'] == 'ticket_comment' && $row['initiator'] == strpos($row['initiator'], $naatcast) && $row['target'] == $log_username) {
              $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
              $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
              $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just commented on your '.'<a href="http://localhost:8080/reminderapp/ticketpage.php?u='.$row['target'].'&id='.$row['post_id'].'">ticket'.'</a></div>';
              $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
              $notify_result .= '</div>';
            } elseif($row['action'] == 'broadcast') {
              $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
              $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
              $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just broadcast an '.' <a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">audio</a>'.'</div>';
                $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
              $notify_result .= '</div>';
            } else if($row['action'] == 'comment') {
              $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
              $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
              $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just commented on a'.' <a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">post</a>'.'</div>';
                $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
              $notify_result .= '</div>';
            } elseif($row['action'] == 'like') {
              $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
              $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
              $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just liked a'.' <a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">post</a>'.'</div>';
                $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
              $notify_result .= '</div>';
            } elseif($row['action'] == 'follow') {
              $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
              $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
              $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just followed '.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['target'].'">'.$row['target'].''.'</a></div>';
              $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
              $notify_result .= '</div>';
            } elseif($row['action'] == 'post') {
              $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
              $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
              $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just shared a '.'<a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">post</a>'.'</div>';
              $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
              $notify_result .= '</div>';
            } elseif($row['action'] == 'rebroadcast') {
              $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
              $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
              $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just rebroadcast a'.' <a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">post</a>'.'</div>';
              $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
              $notify_result .= '</div>';
            } elseif($row['action'] == 'request') {
              $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
              $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
              $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just requested a'.' <a href="http://localhost:8080/reminderapp/postpage.php?u='.$row['initiator'].'&id='.$row['post_id'].'&tag='.$row['post_tag'].'">broadcast</a>'.'</div>';
              $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
              $notify_result .= '</div>';
            } elseif($row['action'] == 'subscribed') {
              $notify_result .= '<div id="notepage" class="notepage" style="height:80px; min-width:160px; background-color:#ddd; margin-right:-120px;">';
              $notify_result .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'"><img src="user/'.$row['initiator'].'/'.$row['avatar'].'" alt="'.$row['initiator'].'" width="41" height="40"></a>';
              $notify_result .= '<div class="notification-message" style="color:white;">'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['initiator'].'">'.$name.'</a>'.' just subscribed to a'.'<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['target'].'"> provider</a>'.'</div>';
              $notify_result .= '<div id="notification-date" data-livestamp="'.$postdate.'" style="color:white; font-size:11px; font-weight:600; font-family:Arial, Helvetica, sans-serif;">'.'</div>';
              $notify_result .= '</div>';
            }
            $notify_result .= '<hr>';
          }
    if ($notification_count == '0') {
        $notification .= '<div id="notify" style="display:inline-block;">'.'<a href="notification_page.php?u='.$log_username.'"><i class="fas fa-bell fa-2x" id="note_still" alt="Notes" title="Notifications" style="color:#3fa9f5; margin-top:0.8px;"></i>'.'</a></div>';
    } else {
        $notification .= '<div id="notify" style="display:inline-block;">'.'<i class="fas fa-bell fa-2x" id="note_still" alt="Notes" title="Notifications" style="color:white; cursor:pointer;margin-top:0.8px;"></i>'.'<span id="totalcount" style="background-color:red; color:white; width:50px; height:20px; font-size:12px; margin-top:5px; cursor:pointer;">'.$notification_count.'</span>';
        $notification .= '<div id="notecontainer" class="notecontainer" style="display:none; border:solid 1px lightslategray; margin-top:4px; overflow-x:auto;">';
        $notification .= '<div style="text-align:center; color:#004080; font-weight:800; font-size:14px; z-index:9999; background-color:white; height:30px;">NOTIFICATIONS</div>';
        $notification .= '<div id="notedisplay" class="notedisplay">'.$notify_result.'</div>';
        $notification .= '<div id="spanBase" style="height:40px; width:320px; background-color:gray; text-align:center; margin-top:-2px;"><a style="color:white; font-weight:800;" href="notification_page.php?u='.$log_username.'">View all notifications</a></div>';
        $notification .= '</div>';
        $notification .= '</div>';
    }
  echo $notification;
}
?>
