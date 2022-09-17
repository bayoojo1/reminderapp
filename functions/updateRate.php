<?php
include_once("../php_includes/mysqli_connect.php");

if(isset($_POST['id'])) {
  $id = $_POST['id'];
  $destination = $_POST['destination'];
  $prefix = $_POST['prefix'];
  $rate = $_POST['rate'];
}
//Update the database
$stmt = $db_connect->prepare("INSERT INTO csv (id, destination, prefix, rate)
VALUES (:id, :destination, :prefix, :rate)
ON DUPLICATE KEY UPDATE
destination = :destination,
prefix = :prefix,
rate = :rate");
if($stmt->execute(array(':id' => $id, ':destination' => $destination, ':prefix' => $prefix, ':rate' => $rate))) {
  echo 'Update successful';
} else {
  echo 'Update not successful';
}

?>
