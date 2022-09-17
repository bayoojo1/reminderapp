<?php
include("./php_includes/mysqli_connect.php");
// Get all the content of users table
$transpage = '';
// Fetch payment history data from the database.
$sql_trans = "SELECT username, postdate, accepted, broadcast, tag FROM once WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM daily WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM weekly WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM monthly WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM yearly WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM dailyround WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM dailydaytime WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM dailynight WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM dailyweekdaytime WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM dailyweekdaynight WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM dailyweekendday WHERE username1=:user AND TRIM(mobile) > '' UNION
SELECT username, postdate, accepted, broadcast, tag FROM  dailyweekendnight WHERE username1=:user AND TRIM(mobile) > ''";
$stmt = $db_connect->prepare($sql_trans);
$stmt->bindParam(':user', $username, PDO::PARAM_STR);
$stmt->execute();
$trans_count = $stmt->rowCount();
// Set row per page.
$rpp = '10';
// Set the sql again with the limit
$limit = ' LIMIT '.$rpp;
$sql = "$sql_trans"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':user', $username, PDO::PARAM_STR);
$stmt->execute();

if($trans_count > 0){
    $transpage .= '<div id="cdr_search" style="overflow-x:auto;">';
        $transpage .= "<table style='width:100%; border: 1px solid gray;'>";
        $transpage .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
        $transpage .= "<tr>";
            $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>B/Name</th>";
            $transpage .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Date Posted</th>";
            $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Schedule</th>";
            $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white'>Target</th>";
            $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Recurrent</th>";
        $transpage .= "</tr>";
        $transpage .= "</thead>";

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $broadcast_name = $row['username'];
    $dateposted = $row['postdate'];
    $schedule = $row['accepted'];
    $target = $row['broadcast'];
    $recurrent = $row['tag'];

    $transpage .= "<tbody>";
    $transpage .= "<tr>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $broadcast_name . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $dateposted . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $schedule . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $target . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $recurrent . "</td>";
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
                $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>B/Name</th>";
                $transpage .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Date Posted</th>";
                $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Schedule</th>";
                $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Target</th>";
                $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Recurrent</th>";
            $transpage .= "</tr>";
            $transpage .= "</thead>";
            $transpage .= "</table>";
            $transpage .= "</div>";
    $transpage .= 'No record found!';
}
echo '<br />';
if($trans_count > 10) {
echo '<a href="http://localhost:8080/reminderapp/user_broadcast_record.php?u='.$username.'">'.'<span style="display:block; text-align:center; font-weight:800; color:#4169E1; margin-bottom:-10px;"><u>'.'Broadcast Records'.'</u></span>'.'</a>';
} else {
  echo '<span style="display:block; text-align:center; font-weight:800; color:grey; margin-bottom:-10px;"><u>'.'Broadcast Records'.'</u></span>';
}
echo '<br />';
echo $transpage;
?>
