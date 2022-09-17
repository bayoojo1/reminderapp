<?php
include("../php_includes/check_login_status.php");
include("../php_includes/mysqli_connect.php");

if($user_ok != true || $log_username == ""){
    header("location: http://localhost:8080/reminderapp/login.php");
    exit();
}

if(isset($_FILES["csv"])) {
  $csv_uploadDir = '/wamp64/www/reminderapp/csv/';
  $csv_fileName = $_FILES["csv"]["name"];
  $csv_fileTmpLoc = $_FILES["csv"]["tmp_name"];
  $csv_fileType = $_FILES["csv"]["type"];
  $csv_fileSize = $_FILES["csv"]["size"];
  $csv_fileErrorMsg = $_FILES["csv"]["error"];
  $kaboom = explode(".", $csv_fileName);
  $csv_fileExt = end($kaboom);

  //$csv_file_name = rand(100000000000,999999999999).".".$csv_fileExt;
  $csv_file_name = 'naatcastRateSheet'.".".$csv_fileExt;
  $csv_db_file_name = $csv_uploadDir . $csv_file_name;

  if(!$csv_fileTmpLoc) {
      echo "ERROR: You need to upload your sample csv file.";
      exit();
  } else if ($csv_fileSize > 2097152) {
      echo "ERROR: Your csv file was larger than 5mb.";
      exit();
  } else if (!preg_match("/\.(csv)$/i", $csv_fileName) ) {
      echo "ERROR: Your file was not csv type.";
      exit();
  } else if ($csv_fileErrorMsg == 1) {
      echo "ERROR: An unknown error occurred.";
      exit();
  }

// Move uploaded file to the final directories
$moveResult = move_uploaded_file($csv_fileTmpLoc, $csv_db_file_name);
if ($moveResult != true) {
    echo "ERROR: File upload failed.";
    exit();
}
// Export the csv into the DB
$file = fopen($csv_db_file_name, "r");
   while (($csvData = fgetcsv($file, 10000, ",")) !== FALSE)
   {
      $stmt = $db_connect->prepare("INSERT INTO csv (Destination, Prefix, Rate)
      VALUES(:Destination, :Prefix, :Rate)");
      $stmt->execute(array(':Destination' => $csvData[0], ':Prefix' => $csvData[1], ':Rate' => $csvData[2]));
   }
   fclose($file);
   echo "CSV File has been successfully Imported.";

} else {
    echo "ERROR: You need to upload your csv file.";
    exit();
}
?>
