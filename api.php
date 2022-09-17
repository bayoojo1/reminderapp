<?php
error_reporting(E_ALL ^ E_WARNING);
include_once("apifunctions/apifileToSpeech.php");
include_once("apifunctions/apitextToSpeech.php");

?><?php
// Gather into variables all values sent by user API call
if(isset($_POST["text"])) {
    apitextToSpeech();
  } else {
        apifileToSpeech();
  }
?>
