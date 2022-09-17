<?php
include("../php_includes/mysqli_connect.php");
//include("../php_includes/wombat_db.php");
$status = $_GET["status"];
    if($status == "pause"){
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
include_once("pauseCampaign.php");
$pause = array(
    'op' => 'pause',
    'campaign' => $username,
    );
    pauseCampaign("http://10.32.0.5:8080/wombat/api/campaigns/?",$pause);
}
?><?php
$play = 'play';
// Update the users table and change the state column to "play"
$sql = "UPDATE $tag SET state=:play WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':play', $play, PDO::PARAM_INT);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
?>
