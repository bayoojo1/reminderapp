<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}
$naatcastType = '';
?><?php
if(isset($_POST['queryoption'])) {
$queryoption = preg_replace('#[^a-z]#i', '', $_POST['queryoption']);
$querydetail = preg_replace('#[^a-z0-9:.,-?@!=+ \']#i', '', $_POST['querydetail']);
}
// Get the naatcast account for each queryoption
if($queryoption == 'Technical') {
  $naatcastType = 'naatcast.support';
} elseif($queryoption == 'Billing') {
  $naatcastType = 'naatcast.billing';
} elseif($queryoption == 'Report') {
  $naatcastType = 'naatcast.report';
} else {
  $naatcastType = 'naatcast.others';
}

//Gather other variables to be used
$string = rand(0, 100000000);
$ticketnumber = trim('TKN'.''.$string);

$stmt = $db_connect->prepare( "INSERT INTO queries (username, ticketnumber, queryoption, detail, date) VALUES(:username, :ticketnumber, :queryoption, :detail, now())");
$stmt->execute(array(':username' => $log_username, ':ticketnumber' => $ticketnumber, ':queryoption' => $queryoption, ':detail' => $querydetail));

usleep(500000);
$queryResult = '';
$action = 'query';
$detail = 'query from users';
//Return the below to the ajax call
$sql = "SELECT id, username, ticketnumber, queryoption, detail, date FROM queries WHERE username=:username ORDER BY id DESC LIMIT 1";
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
    $queryResult .= '<a href="http://localhost:8080/reminderapp/ticketpage.php?u='.$row['username'].'&id='.$row['id'].'">'.'<img src="./images/chat.png" alt="comment" title="Comment" style="float:right; height:20px; width:20px;">'.'<span style="color:white; font-size:10px; font-family:Arial, Helvetica, sans-serif; float:right;">'.$commentCounts.'</span>'.'</a>';
    $queryResult .= '<span style="background-color:#B0E0E6; color:#00ced1; font-weight:bold;">'.$username.'</span>';
    $queryResult .= '<br>';
    $queryResult .= '<span style="color:#F0FFFF; font-weight:bold;">Ticket Number:</span> '.'<span>'.'<pre style="display:inline;">' .$ticketnumber.'</pre>'.'</span>';
    $queryResult .= '<br>';
    $queryResult .= '<span style="color:#F0FFFF; font-weight:bold;">Question:</span> '.'<span>'.'<pre style="display:inline;">' .$queryoption.'</pre>'.'</span>';
    $queryResult .= '<span data-livestamp="'.$date.'" style="color:white; font-size:10px; font-family:Arial, Helvetica, sans-serif; font-weight:bold; float:right; margin-top:6px;">'.'</span>';
    $queryResult .= '</div>';
    $queryResult .= '<div id="childdiv1" text-align:center>'.'<div style="margin-right:5px; margin-left:5px; background-color:white;">'.nl2br($querydetail).'</div>'.'</div>'.'<br />';
}
echo $queryResult;
// Insert variables into the notifications table
$stmt = $db_connect->prepare("INSERT INTO notifications (initiator, target, action, post_id, detail, postdate)
VALUES(:initiator, :target, :action, :post_id, :detail, now())");
$stmt->execute(array(':initiator' => $log_username, ':target' => $naatcastType, ':action' => $action, ':post_id' => $id, ':detail' => $detail));
?>
