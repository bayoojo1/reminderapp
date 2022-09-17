<?php
  try {
    $wombat_db = new PDO('mysql:host=10.32.1.200;dbname=wombat', 'root', '0pelcorsa');
    $wombat_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    //echo $e->getMessage();
    die('connection_failure');
}
 ?>
