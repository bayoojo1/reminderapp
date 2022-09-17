<?php
include("../php_includes/check_login_status.php");
include("../php_includes/wombat_db.php");
include("../php_includes/mysqli_connect.php");
include("../php_includes/a2billing_db.php");

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}

if(isset($_POST['feed_tag'])) {
    $feed_tag = lcfirst(($_POST['feed_tag']));
    $feed_id = ($_POST['feed_id']);
}
// Get the requested audio from the database
//$sql = "SELECT audio FROM $feed_tag WHERE id=:id";
$sql = "SELECT audio, email, firstname FROM $feed_tag INNER JOIN users ON $feed_tag.username1=users.username WHERE $feed_tag.id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindValue(':id', $feed_id, PDO::PARAM_INT);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $audio = $row['audio'];
    $email = $row['email'];
    $provider = $row['firstname'];
}

// Scoop out the detail of the user requesting this audio, the login user or logusername
$sql ="SELECT username, email, countrycode, mobile FROM users WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $username = $row['username'];
    $email = $row['email'];
    $countrycode = $row['countrycode'];
    $mobile = $row['mobile'];
}
// GATHER OTHER REQUIRED VARIABLES
$string = rand(0, 100000000);
$z = $username."".($string);
$asterisk_fn = "";
if(pathinfo($audio, PATHINFO_EXTENSION) == "wav") {
    $asterisk_fn = pathinfo($audio, PATHINFO_FILENAME); // This will return the filename without the extension
} else {
    $asterisk_fn = pathinfo($audio, PATHINFO_BASENAME); // This will return filename with the extension.
}
$date = date("Y-m-d.H:i:s",strtotime(date("Y-m-d H:i:s")." +5 seconds"));
$p_remote = "/home/uploads/$asterisk_fn"; // This is the path to remote audio file on the asterisk server
$db_filename = pathinfo($audio, PATHINFO_BASENAME);


// Scoop out credit balance from the a2billing database
$sql = "SELECT credit FROM cc_card WHERE phone=:phone";
$stmt = $a2billing_db->prepare($sql);
$stmt->bindParam(':phone', $mobile, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
    $credit = $row["credit"];
}

if($credit < 0.2) {
    echo "no_credit";
    exit();
} else {

// Create the campaign
include_once("createCampaign.php");
$create = array(
    'op' => 'clone',
    'campaign' => 'once-file',
    'newcampaign' => $z
    );
    createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);

// Update Wombat DB
$m = trim($countrycode).trim(substr($mobile, 1));
$sql = "UPDATE campaigns SET dial_clid=:dial_clid, dial_pres=:dial_pres, agentClid=:agentClid, email_addresses=:email_addresses WHERE name=:name";
$stmt = $wombat_db->prepare($sql);
$stmt->bindParam(':dial_clid', $m, PDO::PARAM_STR);
$stmt->bindParam(':dial_pres', $m, PDO::PARAM_STR);
$stmt->bindParam(':agentClid', $m, PDO::PARAM_STR);
$stmt->bindParam(':email_addresses', $email, PDO::PARAM_STR);
$stmt->bindParam(':name', $z, PDO::PARAM_STR);
$stmt->execute();

// Start a campaign
include_once("startCampaign.php");
$start = array(
'op' => 'start',
'campaign' => $z
);
startCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$start);


usleep(500000);

// Add call to a campaign
include_once("addCall.php");
$add = array(
'op' => 'addcall',
'campaign' => $z,
'number' => $m,
'schedule' => $date,
'attrs' => "filepath:{$p_remote}"
);
if(addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add)) {
    echo "success";
}
    // Send a mail to the provider of this request
//    require("/home/naat/vendor/phpmailer/phpmailer/src/PHPMailer.php");
/*
$mail = new PHPMailer;

$email_body = '<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>NaatCast Message</title>
</head>
<body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;">
  <div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.naatcast.com"><img src="/images/logo.png" width="36" height="30" alt="NaatCast" style="border:none; float:left;"></a>NaatCast - Audio Request Notification
  </div>
  <div style="padding:24px; font-size:17px;">
    Hello '.$provider.',
    <br /><br />
    One of your subscribers - '.$log_username.' has just requested your broadcast.<br />
    Use all the social button tools provider for each of your broadcast to create more awareness of all your broadcast.<br />
    This will enable more people to subscribe to you and continue to request your audio.<br />
    <p><b></b>Continue NaatCasting!.</b><p>

  </div>
</body>
</html>';

    $email = 'info@naatcast.com';
    $name = 'NaatCast';

    $mail->Host = 'email.smilecoms.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = false;                               // Enable SMTP authentication
    $mail->Username = '';                 // SMTP username
    $mail->Password = '';                           // SMTP password
    $mail->SMTPSecure = '';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;                                    // TCP port to connect to


    $mail->setFrom($email, $name);
    $mail->addAddress("$e");     // Add a recipient

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'Audio Request Notification ' . $name;
    $mail->Body    = $email_body;
    $mail->send(); */

}
?><?php
// Scoop out the detail of the audio from wombat log file
$terminated = "TERMINATED";
$sql_statement = "SELECT waitAfter, statusCode, numberDialed, attempted, astUnique FROM log_f WHERE attributes LIKE :audio AND statusCode=:statusCODE ORDER BY id DESC LIMIT 1";
$stmt = $wombat_db->prepare($sql_statement);
$stmt->bindValue(':audio', '%'.$asterisk_fn.'%', PDO::PARAM_STR);
$stmt->bindParam(':statusCODE', $terminated, PDO::PARAM_STR);
//$stmt->bindParam(':callNumber', $m, PDO::PARAM_STR);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $talk = $row['waitAfter'];
    $Date = $row['attempted'];
    $mobile = $row['numberDialed'];
    $astUnique = $row['astUnique'];

// Update naatcast database with the required details for this audio
$stmt = $db_connect->prepare("INSERT INTO audio_stats (audio, mobile, dates, statusCode, talk, username, astUnique, audio_id, audio_tag)
    VALUES (:audio, :mobile, :dates, :statusCode, :talk, :username, :astUnique, :audio_id, :audio_tag) ON DUPLICATE KEY UPDATE
    audio = :audio,
    mobile = :mobile,
    dates = :dates,
    statusCode = :statusCode,
    talk = :talk,
    username = :username,
    audio_id = :audio_id,
    audio_tag = :audio_tag");
$stmt->execute(array(':audio' => $db_filename, ':mobile' => $mobile, ':dates' => $Date, ':statusCode' => $terminated, ':talk' => $talk, ':username' => $log_username, ':astUnique' => $astUnique, ':audio_id' => $feed_id, ':audio_tag' => $feed_tag));
}
// Insert variables into notification table
$detail = 'request audio content';
$action = 'request';
$stmt = $db_connect->prepare("INSERT INTO notifications (initiator, target, action, post_id, post_tag, detail, postdate)
VALUES(:initiator, :target, :action, :post_id, :post_tag, :detail, now())");
$stmt->execute(array(':initiator' => $log_username, ':target' => $provider, ':action' => $action, ':post_id' => $feed_id, ':post_tag' => $feed_tag, ':detail' => $detail));
?>
