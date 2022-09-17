<?php
// Update the schedule table column with new date for recurrent
include_once("../php_includes/mysqli_connect.php");
include_once("../php_includes/wombat_db.php");
include_once("../php_includes/a2billing_db.php");
$sql = "SELECT * FROM yearly WHERE postdate >= DATE_SUB(NOW(),INTERVAL 1 YEAR)";
$stmt = $db_connect->prepare($sql);
$stmt->execute();
$numrows = $stmt->rowCount();
if($numrows == 0) {
  exit();
} else {
  $sql = "UPDATE yearly SET `accepted` = DATE_ADD(`accepted` , INTERVAL 1 YEAR)";
  $stmt = $db_connect->prepare($sql);
  $stmt->execute();
}
// Create a view
$sql = "CREATE OR REPLACE VIEW `virtual` AS SELECT * FROM yearly";
$stmt = $db_connect->prepare($sql);
$stmt->execute();
// Select the created view
$sql = "SELECT * FROM `virtual`";
$stmt = $db_connect->prepare($sql);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
  $username = $row["username1"];

  // Scoop out detail of this user.
  $sql = "SELECT countrycode, mobile, email FROM users WHERE username=:username";
  $stmt = $db_connect->prepare($sql);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->execute();
  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $cc = $row["countrycode"];
    $mobile = $row["mobile"];
    $email = $row["email"];
  }
  $login_mobile = trim($cc.''.$mobile);
  // Scoop out credit balance from the a2billing database
  $sql = "SELECT credit FROM cc_card WHERE phone=:phone";
  $stmt = $a2billing_db->prepare($sql);
  $stmt->bindParam(':phone', $mobile, PDO::PARAM_STR);
  $stmt->execute();
  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $credit = $row["credit"];

  if($credit < 50) {
    // Send email to the users
  exit();
    }
  }
}
// Start the process of setting up the next reminder
$sql = "SELECT username1, mobile, accepted, message, audio
        FROM `virtual`
        WHERE postdate >= DATE_SUB(NOW(),INTERVAL 1 YEAR)";
        $stmt = $db_connect->prepare($sql);
        $stmt->execute();
        $numrows = $stmt->rowCount();
        if($numrows == 0){
            exit();
        }
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
          $u = $row["username1"];
          $m = $row["mobile"];
          $s = $row["accepted"];
          $t = $row["message"];
          $audio = $row["audio"];

          $string = rand(0, 100000000);
          $c = $u."".($string);
          include_once ("createCampaign.php");
          $create = array(
              'op' => 'clone',
              'campaign' => 'once-text',
              'newcampaign' => $c
          );
          createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);

          // Update Wombat DB
          $sql = "UPDATE campaigns SET dial_clid=:dial_clid, dial_pres=:dial_pres, agentClid=:agentClid, email_addresses=:email WHERE name=:name";
          $stmt = $wombat_db->prepare($sql);
          $stmt->bindParam(':dial_clid', $mobile, PDO::PARAM_STR);
          $stmt->bindParam(':dial_pres', $mobile, PDO::PARAM_STR);
          $stmt->bindParam(':agentClid', $mobile, PDO::PARAM_STR);
          $stmt->bindParam(':email', $email, PDO::PARAM_STR);
          $stmt->bindParam(':name', $c, PDO::PARAM_STR);
          $stmt->execute();

          // Start a campaign
          include_once ("startCampaign.php");
          $start = array(
              'op' => 'start',
              'campaign' => $c
          );
          startCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$start);

          usleep(500000);

          $array = explode(',', $m); //split mobile numbers into array seperated by ', '
          foreach($array as $n) //loop over the numbers
          {
            if($audio == "") {
          include_once("addCall.php");
          $a = str_replace(' ', '.', $s);
          $add = array(
          'op' => 'addcall',
          'campaign' => $c,
          'number' => "{$n}",
          'schedule' => $a,
          'attrs' => "text:{$t}"
      );
      addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
    } else {
      include_once("addCall.php");
      $a = str_replace(' ', '.', $s);
      $add = array(
      'op' => 'addcall',
      'campaign' => $c,
      'number' => "{$n}",
      'schedule' => $a,
      'attrs' => "filepath:{$audio}"
  );
  addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
    }
  }
}
?>
