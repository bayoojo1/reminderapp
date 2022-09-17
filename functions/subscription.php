<?php
include("./php_includes/mysqli_connect.php");

include("page_functions.php");

$sql_statement = "SELECT subscriber, subscription.provider, subscription.countrycode, subscriber_mobile, users.id, users.firstname, users.lastname, users.fullname, users.avatar, users.alias, content_provider.content_type, useroptions.aliascheck FROM subscription INNER JOIN users ON subscription.provider=users.username INNER JOIN content_provider ON subscription.provider=content_provider.provider INNER JOIN useroptions ON subscription.provider=useroptions.username WHERE content_provider.approved=:approved AND subscriber=:logusername";
$stmt = $db_connect->prepare($sql_statement);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindValue(':approved', '1', PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();

if($count < 1) {
    echo '<br />';
    echo '<hr />';
    echo '<br />';
    echo 'You are yet to subscribe to any content provider. There are content providers under the following categories: <b>educational, comedy, sport, breaking news, weather report, traffic report, market survey, entertainment, hot gist, religion, health and fitness, food and nutrition, fashion and beauty, finance, security,</b> etc. Search and subscribe to the content(s) of your choice under these categories.';
    echo '<br />';
    echo '<br />';
    echo '<hr />';
    echo '<br />';
    if(!$isProvider) {
    echo 'Do you also have content under any of these categories that can be of benefit to the public?';
    echo '<br />';
    echo '<br />';
    echo 'Become a content provider and monetize your content, <b><a href="http://localhost:8080/reminderapp/subscription_registration.php?u='.$u.'">click this link to start the process.</a></b>';
    }
    echo '<br />';
    echo '<br />';
    echo '<div id="howitworks" style="text-align:center; font-weight:800; color:#004080; cursor:pointer;">'.'How it works'.'</div>';
    echo '<hr />';
    echo '<br />';
    echo '<div id="howitworksdisplay" style="display:none;">';
    echo 'NaatCast provides a platform where anyone with valuable and beneficial audio contents can market his/her content. As a content provider on NaatCast, you create the audio content, upload and then NaatCast takes over the remaining. Your subscribers are notified of the new content you shared, and the content would show up in their timeline with a "Request Audio" button. Once they click the button, they receive a phone call from NaatCast, and once they pick the call, the content is played back to them.
    Your subscriber don\'t require Internet connection to get the content delivered to their mobile phone, as long as they are connected to their provider network, they are good to go.';
    echo '<br />';
    echo '<p style="text-align:center; font-weight:800; color:#004080;">'.'How do you benefit from this as a content provider?'.'</p>';
    echo 'NaatCast and content provider have 60/40 profit sharing per content.
    And anytime your subscriber request a re-broadcast of a particular content directly from the web application, he/she gets billed and you are being paid. This means a continous revenue from a single content you provided just once, as long as the content remains on NaatCast platform.
    NaatCast has also provided tools you can use to share each content to other social media. With this, you as a provider can create more awareness for your contents on other platforms, hence increasing your listening audience.';
    echo '<br />';
    echo '<br />';
    echo '<hr />';
    echo '</div>';
} else {
    // Specify how many result per page
    $rpp = '10';
    // This tells us the page number of the last page
    $last = ceil($count/$rpp);
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
$stmt->bindValue(':approved', '1', PDO::PARAM_STR);
$stmt->execute();

// Establish the $paginationCtrls variable
$paginationCtrls = '';

if($count > 0){
    $paginationCtrls .= '<div id="paginationctrls">';
    if($last != 1){
        /* First we check if we are on page one. If we are then we don't need a link to
           the previous page or the first page so we do nothing. If we aren't then we
           generate links to the first page, and to the previous page. */
        if ($pn > 1) {
            $previous = $pn - 1;
            $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
            // Render clickable number links that should appear on the left of the target page number
            for($i = $pn-4; $i < $pn; $i++){
                if($i > 0){
                    $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
                }
            }
        }
        // Render the target page number, but without it being a link
        $paginationCtrls .= ''.$pn.' &nbsp; ';
        // Render clickable number links that should appear on the right of the target page number
        for($i = $pn+1; $i <= $last; $i++){
            $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
            if($i >= $pn+4){
                break;
            }
        }
        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
        if ($pn != $last) {
            $next = $pn + 1;
            $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'">Next</a> ';
        }
    }
    $paginationCtrls .= '</div>';

    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $provider = $row['provider'];
        $providerid = $row['id'];

        $subscribed = false;

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
        if($subscribed == true){
            $subscription_Btn = '<button onclick="subscriptionToggle(\'unsubscribe\',\''.$provider.'\',\''.$providerid.'\')">Unsubscribe</button>';
            } elseif($provider != $log_username && $subscribed == false && $user_ok == true) {
                $subscription_Btn = '<button onclick="subscriptionToggle(\'subscribe\',\''.$provider.'\',\''.$providerid.'\')">Subscribe</button>';
            } else {
                $subscription_Btn = '<button disabled>Me</button>';
            }
            $subscription_List = '';
            $subscription_List .= '<br />';
            $subscription_List .= '<div id="subscriptionList">';
            $subscription_List .= '<a class="image" href="http://localhost:8080/reminderapp/user_audio.php?u='.$provider.'"><img src="user/'.$provider.'/'.$row['avatar'].'" alt="'.$provider.'">'.'<br />';
            if($row['aliascheck'] == '1') {
                $subscription_List .= '<div id="fuserName">'.$row["fullname"].'</a>'.'</div>';
            } else if($row['aliascheck'] == '0') {
                $subscription_List .= '<div id="fuserName">'.$row["alias"].'</a>'.'</div>';
            }
            $subscription_List .= '<span id='.$providerid.'>'.$subscription_Btn.'</span>';
            $subscription_List .= '<span id="cont_type">'.'<b>'.$row['content_type'].'</b>'.'</span>';
        $subscription_List .= '</div><br />';

        echo $paginationCtrls;
        echo $subscription_List;
    }

}
}

?>
