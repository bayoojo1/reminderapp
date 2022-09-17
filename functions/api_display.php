<?php
echo '<div class="dropdown" style="background-color:gainsboro;">';
echo '<div class="dropbtn">'.'<a href="http://localhost:8080/reminderapp/apicreate_page.php?u='.$u.'">'.'<b>'.'Create API'.'</b>'.'</a>'.' <i class="fa fa-caret-down" style="color:blue;"></i>'.'</div>';
        echo '<div class="dropdown-content">';
        echo '<div>'.'<a href="http://localhost:8080/reminderapp/callrecords_page.php?u='.$u.'">'.'Call Record'.'</a>'.'</div>';
        echo '<div>'.'<a href="http://localhost:8080/reminderapp/billing_page.php?u='.$u.'">'.'Payment History'.'</a>'.'</div>';
        echo '<div>'.'<a href="http://localhost:8080/reminderapp/profile_page.php?u='.$u.'">'.'My Profile'.'</a>'.'</div>';
        echo '</div>';
    echo '</div>';
    //echo '</div>';
echo '<br />'.'<br />';

echo "<div>If you have a platform or an application, and you would like to send audio broadcast to your users directly from your application, or you want to send realtime transactional message in audible form to your clients, our API is built for you.";
echo "<br />";
echo "Follow the steps below to generate your API and start enjoying the benefits it offers. To see sample codes and implementation instruction for the API, click on this <span id='samplecode' style='font-weight:bold; cursor:pointer;'>link.</span></div>";
echo "<br />";
echo "<button id='apibtn'>Create API</button>";
echo "<br /><br />";
echo "<div id='createapi' style='border:5px solid rgba(97, 177, 255, 0.3); display:none;'>";
echo "<div style='color:#004080; font-weight:bold;'>API Name:</div> "."<input id='api'/>";
echo "<br /><br />";
echo "<span style='color:#004080; font-weight:bold;'>API Description:</span> "."<textarea id='apidesc' placeholder='Brief description for this API'>"."</textarea>";
echo "<br /><br />";
echo "<button onclick='saveapi()'>Save</button>";
echo "</div>";
echo "<br />";
echo "<div style='background-color:#004050; color:white; text-align:center;'>My API List</div>";
echo "<br />";
echo "<div id='status'></div>";
echo "<div id='apilist'></div>";
?>
<div id='samplecd' style='display:none;'>
  <span>After creating your API, getting your platform or application to talk to NaatCast using the API is very easy. We'll provide two code samples in cURL that can help you to get the job done very easy and fast. The code samples below shows how to use cURL function to initiate a broadcast directly from your application.

    <p><u>Sample 1: For broadcasting text to speech</u></p>
    <pre style='height:420px; background-color:#F0F0F0; font-size:12px;'>
    <code>
    $url = "http://www.naatcast.com/api.php";
    $params = array(
       "apiname" => "myapiname",
       "apitoken" => "abcdefgh123456",
       "recurrent" => "Once",
       "schedule" => "2018-08-01.00:41:00",
       "mobile" => "2348020000000",
       "text" => "I'm testing naatcast API."
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
            echo "Error Number:".curl_errno($ch);
            echo "Error String:".curl_error($ch);
        }
        curl_close($ch);
        return $output;

    </code>
    </pre>
    <br>
    <p><u>Sample 2: For broacasting audio file(.mp3 or .wav)</u></p>
    <xmp style='height:720px; background-color:#F0F0F0; font-size:12px; overflow:auto;'>

  if ( isset($_POST['submit']) )
  {
      // Make sure there are no upload errors
      if ($_FILES['audio']['error'] > 0)
      {
          die("Error uploading file...");
      }

      // Prepare the cURL file to upload, including file name and MIME type
      $post = array(
      'audio' => new CurlFile($_FILES["audio"]["tmp_name"], $_FILES["audio"]["type"], $_FILES["audio"]["name"]),
      );


      // Include the other $_POST fields from the form?
      $post = array_merge($post,$_POST);

      // Prepare the cURL call to upload the external script
      $ch = curl_init();
      //curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/reminderapp/api.php");
      curl_setopt($ch, CURLOPT_URL, "http://www.naatcast.com/api.php");
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      $result = curl_exec($ch);
      curl_close($ch);

      // Print the result?
      print_r($result);
  }

  <form action="your-script-name.php" method="post" enctype="multipart/form-data">
      Select file to upload:
      <input type="file" name="audio"><br>
      <input type="hidden" name="apiname" value="myapiname">
      <input type="hidden" name="apitoken" value="abcdefgh123456">
      Recurrent: <input type="text"  name="recurrent" value=""><br>
      Schedule: <input type="text"  name="schedule" value=""><br>
      Mobile: <input type="text" name="mobile" value=""><br>
      <input type="submit" name="submit" value="Upload File">
  </form>
</xmp>

For sample code 1, you would have to pass the "key and value pair" parameters in the array into the cURL function, you can gather those values anyway you like, either as direct input or as variables, e.g. you could declare a variable below: <pre style='background-color:#F0F0F0;'> $text = 'I'm testing naatcast API.';</pre> and pass it as value to the "text" key in the array to give you <pre style='background-color:#F0F0F0;'>"text" => $text,</pre> Your API name and token are displayed above if you've created one. Just copy and paste the apiname and token as they are into the corresponding keys in the array.<br><br> For sample code 2, the example shows how to use our API to initiate broadcast for uploaded audio file directly from your platform or app. Note the following:<br><br> 1. The input field for the API name and token are hidden.<br> 2. Replace the values of your API name and token with your generated value.<br> 3. The value of schedule should be in the format: YYYY:MM:DD.HH:MM:SS<br> 4. The mobile field can take multiple mobile numbers separated by comma, without any space in-between them.
<br>
The value for the recurrent key is <b>case sensitive</b> and  has a couple of options depending on how you want your broadcast to be repeated. Listed below are the options:<br>

    <br />
    <b>Options for recurrent key:</b>
    <table style='width:100%; border: 1px solid black;'>
      <tr>
      <th style='border: 1px solid black;'>Option</th>
      <th style='border: 1px solid black;'>Description</th>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>Once</td>
      <td style='text-align:center; border: 1px solid black;'>This option will run the broadcast only once starting at the schedule date/time. It will place  calls to all the recepient(s) until the list is exhausted.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>Daily</td>
      <td style='text-align:center; border: 1px solid black;'>This option will start the same broadcast AFRESH every day, and place calls to recepient(s), follower(s) or subscriber(s) until the list is exhausted. The option is good for broadcasting daily recurrent messages.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>DailyRound</td>
      <td style='text-align:center; border: 1px solid black;'>This option will run a broadcast 24/7 until the list of recepient(s) is exhausted.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>DailyDaytime</td>
      <td style='text-align:center; border: 1px solid black;'>This option will run a broadcast from Sunday to Saturday, 7AM to 6PM every day until the list of recepient(s) is exhausted.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>DailyNight</td>
      <td style='text-align:center; border: 1px solid black;'>This option will run a broadcast from Sunday to Saturday, 7PM to 6AM every day until the list of recepient(s) is exhausted.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>DailyWeekDaytime</td>
      <td style='text-align:center; border: 1px solid black;'>This option will run a broadcast from Monday to Friday, 7AM to 6PM until the list of recepient(s) is exhausted.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>DailyWeekDayNight</td>
      <td style='text-align:center; border: 1px solid black;'>This option will run a broadcast from Monday to Friday, 7PM to 6AM until the list of recepient(s) is exhausted.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>DailyWeekendDay</td>
      <td style='text-align:center; border: 1px solid black;'>This option will run a broadcast from Saturday to Sunday, 7AM to 6PM until the list of recepient(s) is exhausted.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>DailyWeekendNight</td>
      <td style='text-align:center; border: 1px solid black;'>This option will run a broadcast from Saturday to Sunday, 7PM to 6AM until the list of recepient(s) is exhausted.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>Weekly</td>
      <td style='text-align:center; border: 1px solid black;'>This option will start the same broadcast AFRESH every week, and place calls to recepient(s) until the list is exhausted. The option is good for broadcasting weekly recurrent messages.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>Monthly</td>
      <td style='text-align:center; border: 1px solid black;'>This option will start the same broadcast AFRESH every month, and place calls to recepient(s) until the list is exhausted. The option is good for broadcasting monthly recurrent messages.</td>
    </tr>
    <tr>
      <td style='text-align:center; border: 1px solid black;'>Yearly</td>
      <td style='text-align:center; border: 1px solid black;'>This option will start the same broadcast AFRESH every year, and place calls to recepient(s) until the list is exhausted. The option is good for broadcasting yearly recurrent messages.</td>
    </tr>
    </table>
  </span>

</div>
