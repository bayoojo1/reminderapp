<?php
include("../php_includes/mysqli_connect.php");

$status = $_GET["status"];
if($status == "delete"){
    $id = $_GET["id"];
}

$sql = "DELETE FROM tickets WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();
?>
