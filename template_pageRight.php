<?php
include_once("functions/page_functions.php");
include("php_includes/mysqli_connect.php");

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
        $subscribeTo .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$provider.'"><img src="user/'.$provider.'/'.$row['avatar'].'" alt="'.$provider.'" style="color:white;">'.'<br />';
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

?>
<div id="PageRight">

<div id="big-ads">
<div class="whoto" style="background-color:white; color:#004080; border:1px solid #bdbdbd; text-align:center;">Who to subscribe to</div>
<div id="divContent" style="min-height:60px; border:1px solid #bdbdbd; margin-bottom:10px; background-color:white;"><?php echo $subscribeTo ?></div>
<div id="refresh" style="background-color:white; margin-top:-10px; border: 1px solid #bdbdbd;">
<span class="refresh" style="color:#0066ff; cursor:pointer; text-align:left;" onclick="refresh()">Refresh</span>
&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<span class="viewall" style="color:#0066ff; cursor:pointer; text-align:right;"><a href="http://localhost:8080/reminderapp/provider.php?u=<?php echo $log_username ?>">View all</a></span>
</div>
</div>
<br />

<div style="color:#0066ff; font-weight:bold;"><span class="ContactUs" style="float:left; margin-left:0px;"><a href="http://localhost:8080/reminderapp/contact.php?u=<?php echo $log_username ?>">Contact Us</a></span><span class="termsandCond" style="float:right; margin-right:0px;"><a href="http://localhost:8080/reminderapp/terms.html" target="_blank"> Terms & Conditions</a></span></div>
<br><br>
<div id="pr-copyright" style="background-color:#004080; font-size:12px;">
    Copyright &copy<?php echo date("Y"); ?> - NaatCast<br />
</div>
</div>
