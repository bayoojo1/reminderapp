<?php
  try {
    $a2billing_db = new PDO('mysql:host=10.32.1.167;dbname=mya2billing', 'a2billinguser', 'a2billing');
    $a2billing_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage()."<br>";
    die();
}
 ?>
