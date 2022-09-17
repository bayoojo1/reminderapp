<?php
include("./php_includes/mysqli_connect.php");
include("./php_includes/a2billing_db.php");
// Get all the content of users table
$sql = "SELECT * FROM users WHERE username=:username LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $u, PDO::PARAM_STR);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $username = $row['username'];
  $firstname = $row['firstname'];
  $lastname = $row['lastname'];
  $email = $row['email'];
  $fullname = $row['fullname'];
  $cc = $row['countrycode'];
  $mobile = $row['mobile'];
  $web = $row['website'];
  $alias = $row['alias'];
  $about = $row['about'];
  $datejoin = $row['signup'];
}
// Get the number of content providers subscribed to
$sql = "SELECT id FROM subscription WHERE subscriber=:username";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$nofsubscription = $stmt->rowCount();
// Get the amount of credit in this user account
$sql = "SELECT credit FROM cc_card WHERE email=:email LIMIT 1";
$stmt = $a2billing_db->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
    $credit = $row["credit"];
}
?><?php
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Username: '.'</span>'." $username";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'First Name: '.'</span>'." $firstname";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Last Name: '.'</span>'." $lastname";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Email: '.'</span>'." $email";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Full Name: '.'</span>'." $fullname";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Country Code: '.'</span>'." $cc";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Mobile: '.'</span>'." $mobile";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Website: '.'</span>'." $web";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Alias: '.'</span>'." $alias";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'About Me: '.'</span>'." $about";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Date Registered: '.'</span>'." $datejoin";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Number of Subscriptions: '.'</span>'." $nofsubscription";
echo '<br>';
echo '<span style="display:inline-block; font-weight:800; color:grey; margin-bottom:-10px;">'.'Account Balance: '.'</span>'." $credit";
?><?php
echo '<br />';
echo '<br />';
// Include the script for user payment record display
include_once('user_payment_record_brief.php');
echo '<br />';
echo '<br />';
// Include the script for user broadcast record display
include_once('user_broadcast_records_brief.php');
echo '<br />';
echo '<br />';
// Include the script for user content request record
include_once('user_content_requests_brief.php');
?>
