<?php
//usleep(500000);
include("./php_includes/mysqli_connect.php");

$queryResult = '';
$commentView = '';
$sql = "SELECT id, username, ticketnumber, queryoption, detail, date FROM queries WHERE username=:username AND id=:id LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':username', $u, PDO::PARAM_STR);
$stmt->bindParam(':id', $ticketid, PDO::PARAM_INT);
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
    $queryResult .= '<div id="childdiv1" text-align:center;>'.'<div style="margin-right:5px; margin-left:5px; background-color:white;">'.nl2br($querydetail).'</div>'.'</div>'.'<br />';

    $commentView .= '<div>';
    $commentView .= '<div style="height:90px; margin-bottom:-35px;">';
    $commentView .= '<textarea rows="2" name="tcomments" id="tcomments" onfocus="emptyElement("status")" placeholder="Write your comment here." style="display:inline-block; float:right;"></textarea>'.'</div>';
    $commentView .= '<div style="margin-top:-15px;">'.'<input id="'.$id.'" class="tcommentClass" type="button" value="COMMENT" name="commentPost" onclick="ticketComment(this.id); commentCnt(this);" style="float:right;  font-weight:800;"/>'.'</div>';
    $commentView .= '</div>';
    $commentView .= '<br />';
    $commentView .= '<br />';
    $commentView .= '<div id="tCommentShow"></div>';
}
echo $queryResult;
echo '<br>';
echo $commentView;
?>
