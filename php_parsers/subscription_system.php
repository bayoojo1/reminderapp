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
$provider = $_POST['user'];
$detail = 'subscribed to a user';
$action = 'subscribed';
if($_POST['type'] == "subscribe" && isset($_POST['user'])){
$stmt = $db_connect->prepare("INSERT INTO subscription (subscriber, provider, date_subscribed, countrycode, subscriber_mobile) VALUES (:subscriber, :provider, now(), :countrycode, :mobile)");
$stmt->execute(array(':subscriber' => $log_username, ':provider' => $provider, ':countrycode' => $countrycode, ':mobile' => $mobile));
            //$db_connect = null;
            echo "subscribe_ok";
            // Insert detail into the notifications table
            $stmt = $db_connect->prepare("INSERT INTO notifications (initiator, target, action, detail, postdate)
            VALUES(:initiator, :target, :action, :detail, now())");
            $stmt->execute(array(':initiator' => $log_username, ':target' => $provider, ':action' => $action, ':detail' => $detail));
            exit();
        } else if($_POST['type'] == "unsubscribe" && isset($_POST['user'])){
            $sql = "DELETE FROM subscription WHERE subscriber=:subscriber AND provider=:provider AND countrycode=:countrycode AND subscriber_mobile=:mobile";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindParam(':subscriber', $log_username, PDO::PARAM_STR);
            $stmt->bindParam(':provider', $provider, PDO::PARAM_STR);
            $stmt->bindParam(':countrycode', $countrycode, PDO::PARAM_STR);
            $stmt->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $stmt->execute();
            $db_connect = null;
            echo "unsubscribe_ok";
            exit();
        }
?>
