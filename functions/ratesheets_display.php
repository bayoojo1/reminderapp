<?php
include("./php_includes/mysqli_connect.php");
// Define the country constant. Remember this is a temporary measure until international payment gateway is enabled and other countries can use the service.

//SQL statement
$sql_statement = "SELECT id, Destination, Prefix, Rate FROM csv";
$stmt = $db_connect->prepare($sql_statement);
$stmt->execute();

$count = $stmt->rowCount();

// Specify how many result per page
$rpp = '30';
// This tells us the page number of the last page
$last = ceil($count/$rpp);
// This makes sure $last cannot be less than 1
if($last < 1){
    $last = 1;
}
// Define pagination control
// Define page number
$pn = "1";

// Get pagenum from URL vars if it is present, else it is = 1
if(isset($_GET['pn'])){
$pn = preg_replace('#[^0-9]#', '', $_GET['pn']);
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
$sql = "$sql_statement"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->execute();

// Establish the $paginationCtrls variable
$paginationCtrls = '';
$resultpage = '';


if($count > 0){
    $resultpage .= '<div id="rate_search" style="overflow-x:auto;">';
        $resultpage .= "<table style='width:100%; border: 1px solid gray;'>";
        $resultpage .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
        $resultpage .= "<tr>";
            $resultpage .= "<th width='15%'; style='border: 1px solid gray; color:white;'>ID</th>";
            $resultpage .= "<th width='40%'; style='border: 1px solid gray; color:white;'>Destination</th>";
            $resultpage .= "<th width='25%'; style='border: 1px solid gray; color:white;'>Prefix</th>";
            $resultpage .= "<th width='20%'; style='border: 1px solid gray; color:white;'>Rate(USD)</th>";
        $resultpage .= "</tr>";
        $resultpage .= "</thead>";

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

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $rate = $row['Rate'];
        $rateIncr = $rate + ($rate * 0.20); //20% increase of the provider rate.
        $resultpage .= "<tbody>";
        $resultpage .= "<tr>";
            $resultpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $row['id'] . "</td>";
            $resultpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $row['Destination'] . "</td>";
            $resultpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $row['Prefix'] . "</td>";
            $resultpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $rateIncr . "</td>";
        $resultpage .= "</tr>";
        $resultpage .= "</tbody>";
        }
        $resultpage .= "</table>";
    $resultpage .= '</div>';
}
echo $paginationCtrls;
echo '<br />';
echo $resultpage;
echo '<br />';
?>
