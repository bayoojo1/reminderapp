<?php
include("./php_includes/mysqli_connect.php");
$naatcast = 'naatcast';
$sql_statement = "SELECT notifications.id, notifications.initiator, target, action, post_id, post_tag, detail, postdate, follows.user2, subscription.provider, users.fullname, users.alias, users.avatar, useroptions.aliascheck FROM notifications LEFT OUTER JOIN follows ON follows.user2=notifications.initiator LEFT OUTER JOIN subscription ON subscription.provider=notifications.initiator LEFT OUTER JOIN users ON users.username=notifications.initiator LEFT OUTER JOIN useroptions ON useroptions.username=notifications.initiator WHERE follows.user1=:logusername OR subscription.subscriber=:logusername OR initiator LIKE :naatcast OR target LIKE :naatcast GROUP BY id ORDER BY notifications.id DESC";

$stmt = $db_connect->prepare($sql_statement);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindValue(':naatcast', '%'.$naatcast.'%', PDO::PARAM_STR);
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
$stmt->bindValue(':naatcast', '%'.$naatcast.'%', PDO::PARAM_STR);
$stmt->execute();

// Check if user has followers
    if($numrows < 1){
    echo '<br />';
    echo '<div id="followerList" style="height:60px; text-align:center; vertical-align: middle; font-size:20px; color:white;">';
    echo "You don't have new notification";
    echo '</div>';
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
$name = "";
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
    $notify_result .= '<br>';
}
echo $paginationCtrls;
echo '<br />';
echo $notify_result;
echo '<br />';
echo $paginationCtrls;
?>
