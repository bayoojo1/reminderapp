<?php
$con = mysqli_connect("69.64.82.82","root","0pelcorsa","wombat");
//$con = mysqli_connect("10.32.1.167","username","password","dbname");
// Check connection
if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    } else {
	echo "Connection Successful!";
}
?>
