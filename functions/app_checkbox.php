<?php
error_reporting(E_ALL ^ E_WARNING);
include_once("../php_includes/check_login_status.php"); 
$u = $log_username;
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["t"])){
    
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    //include_once ("../php_includes/db_connect.php"); 
    $string = rand(0, 100000000);
    $z = $u."".($string);
    //$words = mysqli_real_escape_string($db_connect, $_POST['t']);
    $words = $_POST['t'];
    $t = str_replace(',', ';', $words); // Text Message
    $date = preg_replace('#[^0-9-]#', '', $_POST['d']); // Schedule
    $time = preg_replace('#[^0-9:]#', '', $_POST['k']); // Schedule
    $s = $date.".".($time);
    //$r = mysqli_real_escape_string($db_connect, $_POST['r']); // Recurrent
    $r = $_POST['r'];
    //$f = mysqli_real_escape_string($db_connect, $_POST['f']); // Broadcast to follower
    $f = preg_replace('#[^a-zA-Z]#', '', $_POST['f']);
    $m = preg_replace('#[^,0-9 ]#', '', $_POST['m']); // Recepients mobile number
    //$sh = mysqli_real_escape_string($db_connect, $_POST['sh']); // Shared on followers feed
    $sh = $_POST['sh'];

    if($t == "" || $s == ""){
              echo "The form submission is missing values.";
        exit();

    } elseif($f == "Yes"){

        include_once ("createCampaign.php");
        $create = array(
            'op' => 'clone',
            'campaign' => 'bayo-lagos-one',
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

                
        $sql = "SELECT countrycode, mobile FROM follows WHERE user2=:logusername";
        $stmt = $db_connect->prepare($sql);
        $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
        $stmt->execute();
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
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
            'attrs' => "text:{$t}"
            ); 
            addCall("http://10.32.0.17:8080/wombat/api/calls/?",$add);
             
        }
     }
        
        $b = date_create($s);
        $a = date_format($b, 'Y-m-d H:i:s');

        //Update the DB with the user options   
        if($r == 'Once' && $sh == 'OnlyMe'){
                $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Once' && $sh == 'Followers'){
                $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Once' && $sh == 'SpecificFollower'){
                $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Daily' && $sh == 'OnlyMe'){
                $stmt = $db_connect->prepare("INSERT INTO daily (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Daily' && $sh == 'Followers'){
                $stmt = $db_connect->prepare("INSERT INTO daily (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Daily' && $sh == 'SpecificFollower'){
                $stmt = $db_connect->prepare("INSERT INTO daily (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Weekly' && $sh == 'OnlyMe'){
                $stmt = $db_connect->prepare("INSERT INTO weekly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Weekly' && $sh == 'Followers'){
                $stmt = $db_connect->prepare("INSERT INTO weekly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Weekly' && $sh == 'SpecificFollower'){
                $stmt = $db_connect->prepare("INSERT INTO weekly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Monthly' && $sh == 'OnlyMe'){
                $stmt = $db_connect->prepare("INSERT INTO monthly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Monthly' && $sh == 'Followers'){
                $stmt = $db_connect->prepare("INSERT INTO monthly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Monthly' && $sh == 'SpecificFollower'){
                $stmt = $db_connect->prepare("INSERT INTO monthly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Yearly' && $sh == 'OnlyMe'){
                $stmt = $db_connect->prepare("INSERT INTO yearly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Yearly' && $sh == 'Followers'){
                $stmt = $db_connect->prepare("INSERT INTO yearly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        } elseif($r == 'Yearly' && $sh == 'SpecificFollower'){
                $stmt = $db_connect->prepare("INSERT INTO yearly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
        }
 
 } else {
    // END FORM DATA ERROR HANDLING
        // Begin to create and start the campaign
        // Create a campaign
        include_once ("createCampaign.php");
        $create = array(
            'op' => 'clone',
            'campaign' => 'bayo-lagos-one',
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
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Once' && $sh == 'Followers'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Once' && $sh == 'SpecificFollower'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Daily' && $sh == 'OnlyMe'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Daily' && $sh == 'Followers'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Daily' && $sh == 'SpecificFollower'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Weekly' && $sh == 'OnlyMe'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Weekly' && $sh == 'Followers'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Weekly' && $sh == 'SpecificFollower'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Monthly' && $sh == 'OnlyMe'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Monthly' && $sh == 'Followers'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Monthly' && $sh == 'SpecificFollower'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Yearly' && $sh == 'OnlyMe'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Yearly' && $sh == 'Followers'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
} elseif($r == 'Yearly' && $sh == 'SpecificFollower'){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
        VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
                $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $t, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $m, ':broadcast' => $sh));
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
            'attrs' => "text:{$t}"
            ); 
            addCall("http://10.32.0.17:8080/wombat/api/calls/?",$add);
             
       }
       
    
        
    }
    exit();

  } 

?>



