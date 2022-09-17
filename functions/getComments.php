<?php
//usleep(500000);
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if(isset($_POST['row'])){
    $row = preg_replace('#[^0-9]#i', '', $_POST['row']);
    $postid = preg_replace('#[^0-9]#i', '', $_POST['postid']);
    $posttag = preg_replace('#[^a-z]#i', '', $_POST['posttag']);
    $increment = preg_replace('#[^0-9]#i', '', $_POST['increment']);
}else{
    exit();
}

$rowperpage = 5;

// Count the total number of comments
$sql_statement = "SELECT comment.id, user_comments, commenter, comment_date, users.username, users.fullname, users.alias, users.avatar, useroptions.aliascheck FROM comment INNER JOIN users ON comment.commenter = users.username INNER JOIN useroptions ON comment.commenter = useroptions.username WHERE comment.post_id=:postid AND comment.post_tag=:posttag ORDER BY comment.id DESC";

$stmt = $db_connect->prepare($sql_statement);
$stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
$stmt->bindParam(':posttag', $posttag, PDO::PARAM_STR);
$stmt->execute();

$numrows = $stmt->rowCount();
// Check if it's the last page
$last = ceil($numrows/$rowperpage);

$limit = 'LIMIT ' . $row .',' .$rowperpage;
$sql = "$sql_statement"." $limit";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
$stmt->bindParam(':posttag', $posttag, PDO::PARAM_STR);
$stmt->execute();

//$numrows = $stmt->rowCount();


foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $postdate = $row['comment_date'];
    $comment_id = $row['id'];
    echo '<div class="commentDisplay">';
        echo '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['commenter'].'">'.'<img id="commentersid" src="user/'.$row['username'].'/'.$row['avatar'].'" alt="'.$row['username'].'">';
        if($row['aliascheck'] == '1') {
        echo '<div id="commentName">'.$row['fullname'].'</div>'.'</a>';
        } else {
            echo '<div id="commentName">'.$row['alias'].'</div>'.'</a>';
        }
        echo '<div id="commentDate" data-livestamp="'.$postdate.'">'.'</div>';
        if($row['commenter'] == $log_username) {
            echo '<span id="deleteCmt">'.'<input id='.$comment_id.' class="deleteComt" type="button" title="Delete" value="x" onclick="deleteComment(this.id);"/>'.'</span>';
        }
    echo '</div>';
    echo '<div id="commentWords">'.'<div style="margin-bottom:15px; margin-left:56px;">'.$row['user_comments'].'</div>';
    echo '</div>';
    echo '<br />';
}
if($increment < $last) {
    echo '<div class="load-comment" id="loadcmt" style="text-align:center; width:90px; margin:0 auto;">Load More</div>';
    echo '<input type="hidden" id="row" value="0">';
    echo '<input type="hidden" id="all" value="'.$numrows.'">';
    echo '<input type="hidden" id="postid" value="'.$postid.'">';
    echo '<input type="hidden" id="posttag" value="'.$posttag.'">';
    echo '<input type="hidden" id="incr" value="">';
  }
  echo '<p>';
?>
