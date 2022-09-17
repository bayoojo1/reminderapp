<?php
// This page provides the functions that enable the search of users on NaatCast //
include("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");
include("theCollector.php");

if(isset($_SESSION['username'])) {
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_SESSION['username']);;
} else {
    exit();
}
?><?php
$searchpage = "";
if(empty($_GET['searchquery'])){
    $searchpage .= '<div id="followerList" style="height:60px; text-align:center; display: table-cell; vertical-align: middle; font-size:20px; color:white;">';
    $searchpage .= 'You did not enter any input to be searched. Please enter the name of the user you want to search in the search box above.';
    $searchpage .= '</div>';
    $paginationCtrls = '';

} else if(isset($_GET['searchquery']) && !empty($_GET['searchquery'])){
    $searchquery = preg_replace('#[^a-z 0-9?!]#i', '', $_GET['searchquery']);
        $sql_statement = "SELECT users.id, alias, fullname, users.username, avatar, useroptions.aliascheck FROM users INNER JOIN useroptions ON users.username=useroptions.username WHERE (firstname LIKE :firstname OR lastname LIKE :lastname OR alias LIKE :alias OR fullname LIKE :fullname) AND activated=:activated";
    $stmt = $db_connect->prepare($sql_statement);
    $stmt->bindValue(':firstname', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':lastname', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':alias', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':fullname', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':activated', '1', PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();

    // Specify how many result per page
    $rpp = '10';
    // This tells us the page number of the last page
    $last = ceil($count/$rpp);
    // This makes sure $last cannot be less than 1
    if($last < 1){
        $last = 1;
    }
    // Define pagination control
    //$paginationCtrls = "";
    // Define page number
    $pn = "1";

    // Get pagenum from URL vars if it is present, else it is = 1
if(isset($_GET['pn'])){
    $pn = preg_replace('#[^0-9]#', '', $_GET['pn']);
    //$searchquery = $_POST['searchquery'];
}

// Make the script run only if there is a page number posted to this script

// This makes sure the page number isn't below 1, or more than our $last page
    if ($pn < 1) {
        $pn = 1;
    } else if ($pn > $last) {
    $pn = $last;
}

// This sets the range of rows to query for the chosen $pn
    $limit = 'LIMIT ' .($pn - 1) * $rpp .',' .$rpp;

// This is the query again, it is for grabbing just one page worth of rows by applying $limit
    $sql = "$sql_statement"." $limit";
    $stmt = $db_connect->prepare($sql);
    $stmt->bindValue(':firstname', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':lastname', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':alias', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':fullname', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':activated', '1', PDO::PARAM_STR);
    $stmt->execute();
    //var_dump($stmt);
    // Establish the $paginationCtrls variable
    $paginationCtrls = '';

    if($count > 0){
        $searchpage .= "<hr />$count results for <strong>$searchquery</strong><hr /><br />";
        $paginationCtrls .= '<div id="paginationctrls">';
        if($last != 1){
            /* First we check if we are on page one. If we are then we don't need a link to
               the previous page or the first page so we do nothing. If we aren't then we
               generate links to the first page, and to the previous page. */
            if ($pn > 1) {
                $previous = $pn - 1;
                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'&searchquery='.$searchquery.'">Previous</a> &nbsp; &nbsp; ';
                // Render clickable number links that should appear on the left of the target page number
                for($i = $pn-4; $i < $pn; $i++){
                    if($i > 0){
                        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'&searchquery='.$searchquery.'">'.$i.'</a> &nbsp; ';
                    }
                }
            }
            // Render the target page number, but without it being a link
            $paginationCtrls .= ''.$pn.' &nbsp; ';
            // Render clickable number links that should appear on the right of the target page number
            for($i = $pn+1; $i <= $last; $i++){
                $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'&searchquery='.$searchquery.'">'.$i.'</a> &nbsp; ';
                if($i >= $pn+4){
                    break;
                }
            }
            // This does the same as above, only checking if we are on the last page, and then generating the "Next"
            if ($pn != $last) {
                $next = $pn + 1;
                $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'&searchquery='.$searchquery.'">Next</a> ';
            }
        }
        $paginationCtrls .= '</div>';
        //echo $paginationCtrls;
        //echo '<br />';
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $searchUsername = $row['username'];
            $userid = $row['id'];

$following = false;
$naatcast = "naatcast";
    //$unfollow = false;
?><?php
if($u == $log_username && $user_ok == true){
    $following_check = "SELECT id FROM follows WHERE user1=:user1 AND user2=:user2 LIMIT 1";
    $stmt = $db_connect->prepare($following_check);
    $stmt->bindParam(':user1', $log_username, PDO::PARAM_STR);
    $stmt->bindParam(':user2', $searchUsername, PDO::PARAM_STR);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        $following = true;
    }
} elseif($u != $log_username && $user_ok == true){
    $following_check = "SELECT id FROM follows WHERE user1=:user1 AND user2=:user2 LIMIT 1";
    $stmt = $db_connect->prepare($following_check);
    $stmt->bindParam(':user1', $log_username, PDO::PARAM_STR);
    $stmt->bindParam(':user2', $searchUsername, PDO::PARAM_STR);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        $following = true;
    }
}
?><?php
if($following == true){
    $searchUsername_Btn = '<button onclick="searchUsernameToggle(\'unfollow\',\''.$searchUsername.'\',\''.$userid.'\')">Unfollow</button>';
    } elseif($searchUsername == $naatcast) {
        $searchUsername_Btn = '<button disabled>Admin</button>';
    } elseif($searchUsername != $log_username && $following == false && $user_ok == true) {
        $searchUsername_Btn = '<button onclick="searchUsernameToggle(\'follow\',\''.$searchUsername.'\',\''.$userid.'\')">Follow</button>';
    } else {
        $searchUsername_Btn = '<button disabled>Me</button>';
    }
?><?php
    $searchpage .= '<div id="followerList">';
        $searchpage .= '<a class="image" href="http://localhost:8080/reminderapp/user_audio.php?u='.$searchUsername.'"><img src="user/'.$searchUsername.'/'.$row['avatar'].'" alt="'.$searchUsername.'">'.'<br />';
        if($row['aliascheck'] == '1') {
            $searchpage .= '<div id="fuserName">'.$row["fullname"].'</a>'.'</div>';
        } else if($row['aliascheck'] == '0') {
            $searchpage .= '<div id="fuserName">'.$row["alias"].'</a>'.'</div>';
        }
        $searchpage .= '<span id='.$userid.'>'.$searchUsername_Btn.'</span>';
    $searchpage .= '</div><br />';
}
}else {
    $searchpage .= "<hr />0 results for <strong>$searchquery</strong><hr />";
    }
}
include_once("functions/page_functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Search - <?php echo $searchquery; ?></title>
<link rel="stylesheet" href="style/normalize.css">
<link href="https://fonts.googleapis.com/css?family=Changa+One:400,400i|Open+Sans:400,400i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/jquery.js"></script>
<script src="js/header_scroll.js"></script>
<script src="js/main.js"></script>
<script src="js/functions.js"></script>
</head>
<body class="insidepage">
<div id="header">
<?php include_once("template_pageTop.php"); ?>
</div><br /><br />
<div id="wrapper">
    <?php echo $pageleft; ?>

    <div id="PageMiddle"><br />
    <form action="" method="post">
        <div id="searchWrap" style="max-width:100%;">
            <span><input type="text" id="searchquery" name="searchquery" placeholder="Search users by name..." onkeypress="key_down(event)"></span></span><i class="fas fa-search" id="search" onclick="searchUsers();" style="color:white; float:right; margin-top:6.5px;"></i>
        </div>
    </form><br /><br />
        <?php echo $paginationCtrls; ?><br />
        <?php echo $searchpage; ?>
        <?php echo $paginationCtrls; ?><br />
    </div>

    <?php include_once("template_pageRight.php"); ?>

</div>
</body>
</html>
