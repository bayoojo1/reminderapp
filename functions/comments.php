<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");


$action = 'comment';
if(isset($_POST['comments']) && !empty($_POST['comments'])){
    $comment = preg_replace('#[^a-z0-9.@,?!_ ;\']#i', '', $_POST['comments']);
    $postid = preg_replace('#[^0-9]#i', '', $_POST['post_id']);
    $posttag = lcfirst(preg_replace('#[^a-z]#i', '', $_POST['post_tag']));
}else{
    exit();
}

// Insert variables into the DB comment table
$stmt = $db_connect->prepare("INSERT INTO comment (post_id, post_tag, user_comments, commenter, comment_date) VALUES(:postid, :posttag, :user_comments, :commenter, now())");
$stmt->execute(array(':postid' => $postid, ':posttag' => $posttag, ':user_comments' => $comment, ':commenter' => $log_username));
// Insert variables into notifications table
$stmt = $db_connect->prepare("INSERT INTO notifications (initiator, action, post_id, post_tag, detail, postdate)
VALUES(:initiator, :action, :post_id, :post_tag, :detail, now())");
$stmt->execute(array(':initiator' => $log_username, ':action' => $action, ':post_id' => $postid, ':post_tag' => $posttag, ':detail' => $comment));

$commentResponse = "";
// Return the latest update to the ajax
$sql = "SELECT comment.id, user_comments, commenter, comment_date, users.username, users.fullname, users.alias, users.avatar, useroptions.aliascheck FROM comment INNER JOIN users ON comment.commenter = users.username INNER JOIN useroptions ON comment.commenter = useroptions.username WHERE user_comments=:comment AND comment.post_id=:postid AND comment.post_tag=:posttag LIMIT 1";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
$stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
$stmt->bindParam(':posttag', $posttag, PDO::PARAM_STR);
$stmt->execute();

foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $postdate = $row['comment_date'];
    $comment_id = $row['id'];
    //$b = date_create($postdate);
    //$readabledate = date_format($b, 'g:ia \o\n l jS F Y');
    $commentResponse .= '<div class="commentDisplay">';
    $commentResponse .= '<a href="http://localhost:8080/reminderapp/user_audio.php?u='.$row['commenter'].'">'.'<img id="commentersid" src="user/'.$row['username'].'/'.$row['avatar'].'" alt="'.$row['username'].'">';
        if($row['aliascheck'] == '1') {
            $commentResponse .= '<div id="commentName">'.$row['fullname'].'</div>'.'</a>';
        } else {
            $commentResponse .= '<div id="commentName">'.$row['alias'].'</div>'.'</a>';
        }
        $commentResponse .= '<span id="commentDate" data-livestamp="'.$postdate.'">'.'</span>';
        if($row['commenter'] == $log_username) {
            $commentResponse .= '<span id="deleteCmt">'.'<input id='.$comment_id.' class="deleteComt" type="button" title="Delete" value="x" onclick="deleteComment(this.id);"/>'.'</span>';
        }
        $commentResponse .= '</div>';
        $commentResponse .= '<div id="commentWords">'.'<div style="margin-bottom:15px; margin-left:15px;">'.$row['user_comments'].'</div>';
        $commentResponse .= '</div>';
        //$commentResponse .= '<div id="cmtreply_'.$comment_id.'" style="float:right; color:gray; margin-top:-18px; cursor:pointer; margin-right:10px;" onclick="showCmt(this)">'.'Reply'.'</div>';
        //$commentResponse .= '<div id="cmtRsp_'.$comment_id.'" style="display:none;">'.'Response to this.'.'</div>';
        $commentResponse .= '<br />';
}
echo $commentResponse;
?>
