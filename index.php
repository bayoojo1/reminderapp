<?php
include("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");
include("theCollector.php");
// If user is already logged in, header that weenis away
if($user_ok == true){
    header("location: /".$_SESSION["username"]);
    exit();
}
?><?php
// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if(isset($_POST["e"])){
    // CONNECT TO THE DATABASE
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
    $e = $_POST['e'];
    $p = ($_POST['p']);
    // GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
    // FORM DATA ERROR HANDLING
    if($e == "" || $p == ""){
        echo "login_failed";
        exit();
    } else {
    // END FORM DATA ERROR HANDLING
    include("php_includes/mysqli_connect.php");
    $sql = "SELECT id, username, password FROM users WHERE email=:email AND activated='1' LIMIT 1";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindParam(':email', $e, PDO::PARAM_STR);
            $stmt->execute();

            foreach($stmt->fetchAll() as $row) {
                $db_id = $row['0'];
                $db_username = $row['1'];
                $db_pass_str = $row['2'];

            }
            $db_connect = null;

        if(!password_verify($p, $db_pass_str)){
            echo "login_failed";
            exit();
        } else {
            // CREATE THEIR SESSIONS AND COOKIES
            $_SESSION['userid'] = $db_id;
            $_SESSION['username'] = $db_username;
            //$_SESSION['email'] = $db_email;
            $_SESSION['password'] = $db_pass_str;
            setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
            setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
            //setcookie("mail", $db_email, strtotime( '+30 days' ), "/", "", "", TRUE);
            setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE);
            // UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
            include("php_includes/mysqli_connect.php");
            $sql = "UPDATE users SET ip=:ip, lastlogin=now() WHERE username=:username LIMIT 1";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindParam(':username', $db_username, PDO::PARAM_STR);
            $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
            $stmt->execute();
            // Get the last visit and update date_visit table
            $sql = "SELECT latest_visit FROM date_visit WHERE username=:username LIMIT 1";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindParam(':username', $db_username, PDO::PARAM_STR);
            $stmt->execute();
            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
              $last_visit = $row['latest_visit'];
            }
            // Update the info in date_visit table
            $sql = "UPDATE date_visit SET last_visited='$last_visit', latest_visit=now() WHERE username=:username LIMIT 1";
            $stmt = $db_connect->prepare($sql);
            $stmt->bindParam(':username', $db_username, PDO::PARAM_STR);
            $stmt->execute();

            echo $db_username;
            exit();
            $db_connect = null;
        }
    }
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>NaatCast - Log In</title>
<link rel="stylesheet" href="style/normalize.css">
<link href="https://fonts.googleapis.com/css?family=Changa+One:400,400i|Open+Sans:400,400i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<link href="style/jquery-gallery.css" rel="stylesheet">
<link rel="stylesheet" href="style/responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--<script src="js/jquery.js"></script>-->
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script src="js/main.js"></script>
<script src="js/jquery-gallery.js"></script>
<script src="js/banners.js"></script>
<script src="js/functions.js"></script>
</head>
<body class="loginpage">
  <div id="header">
  <?php include_once("template_pageTop.php"); ?>
  </div>
<div id="PageMiddle">
<div id="loginformMiddle" style="margin-top: 50px;">
    <div style="height:50px;"></div>
    <!-- LOGIN FORM -->
    <form id="loginform" onsubmit="return false;">
    <div class="loginenvelope">
    <i class="fas fa-envelope loginIcon"></i>
    <input type="text" id="email" placeholder="Email" onfocus="emptyElement('status')">
    </div>
    <br />
    <div class="loginkey">
    <i class="fas fa-key loginIcon"></i>
    <input type="password" id="password" placeholder="Password" onfocus="emptyElement('status')">
    </div>
    <br />
    <button id="loginbtn" onclick="login()">Login</button>
    <p class="loginstatus" id="status"></p>
    <br />
    <div class="forgotpasswd" style="height:30px; margin-bottom:-50px; padding-top:10px; text-align:justify; font-size:14px;">
    <a id="forgotpasswda" style="color:#fff;" href="http://www.naatcast.com/forgotpass">Forgot Your Password?</a>
  </div>
    </form>
    <div class="darkdiv" style="height:80px; background-color:black; margin-top:50px;">
      <div style="padding-top:25px;">
      <a href="https://www.facebook.com/naatcast"><i class="fab fa-facebook-square fa-2x" id="facebookicon"></i></a>
      <a href="https://twitter.com/naatcast"><i class="fab fa-twitter-square fa-2x" id="twittericon"></i></a>
      <a href="https://linkedin.com/company/naatcast"><i class="fab fa-linkedin fa-2x" id="linkedinicon"></i></a>
      <a href="https://www.youtube.com/channel/UC7uohRTwyIZpi7NbwEbkAGg"><i class="fab fa-youtube-square fa-2x" id="youtubeicon"></i></a></div>
    </div>
</div>
</div>
        <!-- LOGIN FORM -->
<ul class="gallery-slideshow">
  <!--<li><img src="images/banners/p-campaign.jpeg"/></li>
  <li><img src="images/banners/prevention1.jpeg"/></li>
  <li><img src="images/banners/p-assistance.jpeg"/></li>
  <li><img src="images/banners/api.jpeg"/></li>
  <li><img src="images/banners/contentprovider.jpeg"/></li>
  <li><img src="images/banners/p-announcement.jpg"/></li>
  <li><img src="images/banners/telemarket.jpeg"/></li>
  <li><img src="images/banners/grassroot2.jpeg"/></li>
  <li><img src="images/banners/dailyreminder.jpeg"/></li>-->
  <li><img src="images/banners/welcome.jpeg"/></li>
  <li><iframe width="510" height="510" src="https://www.youtube.com/embed/DxH7rJFYT8M" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
  <li><iframe width="510" height="510" src="https://www.youtube.com/embed/Ax3dMh8gjjQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
  <li><iframe width="510" height="510" src="https://www.youtube.com/embed/ZlRM7GlSKis" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
  <li><iframe width="510" height="510" src="https://www.youtube.com/embed/IkEqXtbe6Mg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
  <li><iframe width="510" height="510" src="https://www.youtube.com/embed/rwq2RQOMYHs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
</ul>
</body>
</html>
