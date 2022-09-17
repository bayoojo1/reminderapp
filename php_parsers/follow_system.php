<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
if($user_ok != true || $log_username == "") {
    exit();
}
?><?php
$sql = "SELECT countrycode, mobile FROM users WHERE username=:logusername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
if($stmt->rowCount() > 0){
    $row = $stmt->fetch();
    $countrycode = $row[0];
    $mobile = $row[1];
}
?><?php
$user = $_POST['user'];
$action = 'follow';
$detail = 'followed a user';
if($_POST['type'] == "follow" && isset($_POST['user'])){
$stmt = $db_connect->prepare("INSERT INTO follows (user1, user2, countrycode, mobile, datefollowed) VALUES (:logusername, :user, :countrycode, :mobile, now())");
$stmt->execute(array(':logusername' => $log_username, ':user' => $user, ':countrycode' => $countrycode, ':mobile' => $mobile));
            //$db_connect = null;
            echo "follow_ok";
            // Insert detail into the notifications table
            $stmt = $db_connect->prepare("INSERT INTO notifications (initiator, target, action, detail, postdate)
            VALUES(:initiator, :target, :action, :detail, now())");
            $stmt->execute(array(':initiator' => $log_username, ':target' => $user, ':action' => $action, ':detail' => $detail));
            exit();
        } else if($_POST['type'] == "unfollow" && isset($_POST['user'])){
            $sql = "DELETE FROM follows WHERE user1=:logusername AND user2=:user AND countrycode=:countrycode AND mobile=:mobile";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
            $stmt->bindParam(':user', $user, PDO::PARAM_STR);
            $stmt->bindParam(':countrycode', $countrycode, PDO::PARAM_STR);
            $stmt->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $stmt->execute();
            $db_connect = null;
            echo "unfollow_ok";
            exit();
        }
?>
