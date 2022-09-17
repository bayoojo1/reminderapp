<?php
//$url = "http://localhost:8080/reminderapp/api.php";
$url = "http://www.naatcast.com/api.php";
$params = array(
   "apiname" => "naatcast",
   "apitoken" => "56030de1a8649e60cce8e30662b2e2c4",
   "recurrent" => "Once",
   "schedule" => "2018-08-01.00:41:00",
   "mobile" => "2348023950246",
   "text" => "I'm not getting a satisfactory voice and sound."
);

    $post = http_build_query($params);
 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);    
 
    $output=curl_exec($ch);
    if($output === false)
    {
        echo "Error Number:".curl_errno($ch)."<br>";
        echo "Error String:".curl_error($ch);
    }
    curl_close($ch);
    return $output;
?>