<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}
?><?php
$queryResult = '';
$sql = "SELECT queries.id, username, ticketnumber, queryoption, detail, date, tickets.ticket_date, tickets.ticket_id FROM queries LEFT JOIN tickets ON queries.id = tickets.ticket_id WHERE username=:username GROUP BY queries.id ORDER BY COALESCE(GREATEST(date, MAX(tickets.ticket_date)), date) DESC";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $log_username, PDO::PARAM_STR);
$stmt->execute();
?><?php
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $id = $row['id'];
    $username = $row['username'];
    $ticketnumber = $row['ticketnumber'];
    $queryoption = $row['queryoption'];
    $querydetail = $row['detail'];
    $date = $row['date'];

    // Count the number of comment per ticket
    $commentCounts = '0';
    $sql_comment = "SELECT id FROM tickets WHERE ticket_id=:ticket_id";
    $stmt = $db_connect->prepare($sql_comment);
    $stmt->bindParam(':ticket_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $commentCounts = $stmt->rowCount();

$queryResult .= '<div style="background-color:#00ced1;">';
$queryResult .= '<a href="http://localhost:8080/reminderapp/ticketpage.php?u='.$row['username'].'&id='.$row['id'].'">'.'<img src="./images/chat.png" id="'.$row['id'].'" alt="comment" title="Comment" style="float:right; height:20px; width:20px;">'.'<span style="color:white; font-size:10px; font-family:Arial, Helvetica, sans-serif; float:right;">'.$commentCounts.'</span>'.'</a>';
$queryResult .= '<span style="background-color:#B0E0E6; color:#00ced1; font-weight:bold;">'.$username.'</span>';
$queryResult .= '<br>';
$queryResult .= '<span style="color:#F0FFFF; font-weight:bold;">Ticket Number:</span> '.'<span>'.'<pre style="display:inline;">' .$ticketnumber.'</pre>'.'</span>';
$queryResult .= '<br>';
$queryResult .= '<span style="color:#F0FFFF; font-weight:bold;">Question:</span> '.'<span>'.'<pre style="display:inline;">' .$queryoption.'</pre>'.'</span>';
$queryResult .= '<span data-livestamp="'.$date.'" style="color:white; font-size:10px; font-family:Arial, Helvetica, sans-serif; font-weight:bold; float:right; margin-top:6px;">'.'</span>';
$queryResult .= '</div>';
$queryResult .= '<div id="childdiv1" text-align:center;>'.'<div style="margin-right:5px; margin-left:5px; background-color:white;">'.nl2br($querydetail).'</div>'.'</div>'.'<br />';
}
echo $queryResult;

?>
