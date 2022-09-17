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

$m_number = trim($countrycode).trim(substr($mobile, 1));

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

// Establish the $paginationCtrls and other variable
$paginationCtrls = '';
$resultpage = '';
$tab = '';
$balance = '';
$search = '';

$tab .= '<div class="dropdown" style="background-color:gainsboro;">';
        $tab .= '<div class="dropbtn">'.'<a href="http://localhost:8080/reminderapp/callrecords_page.php?u='.$u.'">'.'<b>'.'Call Record'.'</b>'.'</a>'.' <i class="fa fa-caret-down" style="color:blue;"></i>'.'</div>';
        $tab .= '<div class="dropdown-content">';
        $tab .= '<div>'.'<a href="http://localhost:8080/reminderapp/billing_page.php?u='.$u.'">'.'Payment History'.'</a>'.'</div>';
        $tab .= '<div>'.'<a href="http://localhost:8080/reminderapp/apicreate_page.php?u='.$u.'">'.'Create API'.'</a>'.'</div>';
        $tab .= '<div>'.'<a href="http://localhost:8080/reminderapp/profile_page.php?u='.$u.'">'.'My Profile'.'</a>'.'</div>';
        $tab .= '</div>';
$tab .= '</div>';
$tab .= '<br />'.'<br />';
if($credit <= 5) {
    $balance .= '<div id="billing" style="background-color:red; color:white; text-align:center;">';
    $balance .= '<b>'.'Available Balance: '.'</b>'. 'USD'.$credit;
    $balance .= '</div>';
} else {
$balance .= '<div id="billing" style="background-color:green; color:white; text-align:center;">';
$balance .= '<b>'.'Available Balance: '.'</b>'. 'USD'.$credit;
$balance .= '</div>';

}
$balance .= '<br />';

$search .= '<div id="call-search">';
    $search .= '<input type="text" name="from_date" id="from_date" placeholder="From date" style="width:100px; background-color:#F3F9DD;" />';
    $search .= '<input type="text" name="to_date" id="to_date" placeholder="To date" style="width:100px; background-color:#F3F9DD;" />';
    $search .= '<select id="call_status" name="call_status" style="width: 120px;"/>';
    $search .= '<option selected="selected">---SELECT---</option>';
        $search .= '<option value="answered">ANSWERED</option>';
        $search .= '<option value="busy">BUSY</option>';
        $search .= '<option value="noanswer">NO ANSWER</option>';
        $search .= '<option value="canceled">CANCELED</option>';
        $search .= '<option value="congestion">CONGESTION</option>';
        $search .= '<option value="chanunavail">CHANUNAVAIL</option>';
    $search .= '</select>';
    $search .= '<input style="height:30px;" type="button" name="filter" id="filter" value="Filter Record" />';
$search .= '</div>'.'<br />';

//echo '<br />';
// Scoop out call detail record from a2billing
$sql_statement = "SELECT starttime, sessiontime, calledstation, sessionbill, terminatecauseid FROM cc_call WHERE src=:mobile ORDER BY starttime DESC";
$stmt = $a2billing_db->prepare($sql_statement);
$stmt->bindParam(':mobile', $m_number, PDO::PARAM_STR);
$stmt->execute();
$count = $stmt->rowCount();

// Specify how many result per page
$rpp = '20';
// This tells us the page number of the last page
$last = ceil($count/$rpp);
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
$sql = "$sql_statement"." $limit";
$stmt = $a2billing_db->prepare($sql);
$stmt->bindParam(':mobile', $m_number, PDO::PARAM_STR);
$stmt->execute();


    if($count > 0){
        $resultpage .= '<div id="cdr_search" style="overflow-x:auto;">';
            $resultpage .= "<table style='width:100%; border: 1px solid gray;'>";
            $resultpage .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
            $resultpage .= "<tr>";
                $resultpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Date/Time</th>";
                $resultpage .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Duration(Sec)</th>";
                $resultpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Recepient</th>";
                $resultpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white'>Amount(USD)</th>";
                $resultpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Status</th>";
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
            $cost = trim(($row['sessionbill']), "-");
            $calledParty = trim($row['calledstation'], "011");

            if($row['terminatecauseid'] == 1) {
                $row['terminatecauseid'] = 'ANSWERED';
            } else if($row['terminatecauseid'] == 2) {
                $row['terminatecauseid'] = 'BUSY';
            } else if($row['terminatecauseid'] == 3) {
                $row['terminatecauseid'] = 'NOANSWER';
            } else if($row['terminatecauseid'] == 4) {
                $row['terminatecauseid'] = 'CANCELED';
            } else if($row['terminatecauseid'] == 5) {
                $row['terminatecauseid'] = 'CONGESTION';
            } else if($row['terminatecauseid'] == 6) {
                $row['terminatecauseid'] = 'CHANUNAVAIL';
            } else if($row['terminatecauseid'] == 7) {
                $row['terminatecauseid'] = 'DONTCALL';
            } else if($row['terminatecauseid'] == 8) {
                $row['terminatecauseid'] = 'TORTURE';
            } else {
                $row['terminatecauseid'] = 'INVALIDARGS';
            }
            $resultpage .= "<tbody>";
            $resultpage .= "<tr>";
                $resultpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $row['starttime'] . "</td>";
                $resultpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $row['sessiontime'] . "</td>";
                $resultpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $calledParty . "</td>";
                $resultpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $cost . "</td>";
                $resultpage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $row['terminatecauseid'] . "</td>";
            $resultpage .= "</tr>";
            $resultpage .= "</tbody>";
            }
            $resultpage .= "</table>";
        $resultpage .= '</div>';
} else {
    $resultpage .= '<div id="cdr_search" style="overflow-x:auto;">';
            $resultpage .= "<table style='width:100%; border: 1px solid gray;'>";
            $resultpage .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
            $resultpage .= "<tr>";
                $resultpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Date/Time</th>";
                $resultpage .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Duration(Sec)</th>";
                $resultpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Recepient</th>";
                $resultpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Amount(USD)</th>";
                $resultpage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Status</th>";
            $resultpage .= "</tr>";
            $resultpage .= "</thead>";
            $resultpage .= "</table>";
            $resultpage .= "</div>";
    $resultpage .= 'No call detail record found!';
}
echo $tab;
echo $balance;
echo $search;
//echo '<div id="searchreturn">';
echo $paginationCtrls;
echo '<br />';
echo $resultpage;
//echo '</div>';
?>
