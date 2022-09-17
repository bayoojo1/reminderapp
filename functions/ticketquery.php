<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if(isset($_POST['tcomments']) && !empty($_POST['tcomments'])){
    $comment = preg_replace('#[^a-z0-9.@,?!_ ;\']#i', '', $_POST['tcomments']);
    $commentid = preg_replace('#[^0-9]#i', '', $_POST['id']);
}else{
    exit();
}
// Get the ticket owner
$sql = "SELECT username, ticketnumber FROM queries WHERE id=:id LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $commentid, PDO::PARAM_INT);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $ticketowner = $row['username'];
  $ticketnumber = $row['ticketnumber'];
}

// Insert variables into the DB tickets table
$stmt = $db_connect->prepare("INSERT INTO tickets (ticket_id, ticket_detail, ticket_owner, commenter, ticket_date) VALUES(:ticket_id, :ticket_detail, :ticket_owner, :commenter, now())");
$stmt->execute(array(':ticket_id' => $commentid, ':ticket_detail' => $comment, ':ticket_owner' => $ticketowner, ':commenter' => $log_username));

$commentResponse = "";
$action = "ticket_comment";
$detail = "comment on tickets";
// Return the latest update to the ajax
$sql = "SELECT * FROM tickets WHERE ticket_id=:ticket_id AND ticket_detail=:ticket_detail AND ticket_owner=:ticketowner ORDER BY id DESC LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':ticket_id', $commentid, PDO::PARAM_INT);
$stmt->bindParam(':ticket_detail', $comment, PDO::PARAM_STR);
$stmt->bindParam(':ticketowner', $ticketowner, PDO::PARAM_STR);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $commentResponse .= '<div class="commentDisplay">';
        $commentResponse .= '<div id="commentName">'.$row['ticket_owner'].'</div>'.'</a>';
        $commentResponse .= '<span id="commentDate" data-livestamp="'.$row['ticket_date'].'">'.'</span>';
        if($row['commenter'] == $log_username) {
            $commentResponse .= '<span id="deleteCmt">'.'<input id='.$row['ticket_id'].' class="deleteComt" type="button" title="Delete" value="x" onclick="deleteComment(this.id);"/>'.'</span>';
        }
        $commentResponse .= '</div>';
        $commentResponse .= '<div id="commentWords">'.'<div style="margin-bottom:15px; margin-left:15px;">'.$row['ticket_detail'].'</div>';
        $commentResponse .= '</div>';
        $commentResponse .= '<br />';
}
echo $commentResponse;
// Insert variables into the notifications table
$stmt = $db_connect->prepare("INSERT INTO notifications (initiator, target, action, post_id, detail, postdate)
VALUES(:initiator, :target, :action, :post_id, :detail, now())");
$stmt->execute(array(':initiator' => $log_username, ':target' => $ticketowner, ':action' => $action, ':post_id' => $commentid, ':detail' => $detail));
?>
