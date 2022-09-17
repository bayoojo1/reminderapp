<?php
// This page provides the functions that enable the search of users on NaatCast //
include("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");
include("theCollector.php");

if(isset($_SESSION['username'])) {
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_SESSION['username']);
} else {
    exit();
}
?><?php
$subscribeTo = "";
$sql_statement = "SELECT users.id, alias, fullname, users.username, avatar, useroptions.aliascheck, content_provider.approved, content_provider.content_type, content_provider.description FROM users INNER JOIN useroptions ON users.username=useroptions.username INNER JOIN content_provider ON users.username=content_provider.provider WHERE approved=:approved ORDER BY RAND()";
$stmt = $db_connect->prepare($sql_statement);
$stmt->bindValue(':approved', '1', PDO::PARAM_STR);
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
$stmt->bindValue(':approved', '1', PDO::PARAM_STR);
$stmt->execute();
//var_dump($stmt);
// Establish the $paginationCtrls variable

if($numrows < 1){
    echo '<br />';
echo '<div id="followerList" style="height:60px; text-align:center; vertical-align: middle; font-size:20px; color:white;">';
echo "There is no content provider";
echo 'If you have useful contents to provide under this category or in any other categories on NaatCast, you can start the registration process now by <b><a href="http://localhost:8080/reminderapp/subscription_registration.php?u='.$u.'">clicking this link</a></b>';
echo '</div>';
    include_once("template_pageRight.php");

    exit();
} else if($numrows > 0) {
    // Establish the $paginationCtrls variable
$paginationCtrls = "";
$providerPage = "";
$subscriber = "Subscribers";
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

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $provider = $row['username'];
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
?><?php
if($subscribed == true){
    $subscription_Btn = '<button onclick="subscriptionToggle(\'unsubscribe\',\''.$provider.'\',\''.$providerid.'\')">Unsubscribe</button>';
    } elseif($provider != $log_username && $subscribed == false && $user_ok == true) {
        $subscription_Btn = '<button onclick="subscriptionToggle(\'subscribe\',\''.$provider.'\',\''.$providerid.'\')">Subscribe</button>';
    } else {
        $subscription_Btn = '<button disabled>Me</button>';
    }
?><?php
// Get the number of subscribers for this provider
$sql = "SELECT id FROM subscription WHERE provider=:provider";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':provider', $provider, PDO::PARAM_STR);
$stmt->execute();
$no_of_sub = $stmt->rowCount();
?><?php
// Get the number of broadcast by this provider
$sql = "SELECT id FROM once WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM daily WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM weekly WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM monthly WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM yearly WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM dailyround WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM dailydaytime WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM dailynight WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM dailyweekdaytime WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM dailyweekdaynight WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM dailyweekendday WHERE username1=:providerName AND broadcast=:subscriber UNION
        SELECT id FROM dailyweekendnight WHERE username1=:providerName AND broadcast=:subscriber";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':providerName', $provider, PDO::PARAM_STR);
$stmt->bindParam(':subscriber', $subscriber, PDO::PARAM_STR);
$stmt->execute();
$no_of_broadcast = $stmt->rowCount();

$providerPage .= '<div id="subscriptionList">';
$providerPage .= '<a class="image" href="http://localhost:8080/reminderapp/user_audio.php?u='.$provider.'"><img src="user/'.$provider.'/'.$row['avatar'].'" alt="'.$provider.'">'.'<br />';
if($row['aliascheck'] == '1') {
    $providerPage .= '<div id="fuserName">'.$row["fullname"].'</a>'.'</div>';
} else if($row['aliascheck'] == '0') {
    $providerPage .= '<div id="fuserName">'.$row["alias"].'</a>'.'</div>';
}
$providerPage .= '<span id='.$providerid.'>'.$subscription_Btn.'</span>';
$providerPage .= '<span id="cont_type">'.'<b>'.$row['content_type'].'</b>'.'</span>';
$providerPage .= '<span id="nofsub" style="color:white; font-style:italic;">Subscribers: '.$no_of_sub.'</span>';
$providerPage .= '<span id="nofbroadcast" style="float:right; color:white; font-style:italic;">Broadcast: '.$no_of_broadcast.'</span>';
$providerPage .= '</div>';
$providerPage .= '<div id="childdiv1" style="background-color:gainsboro;">'.$row['description'].'</div>';
$providerPage .= '<br />';
}
}
include_once("functions/page_functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Content Provider</title>
<link rel="stylesheet" href="style/normalize.css">
<link href="https://fonts.googleapis.com/css?family=Changa+One:400,400i|Open+Sans:400,400i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/jquery.js"></script>
<script src="js/header_scroll.js"></script>
<script src="js/main.js"></script>
<script src="js/functions.js"></script>
</head>
<body class="insidepage">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div><br /><br />
<div id="wrapper">
    <?php echo $pageleft; ?>

    <div id="PageMiddle"><br class="b1">
    <table>
        <tr>
            <td>
                <div style="float: right; font-style:italic;">Select a category:
            </td>
            <td>
                <select id="contentCategory" name="contentCategory" style="width: 160px;">
                        <option selected="selected" value="all">-------Select-------</option>
                        <option value="Educational">Educational</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Sport">Sport</option>
                        <option value="Breaking News">Breaking News</option>
                        <option value="Weather Report">Weather Report</option>
                        <option value="Traffic Report">Traffic Report</option>
                        <option value="Market Survey">Market Survey</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Hot Gist">Hot Gist</option>
                        <option value="Religion">Religion</option>
                        <option value="Health and Fitness">Health and Fitness</option>
                        <option value="Food and Nutrition">Food and Nutrition</option>
                        <option value="Fashion and Beauty">Fashion and Beauty</option>
                        <option value="Finance">Finance</option>
                        <option value="Security">Security</option>
                        <option value="Auto Care">Auto Care</option>
                        <option value="Pet Care">Pet Care</option>
                </select>
                </div>
            </td>
        </tr>
    </table><br />
        <?php echo $paginationCtrls; ?><br class="b2">
        <div id="providerPage"><?php echo $providerPage; ?></div>
        <?php echo $paginationCtrls; ?><br />
    </div>

    <?php include_once("template_pageRight.php"); ?>

</div>
</body>
</html>
