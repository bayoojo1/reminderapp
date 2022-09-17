<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}
?><?php
if(isset($_POST['id'])) {
$id = $_POST['id'];
$c = $_POST['checked'];
}
// Scoop out the firstname of the content provider
include("../php_includes/mysqli_connect.php");
$sql = "SELECT users.firstname, users.email FROM content_provider INNER JOIN users ON content_provider.provider=users.username WHERE content_provider.id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $provider = $row['firstname'];
    $e = $row['email'];
}
// 
if($c == '0') {
include("../php_includes/mysqli_connect.php");
$sql = "UPDATE content_provider SET approved=:approved WHERE id=:providerid";
$stmt = $db_connect->prepare($sql);
$stmt->bindValue(':approved', $c, PDO::PARAM_INT);
$stmt->bindValue(':providerid', $id, PDO::PARAM_INT);
$stmt->execute();
$db_connect = null;

} else if($c == '1') {
    include("../php_includes/mysqli_connect.php");
    $sql = "UPDATE content_provider SET approved=:approved WHERE id=:providerid";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindValue(':approved', $c, PDO::PARAM_INT);
    $stmt->bindValue(':providerid', $id, PDO::PARAM_INT);
    $stmt->execute();
    $db_connect = null;

// Send email to the provider notifying of the approval
require("/home/naat/vendor/phpmailer/phpmailer/src/PHPMailer.php");
$mail = new PHPMailer;

$email_body = '<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>NaatCast Message</title>
</head>
<body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;">
  <div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.naatcast.com"><img src="../images/logo.png" width="36" height="30" alt="NaatCast" style="border:none; float:left;"></a>NaatCast Content Provider Approval
  </div>
  <div style="padding:24px; font-size:17px;">
    Hello '.$provider.',
    <br /><br />
    We are glad to notify you of your approval as a content provider on NaatCast. This approval has given you the opportunity<br />
    of monetizing your contents like never before. Please take your time to go through the link below and familiarise yourself with<br />
    how you can make use of this platform to your maximum advantage.<br />
    <p>Once again, you are welcome to NaatCast.<p>
    <br /><br />
    <a href="http://www.naatcast.com/content_provider_faq.php">FAQ for Content Provider</a>

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

    $mail->Subject = 'Content Provider Approval ' . $name;
    $mail->Body    = $email_body;

    if($mail->send()) {
        echo "signup_success";
        exit();
        }
        $error_message = 'Message could not be sent.';
        $error_message .= 'Mailer Error: ' . $mail->ErrorInfo;
}
?>