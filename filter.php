<?php
include("php_includes/check_login_status.php");
include("php_includes/a2billing_db.php");
include("php_includes/mysqli_connect.php");
include("theCollector.php");

if(isset($_SESSION['username'])) {
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_SESSION['username']);;
} else {
    exit();
}

// Scoop some variables from the user table
$sql = "SELECT countrycode, mobile FROM users WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $c = $row['countrycode'];
    $m = $row['mobile'];
}

// Scoop out credit balance from the a2billing database
$sql = "SELECT credit FROM cc_card WHERE phone=:phone";
$stmt = $a2billing_db->prepare($sql);
$stmt->bindParam(':phone', $m, PDO::PARAM_STR);
$stmt->execute();
foreach($stmt->fetchAll() as $row) {
    $credit = $row["credit"];
}

$m_number = trim($c).trim(substr($m, 1));

if(isset($_GET['from'])) {
    $from =  preg_replace('#[^0-9-]#', '', $_GET['from']);
    $to = preg_replace('#[^0-9-]#', '', $_GET['to']);
    $call_status = preg_replace('#[^a-z]#i', '', $_GET['status']);
}

$tab = '';
$balance = '';
$output = '';
$search = '';
$paginationCtrls = '';
$terminatecauseid = '';

$tab .= '<div class="dropdown">';
        $tab .= '<div class="dropbtn" style="background-color:white;">'.'<a href="http://localhost:8080/reminderapp/callrecords_page.php?u='.$u.'">'.'<b>'.'Call Record'.'</b>'.'</a>'.' <i class="fa fa-caret-down" style="color:blue;"></i>'.'</div>';
        $tab .= '<div class="dropdown-content">';
        $tab .= '<div>'.'<a href="http://localhost:8080/reminderapp/billing_page.php?u='.$u.'">'.'Payment History'.'</a>'.'</div>';
        $tab .= '<div>'.'<a href="http://localhost:8080/reminderapp/profile_page.php?u='.$u.'">'.'My Profile'.'</a>'.'</div>';
        $tab .= '<div>'.'<a href="http://localhost:8080/reminderapp/apicreate_page.php?u='.$u.'">'.'Create API'.'</a>'.'</div>';
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

if($call_status == 'answered') {
                $terminatecauseid = '1';
            } else if($call_status == 'busy') {
                $terminatecauseid = '2';
            } else if($call_status == 'noanswer') {
                $terminatecauseid = '3';
            } else if($call_status == 'canceled') {
                $terminatecauseid = '4';
            } else if($call_status == 'congestion') {
                $terminatecauseid = '5';
            } else if($call_status == 'chanunavail') {
                $terminatecauseid = '6';
            } else if($call_status == 'dontcall') {
                $terminatecauseid = '7';
            } else if($call_status == 'torture') {
                $terminatecauseid = '8';
            } else {
                $terminatecauseid = '9';
            }

    $sql_search = "SELECT starttime, sessiontime, calledstation, sessionbill, terminatecauseid FROM cc_call
    WHERE starttime BETWEEN :start AND :end AND terminatecauseid=:terminate AND src=:src ORDER BY starttime DESC";

    $stmt = $a2billing_db->prepare($sql_search);
    $stmt->bindParam(':start', $from, PDO::PARAM_STR);
    $stmt->bindParam(':end', $to, PDO::PARAM_STR);
    $stmt->bindParam(':terminate', $terminatecauseid, PDO::PARAM_STR);
    $stmt->bindParam(':src', $m_number, PDO::PARAM_STR);
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
    $sql = "$sql_search"." $limit";
    $stmt = $a2billing_db->prepare($sql);
    $stmt->bindParam(':start', $from, PDO::PARAM_STR);
    $stmt->bindParam(':end', $to, PDO::PARAM_STR);
    $stmt->bindParam(':terminate', $terminatecauseid, PDO::PARAM_STR);
    $stmt->bindParam(':src', $m_number, PDO::PARAM_STR);
    $stmt->execute();

    if($count > 0){
        $output .= '<div id="cdr_search" style="overflow-x:auto;">';
            $output .= "<table style='width:100%; border: 1px solid gray;'>";
            $output .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
            $output .= "<tr>";
                $output .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Date/Time</th>";
                $output .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Duration(Sec)</th>";
                $output .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Recepient</th>";
                $output .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Amount(USD)</th>";
                $output .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Status</th>";
            $output .= "</tr>";
            $output .= "</thead>";

            $paginationCtrls .= '<div id="paginationctrls">'; //Use to be $paginationCtrls
            if($last != 1){
                /* First we check if we are on page one. If we are then we don't need a link to
                   the previous page or the first page so we do nothing. If we aren't then we
                   generate links to the first page, and to the previous page. */
                if ($pn > 1) {
                    $previous = $pn - 1;
                    $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&from='.$from.'&to='.$to.'&status='.$call_status.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
                    // Render clickable number links that should appear on the left of the target page number
                    for($i = $pn-4; $i < $pn; $i++){
                        if($i > 0){
                            $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&from='.$from.'&to='.$to.'&status='.$call_status.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
                        }
                    }
                }
                // Render the target page number, but without it being a link
                $paginationCtrls .= ''.$pn.' &nbsp; ';
                // Render clickable number links that should appear on the right of the target page number
                for($i = $pn+1; $i <= $last; $i++){
                    $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&from='.$from.'&to='.$to.'&status='.$call_status.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
                    if($i >= $pn+4){
                        break;
                    }
                }
                // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                if ($pn != $last) {
                    $next = $pn + 1;
                    $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?u='.$log_username.'&from='.$from.'&to='.$to.'&status='.$call_status.'&pn='.$next.'">Next</a> ';
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
            $output .= "<tbody>";
            $output .= "<tr>";
                $output .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $row['starttime'] . "</td>";
                $output .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $row['sessiontime'] . "</td>";
                $output .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $calledParty . "</td>";
                $output .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $cost . "</td>";
                $output .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $row['terminatecauseid'] . "</td>";
            $output .= "</tr>";
            $output .= "</tbody>";
            }
            $output .= "</table>";
        $output .= '</div>';
} else {
    $output .= '<div id="cdr_search" style="overflow-x:auto;">';
            $output .= "<table style='width:100%; border: 1px solid gray;'>";
            $output .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
            $output .= "<tr>";
                $output .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Date/Time</th>";
                $output .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Duration(Sec)</th>";
                $output .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Recepient</th>";
                $output .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Amount(USD)</th>";
                $output .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Status</th>";
            $output .= "</tr>";
            $output .= "</thead>";
            $output .= "</table>";
            $output .= "</div>";
    $output .= 'No call detail record found!';
}
include_once("functions/page_functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Call Record</title>
<link rel="stylesheet" href="style/normalize.css">
<link href="https://fonts.googleapis.com/css?family=Changa+One:400,400i|Open+Sans:400,400i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="js/header_scroll.js"></script>
<script src="js/main.js"></script>
<script src="js/functions.js"></script>
</head>
<body class="insidepage">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div><br /><br />
<div id="wrapper">
    <?php echo $pageleft; ?>

    <div id="PageMiddle"><br /><br />
        <?php echo $tab; ?>
        <?php echo $balance; ?><br />
        <?php echo $search; ?>
        <?php echo $paginationCtrls; ?><br />
        <?php echo $output; ?><br />
        <?php echo $paginationCtrls; ?><br />
    </div>

    <?php include_once("template_pageRight.php"); ?>

</div>
</body>
</html>
