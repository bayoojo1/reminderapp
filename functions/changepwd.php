<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
include("../php_includes/asterisk_db.php");
include("../php_includes/a2billing_db.php");

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}

if(isset($_POST['cp'])) {
    $currentpwd = ($_POST['cp']);
    $newpwd = password_hash($_POST['np'], PASSWORD_DEFAULT);
    $confirmnewpwd = ($_POST['cf']);
}
$sql = "SELECT password, countrycode, email, mobile FROM users WHERE username=:username AND activated=:activated LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $log_username, PDO::PARAM_STR);
$stmt->bindValue(':activated', '1', PDO::PARAM_STR);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $password = $row['password'];
    $cc = $row['countrycode'];
    $mobile = $row['mobile'];
}

//$m = trim($cc).trim(substr($mobile, 1));
$m = trim($cc).trim($mobile);

if($currentpwd == "" || $newpwd == "" || $confirmnewpwd == "") {
    echo "empty_field";
    exit();
} else if(!password_verify($currentpwd, $password)) {
    echo "not_thesame_1";
    exit();
} else if(!password_verify($confirmnewpwd, $newpwd)) {
    echo "not_thesame_2";
    exit();
} else {
  // Update the users table
    $sql = "UPDATE users SET password=:password WHERE username=:username";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':password', $newpwd, PDO::PARAM_STR);
    $stmt->bindParam(':username', $log_username, PDO::PARAM_STR);
    $stmt->execute();

    // Update the asterisk database SIP table
    $sql = "UPDATE SIP SET secret=:secret WHERE callerid=:callerid";
    $stmt = $asterisk_db->prepare($sql);
    $stmt->bindParam(':secret', $confirmnewpwd, PDO::PARAM_STR);
    $stmt->bindParam(':callerid', $m, PDO::PARAM_STR);
    $stmt->execute();

    // Update the a2billing database
    $sql = "UPDATE cc_card SET uipass=:uipass WHERE email=:email";
    $stmt = $a2billing_db->prepare($sql);
    $stmt->bindParam(':uipass', $newpwd, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Echo success to ajax
    echo "successful";

}
?><?php
//Send email to this user notifying of a change in the countrycode.
require "../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "../vendor/phpmailer/phpmailer/src/SMTP.php";
require "../vendor/phpmailer/phpmailer/src/Exception.php";

  $mail = new PHPMailer\PHPMailer\PHPMailer();
  $mail->IsSMTP();

  $email_body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>NaatCast Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#004080; font-size:24px; color:#CCC;"><a href="http://www.naatcast.com"><img src="/images/logo.png" width="36" height="30" alt="NaatCast" style="border:none; float:left;"></a>Alert! - A Change in Your Password!</div><div style="padding:24px; font-size:17px;">Hello '.$f.',<br /><br />We just noticed a change of your password to '.$newpwd.' in your profile page. In case you are not the one who made this change, login and correct the change.<br /><br />Best Regards,<br />Team NaatCast.</div></body></html>';

      $email = 'info@naatcast.com';
      $name = 'NaatCast';

      $mail->SMTPDebug = 4;
      $mail->Host = 'smtp.mailgun.org';
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'postmaster@mail1.naatcast.com';                 // SMTP username
      $mail->Password = '054ca0cded46010fb0e6a3c2eed6cf88-116e1e4d-9ffaa304';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to
      $mail->WordWrap = 50;

      $mail->From = $email;
      $mail->FromName = $name;
      $mail->addAddress("$e");     // Add a recipient

      $mail->isHTML(true);                                  // Set email format to HTML

      $mail->Subject = 'Alert! - A Change in Your Password ' . $name;
      $mail->Body    = $email_body;

      if(!$mail->send()) {
          //exit;
          $error_log = 'Message could not be sent.';
          $error_log .= 'Mailer Error: ' . $mail->ErrorInfo;
          }
?>
