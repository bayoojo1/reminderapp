<?php
function fileToSpeech() {
        include_once("check_login_status.php");
        $u = $log_username;
    // Access the $_FILES global variable for this specific file being uploaded
        // and create local PHP variables from the $_FILES array of information
        
        $fileName = $_FILES["audio"]["name"]; // The file name
        $fileTmpLoc = $_FILES["audio"]["tmp_name"]; // File in the PHP tmp folder
        $fileType = $_FILES["audio"]["type"]; // The type of file
        $fileSize = $_FILES["audio"]["size"]; // File size in bytes
        $fileErrorMsg = $_FILES["audio"]["error"]; //0 for false....and 1 for true
        $kaboom = explode(".", $fileName);
        $fileExt = end($kaboom);
        $db_file_name = date("DMjGisY")."".rand(1000000,9999999).".".$fileExt; // WedFeb272120452013RAND.jpg
        // Start PHP Image Upload Error Handling.....
        if(!$fileTmpLoc) { // if file not chosen
                echo "ERROR: Please browse for a file before clicking the upload button";
                exit();
        } else if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
                echo "ERROR: Your file was larger than 5 Megabytes in size.";
                unlink($fileTmpLoc); // Remove the upload file from the PHP temp folder
                exit();
        } else if(!preg_match("/\.(mp3|wav)$/i", $fileName)) {
                // This condition is only if you wish to allow uploading of specific file type
                echo "ERROR: Your file was not .mp3, .wav";
                unlink($fileTmpLoc);
                exit();
        } else if($fileErrorMsg == 1) { // if file upload error key is equal to 1
                echo "ERROR: An error occured while processing the file. Try again.";
                exit();
        } 
        // END PHP image Upload Error Handling...........
        // Place it into your "uploads" folder now using the move_uploaded_file() function
        //$moveResult = move_uploaded_file($fileTmpLoc, "/home/uploads/$log_username/$db_file_name");
        $moveResult = move_uploaded_file($fileTmpLoc, "/wamp64/www/reminderapp/uploads/$log_username/$db_file_name");

        if ($moveResult == true) {
        // Prepare remote upload data
        $uploadRequest = array(
            'fileName' => $db_file_name,
            'fileData' => base64_encode(file_get_contents($db_file_name))
        );

        // Execute remote upload
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://10.32.0.17/receiver.php');
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $uploadRequest);
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;

        // Now delete local temp file
        unlink($fileTmpLoc);

        } else if($moveResult != true) {
                echo "ERROR: File not uploaded. Try again.";
                unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
                exit();
        }
        //unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder


?><?php
        // GATHER THE POSTED DATA INTO LOCAL VARIABLES 
        $string = rand(0, 100000000);
        $z = $u."".($string);
        $p = "/home/uploads/$db_file_name";
        //$p = "/home/uploads/$log_username/$db_file_name"; // File path
        $date = preg_replace('#[^0-9-:]#', '', $_POST['date']); // Schedule
        $s = substr_replace($date, ".", 10, 0);
        $r = mysqli_real_escape_string($db_connect, $_POST['recurrent']); // Recurrent
        $f = preg_replace('#[^a-zA-Z]#', '', $_POST['followers']); // Broadcast to followers
        $m = preg_replace('#[^,0-9 ]#', '', $_POST['mobile']); // Recepients mobile number
        $sh = mysqli_real_escape_string($db_connect, $_POST['shared']); // Shared on followers feed

        if($p == "" || $s == ""){
                echo "The form submission is missing values.";
                exit();

        }elseif($f == "Yes"){

                include_once ("createCampaign.php");
                $create = array(
                'op' => 'clone',
                'campaign' => 'bayo-lagos-two',
                'newcampaign' => $z
                );
                createCampaign("http://10.32.0.17:8080/wombat/api/campaigns/?",$create);
                // Start a campaign
                include_once ("startCampaign.php");
                $start = array(
                'op' => 'start',
                'campaign' => $z
                );
                startCampaign("http://10.32.0.17:8080/wombat/api/campaigns/?",$start);

                        
                $sql = "SELECT countrycode, mobile FROM follows WHERE user2='$log_username'";
                $query = mysqli_query($db_connect, $sql);
                while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
                        $cc = $row["countrycode"];
                        $mb = $row["mobile"];

                usleep(500000);

                $array = explode(',', $mb); //split mobile numbers into array seperated by ', '
                foreach($array as $v) //loop over the numbers
                {
                // Add call to a campaign
                include_once ("addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => $v,
                'schedule' => $s,
                'attrs' => "filepath:{$p}"
                ); 
                addCall("http://10.32.0.17:8080/wombat/api/calls/?",$add);
                
                }
        }
                
                $b = date_create($s);
                $a = date_format($b, 'Y-m-d H:i:s');

                //Update the DB with the user options   
                if($r == 'Once' && $sh == 'OnlyMe'){
                $sql = "INSERT INTO once (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Once' && $sh == 'Followers'){
                $sql = "INSERT INTO once (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Once' && $sh == 'SpecificFollower'){
                $sql = "INSERT INTO once (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Daily' && $sh == 'OnlyMe'){
                $sql = "INSERT INTO daily (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Daily' && $sh == 'Followers'){
                $sql = "INSERT INTO daily (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Daily' && $sh == 'SpecificFollower'){
                $sql = "INSERT INTO daily (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Weekly' && $sh == 'OnlyMe'){
                $sql = "INSERT INTO weekly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Weekly' && $sh == 'Followers'){
                $sql = "INSERT INTO weekly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Weekly' && $sh == 'SpecificFollower'){
                $sql = "INSERT INTO weekly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Monthly' && $sh == 'OnlyMe'){
                $sql = "INSERT INTO monthly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Monthly' && $sh == 'Followers'){
                $sql = "INSERT INTO monthly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Monthly' && $sh == 'SpecificFollower'){
                $sql = "INSERT INTO monthly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Yearly' && $sh == 'OnlyMe'){
                $sql = "INSERT INTO yearly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Yearly' && $sh == 'Followers'){
                $sql = "INSERT INTO yearly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                } elseif($r == 'Yearly' && $sh == 'SpecificFollower'){
                $sql = "INSERT INTO yearly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$v',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
                }
        
        } else {
        // END FORM DATA ERROR HANDLING
                // Begin to create and start the campaign
                // Create a campaign
                include_once ("createCampaign.php");
                $create = array(
                'op' => 'clone',
                'campaign' => 'bayo-lagos-two',
                'newcampaign' => $z
                );
                createCampaign("http://10.32.0.17:8080/wombat/api/campaigns/?",$create);
                // Start a campaign
                include_once ("startCampaign.php");
                $start = array(
                'op' => 'start',
                'campaign' => $z
                );
                startCampaign("http://10.32.0.17:8080/wombat/api/campaigns/?",$start);
                // Add call to a campaign
        
                $b = date_create($s);
                $a = date_format($b, 'Y-m-d H:i:s');
                
        if($r == 'Once' && $sh == 'OnlyMe'){
                $sql = "INSERT INTO once (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Once' && $sh == 'Followers'){
                $sql = "INSERT INTO once (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Once' && $sh == 'SpecificFollower'){
                $sql = "INSERT INTO once (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Daily' && $sh == 'OnlyMe'){
                $sql = "INSERT INTO daily (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Daily' && $sh == 'Followers'){
                $sql = "INSERT INTO daily (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Daily' && $sh == 'SpecificFollower'){
                $sql = "INSERT INTO daily (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Weekly' && $sh == 'OnlyMe'){
                $sql = "INSERT INTO weekly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Weekly' && $sh == 'Followers'){
                $sql = "INSERT INTO weekly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Weekly' && $sh == 'SpecificFollower'){
                $sql = "INSERT INTO weekly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Monthly' && $sh == 'OnlyMe'){
                $sql = "INSERT INTO monthly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Monthly' && $sh == 'Followers'){
                $sql = "INSERT INTO monthly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Monthly' && $sh == 'SpecificFollower'){
                $sql = "INSERT INTO monthly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Yearly' && $sh == 'OnlyMe'){
                $sql = "INSERT INTO yearly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Yearly' && $sh == 'Followers'){
                $sql = "INSERT INTO yearly (username, username1, message, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        } elseif($r == 'Yearly' && $sh == 'SpecificFollower'){
                $sql = "INSERT INTO yearly (username, username1, audio, schedule, accepted, tag, mobile, postdate, broadcast)
                        VALUES('$z','$u','$p','$s','$a','$r','$m',now(),'$sh')";
                $query = mysqli_query($db_connect, $sql);
        }


        ?><?php

        usleep(500000);

                $array = explode(',', $m); //split mobile numbers into array seperated by ', '
                foreach($array as $n) //loop over the numbers
                {
                // Add call to a campaign
                include_once ("addCall.php");
                $add = array(
                'op' => 'addcall',
                'campaign' => $z,
                'number' => $n,
                'schedule' => $s,
                'attrs' => "filepath:{$p}"
                ); 
                addCall("http://10.32.0.17:8080/wombat/api/calls/?",$add);
                
        }
        
        
                
    }
        //exit();
}
?>