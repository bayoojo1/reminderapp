<?php
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
    if (strlen($email) < 3 || strlen($email) > 30) {
        echo '<strong style="color:#F00;">3 - 30 characters please</strong>';
        exit();
    }
    if (is_numeric($email[0])) {
        echo '<strong style="color:#F00;">Email must begin with a letter</strong>';
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
    // GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
    // DUPLICATE DATA CHECKS FOR MOBILE AND EMAIL
    $sql = "SELECT id FROM users WHERE mobile=:mobile LIMIT 1";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindParam(':mobile', $m, PDO::PARAM_STR);
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
        echo "The mobile number is already in use in the system";
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
        // Add user info into the database table for the main site table
        $av = 'avatardefault.png';
        $stmt = $db_connect->prepare("INSERT INTO users (username, email, firstname, lastname, fullname, password, countrycode, mobile, ip, signup, lastlogin, notescheck, avatar)
        VALUES(:username, :email, :firstname, :lastname, :fullname, :password, :countrycode, :mobile, :ip, now(), now(), now(), :avatar)");
        $stmt->execute(array(':username' => $u, ':email' => $e, ':firstname' => $f, ':lastname' => $l, ':fullname' => $fullName, ':password' => $p_hash, ':countrycode' => $c, ':mobile' => $m, ':ip' => $ip, ':avatar' => $av));
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
        // Update the users table
        $password = '';
        $voicemail = 'novm';
        $ringtimer = 0;
        $noanswer = '';
        $recording = '';
        $outboundcid = "$fullName<$m>";
        $sipname = '';
        $noanswer_cid = '';
        $busy_cid = '';
        $chanunavail_cid = '';
        $noanswer_dest = '';
        $busy_dest = '';
        $chanunavail_dest = '';
        $mohclass = 'default';
        include("php_includes/asterisk_db.php");
        $stmt = $asterisk_db->prepare("INSERT INTO users (extension, password, name, voicemail, ringtimer, noanswer, recording, outboundcid, sipname, noanswer_cid, busy_cid, chanunavail_cid, noanswer_dest, busy_dest, chanunavail_dest, mohclass) 
        VALUES (:extension, :password, :name, :voicemail, :ringtimer, :noanswer, :recording, :outboundcid, :sipname, :noanswer_cid, :busy_cid, :chanunavail_cid, :noanswer_dest, :busy_dest, :chanunavail_dest, :mohclass)");
        $stmt->execute(array(':extension' => $m, ':password' => $password, ':name' => $fullName, ':voicemail' => $voicemail, ':ringtimer' => $ringtimer, ':noanswer' => $noanswer, ':recording' => $recording, ':outboundcid' => $outboundcid, ':sipname' => $sipname, ':noanswer_cid' => $noanswer_cid, ':busy_cid' => $busy_cid, ':chanunavail_cid' => $chanunavail_cid, ':noanswer_dest' => $noanswer_dest, ':busy_dest' => $busy_dest, ':chanunavail_dest' => $chanunavail_dest, ':mohclass' => $mohclass));


        // Update the device table
        $tech = 'sip';
        $dial = "SIP/$m";
        $devicetype = 'fixed';
        $emergency_cid = '';
        include("php_includes/asterisk_db.php");
        $stmt = $asterisk_db->prepare("INSERT INTO devices (id, tech, dial, devicetype, user, description, emergency_cid) VALUES (:id, :tech, :dial, :devicetype, :user, :description, :emergency_cid)");
        $stmt->execute(array(':id' => $m, ':tech' => $tech, ':dial' => $dial, ':devicetype' => $devicetype, ':user' => $m, ':description' => $fullName, ':emergency_cid' => $emergency_cid));

        // Update the sip table
        include("php_includes/asterisk_db.php");
        $stmt = $asterisk_db->prepare("INSERT INTO sip (id, keyword, data, flags) VALUES (:id, :keyword, :data, :flags)");   
        $data = array(
            array(':id' => $m, ':keyword' => 'secret', ':data' => $p, ':flags' => 2),
            array(':id' => $m, ':keyword' => 'dtmfmode', ':data' => 'rfc2833', ':flags' => 3),
            array(':id' => $m, ':keyword' => 'canreinvite', ':data' => 'no', ':flags' => 4),
            array(':id' => $m, ':keyword' => 'context', ':data' => 'from-internal', ':flags' => 5),
            array(':id' => $m, ':keyword' => 'host', ':data' => 'dynamic', ':flags' => 6),
            array(':id' => $m, ':keyword' => 'trustrpid', ':data' => 'yes', ':flags' => 7),
            array(':id' => $m, ':keyword' => 'sendrpid', ':data' => 'yes', ':flags' => 8),
            array(':id' => $m, ':keyword' => 'type', ':data' => 'friend', ':flags' => 9),
            array(':id' => $m, ':keyword' => 'nat', ':data' => 'yes', ':flags' => 10),
            array(':id' => $m, ':keyword' => 'port', ':data' => '5060', ':flags' => 11),
            array(':id' => $m, ':keyword' => 'qualify', ':data' => 'yes', ':flags' => 12),
            array(':id' => $m, ':keyword' => 'qualifyfreq', ':data' => '60', ':flags' => 13),
            array(':id' => $m, ':keyword' => 'transport', ':data' => 'udp', ':flags' => 14),
            array(':id' => $m, ':keyword' => 'avpf', ':data' => 'no', ':flags' => 15),
            array(':id' => $m, ':keyword' => 'icesupport', ':data' => 'no', ':flags' => 16),
            array(':id' => $m, ':keyword' => 'encryption', ':data' => 'yes', ':flags' => 17 ),
            array(':id' => $m, ':keyword' => 'callgroup', ':data' => '', ':flags' => 18),
            array(':id' => $m, ':keyword' => 'pickupgroup', ':data' => '', ':flags' => 19),
            array(':id' => $m, ':keyword' => 'disallow', ':data' => '', ':flags' => 20),
            array(':id' => $m, ':keyword' => 'allow', ':data' => '', ':flags' => 21),
            array(':id' => $m, ':keyword' => 'dial', ':data' => "SIP/$m", ':flags' => 22),
            array(':id' => $m, ':keyword' => 'mailbox', ':data' => "$m@device", ':flags' => 23),
            array(':id' => $m, ':keyword' => 'deny', ':data' => '0.0.0.0/0.0.0.0', ':flags' => 24),
            array(':id' => $m, ':keyword' => 'permit', ':data' => '0.0.0.0/0.0.0.0', ':flags' => 25),
            array(':id' => $m, ':keyword' => 'account', ':data' => $m, ':flags' => 26),
            array(':id' => $m, ':keyword' => 'callerid', ':data' => "device <$m>", ':flags' => 27)
        );
        foreach($data as $record) {
            $stmt->execute($record);
        }

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
        $callerid = $c.''.$m;
        $activated = 't';
        //include("php_includes/a2billing_db.php");
        $stmt = $a2billing_db->prepare("INSERT INTO cc_callerid (cid, id_cc_card, activated) VALUES (:cid,:id_cc_card, :activated)");
        $stmt->execute(array(':cid' => $callerid, ':id_cc_card' => $uid_cc_card, ':activated' => $activated));

        //***********A2BILLING DB Update Ends Here ********//

        // Email the user their activation link

        $to = "$e";
        $from = "naatcast@naatcast.com";
        $subject = 'NaatCast Account Activation';
        $message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>NaatCast Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.yoursitename.com"><img src="/images/logo.png" width="36" height="30" alt="NaatCast" style="border:none; float:left;"></a>NaatCast Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$fullName.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http:localhost:8080/learning/socialnetwork/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';
        $headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
        mail($to, $subject, $message, $headers);
        echo "signup_success";
        exit();
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
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/normalize.css">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/main.js"></script>
<script src="js/ajax.original.js"></script>

<script>
function restrict(elem){
    var tf = _(elem);
    var rx = new RegExp;
    if(elem == "email"){
        rx = /[' "]/gi;
    } else if(elem == "firstname"){
        rx = /[^a-z]/gi;
    } else if(elem == "lastname"){
        rx = /[^a-z]/gi;
    } else if(elem == "mobile"){
        rx = /[^0-9,]/gi;
    }
    tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
    _(x).innerHTML = "";
}
function checkemail(){
    var e = _("email").value;
    if(e != ""){
        _("emailstatus").innerHTML = 'checking ...';
        var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
            if(ajaxReturn(ajax) == true) {
                _("emailstatus").innerHTML = ajax.responseText;
            }
        }
        ajax.send("email="+e);
    }
}
function signup(){
    var e = _("email").value;
    var f = _("firstname").value;
    var l = _("lastname").value;
    var p1 = _("pass1").value;
    var p2 = _("pass2").value;
    var c = _("country").value;
    var m = _("mobile").value;
    var w = _("website").value;
    var status = _("status");
    if(e == "" || f == "" || l == "" || p1 == "" || p2 == "" || c == "" || m == ""){
        status.innerHTML = "Fill out all of the form data";
    } else if(p1 != p2){
        status.innerHTML = "Your password fields do not match";
    } else if(!document.getElementById("checkbox_id").checked){
        status.innerHTML = "You must accept the terms and condition to register";
    } else {
        _("signupbtn").style.display = "none";
        status.innerHTML = 'Please wait...<img src="images/loading2.gif" height="30", width="30">';
        var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
            if(ajaxReturn(ajax) == true) {
                if(ajax.responseText != "signup_success"){
                    status.innerHTML = ajax.responseText;
                    _("signupbtn").style.display = "block";
                } else {
                    window.scrollTo(0,0);
                    _("signupform").innerHTML = "OK "+f+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
                }
            }
        }
        ajax.send("e="+e+"&f="+f+"&l="+l+"&p="+p1+"&c="+c+"&m="+m+"&w="+w);
    }
}

</script>
</head>
<body background="images/signup-bkg.png" style="background-repeat:no-repeat; background-size:cover;">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div>
<div id="PageMiddle">
  <div id="pageMiddleForm" style="margin-top: 50px;">
    <h3 style="color: white;">Sign Up Here</h3>
    <form name="signupform" id="signupform" onsubmit="return false;">
      <div>First Name: </div>
      <input id="firstname" type="text" onkeyup="restrict('firstname')" maxlength="16">
      <div>Last Name: </div>
      <input id="lastname" type="text" onkeyup="restrict('lastname')" maxlength="16">
      <div>Email Address:</div>
      <input id="email" type="text" onblur="checkemail()" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
      <span id="emailstatus"></span>
      <div>Create Password:</div>
      <input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="100">
      <div>Confirm Password:</div>
      <input id="pass2" type="password" onfocus="emptyElement('status')" maxlength="100">
      <div>Country Code:</div>
      <select id="country" onfocus="emptyElement('status')" style="width: 20%;">
        <?php include_once("php_includes/template_countrycode.php"); ?>
      </select>
      Mobile:
      <input id="mobile" type="text" onfocus="emptyElemet('status')" onkeyup="restrict('mobile')" style="width:22%;">
      <div>Website(Optional):</div>
      <input id="website" type="text" style=width:50%; placeholder="e.g www.example.com">
      <div><input name="checkbox_name" id="checkbox_id" type="checkbox"><a href="http://localhost:8080/reminderapp/app.php" target="_blank">Terms & Conditions</a></div>
      <br /><br />
      <button id="signupbtn" onclick="signup()">Create Account</button>
      <span id="status"></span>
    </form>
  </div>
</div>
</body>
</html>
