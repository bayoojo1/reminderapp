<?php
include("./php_includes/mysqli_connect.php");
// Get all the content of users table
$transpage = '';

// Fetch payment history data from the database.
$sql_trans = "SELECT mobile, dates, statusCode, talk FROM audio_stats WHERE username=:username";
$stmt = $db_connect->prepare($sql_trans);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();
$trans_count = $stmt->rowCount();

// Specify how many result per page
$rpp = '10';
$limit = ' LIMIT '.$rpp;
// This is the query again, it is for grabbing just one page worth of rows by applying $limit
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
            $transpage .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Recipient</th>";
            $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Duration</th>";
            $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white'>Status</th>";
        $transpage .= "</tr>";
        $transpage .= "</thead>";

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $target = $row['mobile'];
    $date = $row['dates'];
    $statuscode = $row['statusCode'];
    $duration = $row['talk'];

    $transpage .= "<tbody>";
    $transpage .= "<tr>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $date . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $target . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $duration . "</td>";
        $transpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $statuscode . "</td>";
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
                $transpage .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Recipient</th>";
                $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Duration</th>";
                $transpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Status</th>";
            $transpage .= "</tr>";
            $transpage .= "</thead>";
            $transpage .= "</table>";
            $transpage .= "</div>";
    $transpage .= 'No record found!';
}
echo '<br />';
if($trans_count > 10) {
echo '<a href="http://localhost:8080/reminderapp/user_content_request_record.php?u='.$username.'">'.'<span style="display:block; text-align:center; font-weight:800; color:#4169E1; margin-bottom:-10px;"><u>'.'Content Request Records'.'</u></span>'.'</a>';
} else {
  echo '<span style="display:block; text-align:center; font-weight:800; color:grey; margin-bottom:-10px;"><u>'.'Content Request Records'.'</u></span>';
}
echo '<br />';
echo $transpage;

?>
