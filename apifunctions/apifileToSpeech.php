<?php
function apifileToSpeech() {
        include("./php_includes/check_login_status.php");
        include("./php_includes/wombat_db.php");
        include("./php_includes/mysqli_connect.php");
        include("./php_includes/a2billing_db.php");
        //include("check_balance.php");
        //$u = $log_username;
        if ( isset($_POST['submit']) ) {
          $apiname = preg_replace('#[^a-z0-9]#i', '', $_POST['apiname']);
          $apitoken = preg_replace('#[^a-z0-9]#i', '', $_POST['apitoken']);
          $recurrent = preg_replace('#[^a-z0-9]#i', '', $_POST['recurrent']);
          $mobile = preg_replace('#[^0-9,+]#i', '', $_POST['mobile']);
          $schedule = preg_replace('#[^0-9-:.]#i', '', $_POST['schedule']);
        }

        // Do some checking
        if(empty($apiname) || empty($apitoken) || empty($recurrent) || empty($mobile) || empty($schedule)) {
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
        $u = $log_username.''.$apiname;
        $string = rand(0, 100000000);
        $z = $u."".($string);
        $p_remote = "/home/uploads/$asterisk_fn"; // This is the path to remote audio file on the asterisk server
        $p_remote_mp3 = "/home/uploads/$filename"; // This is the path to remote .mp3 file on asterisk server
        
        // Start the process of sending the campaign.
        if($p_remote == "" || $p_remote_mp3 == ""){
                echo "The form submission is missing values.";
                exit();
        }
        // Create the campaign
        include_once ("./functions/createCampaign.php");
        if($recurrent == "Once") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'once-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);

        } else if ($recurrent == "Daily" || $recurrent == "Weekly" || $recurrent == "Monthly" || $recurrent == "Yearly") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'recurrent-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($recurrent == "DailyRound") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyRound-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($recurrent == "DailyDaytime") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyDaytime-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($recurrent == "DailyNight") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyNight-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($recurrent == "DailyWeekDaytime") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekDaytime-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($recurrent == "DailyWeekDayNight") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekDayNight-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($recurrent == "DailyWeekendDay") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekendDay-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        } else if ($recurrent == "DailyWeekendNight") {
                $create = array(
                        'op' => 'clone',
                        'campaign' => 'dailyWeekendNight-file',
                        'newcampaign' => $z
                        );
                        createCampaign("http://10.32.1.200:8080/wombat/api/campaigns/?",$create);
        }

        // Update Wombat DB
        $m = trim($cc.''.substr($login_mobile, 1));
        $sql = "UPDATE campaigns SET dial_clid=:dial_clid, dial_pres=:dial_pres, agentClid=:agentClid, email_addresses=:email_addresses WHERE name=:name";
        $stmt = $wombat_db->prepare($sql);
        $stmt->bindParam(':dial_clid', $m, PDO::PARAM_STR);
        $stmt->bindParam(':dial_pres', $m, PDO::PARAM_STR);
        $stmt->bindParam(':agentClid', $m, PDO::PARAM_STR);
        $stmt->bindParam(':email_addresses', $email, PDO::PARAM_STR);
        $stmt->bindParam(':name', $z, PDO::PARAM_STR);
        $stmt->execute();

        // Start a campaign
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
        // Check if the user have followers

        $array = explode(',', $mobile); //split mobile numbers into array seperated by ', '
        if($fileExt == 'wav') {
                foreach($array as $v) //loop over the numbers
                {
                // Add call to a campaign
                include_once ("./functions/addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => "{$v}",
                'schedule' => $schedule,
                'attrs' => "filepath:{$p_remote}"
                );
                addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);

                }
        } else if($fileExt == 'mp3') {
                foreach($array as $v) //loop over the numbers
                {
                // Add call to a campaign
                include_once ("./functions/addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => "{$v}",
                'schedule' => $s,
                'attrs' => "filepath:{$p_remote_mp3}"
                );
                addCall("http://10.32.1.200:8080/wombat/api/calls/?",$add);

                }
        } else {
                exit();
        }
    }
?>
