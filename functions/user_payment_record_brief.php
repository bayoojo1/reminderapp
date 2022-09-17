<?php
include("./php_includes/mysqli_connect.php");
// Get all the content of users table
$transpage = '';
//$paginationCtrls = '';

// Fetch payment history data from the database.
$sql_trans = "SELECT transaction_id, email, total_amount, status, date FROM payment WHERE merchant_ref=:username";
$stmt = $db_connect->prepare($sql_trans);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$trans_count = $stmt->rowCount();
// Set row per page.
$rpp = '10';
// Set the sql again with the limit
$limit = ' LIMIT '.$rpp;
$sql = "$sql_trans"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
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
echo '<br />';
if($trans_count > 10) {
echo '<a href="http://localhost:8080/reminderapp/user_payment_record.php?u='.$username.'">'.'<span style="display:block; text-align:center; font-weight:800; color:#4169E1; margin-bottom:-10px;"><u>'.'Payment Records'.'</u></span>'.'</a>';
} else {
  echo '<span style="display:block; text-align:center; font-weight:800; color:grey; margin-bottom:-10px;"><u>'.'Payment Records'.'</u></span>';
}
echo '<br />';
echo $transpage;
?>
