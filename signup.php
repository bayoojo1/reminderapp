<?php
include("theCollector.php");
// If user is logged in, header them away
if(isset($_SESSION["email"])){
    header("location: message.php?msg=NO to that weenis");
    exit();
}
?><?php
// Ajax calls this EMAIL CHECK code to execute
if(isset($_POST["email"])){
    include("php_includes/mysqli_connect.php");
    $email = $_POST['email'];
    $sql = "SELECT id FROM users WHERE email=:email LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $email_check = $stmt->rowCount();

    // Get the username part of the email
    $uparts = explode("@", $email);
    $uname = $uparts[0]; // Username
    // Check the database if username already exist
    $sql = "SELECT id FROM users WHERE username=:username LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':username', $uname, PDO::PARAM_STR);
    $stmt->execute();
    $username_check = $stmt->rowCount();

    if (strlen($email) < 3 || strlen($email) > 30) {
        echo '<strong style="color:#F00;">3 - 30 characters please</strong>';
        exit();
    }
    if (is_numeric($email[0])) {
        echo '<strong style="color:#F00;">Email must begin with a letter</strong>';
        exit();
    }
    if ($username_check > 0) {
      echo '<strong style="color:#F00;">"' . $uname . '" already exist, please use another email.</strong>';
      exit();
    }
    if ($email_check < 1) {
        echo '<strong style="color:#009900;">' . $email . ' is OK</strong>';
        exit();
    } else {
        echo '<strong style="color:#F00;">' . $email . ' is taken</strong>';
        exit();
    }
}
?><?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["e"])){
    // CONNECT TO THE DATABASE
    include("php_includes/mysqli_connect.php");
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $e = $_POST['e'];
    $parts = explode("@", $e);
    $u = $parts[0]; // Username
    $fname = preg_replace('#[^a-z]#i', '', $_POST['f']);
    $lname = preg_replace('#[^a-z]#i', '', $_POST['l']);
    $n = $fname." ".($lname);
    $p = $_POST['p']; // Password
    $c = preg_replace('#[^0-9]#', '', $_POST['c']); // Country Code
    $m = preg_replace('#[^,0-9 ]#', '', $_POST['m']); // Mobile Number
    $isdn = trim($c.''.$m);
    // GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
    // DUPLICATE DATA CHECKS FOR MOBILE AND EMAIL
    $sql = "SELECT id FROM users WHERE isdn=:isdn LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':isdn', $isdn, PDO::PARAM_STR);
    $stmt->execute();
    $m_check = $stmt->rowCount();
    // -------------------------------------------
    $sql = "SELECT id FROM users WHERE email=:email LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':email', $e, PDO::PARAM_STR);
    $stmt->execute();
    $e_check = $stmt->rowCount();

    // FORM DATA ERROR HANDLING
    $name = str_split($n);
    foreach($name as $names){
        if(is_numeric($names)){
            echo "Firstname or Lastname can only contain alphabet";
            exit();
    } else if($e == "" || $fname == "" || $lname == "" || $p == "" || $c == "" || $m == ""){
        echo "The form submission is missing values.";
        exit();
    } else if ($e_check > 0){
        echo "That email address is already in use in the system";
        exit();
    } else if ($m_check > 0) {
        echo "The mobile number '$isdn' is already in use in the system";
        exit();
    } else {
    // END FORM DATA ERROR HANDLING
        // Begin Insertion of data into the database
        // Hash the password and apply your own mysterious unique salt
        // $cryptpass = crypt($p);
        // include_once ("php_includes/randStrGen.php");
        $p_hash = password_hash($p, PASSWORD_DEFAULT);
        $f = ucfirst($fname);
        $l = ucfirst($lname);
        $fullName = $f." ".$l;
        // Generate some md5 from string
        $naatcast = "NaatCast";
        $somestr = "0987654321";
        $secret = "You cannot hack this! So stop it. $u";
        $string = trim("$u"."$e"."$f"."$naatcast"."$somestr");
        $str1 = md5 ($string);
        $str2 = md5 ($secret);
        $hash = "$str1"."$str2";

        // Add user info into the database table for the main site table
        $av = 'avatardefault.png';
        $stmt = $db_connect->prepare("INSERT INTO users (username, email, firstname, lastname, fullname, password, countrycode, mobile, isdn, hash, ip, signup, lastlogin, notescheck, avatar)
        VALUES(:username, :email, :firstname, :lastname, :fullname, :password, :countrycode, :mobile, :isdn, :hash, :ip, now(), now(), now(), :avatar)");
        $stmt->execute(array(':username' => $u, ':email' => $e, ':firstname' => $f, ':lastname' => $l, ':fullname' => $fullName, ':password' => $p_hash, ':countrycode' => $c, ':mobile' => $m, ':isdn' => $isdn, ':hash' => $hash, ':ip' => $ip, ':avatar' => $av));
        //$uid = mysqli_insert_id($db_connect);
        $uid = $db_connect->lastInsertId();
        // Establish their row in the useroptions table
        $background = 'original';
        $stmt = $db_connect->prepare("INSERT INTO useroptions (id, username, background) VALUES (:id, :username, :background)");
        $stmt->execute(array(':id' => $uid, ':username' => $u, ':background' => $background));

        // Create directory(folder) to hold each user's files(pics, MP3s, etc.)
        if (!file_exists("user/$u")) {
            mkdir("user/$u", 0755);
        }
        if (!file_exists("uploads/$u")){
            mkdir("uploads/$u", 0755);
        }
        // Copy avatar
        $avatar = "images/avatardefault.png";
        $avatar2 = "user/$u/avatardefault.png";
        if (!copy($avatar, $avatar2)) {
            echo "failed to create avatar.";
        }
        // Insert the user info into date_visit table
        $stmt = $db_connect->prepare("INSERT INTO date_visit (username, latest_visit) VALUES (:username, now())");
        $stmt->execute(array(':username' => $u));

        //*********** UPDATE ASTERISK DB ****************//
        // Update the device table
        $context = 'NaatCast-1';
        $dtmfmode = "rfc2833";
        $fromuser = 'NULL';
        $host = 'dynamic';
        $port = '5060';
        $ipaddr = '';
        $nat = 'force_rport';
        $mailbox = 'NULL';
        $deny = 'NULL';
        $permit = 'NULL';
        $qualify = 'yes';
        $secret = $p;
        $callerid = trim($c).trim(substr($m, 1));
        $directmedia = 'yes';
        $type = 'friend';
        $regseconds = '0';

        include("php_includes/asterisk_db.php");
        $stmt = $asterisk_db->prepare("INSERT INTO SIP (NAME, context, dtmfmode, fromuser, host, port, ipaddr, nat, mailbox, deny, permit, qualify, secret, callerid, directmedia, type, defaultuser, regseconds) VALUES (:NAME, :context, :dtmfmode, :fromuser, :host, :port, :ipaddr, :nat, :mailbox, :deny, :permit, :qualify, :secret, :callerid, :directmedia, :type, :defaultuser, :regseconds)");
        $stmt->execute(array(':NAME' => $callerid, ':context' => $context, ':dtmfmode' => $dtmfmode, ':fromuser' => $fromuser, ':host' => $host, ':port' => $port, ':ipaddr' => $ipaddr, ':nat' => $nat, ':mailbox' => $mailbox, ':deny' => $deny, ':permit' => $permit, ':qualify' => $qualify, ':secret' => $secret, ':callerid' => $callerid, ':directmedia' => $directmedia, ':type' => $type, ':defaultuser' => $callerid, ':regseconds' => $regseconds));

        //***********Asterisk DB Update Ends Here ********//


        //*********** UPDATE A2BILLING DB ****************//
        $u_name = mt_rand(1000000000,9999999999);
        $u_alias1 = mt_rand(1000000000,9999999999);
        $u_alias2 = mt_rand(10000,99999);
        $u_alias = $u_alias1.''.$u_alias2;
        $e_date = date('Y-m-d H:i:s', strtotime("+10 year")); // Expiration date


        // Update the cc_card table
        $firstusedate = '0000-00-00 00:00:00';
        $enableexpire = '0';
        $expiredays = '0';
        $credit = '5.00000';
        $tariff = '1';
        $id_didgroup = '-1';
        $activated = 'f';
        $status = '1';
        $address = '';
        $city = '';
        $state = '';
        $country = 'NGA';
        $fax = '';
        $inuse = '0';
        $simultaccess = '1';
        $currency = 'USD';
        $lastuse = '0000-00-00 00:00:00';
        $nbused = '0';
        $typepaid = '0';
        $creditlimit = '0';
        $voipcall = '0';
        $sip_buddy = '0';
        $iax_buddy = '0';
        $language = 'en';
        $redial = '';
        $runservice = '0';
        $nbservice = '0';
        $id_campaign = '-1';
        $num_trials_done = '0';
        $vat = '0';
        $servicelastrun = '0000-00-00 00:00:00';
        $initialbalance = '0.00000';
        $invoiceday = '1';
        $autorefill = '0';
        $loginkey = '';
        $mac_addr = '00-00-00-00-00-00';
        $id_timezone = '32';
        $tag = '';
        $voicemail_permitted = '0';
        $voicemail_activated = '0';
        $last_notification = '';
        $email_notification = '';
        $notify_email = '';
        $credit_notification = '-1';
        $id_group = '1';
        $company_name = '';
        $company_website = '';
        $vat_rn = '';
        $traffic = '0';
        $traffic_target = '';
        $discount = '0.00';
        $restriction = '0';
        $id_seria = '-1';
        $serial = '';
        $block = '0';
        $lock_pin = '0';
        $lock_date = '';
        $max_concurrent = '1000';

        include("php_includes/a2billing_db.php");
        $stmt = $a2billing_db->prepare("INSERT INTO cc_card (creationdate, firstusedate, expirationdate, enableexpire, expiredays, username, useralias, uipass, credit, tariff, id_didgroup, activated, status, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, inuse, simultaccess, currency, lastuse, nbused, typepaid, creditlimit, voipcall, sip_buddy, iax_buddy, language, redial, runservice, nbservice, id_campaign, num_trials_done, vat, servicelastrun, initialbalance, invoiceday, autorefill, loginkey, mac_addr, id_timezone, tag, voicemail_permitted, voicemail_activated, last_notification, email_notification, notify_email, credit_notification, id_group, company_name, company_website, vat_rn, traffic, traffic_target, discount, restriction, id_seria, serial, block, lock_pin, lock_date, max_concurrent)
        VALUES (now(), :firstusedate, :expirationdate, :enableexpire, :expiredays, :username, :useralias, :uipass, :credit, :tariff, :id_didgroup, :activated, :status, :lastname, :firstname, :address, :city, :state, :country, :zipcode, :phone, :email, :fax, :inuse, :simultaccess, :currency, :lastuse, :nbused, :typepaid, :creditlimit, :voipcall, :sip_buddy, :iax_buddy, :language, :redial, :runservice, :nbservice, :id_campaign, :num_trials_done, :vat, :servicelastrun, :initialbalance, :invoiceday, :autorefill, :loginkey, :mac_addr, :id_timezone, :tag, :voicemail_permitted, :voicemail_activated, :last_notification, :email_notification, :notify_email, :credit_notification, :id_group, :company_name, :company_website, :vat_rn, :traffic, :traffic_target, :discount, :restriction, :id_seria, :serial, :block, :lock_pin, :lock_date, :max_concurrent)");

        $stmt->execute(array(':firstusedate' => $firstusedate, ':expirationdate' => $e_date, ':enableexpire' => $enableexpire, ':expiredays' => $expiredays, ':username' => $u_name, ':useralias' => $u_alias, ':uipass' => $p_hash, ':credit' => $credit, ':tariff' => $tariff, ':id_didgroup' => $id_didgroup, ':activated' => $activated, ':status' => $status, ':lastname' => $l, ':firstname' => $f, ':address' => $address, ':city' => $city, ':state' => $state, ':country' => $country, ':zipcode' => $c, ':phone' => $m, ':email' => $e, ':fax' => $fax, ':inuse' => $inuse, ':simultaccess' => $simultaccess, ':currency' => $currency, ':lastuse' => $lastuse, ':nbused' => $nbused, ':typepaid' => $typepaid, ':creditlimit' => $creditlimit, ':voipcall' => $voipcall, ':sip_buddy' => $sip_buddy, ':iax_buddy' => $iax_buddy, ':language' => $language, ':redial' => $redial, ':runservice' => $runservice, ':nbservice' => $nbservice, ':id_campaign' => $id_campaign, ':num_trials_done' => $num_trials_done, ':vat' => $vat, ':servicelastrun' => $servicelastrun, ':initialbalance' => $initialbalance, ':invoiceday' => $invoiceday, ':autorefill' => $autorefill, ':loginkey' => $loginkey, ':mac_addr' => $mac_addr, ':id_timezone' => $id_timezone, ':tag' => $tag, ':voicemail_permitted' => $voicemail_permitted, ':voicemail_activated' => $voicemail_activated, ':last_notification' => $last_notification, ':email_notification' => $email_notification, ':notify_email' => $notify_email, ':credit_notification' => $credit_notification, ':id_group' => $id_group, ':company_name' => $company_name, ':company_website' => $company_website, ':vat_rn' => $vat_rn, ':traffic' => $traffic, ':traffic_target' => $traffic_target, ':discount' => $discount, ':restriction' => $restriction, ':id_seria' => $id_seria, ':serial' => $serial, ':block' => $block, ':lock_pin' => $lock_pin, ':lock_date' => $lock_date, ':max_concurrent' => $max_concurrent));

        $uid_cc_card = $a2billing_db->lastInsertId();

        // Establish their row in the cc_callerid table
        $callerid = trim($c).trim(substr($m, 1));
        $activated = 't';
        //include("php_includes/a2billing_db.php");
        $stmt = $a2billing_db->prepare("INSERT INTO cc_callerid (cid, id_cc_card, activated) VALUES (:cid,:id_cc_card, :activated)");
        $stmt->execute(array(':cid' => $callerid, ':id_cc_card' => $uid_cc_card, ':activated' => $activated));

        //***********A2BILLING DB Update Ends Here ********//

        // Email the user their activation link
        $email_body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>NaatCast Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://10.32.0.17"><img src="/images/logo.png" width="36" height="30" alt="NaatCast" style="border:none; float:left;"></a>NaatCast Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$fullName.',<br /><br />Click the link below to activate your account before the next 24 hours. The link would become unusable after then.:<br /><br /><a href="http://10.32.0.17/activation.php?id='.$uid.'&u='.$u.'&hash='.$hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';

          $ch = curl_init();

          curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/mail1.naatcast.com/messages");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_USERPWD, "api" . ":" . "key-833e41bd255e7d164bbfe48f981bdf6e");
          $post = array(
              'from' => 'No Reply <no-reply@naatcast.com>',
              'to' => $e,
              'subject' => 'NaatCast Account Activation',
              'html' => $email_body,
          );
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

          $result = curl_exec($ch);
          if($result === false)
          {
              echo "Error Number:".curl_errno($ch)."<br>";
              echo "Error String:".curl_error($ch);
          } else {
            echo "signup_success";
          }
          curl_close($ch);
    }
    exit();
 }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>NaatCast - Sign Up</title>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="stylesheet" href="style/normalize.css">
<link rel="stylesheet" href="style/style.css">
<link href="style/jquery-gallery.css" rel="stylesheet">
<link rel="stylesheet" href="style/responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
</head>
<body class="loginpage">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div>
<div id="PageMiddle" class="PageMiddle">
  <div id="pageMiddleForm" class="pageMiddleForm" style="margin-top: 50px;">
  <div style="height:50px;"></div>
    <form name="signupform" id="signupform" onsubmit="return false;">
      <div class="iconsignup">
      <i class="fas fa-user-circle loginIcon"></i>
      <input id="firstname" type="text" placeholder="Firstname" onkeyup="restrictsignup('firstname')" maxlength="16">
      </div>
      <div class="iconsignup">
      <i class="far fa-user-circle loginIcon"></i>
      <input id="lastname" type="text" placeholder="Lastname" onkeyup="restrictsignup('lastname')" maxlength="16">
      </div>
      <div class="iconsignup">
      <i class="fas fa-envelope loginIcon"></i>
      <input id="email" type="text" placeholder="Email" onblur="checkemail()" onfocus="emptyElementsignup('status')" onkeyup="restrictsignup('email')" maxlength="88">
      <span id="emailstatus"></span>
      </div>
      <div class="iconsignup">
      <i class="fas fa-key loginIcon"></i>
      <input id="pass1" type="password" placeholder="Create Password" onfocus="emptyElementsignup('status')" maxlength="100">
      </div>
      <div class="iconsignup">
      <i class="fas fa-key loginIcon"></i>
      <input id="pass2" type="password" placeholder="Confirm Password" onfocus="emptyElementsignup('status')" maxlength="100">
      </div>
      <div class="iconsignup">
      <i class="fas fa-globe loginIcon"></i>
      <select id="country" class="ccode" onfocus="emptyElementsignup('status')" maxlength="100" style="margin-left:-5px; height:40px;">
        <?php include_once("php_includes/template_countrycode.php"); ?>
      </select>
      </div>
      <div class="iconsignup">
      <i class="fas fa-mobile-alt loginIcon"></i>
      <input id="mobile" type="text" placeholder="Mobile" onfocus="emptyElementsignup('status')" onkeyup="restrictsignup('mobile')" maxlength="100">
      </div>
      <!--<div class="iconsignup">
      <i class="fas fa-at loginIcon"></i>
      <input id="website" type="text" maxlength="100" placeholder="e.g www.example.com (Optional)">
    </div>-->
      <div><input name="checkbox_name" id="checkbox_id" type="checkbox"><a style="color:white;" href="http://localhost:8080/reminderapp/terms.html" target="_blank">Terms & Conditions</a></div>
      <br />
      <button id="signupbtn" onclick="signup()">Create Account</button>
      <span class="signupstatus" id="status" style="height:20px;"></span>
    </form>
    <div style="height:30px;"></div>
  </div>
</div>
<ul class="gallery-slideshow">
  <li><img src="images/banners/p-campaign.jpeg"/></li>
  <li><img src="images/banners/prevention1.jpeg"/></li>
  <li><img src="images/banners/p-assistance.jpeg"/></li>
  <li><img src="images/banners/api.jpeg"/></li>
  <li><img src="images/banners/contentprovider.jpeg"/></li>
  <li><img src="images/banners/p-announcement.jpg"/></li>
  <li><img src="images/banners/telemarket.jpeg"/></li>
  <li><img src="images/banners/grassroot1.jpeg"/></li>
  <li><img src="images/banners/dailyreminder.jpeg"/></li>
  <li><img src="images/banners/welcome.jpeg"/></li>
</ul>
    <script src="js/main.js"></script>
    <script src="js/ajax.original.js"></script>
    <script src="js/jquery-gallery.js"></script>
    <script src="js/banners.js"></script>
    <script src="js/functions.js"></script>
</body>
</html>
