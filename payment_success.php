<?php
include("php_includes/a2billing_db.php");
include("php_includes/mysqli_connect.php");

if(isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
}
// Send request to VoguePay
$data = file_get_contents('https://voguepay.com/?v_transaction_id='.$transaction_id.'&type=json&demo=true');
$arr = json_decode($data, true);

// Get all the needed variables to be used
$merchant_id = $arr['merchant_id'];
$email = $arr['email'];
$total_amount = $arr['total_amount'];
$log_username = $arr['merchant_ref'];
$memo = $arr['memo'];
$status = $arr['status'];
$date = $arr['date'];
$total_credited_to_merchant = $arr['total_credited_to_merchant'];
$extra_charges_by_merchant = $arr['extra_charges_by_merchant'];
$charges_paid_by_merchant = $arr['charges_paid_by_merchant'];
$fund_maturity = $arr['fund_maturity'];
$currency = $arr['cur'];
$total_paid_by_buyer = $arr['total_paid_by_buyer'];
$total = $arr['total'];


// Get out the logusername from the referrer url
//$log_username = end(explode('/', rtrim($arr['referrer'], '/')));

// Do a little sanitation
if($arr['referrer'] != 'http://www.naatcast.com/billing/'.$log_username) {
    header('location: http://www.naatcast.com');
    exit();
}



if($status == 'Approved') {
// Scoop the mobile number of this user
$sql = "SELECT countrycode, mobile FROM users WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $mobile = $row["mobile"];
    $cc = $row["countrycode"];
}
// Update the user credit
$sql = "UPDATE cc_card SET credit = credit + $total WHERE zipcode=:zipcode AND phone=:phone";
$stmt = $a2billing_db->prepare($sql);
$stmt->bindParam(':phone', $mobile, PDO::PARAM_STR);
$stmt->bindParam(':zipcode', $cc, PDO::PARAM_STR);
if($stmt->execute()) {
    header("location: http://www.naatcast.com/billing/$log_username");
    }

// Send email to the user of this transaction
}
?>
