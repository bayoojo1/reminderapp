<?php
    //$u != $log_username;
    include("page_functions.php");
    include("./php_includes/mysqli_connect.php");
    
    $sql_statement = "SELECT users.id, users.firstname, users.lastname, users.fullname, users.avatar, users.username, users.alias, useroptions.aliascheck FROM users INNER JOIN follows ON users.username=follows.user1 INNER JOIN useroptions ON users.username=useroptions.username WHERE user2=:user";

    $stmt = $db_connect->prepare($sql_statement);
    $stmt->bindParam(':user', $u, PDO::PARAM_STR);
    $stmt->execute();
    $numrows = $stmt->rowCount();

    // Specify how many result per page
    $rpp = '10';
    // This tells us the page number of the last page
    $last = ceil($numrows/$rpp);
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
    $stmt->bindParam(':user', $u, PDO::PARAM_STR);
    $stmt->execute();

    // Check if user has followers
        if($numrows < 1){
        echo '<div id="nav-follow">';
          echo '<ul>';
           echo '<li>'.'<a href="http://localhost:8080/reminderapp/follow_page.php?u='.$u.'" class="selected">'.'Followers'.'</a>'.'</li>';
           echo '<li>'.'<a href="http://localhost:8080/reminderapp/following_page.php?u='.$u.'">'.'Following'.'</a>'.'</li>';
          echo '</ul>';
        echo '</div>';
        echo '<br />';
        echo '<div id="followerList" style="height:60px; text-align:center; vertical-align: middle; font-size:20px; color:white;">';      
        echo "I don't have any follower yet. If I start following others, they will also follow me.";
        echo '</div></div>'; 
            include_once("template_pageRight.php");
            
            exit();
        } else if($numrows > 0) {
            // Establish the $paginationCtrls variable
    $paginationCtrls = '';
    $paginationCtrls .= '<div id="paginationctrls">';
    if($last != 1){
        /* First we check if we are on page one. If we are then we don't need a link to 
           the previous page or the first page so we do nothing. If we aren't then we
           generate links to the first page, and to the previous page. */
        if ($pn > 1) {
            $previous = $pn - 1;
            $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$previous.'">Previous</a> &nbsp; &nbsp; ';
            // Render clickable number links that should appear on the left of the target page number
            for($i = $pn-4; $i < $pn; $i++){
                if($i > 0){
                    $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
                }
            }
        }
        // Render the target page number, but without it being a link
        $paginationCtrls .= ''.$pn.' &nbsp; ';
        // Render clickable number links that should appear on the right of the target page number
        for($i = $pn+1; $i <= $last; $i++){
            $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$i.'">'.$i.'</a> &nbsp; ';
            if($i >= $pn+4){
                break;
            }
        }
        // This does the same as above, only checking if we are on the last page, and then generating the "Next"
        if ($pn != $last) {
            $next = $pn + 1;
            $paginationCtrls .= ' &nbsp; &nbsp; <a href="'.$_SERVER['PHP_SELF'].'?u='.$u.'&pn='.$next.'">Next</a> ';
        }
    }
    $paginationCtrls .= '</div>';
?><?php
        echo '<div id="nav-follow">';
          echo '<ul>';
           echo '<li>'.'<a href="http://localhost:8080/reminderapp/follow_page.php?u='.$u.'" class="selected">'.'Followers'.'</a>'.'</li>';
           echo '<li>'.'<a href="http://localhost:8080/reminderapp/following_page.php?u='.$u.'">'.'Following'.'</a>'.'</li>';
          echo '</ul>';
        echo '</div>'.'<br />';
        echo $paginationCtrls;
        echo '<br />';
    // Fetch the user row from the query above. This was a working script//
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        foreach($rows as $row) {
        $followUsername = $row['username'];
        $userid = $row['id'];
        $db_connect = null;
        

?><?php
    $following = false;
    
    if($u == $log_username && $user_ok == true){
        include("./php_includes/mysqli_connect.php");
        $follow_check = "SELECT id FROM follows WHERE user1=:logusername AND user2=:followusername LIMIT 1";
        $stmt = $db_connect->prepare($follow_check);
        $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
        $stmt->bindParam(':followusername', $followUsername, PDO::PARAM_STR);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $following = true;  
        }
        $db_connect = null;
    } elseif($u != $log_username && $user_ok == true){
        include("./php_includes/mysqli_connect.php");
        $follow_check = "SELECT id FROM follows WHERE user1=:logusername AND user2=:followusername LIMIT 1";
        $stmt = $db_connect->prepare($follow_check);
        $stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
        $stmt->bindParam(':followusername', $followUsername, PDO::PARAM_STR);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $following = true;  
        }
        $db_connect = null;
    }
?><?php
    //$followUsername_Btn = "";
    if($following == true){
        $followUsername_Btn = '<button onclick="followUsernameToggle(\'unfollow\',\''.$followUsername.'\',\''.$userid.'\')">Unfollow</button>';
        } elseif($followUsername != $log_username && $following == false && $user_ok == true) {
            $followUsername_Btn = '<button onclick="followUsernameToggle(\'follow\',\''.$followUsername.'\',\''.$userid.'\')">Follow</button>';
        } else {
            $followUsername_Btn = '<button disabled>Me</button>';
        }
?><?php 
        echo '<div id="followerList">';
            echo '<a class="image" href="http://localhost:8080/reminderapp/user_audio.php?u='.$followUsername.'"><img src="user/'.$followUsername.'/'.$row['avatar'].'" alt="'.$followUsername.'">'.'<br />';
            if($row['aliascheck'] == '1') {
                echo '<div id="fuserName">'.$row["fullname"].'</a>'.'</div>';
            } else if($row['aliascheck'] == '0') {
                echo '<div id="fuserName">'.$row['alias'].'</a>'.'</div>';
            }
            echo '<span id='.$userid.'>'.$followUsername_Btn.'</span>';   
        echo '</div><br />';
}
echo $paginationCtrls;
} 
?>