<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}
?><?php
// Scoop out the mobile number and country code of the person I'm following.
//$db_connect = new PDO('mysql:host=localhost;dbname=reminderapp', 'root', 'wifi1234');
$sql = "SELECT countrycode, mobile FROM users WHERE username=:logusername AND activated='1' LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $user_cc = $row['countrycode'];
    $user_mobile = $row['mobile'];
}
$db_connect = null;
?><?php
if(isset($_POST['u'])) {
$u = $_POST['u'];
$c = $_POST['checked'];
}
// Update the follows table. Add or remove the mobile and country code.
if($c == '0') {
include("../php_includes/mysqli_connect.php");
$sql = "UPDATE follows SET noaudiocheck=:noaudiocheck, countrycode=:countrycode, mobile=:mobile WHERE user1=:logusername AND user2=:user";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':noaudiocheck', $c, PDO::PARAM_INT);
$stmt->bindParam(':countrycode', $user_cc, PDO::PARAM_STR);
$stmt->bindParam(':mobile', $user_mobile, PDO::PARAM_STR);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->bindParam(':user', $u, PDO::PARAM_STR);
$stmt->execute();
$db_connect = null;

} else if($c == '1') {
    $thecode = ''; // Empty the code
    $thenumber = ''; // Empty the number
    include("../php_includes/mysqli_connect.php");
    $sql = "UPDATE follows SET noaudiocheck=:noaudiocheck, countrycode=:countrycode, mobile=:mobile WHERE user1=:logusername AND user2=:user";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':noaudiocheck', $c, PDO::PARAM_INT);
    $stmt->bindParam(':countrycode', $thecode, PDO::PARAM_STR);
    $stmt->bindParam(':mobile', $thenumber, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->bindParam(':user', $u, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
}
?>