<?php
function exchangeRate() {
  include("./php_includes/check_login_status.php");
  include("./php_includes/mysqli_connect.php");
  // Get the country code of the login user
  $sql = "SELECT countrycode FROM users WHERE username=:logusername";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
  $stmt->execute();
  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $cc = $row['countrycode'];
  }
  $ccode = '+'.$cc;
  // Now get the country currency symbol from country table
  $sql = "SELECT currency_code, dial_code FROM country WHERE dial_code=:dialcode";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':dialcode', $ccode, PDO::PARAM_STR);
  $stmt->execute();
  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    //$currencySymbol = $row['currency_symbol'];
    $currencyCode = $row['currency_code'];
    $dialcode = $row['dial_code'];
  }
  // Remove the trailing plus from the dial code.
  $dialingCode = ltrim($dialcode, "+");
  // Now get the call rate for this dial code
  $sql = "SELECT Rate FROM csv WHERE Prefix=:prefix LIMIT 1";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindValue(':prefix', $dialingCode, PDO::PARAM_STR);
  $stmt->execute();
  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    //$currencySymbol = $row['currency_symbol'];
    $callRate = $row['Rate'];
  }
  $rate = $callRate + ($callRate * 0.20); //20% increase of the provider rate.
	$req_url = 'https://v3.exchangerate-api.com/pair/e7d4bf2db5bceb012c3647f1/USD/'.$currencyCode;
	$response_json = file_get_contents($req_url);
	$response_object = json_decode($response_json);
	if('success' === $response_object->result) {
      $price = round(($rate * $response_object->rate), 2);
      return $currencyCode.'' .$price;
	}
}
//exchangeRate();
?>
