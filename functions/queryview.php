<?php
include("./php_includes/mysqli_connect.php");
// If the page requestor is not logged in, usher them away
if($u == $naatcast) {
 $sql = "SELECT queries.id, username, ticketnumber, queryoption, detail, date, tickets.ticket_date, tickets.ticket_id FROM queries LEFT JOIN tickets ON queries.id = tickets.ticket_id GROUP BY queries.id ORDER BY COALESCE(GREATEST(date, MAX(tickets.ticket_date)), date) DESC";
 $stmt = $db_connect->prepare($sql);
 $stmt->execute();

 $queryResult = '';
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
     $queryResult .= '<div id="childdiv1">'.'<div style="background-color:#bdbdbd;">'.nl2br($querydetail).'</div>'.'</div>'.'<br />';
 }
 echo $queryResult;
} elseif($u == $naatcastbilling) {
  $billing = 'Billing';
  $sql = "SELECT queries.id, username, ticketnumber, queryoption, detail, date, tickets.ticket_date, tickets.ticket_id FROM queries LEFT JOIN tickets ON queries.id = tickets.ticket_id WHERE queryoption=:queryoption GROUP BY queries.id ORDER BY COALESCE(GREATEST(date, MAX(tickets.ticket_date)), date) DESC";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':queryoption', $billing, PDO::PARAM_STR);
  $stmt->execute();

  $queryResult = '';
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
      $queryResult .= '<div id="childdiv1">'.'<div style="background-color:#bdbdbd;">'.nl2br($querydetail).'</div>'.'</div>'.'<br />';
  }
  echo $queryResult;
} elseif ($u == $naatcastsupport) {
  $support = 'Technical';
  $sql = "SELECT queries.id, username, ticketnumber, queryoption, detail, date, tickets.ticket_date, tickets.ticket_id FROM queries LEFT JOIN tickets ON queries.id = tickets.ticket_id WHERE queryoption=:queryoption GROUP BY queries.id ORDER BY COALESCE(GREATEST(date, MAX(tickets.ticket_date)), date) DESC";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':queryoption', $support, PDO::PARAM_STR);
  $stmt->execute();

  $queryResult = '';
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
      $queryResult .= '<div id="childdiv1">'.'<div style="background-color:#bdbdbd;">'.nl2br($querydetail).'</div>'.'</div>'.'<br />';
  }
  echo $queryResult;
} elseif ($u == $naatcastreport) {
  $report = 'Report';
  $sql = "SELECT queries.id, username, ticketnumber, queryoption, detail, date, tickets.ticket_date, tickets.ticket_id FROM queries LEFT JOIN tickets ON queries.id = tickets.ticket_id WHERE queryoption=:queryoption GROUP BY queries.id ORDER BY COALESCE(GREATEST(date, MAX(tickets.ticket_date)), date) DESC";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':queryoption', $report, PDO::PARAM_STR);
  $stmt->execute();

  $queryResult = '';
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
      $queryResult .= '<div id="childdiv1">'.'<div style="background-color:#bdbdbd;">'.nl2br($querydetail).'</div>'.'</div>'.'<br />';
  }
  echo $queryResult;
} elseif ($u == $naatcastothers) {
  $others = 'Others';
  $sql = "SELECT queries.id, username, ticketnumber, queryoption, detail, date, tickets.ticket_date, tickets.ticket_id FROM queries LEFT JOIN tickets ON queries.id = tickets.ticket_id WHERE queryoption=:queryoption GROUP BY queries.id ORDER BY COALESCE(GREATEST(date, MAX(tickets.ticket_date)), date) DESC";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':queryoption', $others, PDO::PARAM_STR);
  $stmt->execute();

  $queryResult = '';
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
      $queryResult .= '<div id="childdiv1">'.'<div style="background-color:#bdbdbd;">'.nl2br($querydetail).'</div>'.'</div>'.'<br />';
  }
  echo $queryResult;
}
?>
