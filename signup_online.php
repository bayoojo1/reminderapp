<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
        if (!file_exists("/var/www/html/user/$u")) {
            mkdir("/var/www/html/user/$u", 0755);
        }
        if (!file_exists("/var/www/html/uploads/$u")){
            mkdir("/var/www/html/uploads/$u", 0755);
        }
        // Copy avatar
        $avatar = "/var/www/html/images/avatardefault.png";
        $avatar2 = "/var/www/html/user/$u/avatardefault.png";
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
        $callerid = trim($c.''.$m);
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
        $callerid = trim($c.''.substr($m, 1));
        $activated = 't';
        //include("php_includes/a2billing_db.php");
        $stmt = $a2billing_db->prepare("INSERT INTO cc_callerid (cid, id_cc_card, activated) VALUES (:cid,:id_cc_card, :activated)");
        $stmt->execute(array(':cid' => $callerid, ':id_cc_card' => $uid_cc_card, ':activated' => $activated));

        //***********A2BILLING DB Update Ends Here ********//

        // Email the user their activation link
        require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require 'vendor/phpmailer/phpmailer/src/SMTP.php';
        require 'vendor/phpmailer/phpmailer/src/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();


        $email_body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>NaatCast Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.naatcast.com"><img src="/images/logo.png" width="36" height="30" alt="NaatCast" style="border:none; float:left;"></a>NaatCast Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$fullName.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://www.naatcast.com/activation/'.$uid.'/'.$u.'/'.$e.'/'.$p_hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';

            $email = 'info@naatcast.com';
            $name = 'NaatCast';

            $mail->Host = 'smtp.mailgun.org';
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'postmaster@mail1.naatcast.com';                 // SMTP username
            $mail->Password = '054ca0cded46010fb0e6a3c2eed6cf88-116e1e4d-9ffaa304';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            $mail->From = $email;
            $mail->FromName = $name;
            $mail->addAddress("$e");     // Add a recipient

            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'NaatCast Account Activation ' . $name;
            $mail->Body    = $email_body;

            if(!$mail->send()) {
                $error_message = 'Message could not be sent.';
                $error_message .= 'Mailer Error: ' . $mail->ErrorInfo;
                exit;
                } else {
                    echo "signup_success";
                }
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
      <div><input name="checkbox_name" id="checkbox_id" type="checkbox"><a href="http://www.naatcast.com/app.php" target="_blank">Terms & Conditions</a></div>
      <br /><br />
      <button id="signupbtn" onclick="signup()">Create Account</button>
      <span id="status"></span>
    </form>
  </div>
</div>
</body>
</html>
