<?php
  function apitextToSpeech() {
      // GATHER THE POSTED DATA INTO LOCAL VARIABLES
      include("./php_includes/check_login_status.php");
      include("./php_includes/wombat_db.php");
      include("./php_includes/mysqli_connect.php");
      include("./php_includes/a2billing_db.php");
      if(isset($_POST['text'])) {
        $t = preg_replace('#[^a-z0-9,;.?:!\']#i', '', $_POST['text']);
        $apiname = preg_replace('#[^a-z0-9]#i', '', $_POST['apiname']);
        $apitoken = preg_replace('#[^a-z0-9]#i', '', $_POST['apitoken']);
        $recurrent = preg_replace('#[^a-z0-9]#i', '', $_POST['recurrent']);
        $mobile = preg_replace('#[^0-9,+]#i', '', $_POST['mobile']);
        $schedule = preg_replace('#[^0-9-:.]#i', '', $_POST['schedule']);
      }

      $text = str_replace(',', ';', $t); // Text Message
      // Do some checking
      if(empty($text) || empty($apiname) || empty($apitoken) || empty($recurrent) || empty($mobile) || empty($schedule)) {
        echo 'The submission is missing a value';
        exit();
      }

      $api_token = '';
      $username = '';
      // Scoop out the details of the user based on the submitted apitoken
      $sql = "SELECT api_name, api_token, username, countrycode, mobile FROM api WHERE api_token=:api_token";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindParam(':api_token', $apitoken, PDO::PARAM_STR);
      $stmt->execute();
      foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
              $username = $row['username'];
              $cc = $row['countrycode'];
              $login_mobile = $row['mobile'];
              $api_name = $row['api_name'];
              $api_token = $row['api_token'];
      }
      //$m = trim($cc.''.substr($login_mobile, 1));
      $sql = "SELECT username, email FROM users WHERE countrycode=:cc AND mobile=:mobile";
      $stmt = $db_connect->prepare($sql);
      $stmt->bindParam(':cc', $cc, PDO::PARAM_STR);
      $stmt->bindParam(':mobile', $login_mobile, PDO::PARAM_STR);
      $stmt->execute();
      foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $log_username = $row['username'];
        $email = $row['email'];
      }

      if($apitoken != $api_token || $log_username != $username) {
        echo 'Wrong token';
        exit();
      }

      // Create the campaign name
      $u = $log_username.''.$apiname;
      $string = rand(0, 100000000);
      $z = $u."".($string);

      // Start the process of sending the campaign.
      // Create the campaign
      include_once ("./functions/createCampaign.php");
      if($recurrent == "Once") {
              $create = array(
                      'op' => 'clone',
                      'campaign' => 'once-text',
                      'newcampaign' => $z
                      );
                      createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);

      } else if ($recurrent == "Daily" || $recurrent == "Weekly" || $recurrent == "Monthly" || $recurrent == "Yearly") {
              $create = array(
                      'op' => 'clone',
                      'campaign' => 'recurrent-text',
                      'newcampaign' => $z
                      );
                      createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
      } else if ($recurrent == "DailyRound") {
              $create = array(
                      'op' => 'clone',
                      'campaign' => 'dailyRound-text',
                      'newcampaign' => $z
                      );
                      createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
      } else if ($recurrent == "DailyDaytime") {
              $create = array(
                      'op' => 'clone',
                      'campaign' => 'dailyDaytime-text',
                      'newcampaign' => $z
                      );
                      createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
      } else if ($recurrent == "DailyNight") {
              $create = array(
                      'op' => 'clone',
                      'campaign' => 'dailyNight-text',
                      'newcampaign' => $z
                      );
                      createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
      } else if ($recurrent == "DailyWeekDaytime") {
              $create = array(
                      'op' => 'clone',
                      'campaign' => 'dailyWeekDaytime-text',
                      'newcampaign' => $z
                      );
                      createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
      } else if ($recurrent == "DailyWeekDayNight") {
              $create = array(
                      'op' => 'clone',
                      'campaign' => 'dailyWeekDayNight-text',
                      'newcampaign' => $z
                      );
                      createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
      } else if ($recurrent == "DailyWeekendDay") {
              $create = array(
                      'op' => 'clone',
                      'campaign' => 'dailyWeekendDay-text',
                      'newcampaign' => $z
                      );
                      createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
      } else if ($recurrent == "DailyWeekendNight") {
              $create = array(
                      'op' => 'clone',
                      'campaign' => 'dailyWeekendNight-text',
                      'newcampaign' => $z
                      );
                      createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
      }
              // Update Wombat DB
              //$mobile = trim($cc).trim($login_mobile);
              $m = trim($cc.''.substr($login_mobile, 1));
              $sql = "UPDATE campaigns SET dial_clid=:dial_clid, dial_pres=:dial_pres, agentClid=:agentClid, email_addresses=:email_addresses WHERE name=:name";
              $stmt = $wombat_db->prepare($sql);
              $stmt->bindParam(':dial_clid', $m, PDO::PARAM_STR);
              $stmt->bindParam(':dial_pres', $m, PDO::PARAM_STR);
              $stmt->bindParam(':agentClid', $m, PDO::PARAM_STR);
              $stmt->bindParam(':email_addresses', $email, PDO::PARAM_STR);
              $stmt->bindParam(':name', $z, PDO::PARAM_STR);
              $stmt->execute();
      // Start the campaign
      //usleep(500000);
              include_once ("./functions/startCampaign.php");
              $start = array(
              'op' => 'start',
              'campaign' => $z
              );
              startCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$start);


              // Scoop out credit balance from the a2billing database
              $sql = "SELECT credit FROM cc_card WHERE phone=:phone";
              $stmt = $a2billing_db->prepare($sql);
              $stmt->bindParam(':phone', $login_mobile, PDO::PARAM_STR);
              $stmt->execute();
              foreach($stmt->fetchAll() as $row) {
              $credit = $row["credit"];
              }

              if($credit < 0.2) {
              echo "You don't have enough credit to complete this request, please recharge";
              exit();
              }

              usleep(500000);

              $array = explode(',', $mobile); //split mobile numbers into array seperated by ', '
              foreach($array as $v) //loop over the numbers
              {
              // Add call to a campaign
              include_once ("./functions/addCall.php");
              $add = array(
              'op' => 'addcall',
              'campaign' => $z,
              'number' => "{$v}",
              'schedule' => $schedule,
              'attrs' => "text:{$text}"
              );
              addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);

              }
}


/*

              $b = date_create($s);
              $a = date_format($b, 'Y-m-d H:i:s');

              //Update the DB with the user options
              if($r == 'Once' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')){
                      $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'Daily' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')){
                      $stmt = $db_connect->prepare("INSERT INTO daily (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'Weekly' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')){
                      $stmt = $db_connect->prepare("INSERT INTO weekly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'Monthly' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')){
                      $stmt = $db_connect->prepare("INSERT INTO monthly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'Yearly' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')){
                      $stmt = $db_connect->prepare("INSERT INTO yearly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'DailyRound' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
                      $stmt = $db_connect->prepare("INSERT INTO dailyround (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'DailyDaytime' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
                      $stmt = $db_connect->prepare("INSERT INTO dailydaytime (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'DailyNight' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
                      $stmt = $db_connect->prepare("INSERT INTO dailynight (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'DailyWeekDaytime' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
                      $stmt = $db_connect->prepare("INSERT INTO dailyweekdaytime (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'DailyWeekDayNight' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
                      $stmt = $db_connect->prepare("INSERT INTO dailyweekdaynight (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'dailyWeekendDay' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
                      $stmt = $db_connect->prepare("INSERT INTO dailyweekendday (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              } elseif($r == 'DailyWeekendNight' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
                      $stmt = $db_connect->prepare("INSERT INTO dailyweekendnight (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
              VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                      $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
              }

}
*/
?>
