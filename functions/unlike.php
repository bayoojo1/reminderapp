<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}

if(isset($_POST['tag'])) {
    $unlike_tag = lcfirst(($_POST['tag']));
    $unlike_id = ($_POST['id']);
}

$status = '0';
$unlike_digit = '0';
$like_digit = '1';
// Check if this user already unlike this post
$sql = "SELECT id FROM like_unlike WHERE post_id=:post_id AND recurrent=:recurrent AND status=:status AND username=:logusername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':post_id', $unlike_id, PDO::PARAM_STR);
$stmt->bindParam(':recurrent', $unlike_tag, PDO::PARAM_STR);
$stmt->bindParam(':status', $unlike_digit, PDO::PARAM_STR);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

$unlike_row = $stmt->rowCount();

// Check if this user already like this post
$sql = "SELECT id FROM like_unlike WHERE post_id=:post_id AND recurrent=:recurrent AND status=:status AND username=:logusername LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':post_id', $unlike_id, PDO::PARAM_STR);
$stmt->bindParam(':recurrent', $unlike_tag, PDO::PARAM_STR);
$stmt->bindParam(':status', $like_digit, PDO::PARAM_STR);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

$like_row = $stmt->rowCount();

if(($like_row || $unlike_row) == '1') {
    echo "not_ok";
    exit();
} else if(($like_row && $unlike_row) == '0') {
// Update the like_unlike table
$stmt = $db_connect->prepare("INSERT INTO like_unlike (username, post_id, status, recurrent)
VALUE (:username, :post_id, :status, :recurrent)");
$stmt->execute(array(':username' => $log_username, ':post_id' => $unlike_id, ':status' => $status, ':recurrent' => $unlike_tag));
}
// Count the number of unlikes in the like_unlike table and spit the result back to ajax
$sql = "SELECT id FROM like_unlike WHERE post_id=:post_id AND status=:status AND recurrent=:recurrent";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':post_id', $unlike_id, PDO::PARAM_STR);
$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':recurrent', $unlike_tag, PDO::PARAM_STR);
$stmt->execute();

$numrows = $stmt->rowCount();

echo $numrows;
?>