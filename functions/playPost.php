<?php
include("../php_includes/mysqli_connect.php");
//include("../php_includes/wombat_db.php");
$status = $_GET["status"];
    if($status == "play"){
        $id = $_GET["id"];
        $tag = lcfirst($_GET["tag"]);

$sql = "SELECT username FROM $tag WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $username = $row['username'];
  }

// Initiate cURL to wombat_db
include_once("resumeCampaign.php");
$resume = array(
    'op' => 'unpause',
    'campaign' => $username,
    );
    resumeCampaign("http://10.32.0.5:8080/wombat/api/campaigns/?",$resume);
}
?><?php
$pause = 'pause';
// Update the users table and change the state column to "play"
$sql = "UPDATE $tag SET state=:pause WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':pause', $pause, PDO::PARAM_INT);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
?>
