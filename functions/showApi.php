<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}
?><?php
//$db_connect = new PDO('mysql:host=localhost;dbname=reminderapp', 'root', 'wifi1234');
$sql = "SELECT id, api_name, api_token, api_description FROM api WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $id = $row['id'];
  $apiname = $row['api_name'];
  $apitoken = $row['api_token'];
  $api_description = $row['api_description'];
}
  echo "<div id='apicontainer'>";
    echo "<span id='$id' class='deleteapi' style='float:right; cursor:pointer; margin-right:2px; margin-top:-1px;' onclick='deleteapi(this.id)'>X</span>";
    echo "<div style='background-color:#0066ff; color:white; text-align:center; font-weight:bold;'><span style='color:#F3F9DD;'>API Name:</span> $apiname</div>";
    echo "<span style='color:#004080; font-weight:bold; margin-right:10px;'>API Description: </span>"."<span class='nameapi'>$api_description</span>";
    echo "<br />";
    echo "<span style='color:#004080; font-weight:bold; margin-right:10px;'>API Token: </span>"."<span class='nameapi'><code>$apitoken</code></span>";
  echo "</div>";
  echo "<br />";

?>
