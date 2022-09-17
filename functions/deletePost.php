<?php
include("../php_includes/mysqli_connect.php");
$status = $_GET["status"];
    if($status == "delete"){
        $id = $_GET["id"];
        $tag = lcfirst($_GET["tag"]);

$sql = "DELETE FROM $tag WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
}
?>



      
