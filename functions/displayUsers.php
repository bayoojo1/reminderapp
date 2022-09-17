<?php
// This page provides the functions that enable the search of users on NaatCast //
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if(isset($_SESSION['username'])) {
    $u = preg_replace('#[^a-z0-9.@_]#i', '', $_SESSION['username']);
} else {
    exit();
}

if(isset($_POST['input']) && $_POST['input'] != "") {
    $option = preg_replace('#[^a-z0-9.@_]#i', '', $_POST['input']);
} else {
    exit();
}
$userspage = "";
?><?php
$sql = "SELECT firstname, lastname, isdn, username FROM users WHERE username=:username AND activated=:activated";
$stmt = $db_connect->prepare($sql);
$stmt->bindValue(':activated', '1', PDO::PARAM_STR);
$stmt->bindParam(':username', $option, PDO::PARAM_STR);
$stmt->execute();
$users_count = $stmt->rowCount();
if($users_count > 0){
    $userspage .= '<div id="user_search" style="overflow-x:auto;">';
        $userspage .= "<table style='width:100%; border: 1px solid gray;'>";
        $userspage .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
        $userspage .= "<tr>";
            $userspage .= "<th width='20%'; style='border: 1px solid gray; color:white;'>First Name</th>";
            $userspage .= "<th width='20%'; style='border: 1px solid gray; color:white;'>Last Name</th>";
            $userspage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Mobile</th>";
            $userspage .= "<th width='20%'; style='border: 1px solid gray; color:white'>Username</th>";
            $userspage .= "<th width='17.5%'; style='border: 1px solid gray; color:white;'>Action</th>";
        $userspage .= "</tr>";
        $userspage .= "</thead>";

        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $mobile = $row['isdn'];
            $username = $row['username'];

            $userspage .= "<tbody>";
            $userspage .= "<tr>";
                $userspage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $firstname . "</td>";
                $userspage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $lastname . "</td>";
                $userspage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $mobile . "</td>";
                $userspage .= "<td style='text-align:center; border: 1px solid gray; color:gray;'>" . $username . "</td>";
                $userspage .= "<td style='text-align:center; border: 1px solid gray; color:white; cursor:pointer;'>"."<a href='http://localhost:8080/reminderapp/user_edit.php?u=".$username."'>"."Edit"."</a>"."&nbsp"."&nbsp"."&nbsp".
                "<a href='http://localhost:8080/reminderapp/user_view.php?u=".$username."'>"."View"."</a>".
                "</td>";
            $userspage .= "</tr>";
            $userspage .= "</tbody>";
          }
          $userspage .= "</table>";
        $userspage .= '</div>';
        } else {
            $userspage .= '<div id="user_search" style="overflow-x:auto;">';
                    $userspage .= "<table style='width:100%; border: 1px solid gray;'>";
                    $userspage .= "<thead style='border: 1px solid gray; background-color:#004080;'>";
                    $userspage .= "<tr>";
                        $userspage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>First Name</th>";
                        $userspage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Last Name ID</th>";
                        $userspage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Mobile</th>";
                        $userspage .= "<th width='22.5%'; style='border: 1px solid gray; color:white;'>Username</th>";
                        $userspage .= "<th width='10%'; style='border: 1px solid gray; color:white;'>Edit</th>";
                    $userspage .= "</tr>";
                    $userspage .= "</thead>";
                    $userspage .= "</table>";
                    $userspage .= "</div>";
            $userspage .= 'No record found!';
    }
echo $userspage;
?>
