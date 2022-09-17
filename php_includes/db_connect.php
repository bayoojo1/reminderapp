<?php
  try {
    $connection = mysqli_connect('localhost', 'root', 'wifi1234','reminderapp');
    //$db_connect = new PDO('mysql:host=localhost;dbname=reminderapp', 'root', 'wifi1234');
} catch (PDOException $e) {
    echo $e->getMessage()."<br>"; // In development
    //error_log($e->getMessage()); //In production
    die();
}
 ?>
