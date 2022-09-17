<?php
include_once("php_includes/mysqli_connect.php");
include_once("php_includes/a2billing_db.php");
include_once("php_includes/asterisk_db.php");
include_once("functions/deletefolder.php");
// This block deletes all accounts that do not activate after 1 days
$activated = '0';
$sql = "SELECT id, username, email, isdn FROM users WHERE signup<=(CURRENT_DATE() - INTERVAL 1 DAY) AND activated=:activated";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':activated', $activated, PDO::PARAM_STR);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $id = $row['id'];
  $username = $row['username'];
  $email = $row['email'];
  $callerid = $row['isdn'];
  $userFolder = "user/$username";
  $uploadFolder = "uploads/$username";
  delete_files($userFolder);
  delete_files($uploadFolder);
// Delete user from users table
$sql = "DELETE FROM users WHERE id=:id AND username=:username AND activated=:activated LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':activated', $activated, PDO::PARAM_STR);
$stmt->execute();
// Delete user from useroptions table
$sql = "DELETE FROM useroptions WHERE username=:username LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();

// Delete user from date_visit table
$sql = "DELETE FROM date_visit WHERE username=:username LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();

// Delete user from asterisk db
$sql = "DELETE FROM SIP WHERE callerid=:callerid LIMIT 1";
$stmt = $asterisk_db->prepare($sql);
$stmt->bindParam(':callerid', $callerid, PDO::PARAM_STR);
$stmt->execute();

// Delete user from cc_callerid table
$sql = "DELETE FROM cc_callerid WHERE cid=:cid LIMIT 1";
$stmt = $a2billing_db->prepare($sql);
$stmt->bindParam(':cid', $callerid, PDO::PARAM_STR);
$stmt->execute();

// Delete user from a2billing db
$sql = "DELETE FROM cc_card WHERE email=:email LIMIT 1";
$stmt = $a2billing_db->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
}
?>
