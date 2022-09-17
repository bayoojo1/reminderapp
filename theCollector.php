<?php
$from_page = '';
include("php_includes/mysqli_connect.php");
$browser  =$_SERVER['HTTP_USER_AGENT'];
$curr_page=$_SERVER['PHP_SELF'];
$ip  =  $_SERVER['REMOTE_ADDR'];
if(isset($_SERVER['HTTP_REFERER'])) {
$from_page = $_SERVER['HTTP_REFERER'];
}
$page=$_SERVER['PHP_SELF'];

//Insert the data in the table…
$stmt = $db_connect->prepare("INSERT INTO stattracker
(browser,ip,thedate_visited,page,from_page) VALUES
(:browser, :ip, now(), :page, :from_page)");
$stmt->execute(array(':browser' => $browser, ':ip' => $ip, ':page' => $page, ':from_page' => $from_page));
?>
