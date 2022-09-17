<?php
include("./php_includes/mysqli_connect.php");

$sql_statement = "SELECT content_provider.id, provider, start_date, approved, content_type, description, content_sample, identification, users.avatar, users.firstname, users.lastname, users.fullname, users.alias, useroptions.aliascheck FROM content_provider INNER JOIN users ON content_provider.provider=users.username INNER JOIN useroptions ON content_provider.provider=useroptions.username ORDER BY id ASC";
$stmt = $db_connect->prepare($sql_statement);
$stmt->execute();
$numrows = $stmt->rowCount();

if($numrows < 1) {
    echo '<div id="followerList" style="height:60px; text-align:center; vertical-align: middle; font-size:20px; color:white;">';
    echo "No provider registration yet.";
    echo '</div>';
} else {

// Specify how many result per page
$rpp = '10';
// This tells us the page number of the last page
$last = ceil($numrows/$rpp);
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
$stmt = $db_connect->prepare($sql);
$stmt->execute();

// Establish the $paginationCtrls variable
$paginationCtrls = '';

if($numrows > 0) {
    $paginationCtrls .= '<div id="paginationctrls">';
    if($last != 1){
        /* First we check if we are on page one. If we are then we don't need a link to 
           the previous page or the first page so we do nothing. If we aren't then we
           generate links to the first page, and to the previous page. */
        if ($pn > 1) {
            $previous = $pn - 1;
            $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
            // Render clickable number links that should appear on the left of the target page number
            for($i = $pn-4; $i < $pn; $i++){
                if($i > 0){
                    $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
                }
            }
        }
        // Render the target page number, but without it being a link
        $paginationCtrls .= ''.$pn.' &nbsp; ';
        // Render clickable number links that should appear on the right of the target page number
        for($i = $pn+1; $i <= $last; $i++){
            $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
            if($i >= $pn+4){
                break;
            }
        }
        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
        if ($pn != $last) {
            $next = $pn + 1;
            $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'">Next</a> ';
    }
}
$paginationCtrls .= '</div>';

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $id = $row['id'];
    $provider = $row['provider'];
    $content_type = $row['content_type'];
    $fullname = $row['fullname'];
    $approval = $row['approved'];

    $subscription_List = '';
    $subscription_List .= '<div id="subscriptionList">';
    $subscription_List .= '<a class="image" href="http://localhost:8080/reminderapp/user_audio.php?u='.$provider.'"><img src="user/'.$provider.'/'.$row['avatar'].'" alt="'.$provider.'">'.'<br />';
    if($row['aliascheck'] == '1') {
        $subscription_List .= '<div id="fuserName">'.$row["fullname"].'</a>'.'</div>';
    } else if($row['aliascheck'] == '0') {
        $subscription_List .= '<div id="fuserName">'.$row["alias"].'</a>'.'</div>';
    }
    $subscription_List .= '<audio controls controlsList="nodownload">';
        $subscription_List .= '<source src='.$row['content_sample'].' type="audio/mpeg">';
        $subscription_List .= '<source src='.$row['content_sample'].' type="audio/wav">';
        $subscription_List .= '<source src='.$row['content_sample'].' type="audio/ogg">';
        $subscription_List .= 'Your browser does not support the audio element.';
    $subscription_List .= '</audio>';
    $subscription_List .= '<span style="font-weight:800; color:#004080;">'.$row['content_type'].'</span>';
    $subscription_List .= '<div id="childdiv1" text-align:center>'.$row['description'].'</div>';
    $subscription_List .= '<br />';
    if($approval == '1') {
    $subscription_List .= '<span style="font-weight:800; color:white;">Approval: </span>'.'<input id='.$row['id'].' type="checkbox" class="approve" onclick="approved(this.id)" checked>';
    } else {
        $subscription_List .= '<span style="font-weight:800; color:white;">Approval: </span>'.'<input id='.$row['id'].' type="checkbox" class="approve" onchange="approved(this.id)">';
    }
    $subscription_List .= '<input id='.$row['id'].' class="remove" type="button" title="Delete" value="x" onclick="deleteProvider(this.id);" />';
    $subscription_List .= '<span id="document">'.'<a href="'.$row['identification'].'" target="_blank"><img src="./images/document.png" style="height:40px; width:30px; float:right;" alt="document" title="Attachment">'.'</a>'.'</span>';
    $subscription_List .= '</div><br /><br />';

    echo $paginationCtrls;
    echo $subscription_List;
        }
    }
}

?>