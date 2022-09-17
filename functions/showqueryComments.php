<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if(isset($_POST['ticket_id']) && isset($_POST['ticket_username'])){
    $commentuser = preg_replace('#[^a-z0-9.]#i', '', $_POST['ticket_username']);
    $commentid = preg_replace('#[^0-9]#i', '', $_POST['ticket_id']);
}else{
    exit();
}

// Get the queryoption from queries table
$sql ="SELECT queryoption FROM queries WHERE id=:id";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':id', $commentid, PDO::PARAM_INT);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $queryoption = $row['queryoption'];
}
if($log_username == 'naatcast.support') {
// Return the latest update to the ajax
$sql = "SELECT tickets.ticket_id, ticket_detail, ticket_owner, ticket_date, commenter, queries.queryoption FROM tickets INNER JOIN queries ON queries.id=tickets.ticket_id WHERE ticket_id=:ticket_id AND queryoption=:queryoption ORDER BY tickets.id DESC";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':ticket_id', $commentid, PDO::PARAM_INT);
$stmt->bindParam(':queryoption', $queryoption, PDO::PARAM_STR);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo '<div class="commentDisplay">';
        echo '<div id="commentName">'.$row['commenter'].'</div>'.'</a>';
        echo '<span id="commentDate" data-livestamp="'.$row['ticket_date'].'">'.'</span>';
        if($row['commenter'] == $log_username) {
            echo '<span id="deleteCmt">'.'<input id='.$row['ticket_id'].' class="deleteComt" type="button" title="Delete" value="x" onclick="deleteticket(this.id);"/>'.'</span>';
        }
        echo '</div>';
        echo '<div id="commentWords">'.'<div style="margin-bottom:15px; margin-left:15px;">'.$row['ticket_detail'].'</div>';
        echo '</div>';
        echo '<br />';
}
} elseif ($log_username == 'naatcast.billing') {
  // Return the latest update to the ajax
  $sql = "SELECT tickets.ticket_id, ticket_detail, ticket_owner, ticket_date, commenter, queries.queryoption FROM tickets INNER JOIN queries ON queries.id=tickets.ticket_id WHERE ticket_id=:ticket_id AND queryoption=:queryoption ORDER BY tickets.id DESC";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':ticket_id', $commentid, PDO::PARAM_INT);
  $stmt->bindParam(':queryoption', $queryoption, PDO::PARAM_STR);
  $stmt->execute();

  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
      echo '<div class="commentDisplay">';
          echo '<div id="commentName">'.$row['commenter'].'</div>'.'</a>';
          echo '<span id="commentDate" data-livestamp="'.$row['ticket_date'].'">'.'</span>';
          if($row['commenter'] == $log_username) {
              echo '<span id="deleteCmt">'.'<input id='.$row['ticket_id'].' class="deleteComt" type="button" title="Delete" value="x" onclick="deleteticket(this.id);"/>'.'</span>';
          }
          echo '</div>';
          echo '<div id="commentWords">'.'<div style="margin-bottom:15px; margin-left:15px;">'.$row['ticket_detail'].'</div>';
          echo '</div>';
          echo '<br />';
  }
} elseif ($log_username == 'naatcast.report') {
  // Return the latest update to the ajax
  $sql = "SELECT tickets.ticket_id, ticket_detail, ticket_owner, ticket_date, commenter, queries.queryoption FROM tickets INNER JOIN queries ON queries.id=tickets.ticket_id WHERE ticket_id=:ticket_id AND queryoption=:queryoption ORDER BY tickets.id DESC";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':ticket_id', $commentid, PDO::PARAM_INT);
  $stmt->bindParam(':queryoption', $queryoption, PDO::PARAM_STR);
  $stmt->execute();

  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
      echo '<div class="commentDisplay">';
          echo '<div id="commentName">'.$row['commenter'].'</div>'.'</a>';
          echo '<span id="commentDate" data-livestamp="'.$row['ticket_date'].'">'.'</span>';
          if($row['commenter'] == $log_username) {
              echo '<span id="deleteCmt">'.'<input id='.$row['ticket_id'].' class="deleteComt" type="button" title="Delete" value="x" onclick="deleteticket(this.id);"/>'.'</span>';
          }
          echo '</div>';
          echo '<div id="commentWords">'.'<div style="margin-bottom:15px; margin-left:15px;">'.$row['ticket_detail'].'</div>';
          echo '</div>';
          echo '<br />';
  }
} elseif ($log_username == 'naatcast.others') {
  // Return the latest update to the ajax
  $sql = "SELECT tickets.ticket_id, ticket_detail, ticket_owner, ticket_date, commenter, queries.queryoption FROM tickets INNER JOIN queries ON queries.id=tickets.ticket_id WHERE ticket_id=:ticket_id AND queryoption=:queryoption ORDER BY tickets.id DESC";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':ticket_id', $commentid, PDO::PARAM_INT);
  $stmt->bindParam(':queryoption', $queryoption, PDO::PARAM_STR);
  $stmt->execute();

  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
      echo '<div class="commentDisplay">';
          echo '<div id="commentName">'.$row['commenter'].'</div>'.'</a>';
          echo '<span id="commentDate" data-livestamp="'.$row['ticket_date'].'">'.'</span>';
          if($row['commenter'] == $log_username) {
              echo '<span id="deleteCmt">'.'<input id='.$row['ticket_id'].' class="deleteComt" type="button" title="Delete" value="x" onclick="deleteticket(this.id);"/>'.'</span>';
          }
          echo '</div>';
          echo '<div id="commentWords">'.'<div style="margin-bottom:15px; margin-left:15px;">'.$row['ticket_detail'].'</div>';
          echo '</div>';
          echo '<br />';
  }
} else {
  // Return the latest update to the ajax
  $sql = "SELECT tickets.ticket_id, ticket_detail, ticket_owner, ticket_date, commenter, queries.queryoption FROM tickets INNER JOIN queries ON queries.id=tickets.ticket_id WHERE ticket_id=:ticket_id AND queryoption=:queryoption ORDER BY tickets.id DESC";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':ticket_id', $commentid, PDO::PARAM_INT);
  $stmt->bindParam(':queryoption', $queryoption, PDO::PARAM_STR);
  $stmt->execute();

  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
      echo '<div class="commentDisplay">';
          echo '<div id="commentName">'.$row['commenter'].'</div>'.'</a>';
          echo '<span id="commentDate" data-livestamp="'.$row['ticket_date'].'">'.'</span>';
          if($row['commenter'] == $log_username) {
              echo '<span id="deleteCmt">'.'<input id='.$row['ticket_id'].' class="deleteComt" type="button" title="Delete" value="x" onclick="deleteticket(this.id);"/>'.'</span>';
          }
          echo '</div>';
          echo '<div id="commentWords">'.'<div style="margin-bottom:15px; margin-left:15px;">'.$row['ticket_detail'].'</div>';
          echo '</div>';
          echo '<br />';
  }
}
?>
