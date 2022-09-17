<?php
session_start();
include("mysqli_connect.php");
// Files that include this file at the very top would NOT require
// connection to database or session_start(), be careful.
// Initialize some vars
$user_ok = false;
$log_id = "";
//$log_email = "";
$log_username = "";
$log_password = "";
// User Verify function
function evalLoggedUser($db_connect,$id,$u,$p) {
    include("mysqli_connect.php");
    $sql = "SELECT ip FROM users WHERE id=:id AND username=:username AND password=:password AND activated='1' LIMIT 1";
    $stmt = $db_connect->prepare($sql); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':username', $u, PDO::PARAM_STR);
    $stmt->bindParam(':password', $p, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count > 0) {
        return true;
    } 
}
$db_connect = null; 

if(isset($_SESSION["userid"]) && isset($_SESSION["username"]) && isset($_SESSION["password"])) {
    $log_id = preg_replace('#[^0-9]#', '', $_SESSION['userid']);
    //$log_email = mysqli_real_escape_string($db_connect, $_SESSION['email']);
    $log_username = preg_replace('#[^a-z0-9.@_]#i', '', $_SESSION['username']);
    $log_password = $_SESSION['password'];
    // Verify the user
    $user_ok = evalLoggedUser($db_connect,$log_id,$log_username,$log_password);
} else if(isset($_COOKIE["id"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])){
    $_SESSION['userid'] = preg_replace('#[^0-9]#', '', $_COOKIE['id']);
    //$_SESSION['email'] = mysqli_real_escape_string($db_connect, $_COOKIE['mail']);
    $_SESSION['username'] = preg_replace('#[^a-z0-9.@_]#i', '', $_COOKIE['user']);
    $_SESSION['password'] = $_COOKIE['pass'];
    $log_id = $_SESSION['userid'];
    //$log_email = $_SESSION['email'];
    $log_username = $_SESSION['username'];
    $log_password = $_SESSION['password'];
    // Verify the user
    $user_ok = evalLoggedUser($db_connect,$log_id,$log_username,$log_password);
    if($user_ok == true){
        // Update their lastlogin datetime field
        include("mysqli_connect.php");
        $sql = "UPDATE users SET lastlogin=now() WHERE id=:id LIMIT 1";
        $stmt = $db_connect->prepare($sql); 
        $stmt->bindParam(':id', $log_id, PDO::PARAM_INT);
        $stmt->execute();
        $db_connect = null;
    }
}
?>
