<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if($user_ok != true || $log_username == "") {
    exit();
}
?><?php
if (isset($_FILES["sample"]["name"]) && $_FILES["sample"]["tmp_name"] != ""){
    $audio_uploadDir = '/wamp64/www/reminderapp/user/'.$log_username.'/';
    $audio_fileName = $_FILES["sample"]["name"];
    $audio_fileTmpLoc = $_FILES["sample"]["tmp_name"];
    $audio_fileType = $_FILES["sample"]["type"];
    $audio_fileSize = $_FILES["sample"]["size"];
    $audio_fileErrorMsg = $_FILES["sample"]["error"];
    $kaboom = explode(".", $audio_fileName);
    $audio_fileExt = end($kaboom);
    
    $audio_file_name = rand(100000000000,999999999999).".".$audio_fileExt;
    $audio_db_file_name = $audio_uploadDir . $audio_file_name;
    $audio_p_local = "http://localhost:8080/reminderapp/user/$log_username/$audio_file_name"; // File path

    if(!$audio_fileTmpLoc) {
        echo "ERROR: You need to upload your sample audio file.";
        exit();
    } else if ($audio_fileSize > 5242880) {
        echo "ERROR: Your audio file was larger than 5mb.";
        exit();
    } else if (!preg_match("/\.(wav|ogg|mp3)$/i", $audio_fileName) ) {
        echo "ERROR: Your audio file was not wav, ogg or mp3 type.";
        exit();
    } else if ($audio_fileErrorMsg == 1) {
        echo "ERROR: An unknown error occurred.";
        exit();
    }
    
} else {
    echo "ERROR: You need to upload your sample audio file.";
    exit();
}
?><?php
if (isset($_FILES["identity"]["name"]) && $_FILES["identity"]["tmp_name"] != ""){
    $uploadDir = '/wamp64/www/reminderapp/user/'.$log_username.'/';
    $fileName = $_FILES["identity"]["name"];
    $fileTmpLoc = $_FILES["identity"]["tmp_name"];
    $fileType = $_FILES["identity"]["type"];
    $fileSize = $_FILES["identity"]["size"];
    $fileErrorMsg = $_FILES["identity"]["error"];
    $kaboom = explode(".", $fileName);
    $fileExt = end($kaboom);
    
    $file_name = rand(100000000000,999999999999).".".$fileExt;
    $db_file_name = $uploadDir . $file_name;
    $p_local = "http://localhost:8080/reminderapp/user/$log_username/$file_name"; // File path

    if(!$fileTmpLoc) {
        echo "ERROR: You need to upload your identity document.";
        exit();
    } else if ($fileSize > 5242880) {
        echo "ERROR: Your uploaded file was larger than 5mb.";
        exit();
    } else if (!preg_match("/\.(pdf|jpg|docx|jpeg|png|doc)$/i", $fileName) ) {
        echo "ERROR: Your uploaded file has to be pdf, jpeg, jpg, png or docx type.";
        exit();
    } else if ($fileErrorMsg == 1) {
        echo "ERROR: An unknown error occurred.";
        exit();
    }
    
} else {
    echo "ERROR: You need to upload your identity document";
    exit();
}
?><?php
// GATHER THE POSTED DATA INTO LOCAL VARIABLES
if($_POST['type'] == 'one') {
    echo "You need to select a content type.";
    exit();
} else {
$content_type = $_POST['type']; //The type of content
}

if($_POST['description'] == '') {
    echo "You need to provide detail description of the content you are submitting.";
    exit();
} else {
$description = preg_replace('#[^-,a-z0-9.?@_: \']#i', '', $_POST['description']); //The description of the content
}
// Move both uploaded files to the final directories
$moveResult = move_uploaded_file($audio_fileTmpLoc, $audio_db_file_name);
if ($moveResult != true) {
    echo "ERROR: File upload failed.";
    exit();
}
$moveResult = move_uploaded_file($fileTmpLoc, $db_file_name);
if ($moveResult != true) {
    echo "ERROR: File upload failed.";
    exit();
}

$approved = '0';
// Update the Database with the content provider detail
$stmt = $db_connect->prepare("INSERT INTO content_provider (provider, start_date, approved, content_type, description, content_sample, identification)
VALUES(:provider, now(), :approved, :content_type, :description, :content_sample, :identification)");
$stmt->execute(array(':provider' => $log_username, ':approved' => $approved, ':content_type' => $content_type, ':description' => $description, ':content_sample' => $audio_p_local, ':identification' => $p_local));

echo "Success...please give us some time to go through your submitted details. Await an email for further instruction from us shortly";
?>