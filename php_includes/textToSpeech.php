  <?php
    function textToSpeech() {
        // GATHER THE POSTED DATA INTO LOCAL VARIABLES
        include("check_login_status.php");
        include("wombat_db.php");
        include("mysqli_connect.php");
        include("a2billing_db.php");
        $naatcast = 'naatcast';
        $naatcastbilling = 'naatcast.billing';
        $naatcastsupport = 'naatcast.support';
        $naatcastreport = 'naatcast.report';
        $naatcastothers = 'naatcast.others';
        $post = 'post';
        if(isset($_POST['subscribers'])) {
                $sub = preg_replace('#[^a-zA-Z]#', '', $_POST['subscribers']); // Broadcast to subscribers
                $u = $log_username;
                $string = rand(0, 100000000);
                $z = $u."".($string);
                if($u == $naatcast || $u == $naatcastbilling || $u == $naatcastsupport || $u == $naatcastreport || $u == $naatcastothers) {
                $words = $_POST['message']; // Only use this when naatcast wants to post.
              } else {
                $words = preg_replace('#[^a-z0-9,.?!@;\' ]#i', '', $_POST['message']); // Always use this.
              }
                $t = str_replace(',', ';', $words); // Text Message
                $date = preg_replace('#[^0-9-:]#', '', $_POST['date']); // Schedule
                $s = substr_replace($date, ".", 10, 0);
                $r = $_POST['recurrent']; // Recurrent
                $f = preg_replace('#[^a-zA-Z]#', '', $_POST['followers']); // Broadcast to followers
                $m = preg_replace('#[^,0-9 ]#', '', $_POST['mobile']); // Recepients mobile number
                $sh = $_POST['shared']; // Shared on followers or subscribers feed
        } else {
        $u = $log_username;
        $string = rand(0, 100000000);
        $z = $u."".($string);
        if($u == $naatcast || $u == $naatcastbilling || $u == $naatcastsupport || $u == $naatcastreport || $u == $naatcastothers) {
        $words = $_POST['message'];
      } else {
        $words = preg_replace('#[^a-z0-9,.?!@;\' ]#i', '', $_POST['message']); // Always use this.
      }
        $t = str_replace(',', ';', $words); // Text Message
        $date = preg_replace('#[^0-9-:]#', '', $_POST['date']); // Schedule
        $s = substr_replace($date, ".", 10, 0);
        $r = $_POST['recurrent']; // Recurrent
        $f = preg_replace('#[^a-zA-Z]#', '', $_POST['followers']); // Broadcast to followers
        $m = preg_replace('#[^,0-9 ]#', '', $_POST['mobile']); // Recepients mobile number
        $sh = $_POST['shared']; // Shared on followers or subscribers feed
        }
        // Scoop out the country code and email of the login user
        $sql = "SELECT countrycode, mobile, email FROM users WHERE username=:logusername";
        $stmt = $db_connect->prepare($sql);
        $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $cc = $row['countrycode'];
                $login_mobile = $row['mobile'];
                $email = $row['email'];
        }

        // Start the process of sending the campaign.
        if($t == "") {
                echo "The form submission is missing values.";
                exit();
              }
        // Create the campaign
      if($date != "") {
        include_once ("createCampaign.php");
        if($r == "Once") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'once-text',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);

        } else if ($r == "Daily" || $r == "Weekly" || $r == "Monthly" || $r == "Yearly") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'recurrent-text',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyRound") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyRound-text',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyDaytime") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyDaytime-text',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyNight") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyNight-text',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyWeekDaytime") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekDaytime-text',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyWeekDayNight") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekDayNight-text',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyWeekendDay") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekendDay-text',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyWeekendNight") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekendNight-text',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        }
      }          // Update Wombat DB
                //$mobile = trim($cc).trim($login_mobile);
                $mobile = trim($cc.''.substr($login_mobile, 1));
                $sql = "UPDATE campaigns SET dial_clid=:dial_clid, dial_pres=:dial_pres, agentClid=:agentClid, email_addresses=:email_addresses WHERE name=:name";
                $stmt = $wombat_db->prepare($sql);
                $stmt->bindParam(':dial_clid', $mobile, PDO::PARAM_STR);
                $stmt->bindParam(':dial_pres', $mobile, PDO::PARAM_STR);
                $stmt->bindParam(':agentClid', $mobile, PDO::PARAM_STR);
                $stmt->bindParam(':email_addresses', $email, PDO::PARAM_STR);
                $stmt->bindParam(':name', $z, PDO::PARAM_STR);
                $stmt->execute();
        // Start the campaign
        //usleep(500000);
         if($date != "") {
                include_once ("startCampaign.php");
                $start = array(
                'op' => 'start',
                'campaign' => $z
                );
                startCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$start);
              }
        if($f == "Yes"){
                if($date == "") {
                        echo "You must set a date";
                        exit();
                }
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
                // Check if the user have followers
                $sql = "SELECT countrycode, mobile FROM follows WHERE user2=:logusername";
                $stmt = $db_connect->prepare($sql);
                $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
                $stmt->execute();
                $numrows = $stmt->rowCount();
                if($numrows < 1) {
                        echo 'You don\'t have any follower';
                        exit();
                } else {
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                        $cc = $row['countrycode'];
                        $mb = $row['mobile'];
                        $phone_str = substr($mb, 1);
                        $follower_mobile = trim($cc.''.$phone_str);

                usleep(500000);

                $array = explode(',', $follower_mobile); //split mobile numbers into array seperated by ', '
                foreach($array as $v) //loop over the numbers
                {
                if($date != "") {
                // Add call to a campaign
                include_once ("addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => "{$v}",
                'schedule' => $s,
                'attrs' => "text:{$t}"
                );
                addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
                  }
                }
                if($v != "") {
                include('ttsdbupdate.php');
                }
        }
}
        } elseif (isset($sub) && $sub == "Yes") {
                if($date == "") {
                        echo "You must set a date";
                        exit();
                }
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
                // Check if the user have subscriber
                $sql = "SELECT countrycode, subscriber_mobile FROM subscription WHERE provider=:logusername";
                $stmt = $db_connect->prepare($sql);
                $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
                $stmt->execute();
                $numrows = $stmt->rowCount();
                if($numrows < 1) {
                        echo 'You don\'t have any subscribers';
                        exit();
                } else {
                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                        $subscriber_cc = $row['countrycode'];
                        $subscriber_mb = $row['subscriber_mobile'];
                        $subscriber_mbs = substr($subscriber_mb, 1);
                        $sub_mobile = trim($subscriber_cc.''.$subscriber_mbs);

                usleep(500000);

                $array = explode(',', $sub_mobile); //split mobile numbers into array seperated by ', '
                foreach($array as $v) //loop over the numbers
                {
                if($date != "") {
                // Add call to a campaign
                include_once ("addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => "{$v}",
                'schedule' => $s,
                'attrs' => "text:{$t}"
                );
                addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
                  }
                  if($v != "") {
                  include('ttsdbupdate.php');
                  }
                }
        }
}
} elseif ($m != "") {
                if($date == "") {
                        echo "You must set a date";
                        exit();
                }
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

                        $array = explode(',', $m); //split mobile numbers into array seperated by ', '
                        foreach($array as $v) //loop over the numbers
                        {
                        if($date != "") {
                        // Add call to a campaign
                        include_once ("addCall.php");
                        $add = array(
                        'op' => 'addcall',
                        'campaign' => $z,
                        'number' => $v,
                        'schedule' => $s,
                        'attrs' => "text:{$t}"
                        );
                        addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
                      }
                      if($v != "") {
                      include('ttsdbupdate.php');
                    }
                }
        } elseif($f == "No" || isset($sub) && $sub == "No" || $m == "") {
                $v = "";
                include('ttsdbupdate.php');
        }
// Retrieve the last insterted ID
$post_id = $db_connect->lastInsertId();
// Insert detail into notification table
if($sh == 'Followers' || $sh == 'Subscribers') {
$stmt = $db_connect->prepare("INSERT INTO notifications (initiator, target, action, post_id, post_tag, detail, postdate)
VALUES(:initiator, :target, :action, :post_id, :post_tag, :detail, now())");
$stmt->execute(array(':initiator' => $u, ':target' => $sh, ':action' => $post, ':post_id' => $post_id, ':post_tag' => $r, ':detail' => $t));
  }
}
?>
