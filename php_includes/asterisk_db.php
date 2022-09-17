<?php
  try {
    $asterisk_db = new PDO('mysql:host=10.32.1.167;dbname=asterisk', 'asterisk', '0pelcorsa');
    $asterisk_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage()."<br>";
    die();
}
 ?>
