<?php
include("./php_includes/mysqli_connect.php");
$userspage = '';
$paginationCtrls = '';

$sql_trans = "SELECT firstname, lastname, isdn, username FROM users WHERE activated=:activated";
$stmt = $db_connect->prepare($sql_trans);
$stmt->bindValue(':activated', '1', PDO::PARAM_STR);
$stmt->execute();
$users_count = $stmt->rowCount();

// Specify how many result per page
$rpp = '20';
// This tells us the page number of the last page
$last = ceil($users_count/$rpp);
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
$stmt->bindValue(':activated', '1', PDO::PARAM_STR);
$stmt->execute();

if($users_count > 0){
    $userspage .= '<div id="user_search" style="overflow-x:auto;">';
        $userspage .= "<table style='width:100%; border: 1px solid gray;'>";
        $userspage .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
        $userspage .= "<tr>";
            $userspage .= "<th width='20%'; style='border: 1px solid gray; color:white;'>First Name</th>";
            $userspage .= "<th width='20%'; style='border: 1px solid gray; color:white;'>Last Name</th>";
            $userspage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Mobile</th>";
            $userspage .= "<th width='20%'; style='border: 1px solid gray; color:white'>Username</th>";
            $userspage .= "<th width='17.5%'; style='border: 1px solid gray; color:white;'>Action</th>";
        $userspage .= "</tr>";
        $userspage .= "</thead>";

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
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $mobile = $row['isdn'];
    $username = $row['username'];

    $userspage .= "<tbody>";
    $userspage .= "<tr>";
        $userspage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $firstname . "</td>";
        $userspage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $lastname . "</td>";
        $userspage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $mobile . "</td>";
        $userspage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $username . "</td>";
        $userspage .= "<td style='text-align:center; border: 1px solid gray; color:white; cursor:pointer;'>"."<a href='http://localhost:8080/reminderapp/user_edit.php?u=".$username."'>"."Edit"."</a>"."&nbsp"."&nbsp"."&nbsp".
        "<a href='http://localhost:8080/reminderapp/user_view.php?u=".$username."'>"."View"."</a>".
        "</td>";
    $userspage .= "</tr>";
    $userspage .= "</tbody>";
  }
  $userspage .= "</table>";
$userspage .= '</div>';
} else {
    $userspage .= '<div id="user_search" style="overflow-x:auto;">';
            $userspage .= "<table style='width:100%; border: 1px solid gray;'>";
            $userspage .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
            $userspage .= "<tr>";
                $userspage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>First Name</th>";
                $userspage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Last Name ID</th>";
                $userspage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Mobile</th>";
                $userspage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Username</th>";
                $userspage .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Edit</th>";
            $userspage .= "</tr>";
            $userspage .= "</thead>";
            $userspage .= "</table>";
            $userspage .= "</div>";
    $userspage .= 'No record found!';
}
echo '<br>';
echo $paginationCtrls;
echo '<br />';
echo $userspage;
?>
