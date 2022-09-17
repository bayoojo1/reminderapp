<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
//$db_connect = new PDO('mysql:host=localhost;dbname=reminderapp', 'root', 'wifi1234');
$sql = "SELECT * FROM users WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $f = $row['firstname'];
    $l = $row['lastname'];
    $p_hash = $row['password'];
    $c = $row['countrycode'];
    $m = $row['mobile'];
    $w = $row['website'];
    $alias = $row['alias'];
    $about = $row['about'];
    $avatar = $row['avatar'];
    $e = $row['email'];
}

if (isset($_GET["status"])){
    $status = $_GET["status"];
}


if($status == "Save"){
  $id = $_GET['id'];
  $value = $_GET['value'];


if(strpos($id, 'firstname') !== false) {
    $sql = "UPDATE users SET firstname=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'lastname') !== false) {
    $sql = "UPDATE users SET lastname=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'alias') !== false) {
    $sql = "UPDATE users SET alias=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'countrycode') !== false) {
    $sql = "UPDATE users SET countrycode=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
    //Send email to this user notifying of a change in the countrycode.
    require("/home/naat/vendor/phpmailer/phpmailer/src/PHPMailer.php");
        $mail = new PHPMailer;
        
        $email_body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>NaatCast Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.naatcast.com"><img src="/images/logo.png" width="36" height="30" alt="NaatCast" style="border:none; float:left;"></a>Alert! - A Change in Your Country Code</div><div style="padding:24px; font-size:17px;">Hello '.$f.',<br /><br />We just noticed a change of your country code to '.$value.' in your profile page. In case you are not the one who made this change, login and correct the change.<br /><br />Best Regards,<br />NaatCast Team.</div></body></html>';
        
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

            $mail->Subject = 'Alert! - A Change in Your Country Code ' . $name;
            $mail->Body    = $email_body;

            if($mail->send()) {
                exit;
                }
                $error_message = 'Message could not be sent.';
                $error_message .= 'Mailer Error: ' . $mail->ErrorInfo;


} else if(strpos($id, 'mobile') !== false) { 
    $sql = "UPDATE users SET mobile=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
    //Send email to this user notifying of a change in the countrycode.
    require("/home/naat/vendor/phpmailer/phpmailer/src/PHPMailer.php");
        $mail = new PHPMailer;
        
        $email_body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>NaatCast Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.naatcast.com"><img src="/images/logo.png" width="36" height="30" alt="NaatCast" style="border:none; float:left;"></a>Alert! - A Change in Your Mobile Number</div><div style="padding:24px; font-size:17px;">Hello '.$f.',<br /><br />We just noticed a change of your mobile number to '.$value.' in your profile page. In case you are not the one who made this change, login and correct the change.<br /><br />Best Regards,<br />NaatCast Team.</div></body></html>';
        
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

            $mail->Subject = 'Alert! - A Change in Your Mobile Number ' . $name;
            $mail->Body    = $email_body;

            if($mail->send()) {
                exit;
                }
                $error_message = 'Message could not be sent.';
                $error_message .= 'Mailer Error: ' . $mail->ErrorInfo;
} else if(strpos($id, 'website') !== false) {
    $sql = "UPDATE users SET website=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'about') !== false) {
    $sql = "UPDATE users SET about=:value WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
} else if(strpos($id, 'description') !== false) {
    $sql = "UPDATE content_provider SET description=:value WHERE provider=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
}

    include("../php_includes/mysqli_connect.php");
    $sql = "SELECT * FROM users WHERE username='$log_username'";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $fname = $row['firstname'];
        $lname = $row['lastname'];
    }
    $db_connect = null;

    $fullNAME = $fname.' '.$lname;
    include("../php_includes/mysqli_connect.php");
    $sql = "UPDATE users SET fullname=:fullname WHERE username=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':fullname', $fullNAME, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
}
?>