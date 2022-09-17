<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");
?><?php
if (isset($_POST["show"]) && $_POST["show"] == "galpics"){
    $picstring = "";
    $gallery = preg_replace('#[^a-z 0-9,]#i', '', $_POST["gallery"]);
    $user = preg_replace('#[^a-z0-9]#i', '', $_POST["user"]);
    $sql = "SELECT * FROM photos WHERE user=:user AND gallery=:gallery ORDER BY uploaddate ASC";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':user', $user, PDO::PARAM_STR);
    $stmt->bindParam(':gallery', $gallery, PDO::PARAM_STR);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $id = $row["id"];
        $filename = $row["filename"];
        $description = $row["description"];
        $uploaddate = $row["uploaddate"];
        $picstring .= "$id|$filename|$description|$uploaddate|||";
    }
    $db_connect = null;
    $picstring = trim($picstring, "|||");
    echo $picstring;
    exit();
}
?><?php
if($user_ok != true || $log_username == "") {
    exit();
}
?><?php
if (isset($_FILES["avatar"]["name"]) && $_FILES["avatar"]["tmp_name"] != ""){
    $fileName = $_FILES["avatar"]["name"];
    $fileTmpLoc = $_FILES["avatar"]["tmp_name"];
    $fileType = $_FILES["avatar"]["type"];
    $fileSize = $_FILES["avatar"]["size"];
    $fileErrorMsg = $_FILES["avatar"]["error"];
    $kaboom = explode(".", $fileName);
    $fileExt = end($kaboom);
    list($width, $height) = getimagesize($fileTmpLoc);
    if($width < 5 || $height < 5){
        header("location: ../message.php?msg=ERROR: That image has no dimensions");
        exit();
    }
    $db_file_name = rand(100000000000,999999999999).".".$fileExt;
    if($fileSize > 5242880) {
        header("location: ../message.php?msg=ERROR: Your image file was larger than 5mb");
        exit();
    } else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
        header("location: ../message.php?msg=ERROR: Your image file was not jpg, gif or png type");
        exit();
    } else if ($fileErrorMsg == 1) {
        header("location: ../message.php?msg=ERROR: An unknown error occurred");
        exit();
    }
    $sql = "SELECT avatar FROM users WHERE username=:logusername LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    //$stmt->bindParam(':naatcast', $naatcast, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    $avatar = $row[0];
    if($avatar != ""){
        $picurl = "../user/$log_username/$avatar";
        if (file_exists($picurl)) { unlink($picurl); }
    }
    $moveResult = move_uploaded_file($fileTmpLoc, "../user/$log_username/$db_file_name");
    if ($moveResult != true) {
        header("location: ../message.php?msg=ERROR: File upload failed");
        exit();
    }
    include_once("../php_includes/image_resize.php");
    $target_file = "../user/$log_username/$db_file_name";
    $resized_file = "../user/$log_username/$db_file_name";
    $wmax = 200;
    $hmax = 300;
    img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
    $sql = "UPDATE users SET avatar=:avatar WHERE username=:logusername LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':avatar', $db_file_name, PDO::PARAM_STR);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
    header("location: ../user_audio.php?u=$log_username");
    exit();

    include_once("../php_includes/image_thumb.php");
    $target_file = "../user/$log_username/$db_file_name";
    $thumb_file = "../user/$log_username/$db_file_name";
    $wthumb = 200;
    $hthumb = 200;
    img_thumb($target_file, $thumb_file, $wthumb, $hthumb, $fileExt);
    $sql = "UPDATE users SET avatar=:avatar WHERE username=:logusername LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':avatar', $db_file_name, PDO::PARAM_STR);
    $stmt->bindParam('logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $db_connect = null;
    header("location: ../user_audio.php?u=$log_username");
    exit();
}
?><?php
if (isset($_FILES["photo"]["name"]) && isset($_POST["gallery"])){
    $sql = "SELECT COUNT(id) FROM photos WHERE user=:logusername";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    if($row[0] > 10000){
        header("location: ../message.php?msg=The platform allows only 10000 pictures total");
        exit();
    }
    $gallery = preg_replace('#[^a-z 0-9,]#i', '', $_POST["gallery"]);
    $fileName = $_FILES["photo"]["name"];
    $fileTmpLoc = $_FILES["photo"]["tmp_name"];
    $fileType = $_FILES["photo"]["type"];
    $fileSize = $_FILES["photo"]["size"];
    $fileErrorMsg = $_FILES["photo"]["error"];
    $kaboom = explode(".", $fileName);
    $fileExt = end($kaboom);
    $db_file_name = date("DMjGisY")."".rand(1000,9999).".".$fileExt; // WedFeb272120452013RAND.jpg
    list($width, $height) = getimagesize($fileTmpLoc);
    if($width < 5 || $height < 5){
        header("location: ../message.php?msg=ERROR: That image has no dimensions");
        exit();
    }
    if($fileSize > 5242880) {
        header("location: ../message.php?msg=ERROR: Your image file was larger than 5mb");
        exit();
    } else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
        header("location: ../message.php?msg=ERROR: Your image file was not jpg, gif or png type");
        exit();
    } else if ($fileErrorMsg == 1) {
        header("location: ../message.php?msg=ERROR: An unknown error occurred");
        exit();
    }
    $moveResult = move_uploaded_file($fileTmpLoc, "../user/$log_username/$db_file_name");
    if ($moveResult != true) {
        header("location: ../message.php?msg=ERROR: File upload failed");
        exit();
    }
    include_once("../php_includes/image_resize.php");
    $wmax = 800;
    $hmax = 600;
    if($width > $wmax || $height > $hmax){
        $target_file = "../user/$log_username/$db_file_name";
        $resized_file = "../user/$log_username/$db_file_name";
        img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
    }
    $stmt = $db_connect->prepare("INSERT INTO photos (user, gallery, filename, uploaddate) VALUES (:user, :gallery, :filename, now())");
    $stmt->execute(array(':user' => $log_username, ':gallery' => $gallery, ':filename' => $db_file_name));
    $db_connect = null;
    header("location: ../photos.php?u=$log_username");
    exit();
}
?><?php
if (isset($_POST["delete"]) && $_POST["id"] != ""){
    $id = preg_replace('#[^0-9]#', '', $_POST["id"]);
    $stmt = $db_connect->prepare("SELECT user, filename FROM photos WHERE id=:id LIMIT 1");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $row = $stmt->fetch();
    $user = $row[0];
    $filename = $row[1];
    if($user == $log_username){
        $picurl = "../user/$log_username/$filename";
        if (file_exists($picurl)) {
            unlink($picurl);
            $stmt = $db_connect->prepare("DELETE FROM photos WHERE id=:id LIMIT 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    $db_connect = null;
    echo "deleted_ok";
    exit();
}
?>
