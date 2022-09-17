<?php
include("./php_includes/mysqli_connect.php");
// Get all the content of users table
$transpage = '';
$paginationCtrls = '';

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

// Specify how many result per page
$rpp = '10';
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

        $paginationCtrls .= '<div id="paginationctrls">';
        if($last != 1){
            /* First we check if we are on page one. If we are then we don't need a link to
               the previous page or the first page so we do nothing. If we aren't then we
               generate links to the first page, and to the previous page. */
            if ($pn > 1) {
                $previous = $pn - 1;
                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$username.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
                // Render clickable number links that should appear on the left of the target page number
                for($i = $pn-4; $i < $pn; $i++){
                    if($i > 0){
                        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$username.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
                    }
                }
            }
            // Render the target page number, but without it being a link
            $paginationCtrls .= ''.$pn.' &nbsp; ';
            // Render clickable number links that should appear on the right of the target page number
            for($i = $pn+1; $i <= $last; $i++){
                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$username.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
                if($i >= $pn+4){
                    break;
                }
            }
            // This does the same as above, only checking if we are on the last page, and then generating the "Next"
            if ($pn != $last) {
                $next = $pn + 1;
                $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?u='.$username.'&pn='.$next.'">Next</a> ';
            }
        }
        $paginationCtrls .= '</div>';

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
echo $paginationCtrls;
echo '<br />';
echo $transpage;
?>
