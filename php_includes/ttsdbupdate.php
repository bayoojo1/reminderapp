<?php
$b = date_create($s);
$a = date_format($b, 'Y-m-d H:i:s');

//Update the DB with the user options
if($r == 'Once' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')){
        $stmt = $db_connect->prepare("INSERT INTO once (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'Daily' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')){
        $stmt = $db_connect->prepare("INSERT INTO daily (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'Weekly' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')){
        $stmt = $db_connect->prepare("INSERT INTO weekly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'Monthly' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')){
        $stmt = $db_connect->prepare("INSERT INTO monthly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'Yearly' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')){
        $stmt = $db_connect->prepare("INSERT INTO yearly (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'DailyRound' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
        $stmt = $db_connect->prepare("INSERT INTO dailyround (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'DailyDaytime' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
        $stmt = $db_connect->prepare("INSERT INTO dailydaytime (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'DailyNight' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
        $stmt = $db_connect->prepare("INSERT INTO dailynight (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'DailyWeekDaytime' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
        $stmt = $db_connect->prepare("INSERT INTO dailyweekdaytime (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'DailyWeekDayNight' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
        $stmt = $db_connect->prepare("INSERT INTO dailyweekdaynight (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'dailyWeekendDay' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
        $stmt = $db_connect->prepare("INSERT INTO dailyweekendday (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
} elseif($r == 'DailyWeekendNight' && ($sh == 'OnlyMe'|| $sh == 'Followers' || $sh == 'Subscribers')) {
        $stmt = $db_connect->prepare("INSERT INTO dailyweekendnight (username, username1, message, schedule, accepted, tag, mobile, postdate, broadcast)
VALUES(:username, :username1, :message, :schedule, :accepted, :tag, :mobile, now(), :broadcast)");
        $stmt->execute(array(':username' => $z, ':username1' => $u, ':message' => $words, ':schedule' => $s, ':accepted' => $a, ':tag' => $r, ':mobile' => $v, ':broadcast' => $sh));
}
?>
