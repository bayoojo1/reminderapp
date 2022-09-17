<?php
error_reporting(E_ALL ^ E_WARNING);
include_once("../php_includes/textToSpeech.php"); 
include_once("../php_includes/fileToSpeech.php");
// Check if user posted text and audio file at same time
/*
if(!empty($_POST["message"]) && !empty($_FILES["audio"])){
  echo "You cannot broadcast text and audio file at the same time.";
  exit();
} */
?><?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["message"]) && !empty($_POST["message"])) {
    textToSpeech();
  } else {
        fileToSpeech();
  }
?>



