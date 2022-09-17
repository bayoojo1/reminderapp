<?php 
usleep(500000);
include("../php_includes/mysqli_connect.php");

if(isset($_POST['post_id'])){
    $post_id = preg_replace('#[^0-9]#i', '', $_POST['post_id']);
    $post_tag = preg_replace('#[^a-z]#i', '', $_POST['post_tag']);
}

// Get the total count of this particular post
$sql = "SELECT id FROM comment WHERE post_id=:post_id AND post_tag=:post_tag";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
$stmt->bindParam(':post_tag', $post_tag, PDO::PARAM_STR);
$stmt->execute();

$count = $stmt->rowCount();

echo $count;
?>