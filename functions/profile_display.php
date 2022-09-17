<?php
//include("./php_includes/check_login_status.php");
include("./php_includes/mysqli_connect.php");

//$db_connect = new PDO('mysql:host=localhost;dbname=reminderapp', 'root', 'wifi1234');
$sql = "SELECT aliascheck FROM useroptions WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $alias_check = $row['aliascheck'];
}


$sql = "SELECT mobilecheck FROM useroptions WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $m_check = $row['mobilecheck'];
}

$sql = "SELECT websitecheck FROM useroptions WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $w_check = $row['websitecheck'];
}

$sql = "SELECT aboutcheck FROM useroptions WHERE username=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $a_check = $row['aboutcheck'];
}

$sql ="SELECT description FROM content_provider WHERE provider=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $description = $row['description'];
}

// Check if I've subscribed to any provider
$isSubscribed = false;
$sql = "SELECT id FROM subscription WHERE subscriber=:logusername";
$stmt = $db_connect->prepare($sql);
$stmt->bindParam(':logusername', $log_username, PDO::PARAM_STR);
$stmt->execute();
if($stmt->rowCount() > 0) {
    $isSubscribed = true;
}


echo '<div class="dropdown" style="background-color:gainsboro;">';
echo '<div class="dropbtn" style="background-color:gainsboro;">'.'<a href="#">'.'<b>'.'My Profile'.'</b>'.'</a>'.' <i class="fa fa-caret-down" style="color:blue;"></i>'.'</div>';
        echo '<div class="dropdown-content">';
        echo '<div>'.'<a href="http://localhost:8080/reminderapp/callrecords_page.php?u='.$u.'">'.'Call Record'.'</a>'.'</div>';
        echo '<div>'.'<a href="http://localhost:8080/reminderapp/billing_page.php?u='.$u.'">'.'Payment History'.'</a>'.'</div>';
        echo '<div>'.'<a href="http://localhost:8080/reminderapp/apicreate_page.php?u='.$u.'">'.'Create API'.'</a>'.'</div>';
        echo '</div>';
    echo '</div>';
    //echo '</div>';
echo '<br />'.'<br />';
echo "<table class='tprofiles'>";
echo "<tr class='trow'>";
echo "<td style='height:30px; color:gray; vertical-align:center;'>"; ?><b>Firstname:</b> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center; font-size:13px;'>"; ?><div id="firstname<?php echo $profile_id; ?>"><?php echo $firstname; ?> </div> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="edit(this)">Edit</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="save(this)" style="visibility:hidden">Save</button><?php echo "</td>";
echo "</tr>";
echo "<tr class='trow'>";
echo "<td style='height:30px; color:gray; vertical-align:center;'>"; ?><b>Lastname:</b> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center; font-size:13px;'>"; ?><div id="lastname<?php echo $profile_id; ?>"><?php echo $lastname; ?> </div> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="edit(this)">Edit</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="save(this)" style="visibility:hidden">Save</button><?php echo "</td>";
echo "</tr>";
echo "<tr class='trow'>";
echo "<td style='height:30px; color:gray; vertical-align:center;'>"; ?><b>Alias:</b> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center; font-size:13px;'>"; ?><div id="alias<?php echo $profile_id; ?>"><?php echo $alias; ?> </div> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="edit1(this)">Edit</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button style="visibility:hidden" onclick="save1(this);aliasCheckboxState();">Save</button><?php echo "</td>";
echo "<td id='testing' style='height:30px; vertical-align:center;'>"; ?><input type="checkbox" title="Check to display your alias instead of your real name" id="aliascheck<?php echo $profile_id; ?>" style="visibility:hidden" <?php if( $alias_check == '0'){ echo "checked"; } ?>><?php echo "</td>";
echo "</tr>";
echo "<tr class='trow'>";
echo "<td style='height:30px; color:gray; vertical-align:center;'>"; ?><b>Country Code:</b> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center; font-size:13px;'>"; ?><div id="countrycode<?php echo $profile_id; ?>"><?php echo $countrycode; ?> </div> <?php echo "</td>";
/*echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="edit(this)">Edit</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button style="visibility:hidden" onclick="save(this)">Save</button><?php echo "</td>"; */
echo "</tr>";
echo "<tr class='trow'>";
echo "<td style='height:30px; color:gray; vertical-align:center;'>"; ?><b>Mobile:</b> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center; font-size:13px;'>"; ?><div id="mobile<?php echo $profile_id; ?>"><?php echo $mobile; ?> </div> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="editphone(this)">Edit</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button style="visibility:hidden" onclick="save1(this);mobileCheckboxState();">Save</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><input type="checkbox" title="Check to hide your mobile number." id="mobilecheck<?php echo $profile_id; ?>" style="visibility:hidden" <?php if( $m_check == '1'){ echo "checked"; } ?>><?php echo "</td>";
echo "</tr>";
echo "<tr class='trow'>";
echo "<td style='height:30px; color:gray; vertical-align:center;'>"; ?><b>Website:</b> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center; font-size:13px;'>"; ?><div id="website<?php echo $profile_id; ?>"><?php echo $website; ?> </div> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="edit1(this)">Edit</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button style="visibility:hidden" onclick="save1(this);websiteCheckboxState();">Save</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><input type="checkbox" title="Check to show your webiste." id="websitecheck<?php echo $profile_id; ?>" style="visibility:hidden" <?php if( $w_check == '0'){ echo "checked"; } ?>><?php echo "</td>";
echo "</tr>";
echo "<tr class='trow'>";
echo "<td style='height:30px; color:gray; vertical-align:center;'>"; ?><b>About Me:</b> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center; font-size:13px;'>"; ?><div id="about<?php echo $profile_id; ?>"><?php echo $about; ?> </div> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="checkEmpty();edit2(this);">Edit</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button style="visibility:hidden" onclick="save1(this);aboutCheckboxState();">Save</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><input type="checkbox" title="Check to show your Bio on your profile." id="aboutcheck<?php echo $profile_id; ?>" style="visibility:hidden" <?php if( $a_check == '0'){ echo "checked"; } ?>><?php echo "</td>";
echo "</tr>";
echo "<tr class='trow'>";
echo "<td style='height:30px; color:gray; vertical-align:center;'>"; ?><b>Email:</b> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center; font-size:13px;'>".'<div id="pmail">'.$email.'</div>'."</td>";
echo "</tr>";
if($isProvider) {
echo "<tr class='trow'>";
echo "<td style='height:30px; color:gray; vertical-align:center;'>"; ?><b>Content Description:</b> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center; font-size:13px;'>"; ?><div id="description<?php echo $profile_id; ?>"><?php echo $description; ?> </div> <?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="edit3(this)">Edit</button><?php echo "</td>";
echo "<td style='height:30px; vertical-align:center;'>"; ?><button onclick="save(this)" style="visibility:hidden">Save</button><?php echo "</td>";
echo "</tr>";
}
echo "</table>"."<br />";
echo "<hr>";
echo "<br />";
echo "<div id='changepassword' style='font-weight:800; color:gray;'>Change Your Password</div>"."<br />";
echo "<form id='pform' method='post'>";
echo "<div id='entercurrent'>Enter Current Password:</div>";
echo "<input id='current' name='current' type='password'>";
echo "<br />";
echo "<div id='enternew'>Enter New Password:</div>";
echo "<input id='newpwd' name='newpwd' type='password'>";
echo "<br />";
echo "<br />";
echo "<div id='enterconfirm'>Confirm New Password:</div>";
echo "<input id='confirmpwd' name='confirmpwd' type='password'>";
echo "<div style='height:50px;'></div>";
echo "<input id='passsubmit' style='margin-top:50px;' type='submit' value='Update Password'>";
echo "</form>";
echo "<div id='pmessage'></div>";
?>
