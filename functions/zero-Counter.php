<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}
$naatcast = 'naatcast';
// Update the database and set notify to current date/time
$sql = "UPDATE date_visit SET last_visited=now() WHERE username=:logusername";
//$sql = "UPDATE users SET lastlogin=now() WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

$sql_statement = "SELECT notifications.id, notifications.initiator, target, action, post_id, post_tag, detail, postdate, follows.user2, subscription.provider, users.fullname, users.alias, users.avatar, useroptions.aliascheck, date_visit.last_visited FROM notifications LEFT JOIN follows ON follows.user2=notifications.initiator LEFT JOIN subscription ON subscription.provider=notifications.initiator INNER JOIN users ON users.username=notifications.initiator INNER JOIN useroptions ON useroptions.username=notifications.initiator INNER JOIN date_visit ON date_visit.username=follows.user1 WHERE notifications.postdate > date_visit.last_visited AND date_visit.username=:logusername AND (follows.user1=:logusername OR subscription.subscriber=:logusername OR initiator LIKE :naatcast OR target LIKE :naatcast)";

$stmt = $db_connect->prepare($sql_statement);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindValue(':naatcast', '%'.$naatcast.'%', PDO::PARAM_STR);
$stmt->execute();

$numrows = $stmt->rowCount();
echo $numrows;
?>
