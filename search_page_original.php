<?php
include("php_includes/check_login_status.php");
include("php_includes/mysqli_connect.php");
if(isset($_SESSION['username'])) {
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_SESSION['username']);;
} else {
    exit();
} 
?><?php
if($_POST['searchquery'] == ""){
    echo '<br />'.'<br />';
    echo '<form action="" method="post">'.'<div id="searchWrap" style="max-width:100%;">';
        echo '<input type="text" id="searchquery" name="searchquery" placeholder="Search users by name..." style="width:89%;" onkeypress="key_down(event)">';
        echo '<input type="button" id="search" value="Search" onclick="searchUsers();" style="width:10%;">'.'</div>';
    echo '</form>'.'<br />'.'<br />';
    echo '<div id="followerList" style="height:60px; text-align:center; display: table-cell; vertical-align: middle; font-size:20px; color:white;">'; 
    echo 'You did not enter any input to be searched. Please enter the name of the user you want to search in the search box above.';
    echo '</div>';
    exit();

} else if(isset($_POST['searchquery'])){
    $searchquery = preg_replace('#[^a-z 0-9?!]#i', '', $_POST['searchquery']);
        $sql_statement = "SELECT users.id, alias, fullname, users.username, avatar, useroptions.aliascheck FROM users INNER JOIN useroptions ON users.username=useroptions.username WHERE firstname LIKE :firstname OR lastname LIKE :lastname OR alias LIKE :alias OR fullname LIKE :fullname ORDER BY RAND()";
    $stmt = $db_connect->prepare($sql_statement);
    $stmt->bindValue(':firstname', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':lastname', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':alias', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->bindValue(':fullname', '%'.$searchquery.'%', PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();

    // Specify how many result per page
    $rpp = '5';
    // This tells us the page number of the last page
    $last = ceil($count/$rpp);
    // This makes sure $last cannot be less than 1
    if($last < 1){
        $last = 1;
    }
    // Define pagination control
    //$paginationCtrls = "";
    // Define page number
    $pn = "2";

    
    
    if($count > 0){
        echo '<br />'.'<br />';
        echo '<form action="" method="post">'.'<div id="searchWrap" style="max-width:100%;">';
            echo '<input type="text" id="searchquery" name="searchquery" placeholder="Search users by name..." style="width:89%;" onkeypress="key_down(event)">';
            echo '<input type="button" id="search" value="Search" onclick="searchUsers();" style="width:10%;">'.'</div>';
        echo '</form>'.'<br />'.'<br />';
        echo "<hr />$count results for <strong>$searchquery</strong><hr /><br />";
        echo '<div id="paginationctrls">';
        if($last != 1) {
            if($pn > 1) { 
                echo '<button onclick="request_page($pn-1)">&lt;</button>';   
            }
            echo "<b>Page $pn of $last</b>";
            if($pn != $last) {
                echo '<button onclick="request_page($pn+1)">&gt;</button>';
            }
        }
        echo '</div>';
        echo '<br />';
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
    echo '<div id="followerList">';
        echo '<a class="image" href="http://localhost:8080/reminderapp/user_audio.php?u='.$searchUsername.'"><img src="user/'.$searchUsername.'/'.$row['avatar'].'" alt="'.$searchUsername.'">'.'<br />';
        if($row['aliascheck'] == '1') {
            echo '<div id="fuserName">'.$row["fullname"].'</a>'.'</div>';
        } else if($row['aliascheck'] == '0') {
            echo '<div id="fuserName">'.$row["alias"].'</a>'.'</div>';
        }
        echo '<span id='.$userid.'>'.$searchUsername_Btn.'</span>';
    echo '</div><br />';

} 
}else {
    echo '<br />'.'<br />';
    echo '<form action="" method="post">'.'<div id="searchWrap" style="max-width:100%;">';
        echo '<input type="text" id="searchquery" name="searchquery" placeholder="Search users by name..." style="width:89%;" onkeypress="key_down(event)">';
        echo '<input type="button" id="search" value="Search" onclick="searchUsers();" style="width:10%;">'.'</div>';
    echo '</form>'.'<br />'.'<br />';
    echo "<hr />0 results for <strong>$searchquery</strong><hr />";
    }
}
?>
