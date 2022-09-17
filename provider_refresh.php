<?php
include("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");

if(isset($_SESSION['username'])) {
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_SESSION['username']);;
} else {
    exit();
}

$subscribeTo = "";
$sql_statement = "SELECT users.id, alias, fullname, users.username, avatar, useroptions.aliascheck, content_provider.approved, content_provider.content_type, content_provider.description FROM users INNER JOIN useroptions ON users.username=useroptions.username INNER JOIN content_provider ON users.username=content_provider.provider WHERE approved=:approved ORDER BY RAND() LIMIT 5";
$stmt = $db_connect->prepare($sql_statement);
$stmt->bindValue(':approved', '1', PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();

if($count > 0) {
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $provider = $row['username'];
        $providerid = $row['id'];

        $subscribed = false;
?><?php
        if($u == $log_username && $user_ok == true){
        $subscribed_check = "SELECT id FROM subscription WHERE subscriber=:subscriber AND provider=:provider LIMIT 1";
        $stmt = $db_connect->prepare($subscribed_check);
        $stmt->bindParam(':subscriber', $log_username, PDO::PARAM_STR);
        $stmt->bindParam(':provider', $provider, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $subscribed = true;
        }
        } else if ($u != $log_username && $user_ok == true) {
        $subscribed_check = "SELECT id FROM subscription WHERE subscriber=:subscriber AND provider=:provider LIMIT 1";
        $stmt = $db_connect->prepare($subscribed_check);
        $stmt->bindParam(':subscriber', $log_username, PDO::PARAM_STR);
        $stmt->bindParam(':provider', $provider, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $subscribed = true;
        }
}
?><?php
        if($subscribed == true){
        $subscription_Btn = '<button onclick="subscriptionToggle(\'unsubscribe\',\''.$provider.'\',\''.$providerid.'\')">Unsubscribe</button>';
        } elseif($provider != $log_username && $subscribed == false && $user_ok == true) {
            $subscription_Btn = '<button onclick="subscriptionToggle(\'subscribe\',\''.$provider.'\',\''.$providerid.'\')">Subscribe</button>';
        } else {
            $subscription_Btn = '<button style="display:none;">Me</button>';
        }


        $subscribeTo .= '<div id="eachprovider" style="background-color:#bdbdbd; margin-bottom:1px;">';
        $subscribeTo .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$provider.'"><img src="user/'.$provider.'/'.$row['avatar'].'" alt="'.$provider.'">'.'<br />';
        if($row['aliascheck'] == '1') {
            $subscribeTo .= '<div id="providerName">'.$row["fullname"].'</a>'.'</div>';
        } else if($row['aliascheck'] == '0') {
            $subscribeTo .= '<div id="providerName">'.$row["alias"].'</a>'.'</div>';
        }
        $subscribeTo .= '<span class="providerclass" id='.$providerid.'>'.$subscription_Btn.'</span>';
        $subscribeTo .= '<span id="content-type">'.'<b>'.$row['content_type'].'</b>'.'</span>';
        $subscribeTo .= '</div>';
        //$subscribeTo .= '<br />';
    }
} else {
    $subscribeTo .= 'There is no provider';
}
echo $subscribeTo;
?>
