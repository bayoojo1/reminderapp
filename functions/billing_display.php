<?php
//include("./php_includes/check_login_status.php");
include("./php_includes/a2billing_db.php");
include("./php_includes/mysqli_connect.php");

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}
// Scoop some variables from the user table
$sql = "SELECT countrycode, mobile FROM users WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $c = $row["countrycode"];
    $m = $row["mobile"];
}
$m_number = trim($c.''.$m);

// Scoop out credit balance from the a2billing database
$sql = "SELECT credit FROM cc_card WHERE phone=:phone";
$stmt = $a2billing_db->prepare($sql);
$stmt->bindParam(':phone', $m, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
    $credit = $row["credit"];
}

// Check if I've subscribed to any provider
$isSubscribed = false;
$sql = "SELECT id FROM subscription WHERE subscriber=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
if($stmt->rowCount() > 0) {
    $isSubscribed = true;
}

$ccode = '+'.$c;
// Now get the country currency code and dial code from country table
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
    $price = ceil(round(($rate * $response_object->rate), 2));
    $actualPrice = $currencyCode.'' .$price;
}

$transpage = '';
$paginationCtrls = '';

// Fetch payment history data from the database.
$sql_trans = "SELECT transaction_id, email, total_amount, status, date FROM payment WHERE merchant_ref=:logusername";
$stmt = $db_connect->prepare($sql_trans);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
$trans_count = $stmt->rowCount();

// Specify how many result per page
$rpp = '20';
// This tells us the page number of the last page
$last = ceil($trans_count/$rpp);
// This makes sure $last cannot be less than 1
if($last < 1){
    $last = 1;
}
// Define pagination control
//$paginationCtrls = "";
// Define page number
$pn = "1";

// Get pagenum from URL vars if it is present, else it is = 1
if(isset($_GET['pn'])){
    $pn = preg_replace('#[^0-9]#', '', $_GET['pn']);
//$searchquery = $_POST['searchquery'];
}

// Make the script run only if there is a page number posted to this script

// This makes sure the page number isn't below 1, or more than our $last page
if ($pn < 1) {
    $pn = 1;
} else if ($pn > $last) {
$pn = $last;
}

// This sets the range of rows to query for the chosen $pn
$limit = 'LIMIT ' .($pn - 1) * $rpp .',' .$rpp;
// This is the query again, it is for grabbing just one page worth of rows by applying $limit
$sql = "$sql_trans"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

if($trans_count > 0){
    $transpage .= '<div id="cdr_search" style="overflow-x:auto;">';
        $transpage .= "<table style='width:100%; border: 1px solid gray;'>";
        $transpage .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
        $transpage .= "<tr>";
            $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Date</th>";
            $transpage .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Transaction ID</th>";
            $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Email</th>";
            $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white'>Amount</th>";
            $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Status</th>";
        $transpage .= "</tr>";
        $transpage .= "</thead>";

        $paginationCtrls .= '<div id="paginationctrls">';
        if($last != 1){
            /* First we check if we are on page one. If we are then we don't need a link to
               the previous page or the first page so we do nothing. If we aren't then we
               generate links to the first page, and to the previous page. */
            if ($pn > 1) {
                $previous = $pn - 1;
                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
                // Render clickable number links that should appear on the left of the target page number
                for($i = $pn-4; $i < $pn; $i++){
                    if($i > 0){
                        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
                    }
                }
            }
            // Render the target page number, but without it being a link
            $paginationCtrls .= ''.$pn.' &nbsp; ';
            // Render clickable number links that should appear on the right of the target page number
            for($i = $pn+1; $i <= $last; $i++){
                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
                if($i >= $pn+4){
                    break;
                }
            }
            // This does the same as above, only checking if we are on the last page, and then generating the "Next"
            if ($pn != $last) {
                $next = $pn + 1;
                $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&pn='.$next.'">Next</a> ';
            }
        }
        $paginationCtrls .= '</div>';

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $trans_id = $row['transaction_id'];
    $trans_email = $row['email'];
    $trans_total_amt = $row['total_amount'];
    $trans_status = $row['status'];
    $trans_date = $row['date'];

    $transpage .= "<tbody>";
    $transpage .= "<tr>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $trans_date . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $trans_id . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $trans_email . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $trans_total_amt . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $trans_status . "</td>";
    $transpage .= "</tr>";
    $transpage .= "</tbody>";
  }
  $transpage .= "</table>";
$transpage .= '</div>';
} else {
    $transpage .= '<div id="cdr_search" style="overflow-x:auto;">';
            $transpage .= "<table style='width:100%; border: 1px solid gray;'>";
            $transpage .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
            $transpage .= "<tr>";
                $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Date</th>";
                $transpage .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Transaction ID</th>";
                $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Email</th>";
                $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Amount</th>";
                $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Status</th>";
            $transpage .= "</tr>";
            $transpage .= "</thead>";
            $transpage .= "</table>";
            $transpage .= "</div>";
    $transpage .= 'No record found!';
}
echo '<div class="dropdown" style="background-color:gainsboro;">';
        echo '<div class="dropbtn">'.'<a href="#">'.'<b>'.'Payment History'.'</b>'.'</a>'.' <i class="fa fa-caret-down" style="color:blue;"></i>'.'</div>';
        echo '<div class="dropdown-content">';
        echo '<div>'.'<a href="http://localhost:8080/reminderapp/apicreate_page.php?u='.$u.'">'.'Create API'.'</a>'.'</div>';
        echo '<div>'.'<a href="http://localhost:8080/reminderapp/profile_page.php?u='.$u.'">'.'My Profile'.'</a>'.'</div>';
        echo '<div>'.'<a href="http://localhost:8080/reminderapp/callrecords_page.php?u='.$u.'">'.'Call Record'.'</a>'.'</div>';
        echo '</div>';
echo '</div>';
echo '<br />';
echo '<br />';
if($credit <= 5) {
    echo '<div id="billing" style="background-color:red; color:white; text-align:center;">';
    echo '<b>'.'Available Balance: '.'</b>'. 'NGN'.round($credit, 2);
    echo '</div>';
} else {
echo '<div id="billing" style="background-color:forestgreen; color:white; text-align:center;">';
echo '<b>'.'Available Balance: '.'</b>'. 'NGN'.round($credit, 2);
echo '</div>';

}
echo '<br />';
echo '<br />';
echo '<div style="text-align:center; background-color:lightblue; color:forestgreen; font-weight:800;">Based on your registered country code, a minute broadcast to mobile number in your country would cost <span style="color:white;">' .$actualPrice.'</span> Checkout our full broadcast rates <a href="http://localhost:8080/reminderapp/ratesheets_page.php?u='.$u.'">here.</a> All broadcast are rated per minute.</div>';
echo '<br />';
echo '<div style="text-align:center; background-color:steelblue; color:white;">Service presently available only to Nigeria numbers. International routes would be opened soon.</div>';
echo '<br />';
echo '<br />';
?><?php
$payment = "";
$payment .= '<div id="pgateway">';
$payment .= '<div id="p-100" style="border: 1px solid #CD7F32; height:160px;">';
$payment .= '<div style="font-size:25px; background-color:#CD7F32; font-weight:800;">Bronze</div>';
$payment .= '<div style="font-size:16px; color:gray; font-weight:bold;">NGN500</div>';
// Pay with VoguePay
$payment .= "<form method='POST' action='https://voguepay.com/pay/'>";
    $payment .= "<input type='hidden' name='v_merchant_id' value='demo' />";
    $payment .= "<input type='hidden' name='merchant_ref' value=$log_username />";
    $payment .= "<input type='hidden' name='memo' value='NaatCast-NGN500' />";
    $payment .= "<input type='hidden' name='notify_url' value='http://localhost:8080/reminderapp/n.php?u=$log_username' />";
    $payment .= "<input type='hidden' name='success_url' value='http://localhost:8080/reminderapp/payment_success.php?u=$log_username' />";
    $payment .= "<input type='hidden' name='fail_url' value='http://www.naatcast.com/failed.php' />";
    $payment .= "<input type='hidden' name='total' value='500' />";
    $payment .= "<input type='hidden' name='cur' value='NGN' />";
    $payment .= "<input type='hidden' name='developer_code' value='5aca789f84458' />";
     ##notification triggers for inline payments only##
    $payment .= "<input type='hidden' name='closed' value='closedFunction'>";
    $payment .= "<input type='hidden' name='success' value='successFunction'>";
    $payment .= "<input type='hidden' name='failed' value='failedFunction'>";
    $payment .= "<input class='pg1' type='image' src='https://voguepay.com/images/buttons/make_payment_blue.png' alt='Submit' />";
$payment .= "</form>";
$payment .= "<br />";
$payment .= "<br />";
$payment .= "<br />";
$payment .= "<br />";
$payment .= '</div>';

$payment .= '<div id="p-500" style="border:1px solid #C0C0C0; height:160px;">';
$payment .= '<div style="font-size:25px; background-color:#C0C0C0; font-weight:800;">Silver</div>';
$payment .= '<div style="font-size:16px; color:gray; font-weight:bold;">NGN1000</div>';
$payment .= "<form method='POST' action='https://voguepay.com/pay/'>";
    $payment .= "<input type='hidden' name='v_merchant_id' value='demo' />";
    $payment .= "<input type='hidden' name='merchant_ref' value=$log_username />";
    $payment .= "<input type='hidden' name='memo' value='NaatCast-NGN1000' />";
    $payment .= "<input type='hidden' name='notify_url' value='http://localhost:8080/reminderapp/n.php?u=$log_username' />";
    $payment .= "<input type='hidden' name='success_url' value='http://localhost:8080/reminderapp/payment_success.php?u=$log_username' />";
    $payment .= "<input type='hidden' name='fail_url' value='http://www.naatcast.com/failed.php' />";
    $payment .= "<input type='hidden' name='total' value='1000' />";
    $payment .= "<input type='hidden' name='cur' value='NGN' />";
    $payment .= "<input type='hidden' name='developer_code' value='5aca789f84458' />";
     ##notification triggers for inline payments only##
    $payment .= "<input type='hidden' name='closed' value='closedFunction'>";
    $payment .= "<input type='hidden' name='success' value='successFunction'>";
    $payment .= "<input type='hidden' name='failed' value='failedFunction'>";
    $payment .= "<input class='pg2' type='image' src='https://voguepay.com/images/buttons/make_payment_blue.png' alt='Submit' />";
$payment .= "</form>";
$payment .= "</div>";
$payment .= '<div id="p-1000" style="border:1px solid #D4AF37; height:160px;">';
$payment .= '<div style="font-size:25px; background-color:#D4AF37; font-weight:800;">Gold</div>';
$payment .= '<div style="font-size:16px; color:gray; font-weight:bold;">NGN5000</div>';
$payment .= "<form method='POST' action='https://voguepay.com/pay/'>";
    $payment .= "<input type='hidden' name='v_merchant_id' value='demo' />";
    $payment .= "<input type='hidden' name='merchant_ref' value=$log_username />";
    $payment .= "<input type='hidden' name='memo' value='NaatCast-NGN5000' />";
    $payment .= "<input type='hidden' name='notify_url' value='http://localhost:8080/reminderapp/n.php?u=$log_username' />";
    $payment .= "<input type='hidden' name='success_url' value='http://localhost:8080/reminderapp/payment_success.php?u=$log_username' />";
    $payment .= "<input type='hidden' name='fail_url' value='http://www.naatcast.com/failed.php' />";
    $payment .= "<input type='hidden' name='total' value='5000' />";
    $payment .= "<input type='hidden' name='cur' value='NGN' />";
    $payment .= "<input type='hidden' name='developer_code' value='5aca789f84458' />";
     ##notification triggers for inline payments only##
    $payment .= "<input type='hidden' name='closed' value='closedFunction'>";
    $payment .= "<input type='hidden' name='success' value='successFunction'>";
    $payment .= "<input type='hidden' name='failed' value='failedFunction'>";
    $payment .= "<input class='pg3' type='image' src='https://voguepay.com/images/buttons/make_payment_blue.png' alt='Submit' />";
$payment .= "</form>";
$payment .= '</div>';
$payment .= '</div>';
echo $payment;
?><?php
echo '<br>';
echo '<div style="font-face:bold; font-size:16px;">';
echo 'For subscription more than the above, please <a href="http://localhost:8080/reminderapp/contact.php?u='.$log_username.'">contact us</a>';
echo '</div>';
echo '<br>';
echo '<br>';
echo '<div style="text-align:center; background-color:#fff; color:#004080; font-weight:bold;">Payment History</div>';
echo $paginationCtrls;
echo '<br />';
echo $transpage;
?>
