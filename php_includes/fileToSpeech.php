<?php
function fileToSpeech() {
        include("check_login_status.php");
        include("wombat_db.php");
        include("mysqli_connect.php");
        include("a2billing_db.php");
        //include("check_balance.php");
        $u = $log_username;
        $post = 'post';
        $detail = 'just shared an audio';
    // Access the $_FILES global variable for this specific file being uploaded
        // and create local PHP variables from the $_FILES array of information
        $uploadDir = '/wamp64/www/reminderapp/uploads/'.$log_username.'/';
        $uploadFile = $uploadDir . basename($_FILES['audio']['name']);
        $fileTmpLoc = $_FILES["audio"]["tmp_name"]; // File in the PHP tmp folder
        $fileType = $_FILES["audio"]["type"]; // The type of file
        $fileSize = $_FILES["audio"]["size"]; // File size in bytes
        $fileErrorMsg = $_FILES["audio"]["error"]; //0 for false....and 1 for true
        //$kaboom = explode(".", $_FILES['audio']['name']);
        $kaboom = explode(".", $uploadFile);
        $fileExt = end($kaboom);
        $filename = date("DMjGisY")."".rand(1000000,9999999).".".$fileExt;
        $asterisk_fn = pathinfo($filename, PATHINFO_FILENAME); // This will return the filename without the extension
        $db_file_name = $uploadDir . $filename; // WedFeb272120452013RAND.jpg
        // Start PHP Image Upload Error Handling.....
        if(!$fileTmpLoc) { // if file not chosen
                echo "ERROR: Please type your message or upload a file, also enter all the required information before clicking the POST button";
                exit();
        } else if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
                echo "ERROR: Your file was larger than 5 Megabytes in size.";
                unlink($fileTmpLoc); // Remove the upload file from the PHP temp folder
                exit();
        } else if(!preg_match("/\.(wav|mp3)$/i", $db_file_name)) {
                // This condition is only if you wish to allow uploading of specific file type
                echo "ERROR: Your file was not .mp3, .wav";
                unlink($fileTmpLoc);
                exit();
        } else if($fileErrorMsg == 1) { // if file upload error key is equal to 1
                echo "ERROR: An error occured while processing the file. Try again.";
                exit();
        }

        if (move_uploaded_file($_FILES['audio']['tmp_name'], $db_file_name)) {
        // Prepare remote upload data
        $uploadRequest = array(
            'fileName' => basename($db_file_name),
            'fileData' => base64_encode(file_get_contents($db_file_name))
        );

        // Execute remote upload
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://10.32.1.167/receiver.php');
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($curl, CURLOPT_CAINFO, 'C:/wamp64/bin/php/php5.6.19/localhost.crt');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $uploadRequest);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        echo $response;
        echo $error;

        // Now delete local temp file
        unlink($fileTmpLoc);
        unlink($uploadFile);
        } else {
        echo "Possible file upload attack!\n";
        exit();
        }
?><?php
        // GATHER THE POSTED DATA INTO LOCAL VARIABLES
        if(isset($_POST['subscribers'])) {
                $string = rand(0, 100000000);
                $z = $u."".($string);
                $p_remote = "/home/uploads/$asterisk_fn"; // This is the path to remote .wav file on the asterisk server
                $p_remote_mp3 = "/home/uploads/$filename"; // This is the path to remote .mp3 file on asterisk server
                $p_local = "http://localhost:8080/reminderapp/uploads/$log_username/$filename"; // File path
                $date = preg_replace('#[^0-9-:]#', '', $_POST['date']); // Schedule
                $s = substr_replace($date, ".", 10, 0); // Replaces the space between date and time with a dot
                $r = $_POST['recurrent']; // Recurrent
                $sub = preg_replace('#[^a-zA-Z]#', '', $_POST['subscribers']); // Broadcast to subscribers
                $f = preg_replace('#[^a-zA-Z]#', '', $_POST['followers']); // Broadcast to followers
                $m = preg_replace('#[^,0-9 ]#', '', $_POST['mobile']); // Recepients mobile number
                $sh = $_POST['shared']; // Shared on followers or subscribers feed
                $sd = preg_replace('#[^a-z0-9,.?!@; ]#i', '', $_POST['sub_description']); // Subscription audio upload description
        } else {
        $string = rand(0, 100000000);
        $z = $u."".($string);
        $p_remote = "/home/uploads/$asterisk_fn"; // This is the path to remote audio file on the asterisk server
        $p_remote_mp3 = "/home/uploads/$filename"; // This is the path to remote .mp3 file on asterisk server
        $p_local = "http://localhost:8080/reminderapp/uploads/$log_username/$filename"; // File path
        $date = preg_replace('#[^0-9-:]#', '', $_POST['date']); // Schedule
        $s = substr_replace($date, ".", 10, 0); // Replaces the space between date and time with a dot
        $r = $_POST['recurrent']; // Recurrent
        $f = preg_replace('#[^a-zA-Z]#', '', $_POST['followers']); // Broadcast to followers
        $m = preg_replace('#[^,0-9 ]#', '', $_POST['mobile']); // Recepients mobile number
        $sh = $_POST['shared']; // Shared on followers or subscribers feed
        $sd = $_POST['sub_description']; // Subscription audio upload description
        }

        // Start the process of sending the campaign.
        if($p_remote == "" || $p_remote_mp3 == "" || $s == ""){
                echo "The form submission is missing values.";
                exit();
        }
        // Create the campaign
      if($date) {
        include_once ("createCampaign.php");
        if($r == "Once") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'once-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);

        } else if ($r == "Daily" || $r == "Weekly" || $r == "Monthly" || $r == "Yearly") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'recurrent-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyRound") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyRound-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyDaytime") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyDaytime-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyNight") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyNight-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyWeekDaytime") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekDaytime-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyWeekDayNight") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekDayNight-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyWeekendDay") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekendDay-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($r == "DailyWeekendNight") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekendNight-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        }
      }
        // Scoop out the country code, mobile and email of the login user
        $sql = "SELECT countrycode, mobile, email FROM users WHERE username=:logusername";
        $stmt = $db_connect->prepare($sql);
        $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $cc = $row['countrycode'];
                $login_mobile = $row['mobile'];
                $email = $row['email'];
        }
        // Update Wombat DB
        //$mobile = trim($cc).trim($login_mobile);
        $mobile = trim($cc.''.$login_mobile);
        $sql = "UPDATE campaigns SET dial_clid=:dial_clid, dial_pres=:dial_pres, agentClid=:agentClid, email_addresses=:email_addresses WHERE name=:name";
        $stmt = $wombat_db->prepare($sql);
        $stmt->bindParam(':dial_clid', $mobile, PDO::PARAM_STR);
        $stmt->bindParam(':dial_pres', $mobile, PDO::PARAM_STR);
        $stmt->bindParam(':agentClid', $mobile, PDO::PARAM_STR);
        $stmt->bindParam(':email_addresses', $email, PDO::PARAM_STR);
        $stmt->bindParam(':name', $z, PDO::PARAM_STR);
        $stmt->execute();

        // Start a campaign
      if($date) {
        include_once ("startCampaign.php");
        $start = array(
        'op' => 'start',
        'campaign' => $z
        );
        startCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$start);
      }

        if($f == "Yes"){
                // Scoop out credit balance from the a2billing database
                $sql = "SELECT credit FROM cc_card WHERE phone=:phone";
                $stmt = $a2billing_db->prepare($sql);
                $stmt->bindParam(':phone', $login_mobile, PDO::PARAM_STR);
                $stmt->execute();
                foreach($stmt->fetchAll() as $row) {
                $credit = $row["credit"];
                }

                if($credit < 50) {
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
        if($fileExt == 'wav') {
                foreach($array as $v) //loop over the numbers
                {
                if($date) {
                // Add call to a campaign
                include_once ("addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => "{$v}",
                'schedule' => $s,
                'attrs' => "filepath:{$p_remote}"
                );
                addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
                  }
                  if($v != "") {
                  include('ftsdbupdate.php');
                  }
                }
        } else if($fileExt == 'mp3') {
                foreach($array as $v) //loop over the numbers
                {
                if($date) {
                // Add call to a campaign
                include_once ("addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => "{$v}",
                'schedule' => $s,
                'attrs' => "filepath:{$p_remote_mp3}"
                );
                addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
                  }
                  if($v != "") {
                  include('ftsdbupdate.php');
                  }
                }
        } else {
                exit();
        }
        }
}
        } elseif (isset($sub) && $sub == "Yes" && $sh == "Subscribers" && $sd == "") {
                echo "You must give a detail description of the audio you are uploading";
                exit();
        } elseif (isset($sub) && $sub == "Yes" && $sh == "Subscribers" && $sd != "") {
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
        if($fileExt == 'wav') {
                foreach($array as $v) //loop over the numbers
                {
                if($date) {
                // Add call to a campaign
                include_once ("addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => "{$v}",
                'schedule' => $s,
                'attrs' => "filepath:{$p_remote}"
                );
                addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
                  }
                  if($v != "") {
                  include('ftsdbupdate.php');
                  }
                }
        } else if($fileExt == 'mp3') {
                foreach($array as $v) //loop over the numbers
                {
                if($date) {
                // Add call to a campaign
                include_once ("addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => "{$v}",
                'schedule' => $s,
                'attrs' => "filepath:{$p_remote_mp3}"
                );
                addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
                  }
                  if($v != "") {
                  include('ftsdbupdate.php');
                  }
                }
        } else {
                exit();
        }
        }
}
} elseif($m != "") {
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

        if($fileExt == 'wav') {
                foreach($array as $v) //loop over the numbers
                {
                if($date) {
                // Add call to a campaign
                include_once ("addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => "{$v}",
                'schedule' => $s,
                'attrs' => "filepath:{$p_remote}"
                );
                addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
              }
              if($v != "") {
              include('ftsdbupdate.php');
              }
        }
        } else if($fileExt == 'mp3') {
                foreach($array as $v) //loop over the numbers
                {
                if($date) {
                // Add call to a campaign
                include_once ("addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => "{$v}",
                'schedule' => $s,
                'attrs' => "filepath:{$p_remote_mp3}"
                );
                addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);
              }
              if($v != "") {
              include('ftsdbupdate.php');
              }
        }
        } else {
                exit();
        }
      } else if($f == "No" || isset($sub) && $sub == "No" || $m == "") {
                // What should happen here?
                $v = "";
                include('ftsdbupdate.php');
        }
// Retrieve the last insterted ID
$post_id = $db_connect->lastInsertId();
// Insert detail into notification table
if($sh == 'Followers' || $sh == 'Subscribers') {
$stmt = $db_connect->prepare("INSERT INTO notifications (initiator, target, action, post_id, post_tag, detail, postdate)
VALUES(:initiator, :target, :action, :post_id, :post_tag, :detail, now())");
$stmt->execute(array(':initiator' => $u, ':target' => $sh, ':action' => $post, ':post_id' => $post_id, ':post_tag' => $r, ':detail' => $detail));
  }
}
?>
